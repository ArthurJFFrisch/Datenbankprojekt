<?php    
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
    $db = connect_to_database();
    if ($db instanceof Throwable) {
        http_response_code(500);
        exit();
    } else if(isset($_SESSION['group_id']) && isset($_POST['question_id']) && isset($_POST['question_type'])) {
        $_SESSION['question_id'] = $_POST['question_id'];
        $_SESSION['question_type'] = $_POST['question_type'];
        if ($_SESSION['question_type'] === "simple_question") {
            header("Location: answersimplequestion.php");
        }
        else if ($_SESSION['question_type'] === "multiple_choice_question") {
            header("Location: answermultiplechoicequestion.php");
        }
        else if ($_SESSION['question_type'] === "text_question") {
            header("Location: answertextquestion.php");
        }
        else if ($_SESSION['question_type'] === "ranking_question") {
            header("Location: answerrankingquestion.php");
        }
        exit;
    } else if (!isset($_SESSION['group_id'])) {
        if (isset($_POST['group_id'])) {
            $_SESSION['group_id'] = $_POST['group_id'];
        } else {
            header("Location: index.php");
            exit;
        }
    }
    if (!isset($_SESSION["group_name"])) {
        $sql = $db->prepare("SELECT group_name FROM group_admin WHERE group_id=?");
        $sql->bind_param("i", $_SESSION["group_id"]);
        $sql->execute();
        $result = $sql->get_result();
        $row = $result->fetch_assoc();
        $_SESSION["group_name"] = $row["group_name"];
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
        <header><h1><?php echo($_SESSION["group_name"]); ?></h1></header>
        <main>
            <div class="card">
                <h1>Eure Fragen</h1>
                <?php
                $db = connect_to_database();
                if ($db instanceof Throwable) {
                    http_response_code(500);
                    exit();
                } else {
                    $question_types = ["simple_question", "multiple_choice_question", "text_question", "ranking_question"];
                    foreach ($question_types as $type) {
                        $sql = $db->prepare("SELECT question_text, question_id FROM " . $type . "s WHERE group_id=? AND expiration_date>=?");
                        $exp_time = date("Y-m-d");
                        $sql->bind_param("is", $_SESSION["group_id"], $exp_time);
                        $sql->execute();
                        $result = $sql->get_result();
                        while ($row = $result->fetch_assoc()) {
                            echo("<button class='option' onclick='select(\"" . $row["question_id"] . "\", \"" . $type . "\")'>" . $row["question_text"] . "</button>\n");
                        }
                    }
                }                
                ?>
            </div>
            <div class="card">
                <h1>Erstellen</h1>
                <button class="option" onclick="window.location.href='simplequestion.php'"><p>Einfachauswahl</p></button>
                <button class="option" onclick="window.location.href='multiplechoicequestion.php'"><p>Mehrfachauswahl</p></button>
                <button class="option" onclick="window.location.href='textquestion.php'"><p>Textfrage</p></button>
                <button class="option" onclick="window.location.href='rankingquestion.php'"><p>Ranking</p></button>
            </div>
        </main>
        <nav>
            <a href="index.php">Home</a>
            <a href="profile.php">Profil</a>
        </nav>
        <footer>
            <a href="impressum.html">Impressum</a>
        </footer>
        <script>
            function select(questionID, questionType){
                const formData = new FormData();
                formData.append('question_id', questionID);
                formData.append('question_type', questionType);
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'choose_question.php';
                formData.forEach((value, key) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                });

                // Bisschen gemoogelt, aber mir ist erstmal nichts besseres eingefallen...
                document.body.appendChild(form);
                form.submit();
            }
        </script>
    </body>
</html>