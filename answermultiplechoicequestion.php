<?php    
require_once __DIR__ . '/setup.php';
require __DIR__ . '/login_tokens.php';
session_start();
if (isset($_SESSION['login_token'])) {
    $login_token = $_SESSION['login_token'];
    $username = check_login_token($login_token);
    $_SESSION['username'] = $username;
} else if (isset($_COOKIE['login_token'])) {
    $login_token = $_COOKIE['login_token'];
    $username = check_login_token($login_token);
    $_SESSION["login_token"] = $login_token;
    $_SESSION["username"] = $username;
} else {
    header("Location: login.php");
    exit;
}
$db = connect_to_database();
    if ($db instanceof Throwable) {
        http_response_code(500);
        exit();
} else if(isset($_SESSION['question_id']) && isset($_SESSION['group_id'])) {
    $sql = $db->prepare("SELECT question_text FROM multiple_choice_questions WHERE question_id=? AND group_id=?");
    $sql->bind_param("ii", $_SESSION["question_id"], $_SESSION["group_id"]);
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_assoc();
    $_SESSION["question_text"] = $row["question_text"];
    $group_id = $_SESSION['group_id'];
    $question_id = $_SESSION['question_id'];
} else {
    header("Location: choose_question.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = connect_to_database();
    if ($db instanceof Throwable) {
        http_response_code(500);
        exit();
    } else if (isset($_POST)) {
        echo("ABGESENDET");
        foreach($_POST as $key => $value) {
            if ($value === "1") {
                $answer = $key;
                echo ($answer);
                echo ($_SESSION["group_id"]);
                echo ($_SESSION["question_id"]);
                echo ($_SESSION["username"]);
                $sql = $db->prepare("INSERT INTO user_answers_multiple_choice_question (group_id, question_id, username, answer) VALUES (?, ?, ?, ?)");
                $sql->bind_param("iiss", $_SESSION["group_id"], $_SESSION["question_id"], $_SESSION["username"], $answer);
                $sql->execute();
            }
        }
        header("Location: show_answer.php");
        exit();
    }
}
$sql = $db->prepare("SELECT COUNT(username) as answer_count FROM user_answers_multiple_choice_question WHERE group_id=? AND question_id=?");
$sql->bind_param("ii", $_SESSION["group_id"], $_SESSION["question_id"]);
$sql->execute();
$result = $sql->get_result();
$row = $result->fetch_assoc();
if ($row["answer_count"] > 0) {
    header("Location: show_answer.php");
    exit();
}
?>

<!Doctype html>
<html lang=de>
    <head>
        <meta charset=UTF-8>
        <meta name=viewport content="width=device-width, initial-scale=1.0">
        <title>FragUns - Frage beantworten</title>
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
        <header><h1><?php echo $_SESSION["group_name"]; ?></h1></header>
        <main>
            <div class="card">
                <h1><?php echo $_SESSION["question_text"]; ?></h1>
                <form id="form" method="post">
                    <?php
                        $sql = $db->prepare("SELECT option_text FROM multiple_choice_options WHERE question_id=?");
                        $sql->bind_param("i", $_SESSION["question_id"]);
                        $sql->execute();
                        $result = $sql->get_result();
                        while ($row = $result->fetch_assoc()) {
                            echo('<input id="'. $row["option_text"] .'" type="hidden" value="0" name="'. $row["option_text"] .'">');
                            echo('<button id="b'. $row["option_text"] .'" onclick="check_option(\''. $row["option_text"] .'\')" class="option" type="button">'. $row["option_text"] .'</button>');
                        }
                    ?>
                    <button class="option" type="submit"><p>Antwort senden</p></button>
                </form>
            </div>
        </main>
        <nav>
            <a href="index.php">Home</a>
            <a href="choose_question.php">Fragen</a>
            <a href="profile.php">Profil</a>
        </nav>
        <footer>
            <a href="impressum.html">Impressum</a>
        </footer>
        <script>
            function check_option(option_id){
                console.log(option_id);
                option_button = document.getElementById("b" + option_id);
                option_input = document.getElementById(option_id);
                if (option_input.value === "0"){
                    option_input.value = "1";
                    option_button.style.backgroundColor = "#e0ffffbe";
                }
                else {
                    option_input.value = "0";
                    option_button.style.backgroundColor = "#ffffff36";
                }
            }
        </script>
    </body>
</html>