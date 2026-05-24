<?php
require_once __DIR__ . '/setup.php';

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
    `question_id` INT NOT NULL AUTO_INCREMENT,
    `option_text` VARCHAR(100) NOT NULL ,
    PRIMARY KEY (`question_id`)
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
    `question_id` INT NOT NULL AUTO_INCREMENT,
    `option_text` VARCHAR NOT NULL ,
    PRIMARY KEY (`question_id`)
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
    `question_id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(32) NOT NULL ,
    `group_id` INT NOT NULL ,
    `answer` VARCHAR(100) NOT NULL ,
    PRIMARY KEY (`question_id`)
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
    PRIMARY KEY (`question_id`)
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

    $sql = "CREATE TABLE `fraguns_datenbankprojekt`.`user_answers_multiple_choice` (
    `question_id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(32) NOT NULL ,
    `group_id` INT NOT NULL ,
    `option_name` VARCHAR(100) NOT NULL ,
    PRIMARY KEY (`question_id`)
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

    $sql = "CREATE TABLE `fraguns_datenbankprojekt`.`user_answers_ranking` (
    `question_id` INT NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(32) NOT NULL ,
    `group_id` INT NOT NULL ,
    `option_name` VARCHAR(100) NOT NULL ,
    PRIMARY KEY (`question_id`)
    ) ENGINE = InnoDB;";
    try {
        $connection->query($sql);
        return 0;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}

?> 