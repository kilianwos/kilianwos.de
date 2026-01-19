# KilianWos.DE - Meine Website
Hi! Das ist der Quellcode meiner persönlichen Website, die unter [KilianWos.DE](https://kilianwos.de) erreichbar ist.
## Das Projekt
Die Seite läuft klassisch auf einem **Apache HTTP Server** mit **PHP**.
Anstatt direkt zu WordPress zu greifen oder dem Hype der "Top-Ten-Web-Frameworks-2026"-Listen (inklusive der nervigen Ads zwischen jedem Platz 😉) zu folgen, verfolge ich mit diesem Projekt ein anderes Ziel:

**Ich möchte die Grundlagen verstehen.**

Mein Fokus liegt darauf, zu lernen, wie PHP, HTML, CSS und JavaScript im Kern funktionieren und zusammenwirken, um eine ansprechende Seite zu bauen. Da letztlich fast jedes moderne Framework auf diesen Technologien basiert, möchte ich ein tiefes Verständnis für das Fundament entwickeln.

Damit das Theorielernen nicht zu trocken wird, dient dieses Projekt als mein persönlicher "Spielplatz".
## Tech-Stack
* **Backend:** PHP
* **Frontend:** HTML, CSS, JavaScript
* **Server:** Apache
## Das Backend
### Unschöne Dateiendungen
Mit Apache könnte ich schnell eine index.html-Datei anlegen welche dann unter "meinedomain.de/" erreichbar wäre. Die Datei "ueber-mich.html" wiederrum wäre über "meinedomain.de/ueber-mich.html" erreichbar! Jedoch sieht man die Dateiendungen, wie .html, heutzutage eher weniger in URI's. Sie wird gerne weggelassen und das möchte ich auch!

Mir sind mehrere Wege innerhalb dieses Projeks bekannt, damit ich meine URI's auch so gestalten kann:
1. Über das Modul ModRewrite oder
2. Mit einem PHP-Skript

Ich habe mich für zweitens entschieden, weil der Umgang damit deutlich flexibler zu gestalten ist. Schließlich ist die Skriptsprache PHP deutlich mächtiger als ein Apache-Modul, oder? So gehe ich davon aus.

### Grundlegende Struktur
In meinem Root-Verzeichnis befindet sich eine besondere Datei, ich habe sie [router.php](https://github.com/kilianwos/kilianwos.de/blob/main/router.php) genannt.

Die Absicht hinter dieser Datei ist, dass jede Anfrage von einem Browser zuerst an diese Datei gesendet wird, damit er dann die richtigen Dateien und Informationen zusammentragen kann, abhängig was im Pfad der URI eingetippt wurde.

Damit mein Apache-Server weiß, dass (fast) alle Anfragen an die [router.php](https://github.com/kilianwos/kilianwos.de/blob/main/router.php)-Datei gehen sollen, habe ich eine weitere Datei im root Verzeichnis. Die [.htaccess](https://github.com/kilianwos/kilianwos.de/blob/main/.htaccess)-Datei. Diese Datei, enthält ein paar Zeilen, damit die Anfragen intern nach [router.php](https://github.com/kilianwos/kilianwos.de/blob/main/router.php) schickt:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    #...#

    # 📥 2. Leite alles andere an router.php
    RewriteRule ^ router.php [L]                      # !Diese Zeile!
</IfModule>
```

**🚧 Status:** Diese Dokumentation ist noch in Arbeit. Ich ergänze die Details zum Router und der Projektstruktur in Kürze.
