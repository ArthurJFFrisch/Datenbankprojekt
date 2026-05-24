<?php    
require_once __DIR__ . '/setup.php';
require __DIR__ . '/login_tokens.php';
session_start();
if (isset($_SESSION['login_token'])) {
    $login_token = $_SESSION['login_token'];
    $username = check_login_token($login_token);
} else if (isset($_COOKIE['login_token'])) {
    $login_token = $_COOKIE['login_token'];
    $username = check_login_token($login_token);
    $_SESSION["login_token"] = $login_token;
} else {
    header("Location: login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = connect_to_database();
    if ($db instanceof Throwable) {
        http_response_code(500);
        exit();
    } else {
        if (isset($_COOKIE['login_token'])) {
            $login_token = $_COOKIE['login_token'];
            $username = check_login_token($login_token);
        } else {
            header("Location: login.php");
            exit;
        }
        echo("HALLO");
        $exp_time = date("Y-m-d", strtotime("+ 1 day"));
        echo($exp_time);
        $sql = $db->prepare("INSERT INTO text_questions (group_id, creator_username, expiration_date, question_text) VALUES (?, ?, ?, ?)");
        $sql->bind_param("isss", $_SESSION["group_id"], $username, $exp_time, $_POST["question_text"]);
        $sql->execute();
    }
}
?>

<!Doctype html>
<html lang=de>
    <head>
        <meta charset=UTF-8>
        <meta name=viewport content="width=device-width, initial-scale=1.0">
        <title>FragUns - Frage erstellen</title>
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
        </div>
        <header><h1><?php echo($_SESSION["group_name"]); ?></h1></header>
        <main>
            <div class="card">
                <h1>Textfrage erstellen</h1>
                <form id="form" method="post">
                    <input name="question_text" placeholder="Geben Sie eine Frage ein"></input>
                    <button class="option" type="submit"><p>Textfrage erstellen</p></button>
                </form>
            </div>
        </main>
        <nav>
            <a>Link1</a>
            <a>Link2</a>
            <a>Link3</a>
        </nav>
        <footer>
            <a href="impressum.html">Impressum</a>
        </footer>
    </body>
</html>