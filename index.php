<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset = utf-8"/>
    <title>RFID Student Monitoring System</title>
</head>

<body>
<div class="container">
    <h1>RFID Student Monitoring System</h1>

    <?php
    /*
     * Ensures that whenever a user comes to the index page, any active session they may have is destroyed.
     * This limits access to other pages in the website
     */
    session_start();
    session_destroy();
    ?>

    <!-- Form that the user enters their username and password into to login to the website. Once the user submits the
     form, the information is passed to the loginProcess.php file using the post method. This ensures the username and
      password are kept secure -->
    <form action="loginProcess.php" method="post" id="form1">
        <label>Username: </label>
        <input type="text" name="username"><br><br>

        <label>Password: </label>
        <input type="password" name="pwd"><br><br>
    </form>

    <!-- Div containing the submit/reset buttons for the form. A message may also be presented to the user based
    on the "msg" query string -->
    <div class="navigation">
        <?php
        if (isset($_GET["msg"]) && $_GET["msg"] == 'failed') {
            echo "Incorrect Username / Password<br><br>";
        }
        if (isset($_GET["msg"]) && $_GET["msg"] == 'logout') {
            echo "You have been logged out<br><br>";
        }
        ?>
        <button type="submit" form="form1" value="login">Login</button>
        <button type="reset" form="form1" value="reset">Reset</button>
        <br><br>
    </div>
</div>
</body>
</html>