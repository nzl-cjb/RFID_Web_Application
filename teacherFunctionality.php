<?php
/**
 * Created by PhpStorm.
 * User: Callum
 * Date: 10/05/2017
 * Time: 2:00 PM
 */
require_once("settings.php");

function teacherDisplayChildren()
{
    $sql = "SELECT s.parentID AS parentID, s.studentNumber AS studentNumber, s.firstName AS firstName, s.lastName AS lastName, t.username AS teacher, s.present AS present  
                FROM student s, enroll e, class c, teacher t
                WHERE t.teacherID = c.teacherID AND c.classID = e.classID AND e.studentID = s.studentID AND t.username LIKE '" . $_SESSION['username'] . "'";
    $result = mysql_query($sql, conn());
    if ($result === FALSE) {
        die(mysql_error());
    }
    echo "<br>Displaying all children at the school in the table below<br>";
    if ($result) {
        echo '<br>
                <table border = "1">
                    <thead>
                        <tr>
                            <th>Parent ID</th>
                            <th>Student Number</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Teacher</th>
                            <th>Present</th>
                        </tr>
                    </thead>';
        $retrieved = false;
        while ($row = mysql_fetch_array($result)) {
            $retrieved = true;
            $isInSchool = $row['present'];

            if ($isInSchool == 0) {
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
                        <td>" . $row['teacher'] . "</td>
                        <td>" . $present . "</td>
                    </tr>";
        }
        if ($retrieved == false) {
            echo "
                    <tr>
                        <td colspan=\"5\" style=\"width:100%\" align = \"center\">No students to display</td>
                    </tr>";
        }
        echo '</table><br>';
    }
}

function teacherUpdatePassword($currentPassword, $newPwd, $confirmPwd)
{
    $teacherID = $_SESSION['id'];
    $sql = "SELECT * FROM teacher WHERE teacherID = '" . mysql_real_escape_string($teacherID) . "'";
    $result = mysql_query($sql, conn());
    $row = mysql_fetch_array($result);
    $dbPassword = $row['password'];
    if ($dbPassword == $currentPassword) {
        if ($newPwd == $confirmPwd) {
            $sql = "UPDATE teacher SET password = '" . $newPwd . "' WHERE teacherID = '" . $teacherID . "'";
            $result = mysql_query($sql, conn());
            echo "updated";
        } else {
            echo "Passwords do not match";
        }
    } else {
        echo "Incorrect password";
    }
}

function showClasses()
{
    $teacherID = $_SESSION['id'];
    $sql = "SELECT c.classCode AS classCode  
                FROM teacher t, class c 
                WHERE t.teacherID = '" . mysql_real_escape_string($teacherID) . "' AND t.teacherID = c.teacherID";
    $result = mysql_query($sql, conn());
    while ($row = mysql_fetch_array($result)) {
        echo "<a href = \"home.php?msg=classes&class=" . $row['classCode'] . "\">" . $row['classCode'] . "</a><br>";
    }
}

/**
 *
 */
function listStudentsInClass()
{
    $sql = "SELECT s.studentID, s.studentNumber AS studentNumber, s.firstName AS firstName, s.lastName AS lastName, c.classCode AS classCode, e.grade AS grade, s.present AS present   
                FROM student s, enroll e, class c, teacher t
                WHERE t.teacherID = c.teacherID AND c.classID = e.classID AND e.studentID = s.studentID AND t.username LIKE '" . $_SESSION['username'] . "'";
    $result = mysql_query($sql, conn()) or die(mysql_error());
    if ($result) {
        echo '<br>
                <table border = "1">
                    <thead>
                        <tr>
                            <th>Student Number</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Course</th>
                            <th>Grade</th>
                            <th>Present</th>
                        </tr>
                    </thead>';
        $retrieved = false;
        while ($row = mysql_fetch_array($result)) {
            $retrieved = true;

            if ($row['present'] == 0) {
                $present = "Absent";
            } else {
                $present = "Present";
            }

            echo "
                    <tr>
                        <td>" . $row['studentNumber'] . "</td>
                        <td>" . $row['firstName'] . "</td>
                        <td>" . $row['lastName'] . "</td>
                        <td>" . $row['classCode'] . "</td>
                        <td>" . $row['grade'] . "</td>
                        <td>" . $present . "</td>
                    </tr>";
        }
        if ($retrieved == false) {
            echo "
                    <tr>
                        <td colspan=\"5\" style=\"width:100%\" align = \"center\">No students to display</td>
                    </tr>";
        }
        echo '</table><br>';
    }
}

?>