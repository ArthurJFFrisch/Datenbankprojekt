<?php    
require_once __DIR__ . '/setup.php';
require __DIR__ . '/login_tokens.php';
session_start();
if (isset($_COOKIE['login_token'])) {
    $login_token = $_COOKIE['login_token'];
    $username = check_login_token($login_token);
} else {
    header("Location: login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = connect_to_database();
    if ($db instanceof Throwable) {
        http_response_code(500);
        exit();
    } else if (isset($_POST["answer_text"]) && isset($_POST['question_id']) && isset($_POST['group_id'])) {
        echo("ABGESENDET");
        $group_id = $_POST['group_id'];
        $question_id = $_POST['question_id'];
        $answer = $_POST["answer_text"];
        $sql = $db->prepare("INSERT INTO user_answers_text_question (group_id, question_id, username, answer) VALUES (?, ?, ?, ?)");
        $sql->bind_param("iiss", $group_id, $question_id, $username, $answer);
        $sql->execute();
        $_SESSION["question_id"] = $question_id;
        $_SESSION["group_id"] = $group_id;
        var_dump($_SESSION["question_id"]);
        echo("ABGESENDET1");
        header("Location: show_answer.php");
        exit();
    } else if(isset($_POST['question_id']) && isset($_POST['group_id'])) {
        $sql = $db->prepare("SELECT question_text FROM text_questions WHERE question_id=? AND group_id=?");
        $sql->bind_param("ii", $_POST["question_id"], $_POST["group_id"]);
        $sql->execute();
        $result = $sql->get_result();
        $row = $result->fetch_assoc();
        $_SESSION["question_text"] = $row["question_text"];
        $group_id = $_POST['group_id'];
        $question_id = $_POST['question_id'];
    }
}
$sql = $db->prepare("SELECT COUNT(username) as answer_count FROM user_answers_text_question WHERE group_id=? AND question_id=?");
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
        <header><h1>FragUns</h1></header>
        <main>
            <div class="card">
                <h1><?php echo $_SESSION["question_text"]; ?></h1>
                <form id="form" method="post">
                    <input type="hidden" name="group_id" value="<?php echo $group_id; ?>"></input>
                    <input type="hidden" name="question_id" value="<?php echo $question_id; ?>"></input>
                    <input name="answer_text" placeholder="Antwort"></input>
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
    </body>
</html>