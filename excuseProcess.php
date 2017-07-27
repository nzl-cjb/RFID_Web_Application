<?php
/**
 * Created by PhpStorm.
 * User: Callum
 * Date: 20/07/2017
 * Time: 11:44 AM
 */

require_once("settings.php");

conn();

$excuseID = $_POST['excuseID'];
$message = $_POST['message'];

$sql = $sql = "UPDATE excuses SET excuse = '$message' WHERE excuseID = $excuseID";

if ($result = mysql_query($sql, conn())) {
    echo "Success";
}
?>