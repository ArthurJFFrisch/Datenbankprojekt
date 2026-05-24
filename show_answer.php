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
    $db = connect_to_database();
    echo("TEST");
    if ($db instanceof Throwable) {
        http_response_code(500);
        exit();
    } else if (isset($_SESSION['question_id']) && isset($_SESSION['group_id'])) {
        echo("HIER");
        $sql = $db->prepare("SELECT question_text FROM ". $_SESSION["question_type"] ."s WHERE question_id=? AND group_id=?");
        $sql->bind_param("ii", $_SESSION["question_id"], $_SESSION["group_id"]);
        $sql->execute();
        $result = $sql->get_result();
        $row = $result->fetch_assoc();
    }
?>

<!Doctype html>
<html lang=de>
    <head>
        <meta charset=UTF-8>
        <meta name=viewport content="width=device-width, initial-scale=1.0">
        <title>FragUns - Antworten anzeigen</title>
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
                <?php
                    if (isset($_SESSION['question_id']) && isset($_SESSION['group_id'])) {
                        $from = (string) "user_answers_" . $_SESSION["question_type"];
                        $from2 = explode("_q", $_SESSION["question_type"])[0] . "_options";
                        $sql = $db->prepare("SELECT username, answer FROM " . $from . " WHERE question_id=? AND group_id=?");
                        $sql->bind_param("ii", $_SESSION["question_id"], $_SESSION["group_id"]);
                        $sql->execute();
                        $result = $sql->get_result();
                        $sql2 = $db->prepare("SELECT option_text FROM " . $from2 . " WHERE question_id=?");
                        $sql2->bind_param("i", $_SESSION["question_id"]);
                        $sql2->execute();
                        $result2 = $sql2->get_result();
                        if ($_SESSION["question_type"] === "simple_question") {
                            while($row2 = $result2->fetch_assoc()) {
                                $sql = $db->prepare("SELECT username, answer FROM " . $from . " WHERE question_id=? AND group_id=?");
                                $sql->bind_param("ii", $_SESSION["question_id"], $_SESSION["group_id"]);
                                $sql->execute();
                                $result = $sql->get_result();
                                $user_pics = "";
                                while ($row = $result->fetch_assoc()) {
                                    if ($row["answer"] === $row2["option_text"]) {
                                        $user_pics .= "<div style='display: flex; flex-direction: column; justify-content: space-around; align-items: center; margin: 10px 0;'><img style='width: 50px; height: 50px; border-radius: 100%; object-fit: fill;' src='https://api.dicebear.com/9.x/notionists/svg?seed=" . $row["username"] . "' alt='Profilbild'><p>" . $row["username"] . "</div>";
                                    }
                                }
                                echo("<div class='option' style='display: flex; flex-direction: row; justify-content: left; align-items: center; margin: 10px 0;'><div><p style='margin-left: 10; margin-right: 10;'>" . $row2["option_text"] . "</p></div><div>" . $user_pics . "</div></div>");
                            }
                        } else if ($_SESSION["question_type"] === "text_question") {
                            while($row = $result->fetch_assoc()) {
                                echo("<div class='option' style='display: flex; flex-direction: row; justify-content: space-around; align-items: center; margin: 10px 0;'><div><img style='width: 50px; height: 50px; border-radius: 100%; object-fit: fill;' src='https://api.dicebear.com/9.x/notionists/svg?seed=" . $row["username"] . "' alt='Profilbild'>");
                                echo("<p>" . $row["username"] . "</div><div>" . $row["answer"] . "</p></div></div>");
                            }
                        } else if ($_SESSION["question_type"] === "multiple_choice_question") {
                            while($row2 = $result2->fetch_assoc()) {
                                $sql = $db->prepare("SELECT username, answer FROM " . $from . " WHERE question_id=? AND group_id=?");
                                $sql->bind_param("ii", $_SESSION["question_id"], $_SESSION["group_id"]);
                                $sql->execute();
                                $result = $sql->get_result();
                                $user_pics = "";
                                while ($row = $result->fetch_assoc()) {
                                    if ($row["answer"] === $row2["option_text"]) {
                                        $user_pics .= "<div style='display: flex; flex-direction: column; justify-content: space-around; align-items: center; margin: 10px 0;'><img style='width: 50px; height: 50px; border-radius: 100%; object-fit: fill;' src='https://api.dicebear.com/9.x/notionists/svg?seed=" . $row["username"] . "' alt='Profilbild'><p>" . $row["username"] . "</div>";
                                    }
                                }
                                echo("<div class='option' style='display: flex; flex-direction: row; justify-content: left; align-items: center; margin: 10px 0;'><div><p style='margin-left: 10; margin-right: 10;'>" . $row2["option_text"] . "</p></div><div>" . $user_pics . "</div></div>");
                            }
                        }
                    }
                ?>
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