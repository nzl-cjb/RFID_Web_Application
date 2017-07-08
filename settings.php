<?php
    session_start();

/**
 * Method that sets the name of the server
 * @return string that represents the name of the server
 */
    function servername() {
        $servername = "localhost";
        return $servername;
    }

/**
 * Method that sets the username of the database connection
 * @return string representing the username
 */
    function username() {
        $username = "root";
        return $username;
    }

/**
 * Method that sets the password for the user
 * @return string representing the password
 */
    function password() {
        $password = "";
        return $password;
    }

/**
 * Method that sets the name of the database that will be connected to
 * @return string representing the name of the database
 */
    function database() {
        $database = "rfid";
        return $database;
    }

/**
 * Method that takes the servername, username and password and uses them to connect to the targeted server. Once the
 * connection is established, the database name is then used to select the appropriate database
 * @return resource representing a connection to a a server with the a database selected
 */
    function conn() {
        $conn = mysql_connect(servername(), username(), password());
        mysql_select_db(database(), $conn);
        return $conn;
    }
?>