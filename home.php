<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset = utf-8"/>
    <link rel="stylesheet" type="text/css" href="styling.css"/>
    <title>Welcome!</title>
</head>

<body>
<div class="navigation">
    <h1>RFID Student Monitoring System</h1>
    <?php
    require_once("settings.php");
    require_once("parentFunctionality.php");
    require_once("teacherFunctionality.php");

    if (!isset($_SESSION['priveleges'])) {
        header("location:index.php?msg=notloggedin");
        exit;
    }
    
    $username = $_SESSION['username'];
    echo "Current user: " . $username . "<br>";
    ?>
    <div class="link"><a href="home.php">Home</a></div>
    <?php
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
    if (isset($_GET["msg"]) && $_GET["msg"] == 'students') {
        if ($_SESSION['priveleges'] == 'Parent') {
            parentDisplayChildren();
        } else {
            teacherDisplayChildren();
        }
    }
    if (isset($_GET["msg"]) && $_GET["msg"] == 'updatepassword') {
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

    if (isset($_GET["msg"]) && $_GET['msg'] == 'verifypassword') {
        if ($_SESSION['priveleges'] == 'Teacher') {
            teacherUpdatePassword($_POST['currentPwd'], $_POST['newPwd'], $_POST['confirmPwd']);
        } else {
            parentUpdatePassword($_POST['currentPwd'], $_POST['newPwd'], $_POST['confirmPwd']);
        }
    }

    if (isset($_GET["class"])) {
        listStudentsInClass();
    } else if (isset($_GET["msg"]) && $_GET["msg"] == 'classes') {
        showClasses();
    }


    ?>
</div>
</body>
</html>