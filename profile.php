<?php
// require ist hier wichtig, weil die App ohne den Zugriff auf Datenbanken nicht funktioniert und setup.php sicherstellt, dass diese korrekt existieren.
require_once __DIR__ . '/setup.php';
require __DIR__ . '/login_tokens.php';

session_start();
if (!isset($_SESSION['username']) && isset($_COOKIE['login_token'])) {
    $login_token = $_COOKIE['login_token'];
    $username = check_login_token($login_token);
    $_SESSION["username"] = $username;
} else if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['DELETE'])) {
        echo("LÖSCHEN");
        $db = connect_to_database();
        if ($db instanceof Throwable) {
            http_response_code(500);
            exit();
        } else {
            echo ("LÖSCHEN");
            echo ($_SESSION['username']);
            $sql = $db->prepare("DELETE FROM user WHERE username=?");
            $sql->bind_param("s", $_SESSION['username']);
            $sql->execute();
            $sql = $db->prepare("DELETE FROM login_tokens WHERE username=?");
            $sql->bind_param("s", $_SESSION['username']);
            $sql->execute();
            session_destroy();
            header("Location: login.php");
            exit;
        }
    }
}

?>

<!Doctype html>
<html lang=de>
    <head>
        <meta charset=UTF-8>
        <meta name=viewport content="width=device-width, initial-scale=1.0">
        <title>FragUns - Profil</title>
        <link rel=stylesheet href=style.css>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">
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
                <h1>Profil</h1>
                <img src="https://api.dicebear.com/9.x/notionists/svg?seed=<?php echo $_SESSION['username']; ?>" alt="Profilbild">
                <p>Benutzername: <?php echo $_SESSION['username'];?></p>
                <p>Anzeigename: <?php echo $_SESSION['displayname'];?></p>
                <form method="POST">
                    <input type="hidden" name="DELETE">
                    <button class="option" onclick="return confirm('Möchten Sie Ihren Account wirklich löschen?');" style="background-color:#e86e46; color:white;">Account löschen</button>
                </form>
            </div>
        </main>
        <nav>
            <a href="index.php">Home</a>
            <a href="choose_question.php">Fragen</a>
        </nav>
        <footer>
            <a href="impressum.html">Impressum</a>
        </footer>
    </body>
</html>
