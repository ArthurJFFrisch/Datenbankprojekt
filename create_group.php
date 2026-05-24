<?php
require_once __DIR__ . '/setup.php';
require __DIR__ . '/login_tokens.php';

if (isset($_COOKIE['login_token'])) {
    $login_token = $_COOKIE['login_token'];
    $username = check_login_token($login_token);
    $db = connect_to_database();
    $sql = $db->prepare("SELECT displayname FROM user WHERE username=?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();
    $row = $result->fetch_assoc();
    $displayname = $row["displayname"];
    
} else {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST["groupname"] !== "" && $_POST["user_array"] !== "") {
        $db = connect_to_database();
        if ($db instanceof Throwable) {
            http_response_code(500);
            exit();
        } else {
            echo("HIER");
            $sql = $db->prepare("INSERT INTO group_admin (admin_username, group_name) VALUES (?, ?)");
            $sql->bind_param("ss", $username, $_POST['groupname']);
            $sql->execute();
            $id = $sql->insert_id;
            echo($_POST["user_array"]);
            $user_array = json_decode($_POST["user_array"]);
            echo($user_array[1]);
            foreach($user_array as $userName){
                $sql = $db->prepare("INSERT INTO group_users (username, group_id) VALUES (?, ?)");
                $sql->bind_param("si", $userName, $id);
                $sql->execute();
                $sql->get_result();
            }
        }
    }
}
?>

<!Doctype html>
<html lang=de>
    <head>
        <meta charset=UTF-8>
        <meta name=viewport content="width=device-width, initial-scale=1.0">
        <title>FragUns - Gruppe erstellen</title>
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
            
        </div>
        <header><h1>FragUns</h1></header>
        <main>
            <div class="card">
                <h1>Gruppe erstellen</h1>
                <input id="groupname" name="groupname" placeholder="Gruppenname">
                <span id="group-name-status" style="color: red;"></span>
                <input id="username" name="username" placeholder="Gruppenmitglieder">
                <span id="display-name-status" style="color: red;"></span>
                <button class="option" style="dsplay:inline;" onclick="add_user()">Hinzufügen</button>
                <span id="user-array-status"></span>
                <span id="username-status" style="color: red;"></span>
                <button class="option" onclick="send_form()"><p>Registrieren</p></button>
            </div>
        </main>
        <footer>
            <a href="impressum.html">Impressum</a>
        </footer>
        <script>
            user_arrayDisplay = document.getElementById("user-array-status");
            const loggedinUsername = "<?php echo($username); ?>";
            console.log(loggedinUsername);
            user_array = [loggedinUsername];
            user_arrayDisplay.textContent = user_array;
            function add_user(){
                username_input = document.getElementById("username");
                statusDisplay = document.getElementById("display-name-status");
                if (username_input.value.length === 0){
                    console.log("HI")
                    statusDisplay.textContent = "Bitte geben Sie einen Benutzernamen ein.";
                } else {
                    username = username_input.value;
                    const formData = new FormData();
                    formData.append('username', username)
                    fetch('check-existence.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() === "exists") {
                            statusDisplay.textContent = "";
                            user_array.push(username);
                            user_arrayDisplay.textContent = user_array;
                        } else {
                            statusDisplay.textContent = "Benutzername existiert nicht.";
                        }
                    })
                    .catch(error => console.error('Fehler:', error));
                }
            }


            function send_form(){
                groupname_input = document.getElementById("groupname");
                statusDisplay = document.getElementById("group-name-status");
                if (groupname_input.value.length === 0){
                    console.log("HI")
                    statusDisplay.textContent = "Bitte geben Sie einen Gruppennamen ein.";
                } else {
                    groupname = groupname_input.value;
                    const formData = new FormData();
                    formData.append('groupname', groupname);
                    formData.append('user_array', JSON.stringify(user_array));
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'create_group.php';

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
            }
            
            // Verfügbarkeit des Benutzernamens
            /*
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
            });*/
        </script>
    </body>
</html>
