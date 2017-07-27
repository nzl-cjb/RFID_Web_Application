<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset = utf-8"/>
    <title>RFID Student Monitoring System</title>
</head>

<body>
<div class="container">
    <form action="excuseProcess.php" method="post" id="form1">
        <input type="hidden" name="excuseID" value="<?php echo htmlspecialchars($_GET['excuse']);?>" />
        <label>Excuse: </label>
        <textarea name="message" rows="5" cols="30"></textarea>
        <input type="submit">
    </form>
</div>
</body>
</html>