<!Doctype html>
<html lang=de>
    <head>
        <meta charset=UTF-8>
        <meta name=viewport content="width=device-width, initial-scale=1.0">
        <meta title="FragUns - Registrieren">
        <title>Document</title>
        <link rel=stylesheet href=style.css>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    </head>
    <body>
        <header><h1>FragUns</h1></header>
        <div class="card">
            <h1>Registrieren</h1>
            <form method="post">
                <input id="username" name="username" placeholder="Benutzername">
                <span id="username-status" style="color: red;"></span>
                <input name="display_name" placeholder="Anzeigename">
                <input id="email"name="email" placeholder="E-Mail" type="email">
                <span id="email-status" style="color: red;"></span>
                <input id="password" name="password" placeholder="Passwort" type="password">
                <span id="password-status" style="color: red;"></span>
                <!-- Todo: Prüflogik in php einbauen -->
                <div class="cf-turnstile" data-sitekey="0x4AAAAAADAsYxWSwYU4ejyd"></div>
                <button class="option" type="submit"><p>Registrieren</p></button>
            </form>
        </div>
        <nav>
            <a>Link1</a>
            <a>Link2</a>
            <a>Link3</a>
        </nav>
        <footer>
            <a>Impressum</a>
        </footer>
        <script>
            // Testen von Verfügbarkeit des Benutzernamens und angemessener Sicherheit des Passworts
            const usernameInput = document.getElementById('username');
            const statusDisplay = document.getElementById('username-status');
            const passwordInput = document.getElementById('password');
            const statusPassword = document.getElementById('password-status');

            // Passwortsicherheit
            passwordInput.addEventListener('input', function() {
                if (this.value.length > 0 && this.value.length < 8) { // Mindestlänge von 8 Zeichen
                    statusPassword.textContent = "Mindestens 8 Zeichen erforderlich.";
                } else if (this.value.length > 0 && /\d/.test(this.value) === false) { // Mindestens eine Zahl
                    statusPassword.textContent = "Mindestens eine Zahl erforderlich.";
                } else if (this.value.length > 0 && /[A-Z]/.test(this.value) === false) { // Mindestens ein Großbuchstabe
                    statusPassword.textContent = "Mindestens ein Großbuchstabe erforderlich.";
                } else if (this.value.length > 0 && /[!@#$%^&*(),.?":{}|<>]/.test(this.value) === false) { // Mindestens ein Sonderzeichen
                    statusPassword.textContent = "Mindestens ein Sonderzeichen erforderlich.";
                }else {
                    statusPassword.textContent = "";
                }
            });

            // Verfügbarkeit des Benutzernamens
            usernameInput.addEventListener('input', function() {
            const username = this.value;

            if (username.length === 0) {
                statusDisplay.textContent = "";
                return;
            } else if (username.length < 3) {
                statusDisplay.textContent = "Mindestens 3 Zeichen erforderlich.";
                return;
            }

            const formData = new FormData();
            formData.append('username', username);

            // Anfrage an check-username.php senden, um die Verfügbarkeit zu überprüfen
            fetch('check-username.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === "taken") {
                    statusDisplay.textContent = "Bereits vergeben.";
                } else {
                    statusDisplay.textContent = "";
                }
            })
            .catch(error => console.error('Fehler:', error));
        });
        </script>
    </body>
</html>

<?php

// require ist hier wichtig, weil die App ohne den Zugriff auf Datenbanken nicht funktioniert und setup.php sicherstellt, dass diese korrekt existieren.
require_once __DIR__ . '/setup.php';

// Setup der Datenbank
$errorCode = setup_database();
if ($errorCode instanceof Throwable) {
    http_response_code(500);
    exit();
} elseif (is_int($errorCode)) {
    http_response_code($errorCode);
    exit();
}

// Todo: Registrierungslogik
$db = connect_to_database();
if ($db instanceof Throwable) {
    http_response_code(500);
    exit();
} else {
    $sql = $db->prepare("INSERT INTO user (username, displayname, password, email) VALUES (?, ?, ?, ?)");
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql->bind_param("ssss", $_POST['username'], $_POST['display_name'], $hashed_password, $_POST['email']);
    $sql->execute();
    $sql->get_result();
}

?>
