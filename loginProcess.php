<?php
require_once("settings.php");

// 	Create connection
conn();

//	Variable tracking if all data is valid
$isValid = true;

//	Code checking if status code is valid
$loginUsername = $_POST['username'];
$loginPassword = $_POST['pwd'];

if (conn()) {
    echo "connected";
    $userfound = FALSE;
    $sql = "SELECT * FROM parent WHERE username = '" . mysql_real_escape_string($loginUsername) . "' AND password = '" . mysql_real_escape_string($loginPassword) . "'";
    $result = mysql_query($sql, conn());
    if (mysql_num_rows($result) > 0) {
        session_start();
        $_SESSION['username'] = $loginUsername;
        $_SESSION['priveleges'] = "Parent";
        $row = mysql_fetch_array($result);
        $parentID = $row['parentID'];
        $_SESSION['id'] = $parentID;
        header("location:home.php");
        $userfound = TRUE;
        exit;
    }
    if ($userfound == FALSE) {
        $sql = "SELECT * FROM teacher WHERE username = '" . mysql_real_escape_string($loginUsername) . "' AND password = '" . mysql_real_escape_string($loginPassword) . "'";
        $result = mysql_query($sql, conn());
        if (mysql_num_rows($result) > 0) {
            session_start();
            $_SESSION['username'] = $loginUsername;
            $_SESSION['priveleges'] = "Teacher";
            $row = mysql_fetch_array($result);
            $teacherID = $row['teacherID'];
            $_SESSION['id'] = $teacherID;
            header("location:home.php");
            $userfound = TRUE;
            exit;
        }
    }
    if ($userfound == FALSE) {
        header("location:index.php?msg=failed");
        exit;
    }
} else {
    echo "Failed to connect";
}

?>

