<?php
    session_start();

    function servername() {
        $servername = "localhost";
        return $servername;
    }

    function username() {
        $username = "root";
        return $username;
    }

    function password() {
        $password = "";
    }

    function database() {
        $database = "rfid";
        return $database;
    }

    function conn() {
        $conn = mysql_connect(servername(), username(), password());
        mysql_select_db(database(), $conn);
        return $conn;
    }
?>