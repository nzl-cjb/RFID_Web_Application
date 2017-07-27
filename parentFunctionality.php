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
function parentDisplayChildren()
{
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
function parentUpdatePassword($currentPassword, $newPwd, $confirmPwd)
{
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

/**
 * @param $studentID
 */
function listStudentAttendance($studentID)
{
    /**
     * Query that selects the columns that will have their data extracted and displayed in the table.
     * Only attendance records where there is an absence, late arrival or early departure will be displayed.
     * This will make the system far easier to use.
     */
    $sql = "SELECT a.attendanceID AS attendanceID, a.date AS date, a.signInTime AS signInTime, a.signOutTime AS signOutTime   
                FROM attendance a, excuses e
                WHERE a.studentID = $studentID AND a.attendanceID = e.attendanceID AND (e.verifiedByStaff = 0 OR e.excuse IS NULL)";
    $result = mysql_query($sql, conn()) or die(mysql_error());
    if ($result) {
        /**
         * Created a new table with the listed column headers
         */
        echo '<br>
                <table border = "1">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Sign In Time</th>
                            <th>Sign Out Time</th>
                            <th>Details</th>
                        </tr>
                    </thead>';
        $retrieved = false;

        /**
         * While loop that iterates through all results produced from the $result query
         */
        while ($row = mysql_fetch_array($result)) {
            $retrieved = true;
            /**
             * Display the information from the current row in the table columns
             */
            echo "
                    <tr>
                        <td>" . $row['date'] . "</td>
                        <td>" . $row['signInTime'] . "</td>
                        <td>" . $row['signOutTime'] . "</td>";

            echo "
                        <td><a href=\"home.php?attendance=" . $row['attendanceID'] . "\">View</a></td>";


            echo "</tr>";
        }

        /**
         * If there are no records to be displayed, then the user is notified by a single row spanning all columns.
         */
        if ($retrieved == false) {
            echo "
                    <tr>
                        <td colspan=\"5\" style=\"width:100%\" align = \"center\">No attendance records to show</td>
                    </tr>";
        }
        echo '</table><br>';
    }
}

/**
 * @param $studentID
 */
function listExcuses($attendanceID)
{
    /**
     * Query that selects the columns that will have their data extracted and displayed in the table
     */
    $sql = "SELECT e.excuseID as excuseID, a.attendanceID AS attendanceID, a.date AS date, a.signInTime AS signInTime, a.signOutTime AS signOutTime, e.reason AS reason, e.verifiedbyStaff AS verified 
                FROM excuses e, attendance a
                WHERE e.attendanceID = $attendanceID AND e.attendanceID = a.attendanceID AND (e.reason IS NULL OR e.verifiedByStaff  = 0)";
    $result = mysql_query($sql, conn()) or die(mysql_error());
    if ($result) {
        /**
         * Created a new table with the listed column headers
         */
        echo '<br>
                <table border = "1">
                    <thead>
                        <tr>
                            <th>AttendanceID</th>
                            <th>Date</th>
                            <th>Sign In Time</th>
                            <th>Sign Out Time</th>
                            <th>Reason</th>
                            <th>Verified</th>
                            <th>Submit</th>
                        </tr>
                    </thead>';
        $retrieved = false;

        /**
         * While loop that iterates through all results produced from the $result query
         */
        while ($row = mysql_fetch_array($result)) {
            $retrieved = true;
            /**
             * Display the information from the current row in the table columns
             */
            echo "
                    <tr>
                        <td>" . $row['attendanceID'] . "</td>
                        <td>" . $row['date'] . "</td>";
            if ($row['reason'] == "Departed early") {
                echo "<td>Not relevant</td>
                            <td>" . $row['signOutTime'] . "</td>";
            } else if ($row['reason'] == "Arrived late") {
                echo "<td>" . $row['signInTime'] . "</td>
                            <td>Not relevant</td>";
            }

            echo "<td>" . $row['reason'] . "</td>";

            if ($row['verified'] == 0) {
                echo "<td>No</td>";
                echo "<td><a href=\"submitExcuse.php?excuse=" . $row['excuseID'] . "\">Explain</a></td>";
            } else {
                echo "<td>Yes</td>";
                echo "<td>NA</td>";
            }

            echo "</tr>";
        }
        if ($retrieved == false) {
            echo "
                    <tr>
                        <td colspan=\"7\" style=\"width:100%\" align = \"center\">No attendance records to show</td>
                    </tr>";
        }
        echo '</table><br>';
    }
}
?>