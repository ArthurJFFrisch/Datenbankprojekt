<?php

// Error 500 ist wenn unser Server generell ein Problem hat. Wenn die Datenbank nicht erreichbar ist, ist es eher ein 503, da es ein temporäres Problem sein könnte (z.B. Wartungsarbeiten).

function connect_to_db_server() {
    $host = '127.0.0.1';
    $user = 'fraguns';
    $pass = 'TrQ@%O0q5Ib*G!';

    try {
        $connection = new mysqli($host, $user, $pass);
        return $connection;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}

// Baut eine Verbindung zur Datenbank auf
function connect_to_database() {
    $host = '127.0.0.1';
    $user = 'fraguns';
    $name = 'fraguns_datenbankprojekt';
    $pass = 'TrQ@%O0q5Ib*G!';

    try {
        $connection = new mysqli($host, $user, $pass, $name);
        return $connection;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}

// Prüft ob die Datenbank bereits existiert
function check_for_existing_database($connection){
    $dbname = 'fraguns_datenbankprojekt';
    $result = $connection->query("SHOW DATABASES LIKE '$dbname'");
    if ($result->num_rows > 0) {
        return 0;
    } else {
        return -1;
    }
}

// Erstellt die Datenbank
function create_database($connection){
    $dbname = 'fraguns_datenbankprojekt';
    if ($connection->query("CREATE DATABASE $dbname") === TRUE) {
        return 0;
    } else {
        return -1;
    }
}


function create_token_table($connection) {
    $dbname = 'fraguns_datenbankprojekt';
    $tableName = 'login_tokens';

    // SQL-Befehl zur Erstellung der Relation "login_tokens", falls sie noch nicht existiert
    $sql = "CREATE TABLE IF NOT EXISTS `fraguns_datenbankprojekt`.`login_tokens` (`username` VARCHAR(20) NOT NULL , `token` VARCHAR(32) NOT NULL, PRIMARY KEY (`token`)) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}

function create_group_table($connection) {

    $dbname = 'fraguns_datenbankprojekt';
    $tableName = 'group_admin';

    // SQL-Befehl zur Erstellung der Relation "user", falls sie noch nicht existiert
    $sql = "CREATE TABLE IF NOT EXISTS `fraguns_datenbankprojekt`. $tableName (`group_id` INT(20) NOT NULL AUTO_INCREMENT, `admin_username` VARCHAR(20) NOT NULL , `group_name` VARCHAR(20) NOT NULL , PRIMARY KEY (`group_id`)) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}


function create_groupmember_table($connection) {

    $dbname = 'fraguns_datenbankprojekt';
    $tableName = 'group_users';

    // SQL-Befehl zur Erstellung der Relation "user", falls sie noch nicht existiert
    $sql = "CREATE TABLE IF NOT EXISTS `fraguns_datenbankprojekt`. $tableName (`username` VARCHAR(20) NOT NULL , `group_id` INT(20) NOT NULL ) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}


function create_user_table($connection) {
    $dbname = 'fraguns_datenbankprojekt';
    $tableName = 'user';

    // SQL-Befehl zur Erstellung der Relation "user", falls sie noch nicht existiert
    $sql = "CREATE TABLE IF NOT EXISTS `fraguns_datenbankprojekt`.$tableName (
    `username` VARCHAR(20) NOT NULL,
    `displayname` VARCHAR(10) NOT NULL,
    `password` VARCHAR(256) NOT NULL,
    `email` VARCHAR(20) NOT NULL,
    `verified` BOOLEAN NOT NULL DEFAULT FALSE,
    `verification_code` VARCHAR(256) NULL DEFAULT NULL,
    `verification_expires` DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (`username`),
    UNIQUE (`email`)
    ) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}

function create_simple_questions_table($connection) {
    $dbname = 'fraguns_datenbankprojekt';

    $sql = "CREATE TABLE `fraguns_datenbankprojekt`.`simple_questions` (
    `question_id` INT NOT NULL AUTO_INCREMENT,
    `group_id` INT NOT NULL ,
    `creator_username` VARCHAR(32) NOT NULL ,
    `expiration_date` DATE NOT NULL ,
    `question_text` VARCHAR(100) NOT NULL ,
    PRIMARY KEY (`question_id`)
    ) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}

function create_simple_options_table($connection) {
    $dbname = 'fraguns_datenbankprojekt';

    $sql = "CREATE TABLE `fraguns_datenbankprojekt`.`simple_options` (
    `question_id` INT NOT NULL,
    `option_text` VARCHAR(100) NOT NULL ,
    PRIMARY KEY (`question_id`, `option_text`)
    ) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}



function create_multiple_choice_questions_table($connection) {
    $dbname = 'fraguns_datenbankprojekt';

    $sql = "CREATE TABLE `fraguns_datenbankprojekt`.`multiple_choice_questions` (
    `question_id` INT NOT NULL AUTO_INCREMENT,
    `group_id` INT NOT NULL ,
    `creator_username` VARCHAR(32) NOT NULL ,
    `expiration_date` DATE NOT NULL ,
    `question_text` VARCHAR(100) NOT NULL ,
    PRIMARY KEY (`question_id`)
    ) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}

function create_multiple_choice_options_table($connection) {
    $dbname = 'fraguns_datenbankprojekt';

    $sql = "CREATE TABLE `fraguns_datenbankprojekt`.`multiple_choice_options` (
    `question_id` INT NOT NULL,
    `option_text` VARCHAR(100) NOT NULL ,
    PRIMARY KEY (`question_id`, `option_text`)
    ) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}

function create_text_questions_table($connection) {
    $dbname = 'fraguns_datenbankprojekt';

    $sql = "CREATE TABLE `fraguns_datenbankprojekt`.`text_questions` (
    `question_id` INT NOT NULL AUTO_INCREMENT,
    `group_id` INT NOT NULL ,
    `question_text` VARCHAR(100) NOT NULL ,
    `creator_username` VARCHAR(32) NOT NULL ,
    `expiration_date` DATE NOT NULL ,
    PRIMARY KEY (`question_id`)
    ) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}

function create_ranking_questions_table($connection) {
    $dbname = 'fraguns_datenbankprojekt';

    $sql = "CREATE TABLE `fraguns_datenbankprojekt`.`ranking_questions` (
    `question_id` INT NOT NULL AUTO_INCREMENT,
    `group_id` INT NOT NULL ,
    `question_text` VARCHAR(100) NOT NULL ,
    `creator_username` VARCHAR(32) NOT NULL ,
    `expiration_date` DATE NOT NULL ,
    PRIMARY KEY (`question_id`)
    ) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}  

function create_ranking_options_table($connection) {
    $dbname = 'fraguns_datenbankprojekt';

    $sql = " CREATE TABLE `fraguns_datenbankprojekt`.`ranking_options` (
    `question_id` INT NOT NULL,
    `option_text` VARCHAR(100) NOT NULL ,
    PRIMARY KEY (`question_id`, `option_text`)
    ) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
} 

function create_user_answers_simple_question_table($connection) {
    $dbname = 'fraguns_datenbankprojekt';

    $sql = "CREATE TABLE `fraguns_datenbankprojekt`.`user_answers_simple_question` (
    `question_id` INT NOT NULL,
    `username` VARCHAR(32) NOT NULL ,
    `group_id` INT NOT NULL ,
    `answer` VARCHAR(100) NOT NULL ,
    PRIMARY KEY (`question_id`, `username`)
    ) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
} 

function create_user_answers_text_question_table($connection) {
    $dbname = 'fraguns_datenbankprojekt';

    $sql = "CREATE TABLE `fraguns_datenbankprojekt`.`user_answers_text_question` (
    `question_id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(32) NOT NULL ,
    `group_id` INT NOT NULL ,
    `answer` VARCHAR(100) NOT NULL ,
    PRIMARY KEY (`question_id`, `username`)
    ) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
} 

function create_user_answers_multiple_choice_table($connection) {
    $dbname = 'fraguns_datenbankprojekt';

    $sql = "CREATE TABLE `fraguns_datenbankprojekt`.`user_answers_multiple_choice_question` (
    `question_id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(32) NOT NULL ,
    `group_id` INT NOT NULL ,
    `answer` VARCHAR(100) NOT NULL ,
    PRIMARY KEY (`question_id`, `username`, `answer`)
    ) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}

function create_user_answers_ranking_table($connection) {
    $dbname = 'fraguns_datenbankprojekt';

    $sql = "CREATE TABLE `fraguns_datenbankprojekt`.`user_answers_ranking_question` (
    `question_id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(32) NOT NULL ,
    `group_id` INT NOT NULL ,
    `answer` VARCHAR(100) NOT NULL ,
    PRIMARY KEY (`question_id`, `username`, `answer`)
    ) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}

// Falls die Datenbank nicht existiert, wird sie erstellt.
function setup_database() {
    $connection = connect_to_db_server();
    if ($connection instanceof Throwable) {
        error_log($connection);
        return 503;
    }
    if (check_for_existing_database($connection) === -1) {
        if (create_database($connection) === -1) {
            error_log('Failed to create database');
            $connection->close();
            return 500;
        } else {
            echo 'Database created successfully'; // Temporär
        }
    }

    // Nun können alle Relationen auf ihre Existenz geprüft und bei Bedarf erstellt werden
    $result = create_user_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $result = create_token_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $result = create_group_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $result = create_groupmember_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $result = create_simple_questions_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $result = create_simple_options_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $result = create_multiple_choice_questions_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $result = create_multiple_choice_options_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $result = create_text_questions_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $result = create_ranking_questions_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $result = create_ranking_options_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $result = create_user_answers_simple_question_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $result = create_user_answers_text_question_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $result = create_user_answers_multiple_choice_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $result = create_user_answers_ranking_table($connection);
    if ($result instanceof Throwable) {
        error_log("Error while creating table: " . $result->getMessage());
        $connection->close();
        return 503;
    }
    $connection->close();
    return $connection;
}

setup_database();