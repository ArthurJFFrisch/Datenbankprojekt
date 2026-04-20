<?php

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
            echo 'Database created successfully';
            $connection->close();
            return $connection;
        }
    } else {
        echo 'Database already exists';
        $connection->close();
        return $connection;
    }
}

?>
