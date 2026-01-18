<?php
/**
 * Main Entry Point & Router
 * * This script handles all incoming web requests, manages the application lifecycle
 * through bootstrapping, and routes requests to specific page files based on a whitelist.
 * 
 * Copyright (C) Kilian David Wos
 */

    try {
        require_once __DIR__ . '/modules/bootstrap.php';
    } catch (Throwable $t) {
        /** @var Throwable $t */
        error_log("Kritischer Fehler: " . $t->getMessage() . " in " . $t->getFile() . ":" . $t->getLine());
        if (ob_get_level() > 0) {
            ob_clean();
        }
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');

        if (defined('DEBUG') && DEBUG) {
            echo "<pre><h3>Internal Server Error:</h3></pre>";
            echo "<pre>" . htmlspecialchars($t->getMessage()) . "<br>";
            echo "Error in " . $t->getFile() . " on line " . $t->getLine() . "</pre>";
            echo "<pre>" . $t->getTraceAsString() . "</pre>";
        } else {
            echo "<pre><h3>Interner Serverfehler:</h3></pre>";
            echo "<pre>Ein unerwarteter Fehler ist aufgetreten. Bitte versuchen Sie es später erneut.</pre>";
        }
    }

    /** @var string $routesFile Path to the routing whitelist file */
    $routesFile = __DIR__ . '/modules/routes.php';
    /** @var array $routes Loaded route mappings [path => file] */
    $routes = require $routesFile;

    /**
    * Extracts the raw path from the current request URI.
    * * Removes the query string and decodes URL-encoded characters.
    * * @return string The decoded path part of the URI.
    */
    function getRoute(): string {
        //Path of the uri
        $route = $_SERVER['REQUEST_URI'] ?? '';
        //Removes ? and any character after that token
        $route = strtok($route, '?');
        //makes an ä readable
        $route = urldecode($route);
        return (string) $route;
    }

    /**
     * Sanitizes a given route string for comparison and redirection.
     * * Normalizes slashes, removes trailing slash, and filters the URL.
     * * @param string $route The raw route to sanitize.
     * @return string The sanitized and normalized route.
     */
    function sanitizeRoute(string $route): string {
        //This should remove consecutive /'s
        $route = preg_replace('#/+#', '/', $route) ?? '';
        if ($route !== '/') {
            //This should remove trailing /, except if the only character is a /.
            $route = rtrim($route, '/');
        }
        //This should remove exotic characters.
        $route = (string) filter_var($route, FILTER_SANITIZE_URL);
        return $route;
    }

    /**
     * Checks if the system is currently in maintenance mode.
     * * Looks for the presence of a specific flag file.
     * * @return bool True if maintenance mode is active, false otherwise.
     */
    function isMaintenance(): bool {
        $file = __DIR__ . '/flags/maintenance.flag';
        return file_exists($file);
    }

    /**
     * Handles the routing process.
     * * Validates the route against a whitelist, performs 301 redirects for
     * uncanonical URLs, and includes the target page file.
     * * @param string $route The raw requested route.
     * @param array $routes The map of allowed routes.
     * @return void
     */
    function handleRouting(string $route, array $routes) {
        //To make sure I am only work with the website path.
        $pagesDir = __DIR__ . '/pages';

        //The maintenance mode has higher priority than any other site.
        if (isMaintenance()) {
            require $pagesDir . '/maintenance.php';
            exit;
        }

        //Creates a sanitized String to compare.
        $sanitizedRoute = sanitizeRoute($route);

        //Checks if the requested site is on the whitelist and if the file exists (If not, a 404 is sent instead of a 500).
        if (array_key_exists($sanitizedRoute, $routes) && file_exists($pagesDir . $routes[$sanitizedRoute])) {

            //Checks if the sanitized Path matches the requested path. If not, it will redirect to the sanitized path. (It removes any multiple /'s, any trailing / and illegal characters)
            if ($route !== $sanitizedRoute) {
                $query = $_SERVER['QUERY_STRING'] ?? '';
                $target = $sanitizedRoute . ($query ? '?' . $query : '');
                
                header("Location: " . $target, true, 301);
                exit;
            }

            //Sets the targetfile to load (if the target file does not exists, it will send a 500 later)
            $targetFile = $pagesDir . $routes[$sanitizedRoute];
        } else {
            //If the request is not in the whitelist or the file does not exists, it will send a 404.
            $targetFile = $pagesDir . '/not_found.php';
        }
        //Loads the target page.
        require $targetFile;
    }

    handleRouting(getRoute(), $routes);