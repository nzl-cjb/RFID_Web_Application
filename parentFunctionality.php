<?php
/**
 * Created by PhpStorm.
 * User: Callum
 * Date: 10/05/2017
 * Time: 2:00 PM
 */
    require_once("settings.php");

/**
 * This function displays the children of the parent based on the session variable "id".
 */
    function parentDisplayChildren() {
        $parentID = $_SESSION['id'];
        $sql = "SELECT * FROM student WHERE parentID = '" . mysql_real_escape_string($parentID) . "'";
        $result = mysql_query($sql, conn());
        if ($result === FALSE) {
            die(mysql_error());
        }
        echo "<br>Displaying your children in the table below<br>";
        if ($result) {
            /**
             * Creates a table with appropriate column headers.
             */
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

            /**
             * Iterates through all results of the query and populates a new row in the table.
             */
            while ($row = mysql_fetch_array($result)) {
                /**
                 * Checks the value of the "present" variable in the databse. A local present variable has either a
                 * Present or Absent value assigned to it.
                 */
                if ($row['present'] == 0) {
                    $present = "Absent";
                } else {
                    $present = "Present";
                }

                /**
                 * Echoes the data for the student into a new table row.
                 */
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

/**
 * This function attempts to update the password of the current parent based on the session varialbe "id".
 * @param $currentPassword password the parent typed as their current password
 * @param $newPwd the password the parent typed as their desired password
 * @param $confirmPwd the password the parent typed confirming their desired password
 */
    function parentUpdatePassword($currentPassword, $newPwd, $confirmPwd) {
        $parentID = $_SESSION['id'];
        $sql = "SELECT * FROM parent WHERE parentID = '" . mysql_real_escape_string($parentID) . "'";
        $result = mysql_query($sql, conn());
        $row = mysql_fetch_array($result);
        $dbPassword = $row['password'];

        /**
         * Checks to see if the current password entered by the user matches the password stored in the database
         */
        if ($dbPassword == $currentPassword) {
            /**
             * Checks to see if the new password and the confirm password variables are the same
             */
            if ($newPwd == $confirmPwd) {
                /**
                 * Execute update query which changes the password for the current user.
                 */
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