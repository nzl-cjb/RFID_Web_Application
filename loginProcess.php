<?php
/**
 *
 */
require_once("settings.php");

/**
 * Calls the conn() method in the settings.php file. This provides access to the database and allows queries to be executed.
 */
conn();

//	Variable tracking if all data is valid
$isValid = TRUE;

/**
 * Retrieves the username and password entered by the user in the login form and stores them as local variables.
 */
$loginUsername = $_POST['username'];
$loginPassword = $_POST['pwd'];

// Checks to see if a connection was successfully established
if (conn()) {
    $userfound = FALSE;
    /**
     * Execute query that searches the parent table for entries that match the username and password submitted in the login
     * form.
     */
    $sql = "SELECT * FROM parent WHERE username = '" . mysql_real_escape_string($loginUsername) . "' AND password = '" . mysql_real_escape_string($loginPassword) . "'";
    $result = mysql_query($sql, conn());

    /**
     * Checks to see if the number of results is greater than 0, if it is, then there must be a match
     */
    if (mysql_num_rows($result) > 0) {
        /**
         * Start a new session and assign session variables such as username, priveleges and id. These will be used to
         * grant or restrict features for the user. Once the session variables have been set, the user is redirected to
         * the home.php page.
         */
        session_start();
        $_SESSION['username'] = $loginUsername;
        $_SESSION['priveleges'] = "Parent";
        $row = mysql_fetch_array($result);
        $parentID = $row['parentID'];
        $_SESSION['id'] = $parentID;
        $userfound = TRUE;
        header("location:home.php");
        exit;
    }
    /**
     * If there were no matches found in the parent table, the teacher table is then queried for entries that match the
     * username and password entered in the login form.
     */
    if ($userfound == FALSE) {
        $sql = "SELECT * FROM teacher WHERE username = '" . mysql_real_escape_string($loginUsername) . "' AND password = '" . mysql_real_escape_string($loginPassword) . "'";
        $result = mysql_query($sql, conn());

        /**
         * Checks to see if the number of results is greater than 0, if it is, then there must be a match
         */
        if (mysql_num_rows($result) > 0) {
            /**
             * Start a new session and assign session variables such as username, priveleges and id. These will be used to
             * grant or restrict features for the user. Once the session variables have been set, the user is redirected to
             * the home.php page.
             */
            session_start();
            $_SESSION['username'] = $loginUsername;
            $_SESSION['priveleges'] = "Teacher";
            $row = mysql_fetch_array($result);
            $teacherID = $row['teacherID'];
            $_SESSION['id'] = $teacherID;
            $userfound = TRUE;
            header("location:home.php");
            exit;
        }
    }

    /**
     * If no match was found in the parent or teacher table, then the user is redirected back to the index.php page
     * with a fail message in the msg query string. This is used to inform the user that the username or password was
     * incorrect
     */
    if ($userfound == FALSE) {
        header("location:index.php?msg=failed");
        exit;
    }
}
?>

