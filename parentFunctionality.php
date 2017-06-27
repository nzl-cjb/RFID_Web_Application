<?php
/**
 * Created by PhpStorm.
 * User: Callum
 * Date: 10/05/2017
 * Time: 2:00 PM
 */
    require_once("settings.php");

    function parentDisplayChildren() {
        $parentID = $_SESSION['id'];
        $sql = "SELECT * FROM student WHERE parentID = '" . mysql_real_escape_string($parentID) . "'";
        //                    $sql = "SELECT * FROM student";
        $result = mysql_query($sql, conn());
        if ($result === FALSE) {
            die(mysql_error()); // TODO: better error handling
        }
        echo "<br>Displaying your children in the table below<br>";
        if ($result) {
            echo '<br>
                <table border = "5">
                    <thead>
                        <tr>
                            <th>Parent ID</th>
                            <th>Student Number</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Present</th>
                        </tr>
                    </thead>';
            while ($row = mysql_fetch_array($result)) {
                if ($row['present'] == 0) {
                    $present = "Absent";
                } else {
                    $present = "Present";
                }
                echo "
                    <tr>
                        <td>" . $row['parentID'] . "</td>
                        <td>" . $row['studentNumber'] . "</td>
                        <td>" . $row['firstName'] . "</td>
                        <td>" . $row['lastName'] . "</td>
                        <td>" . $present . "</td>
                    </tr>";
            }
            echo '</table><br>';
        }
    }

    function parentUpdatePassword($currentPassword, $newPwd, $confirmPwd) {
        $parentID = $_SESSION['id'];
        $sql = "SELECT * FROM parent WHERE parentID = '" . mysql_real_escape_string($parentID) . "'";
        $result = mysql_query($sql, conn());
        $row = mysql_fetch_array($result);
        $dbPassword = $row['password'];
        if ($dbPassword == $currentPassword) {
            if ($newPwd == $confirmPwd) {
                $sql = "UPDATE parent SET password = '" . $newPwd . "' WHERE parentID = '" . $parentID . "'";
                $result = mysql_query($sql, conn());
                echo "updated";
            } else {
                echo "Passwords do not match";
            }
        } else {
            echo "Incorrect password";
        }
    }
?>