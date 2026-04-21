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
    </head>
    <body>
        <header><h1>FragUns</h1></header>
        <div class="card">
            <h1>Registrieren</h1>
            <form method="post">
                <input id="username" name="username" placeholder="Benutzername">
                <span id="username-status" style="color: red;"></span>
                <input name="placeholder" placeholder="Anzeigename">
                <input name="email" placeholder="E-Mail" type="email">
                <input name="password" placeholder="Passwort", type="password">
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

            usernameInput.addEventListener('input', function() {
                const username = this.value;

                if (username.length < 3) {
                    statusDisplay.textContent = "";
                    return;
                }

                // Anfrage an den Server schicken
                const formData = new FormData();
                formData.append('username', username);

                fetch('check-username.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data === "taken") {
                        console.log("Username ist bereits vergeben.");
                        statusDisplay.textContent = "Dieser Name ist bereits vergeben.";
                    } else if (data === "available") {
                        statusDisplay.textContent = "";
                    } else {
                        // Todo: Fehlerbehandlung
                    }
                })
                .catch(error => console.error('Fehler:', error));
            });
        </script>
    </body>
</html>

<?php
function test_username_availability($db, $username) {
    $sql = $db->prepare("SELECT username FROM user WHERE username = ?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();
    return ($result->num_rows > 0) ? false : true;
}

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
    $sql = $db->prepare("INSERT INTO user (username, displayname, password) VALUES (?, ?, ?)");
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $sql->bind_param("sss", $_POST['username'], $_POST['placeholder'], $hashed_password);
    $sql->execute();
    $sql->get_result();
}

?>
