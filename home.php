<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset = utf-8"/>
    <link rel="stylesheet" type="text/css" href="styling.css"/>
    <title>Welcome!</title>
</head>

<body>

<?php
/**
 * Includes the settings, parentFunctionality and teacherFuncionality files within the home.php file. This allows
 * methods to be accessed in these files without cluttering the home.php file too much.
 */
require_once("settings.php");
require_once("parentFunctionality.php");
require_once("teacherFunctionality.php");

/**
 * Check to see if the session variable "priveleges" is set, if it isn't then a user has tried to manually
 * enter the url link to the home page without first logging in. The user will be redirected to the home page
 * with a "notloggedin" error message in the msg query string.
 */
if (!isset($_SESSION['priveleges'])) {
    header("location:index.php?msg=notloggedin");
    exit;
}

/**
 * Retrieves the session variable "username" and stores it as a local variable. The username is then used to
 * identify the user by printing it on the screen.
 */
$username = $_SESSION['username'];
echo "Current user: " . $username . "<br>";
?>

<!-- The navigation div contains the links to other sections of the website -->
<div class="navigation">
    <h1>RFID Student Monitoring System</h1>

    <div class="link"><a href="home.php">Home</a></div>

    <?php
    /**
     * Checks to see if the signed in user is a teacher. If they are, another link is made visible to them that offers
     * additional functionality.
     */
    if ($_SESSION['priveleges'] == 'Teacher') {
        echo "<div class=\"link\"><a href = \"home.php?msg=classes\">Classes</a></div>";
    }
    ?>

    <div class="link"><a href="home.php?msg=students">Students</a></div>
    <div class="link"><a href="home.php?msg=updatepassword">Change Password</a></div>
    <div class="link"><a href="index.php?msg=logout">Logout</a></div>

</div>

<div class="children">

    <?php
    /**
     * Checks to see if the msg query string is set
     */
    if (isset($_GET["msg"])) {
        /**
         * Check to see if the msg query string is "students"
         */
        if ($_GET["msg"] == 'students') {
            /**
             * If the session variable "priveleges is parent, then the children of the current parent are displayed
             * in a table. Otherwise, all students that are taught by the teacher are displayed in a table.
             */
            if ($_SESSION['priveleges'] == 'Parent') {
                parentDisplayChildren();
            } else {
                teacherDisplayChildren();
            }
        }

        /**
         * Check to see if the msg query string is "updatepassword"
         */
        if ($_GET["msg"] == 'updatepassword') {
            /**
             * A form is presented to the user with 3 fields: Current password, new password and confirm password.
             * When the form is submitted, the msg query string will be updated to verifypassword. The contents of
             * the form will be verified from here.
             */
            echo '<form action = "home.php?msg=verifypassword" method = "post">
                            <label>Current Password: </label>
                                <input type = "password" name = "currentPwd"><br><br>

                            <label>New Password: </label>
                                <input type="password" name="newPwd"><br><br>
                            <label>Confirm Password: </label>
                                <input type="password" name="confirmPwd"><br><br>
                            <input type="submit" name="changePwd" value="Update Password" />
                        </form>';
        }

        /**
         * Check to see if the msg query string is "verifypassword"
         */
        if ($_GET['msg'] == 'verifypassword') {
            /**
             * Verifies the content of the data submitted in the update password form.
             */
            if ($_SESSION['priveleges'] == 'Teacher') {
                teacherUpdatePassword($_POST['currentPwd'], $_POST['newPwd'], $_POST['confirmPwd']);
            } else {
                parentUpdatePassword($_POST['currentPwd'], $_POST['newPwd'], $_POST['confirmPwd']);
            }
        }

        /**
         * Check to see if the msg query string is "classes"
         */
        if ($_GET["msg"] == 'classes') {
            /**
             * Shows
             */
            showClasses();
        }
    }

    /**
     * Check if the session variable "class" is set.
     */
    if (isset($_GET["class"])) {
        /**
         * If it is, then the listStudentInClass function is called.
         */
        listStudentsInClass();
    }
    ?>
</div>
</body>
</html>