<?php    
require_once __DIR__ . '/setup.php';
require __DIR__ . '/question-logic.php';
create_ranking_questions_table(connect_to_database());
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = connect_to_database();
    if ($db instanceof Throwable) {
        http_response_code(500);
        exit();
    } else {
        $sql = $db->prepare("INSERT INTO ranking_questions (question_id, group_id, question_text, creator_username, expiration_date) VALUES (?, ?, ?, ?, ?)");
        $sql->bind_param("iisds", $_POST["question_id"], $_POST["group_id"],  $_POST["question_text"], $_POST["creator_username"], $_POST["expiration_date"]);
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
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
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
                <h1>Frage erstellen</h1>
                <form id="form" method="post">
                    <input name="Frage" placeholder="Ranglistensortierfrage"></input>
                    <button class="option" type="submit"><p>Ranglistensortierfrage erstellen</p></button>
                </form>
            </div>
        </main>
        <nav>
            <a>Link1</a>
            <a>Link2</a>
            <a>Link3</a>
        </nav>status
        <footer>
            <a href="impressum.html">Impressum</a>
        </footer>
        <script>




            



        </script>
    </body>
</html>