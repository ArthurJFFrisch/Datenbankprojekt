<?php
    require_once __DIR__ . '/setup.php';
    require __DIR__ . '/send_verify_mail.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (strlen($_POST["username"]) >= 3 || strlen($_POST["email"]) >= 5) {
            $db = connect_to_database();
            if ($db instanceof Throwable) {
                http_response_code(500);
                exit();
            } else {
                if (strlen($_POST["username"]) >= 3) {
                    $sql = $db->prepare("SELECT email FROM user WHERE username=?");
                    $sql->bind_param("s", $_POST['username']);
                    $sql->execute();
                    $result = $sql->get_result();
                    $row = $result->fetch_assoc();
                    $verification_code = 100000; //random_int(100000, 999999);
                    $hashed_verification_code = password_hash($verification_code, PASSWORD_DEFAULT);
                    $sql = $db->prepare("UPDATE user SET verification_code=? WHERE username=?");
                    $sql->bind_param("ss", $hashed_verification_code, $_POST['username']);
                    $sql->execute();
                    if ($row) {
                        send_password_reset_mail($_POST['username'], $verification_code, $row['email']);
                    }
                } else if (strlen($_POST["email"]) >= 5) {
                    $sql = $db->prepare("SELECT username FROM user WHERE email=?");
                    $sql->bind_param("s", $_POST['email']);
                    $sql->execute();
                    $result = $sql->get_result();
                    $row = $result->fetch_assoc();
                    $verification_code = 100000; //random_int(100000, 999999);
                    $hashed_verification_code = password_hash($verification_code, PASSWORD_DEFAULT);
                    $sql = $db->prepare("UPDATE user SET verification_code=? WHERE email=?");
                    $sql->bind_param("ss", $hashed_verification_code, $_POST['email']);
                    $sql->execute();
                    if ($row) {
                        send_password_reset_mail($row['username'], $verification_code, $_POST['email']);
                    }
                }
                header("Location: reset_password_verify.php?username=" . urlencode($_POST['username'] ?? ''));
                exit;
            }
        } else {
            echo "<script>alert('Bitte überprüfen Sie die eingegebenen Daten.')</script>";
        }
    }

?>


<!Doctype html>
<html lang=de>
    <head>
        <meta charset=UTF-8>
        <meta name=viewport content="width=device-width, initial-scale=1.0">
        <title>FragUns - Login</title>
        <link rel=stylesheet href=style.css>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    </head>
    <body>
        <div class="aurora-wrapper">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="blob blob-3"></div>
            <div class="blob blob-4"></div>
            <div class="blob blob-5"></div>
            <div class="blob blob-6"></div>
        </div>
        <header><h1>FragUns</h1></header>
        <main>
            <div class="card">
                <h1>Passwort zurücksetzen</h1>
                <form id="form" method="post">
                    <input id="username" name="username" placeholder="Benutzername">
                    <p>oder</p>
                    <input id="email" name="email" placeholder="E-Mail" type="email">
                    <span id="username-status" style="color: red;"></span>
                    <button class="option" type="submit"><p>Passwort zurücksetzen</p></button>
                </form>
            </div>
        </main>
        <footer>
            <a href="impressum.html">Impressum</a>
        </footer>
        <script>
            // Testen von Verfügbarkeit des Benutzernamens und angemessener Sicherheit des Passworts
            const formInput = document.getElementById('form');
            const usernameInput = document.getElementById('username');
            const emailInput = document.getElementById('email');
            const statusDisplay = document.getElementById('username-status');

            formInput.addEventListener('submit', function(event) {
                event.preventDefault();

                everythingValid = true;

                // Prüflogik Client
                if (usernameInput.value.length === 0 && emailInput.value.length === 0) { // Benutzername und E-Mail fehlen
                    statusDisplay.textContent = "Bitte geben Sie einen Benutzernamen oder eine E-Mail ein."
                    everythingValid = false
                }

                if (everythingValid === true) {
                    formInput.submit();
                }
            });
        </script>
    </body>
</html>
