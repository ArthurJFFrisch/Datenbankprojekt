<?php

// Error 500 ist wenn unser Server generell ein Problem hat. Wenn die Datenbank nicht erreichbar ist, ist es eher ein 503, da es ein temporäres Problem sein könnte (z.B. Wartungsarbeiten).

// Baut eine Verbindung zur Datenbank auf
function connect_to_database(){
    $host = '127.0.0.1';
    $user = 'root';
    $pass = '';
    try {
        $connection = new mysqli($host, $user, $pass);
        return $connection;
    } catch (mysqli_sql_exception $e) {
        return $e;
    }
}

// Prüft ob die Datenbank bereits existiert
function check_for_existing_database($connection){
    $dbname = 'datenbankprojekt';
    $result = $connection->query("SHOW DATABASES LIKE '$dbname'");
    if ($result->num_rows > 0) {
        return 0;
    } else {
        return -1;
    }
}

// Erstellt die Datenbank
function create_database($connection){
    $dbname = 'datenbankprojekt';
    if ($connection->query("CREATE DATABASE $dbname") === TRUE) {
        return 0;
    } else {
        return -1;
    }
}

function create_user_table($connection) {
    $dbname = 'datenbankprojekt';
    $tableName = 'user';

    // SQL-Befehl zur Erstellung der Relation "user", falls sie noch nicht existiert
    $sql = "CREATE TABLE IF NOT EXISTS `$dbname`.`$tableName` (
        `username` VARCHAR(32) NOT NULL, 
        `displayname` VARCHAR(64) NOT NULL, 
        `password` VARCHAR(255) NOT NULL, 
        PRIMARY KEY (`username`)
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
    $connection = connect_to_database();
    if ($connection instanceof Throwable) {
        error_log($connection);
        return 503;
    } elseif (check_for_existing_database($connection) === -1) {
        if (create_database($connection) === -1) {
            error_log('Failed to create database');
            $connection->close();
            return 500;
        } else {
            echo 'Database created successfully'; // Temporär
            $connection->close();
            return $connection;
        }
    } else {
        echo 'Database already exists'; // Temporär

        // Nun können alle Relationen auf ihre Existenz geprüft und bei Bedarf erstellt werden
        $result = create_user_table($connection);
        if ($result instanceof Throwable) {
            error_log("Error while creating table: " . $result->getMessage());
            $connection->close();
            return 503;
        }
        $connection->close();
        return $connection;
    }
}

?>
