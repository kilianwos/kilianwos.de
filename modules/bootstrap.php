<?php
ini_set('display_errors', '1');            // Fehler nicht im Browser anzeigen
ini_set('log_errors', '1');                // Fehler werden irgendwo geloggt (an den Hosting-Log)
error_reporting(E_ALL);                      // Alle Fehler erfassen
ini_set('memory_limit', '128M');           // Sicherheit: Speichergrenze setzen
ini_set('max_execution_time', '30');       // Sicherheit: Laufzeitgrenze setzen
define('DEBUG', false);

ob_start(); // Output-Buffer starten


set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        return false;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

set_exception_handler(function (Throwable $t) {
    error_log("Uncaught Exception: " . $t->getMessage());
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
    exit;
});

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        if (ob_get_level() > 0) {
            ob_clean();
        }
        http_response_code(500);
        if (defined('DEBUG') && DEBUG) {
            echo "<pre><h3>Fatal Shutdown Error</h3></pre>";
            print_r($error);
        } else {
            echo "<pre><h3>Ein kritischer Systemfehler ist aufgetreten.</h3></pre>";
        }
    } else {
        if (ob_get_level() > 0) {
            ob_end_flush();
        }
    }
});