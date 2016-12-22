<?php
session_start();
if (array_key_exists("user", $_SESSION)) {
    $sessionUser = $_SESSION['user'];
    $sessionUserNm = $_SESSION['userNm'];
} else {
    header('Location: index');
    exit;
}
include("toolbar.php");
?>
<?php
require_once ("DB/DataBase.php");
if ($_SERVER ['REQUEST_METHOD'] == "POST") {
    session_start();
    $_SESSION['user'] = $_POST['user'];
    header('Location: editCourses');
    exit;
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <link href="main.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <title>Welcome, <?php echo $sessionUserNm; ?></title>
        <br>My Courses<br>
        <?php
        require_once("DB/DataBase.php");
        $clntID = DataBase::getInstance()->getClientID($sessionUser);
        //echo "<br><br>$clntID";
        if (!$clntID) {
            exit("The client " . $sessionUser . " is not found. Please check the spelling and try again");
        }
        ?>
        <br>
        Client info:
        <table>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Profession</th>
            </tr>
            <?php
            $result = DataBase::getInstance()->getClientInfo($clntID);
            try {
                while ($row = mysqli_fetch_array($result)) {
                    $clntType = $row['Profession'];
                    $_SESSION['clientType'] = $clntType;
                    $shrt = array('FirstName', 'LastName', 'Gender', 'DateOfBirth', 'Profession');
                    for ($i = 0; $i < count($shrt); $i++) {
                        if ($row[$shrt[$i]] == 'INST') {
                            echo '<td>Instructor</td>';
                        } elseif ($row[$shrt[$i]] == 'STDT') {
                            echo '<td>Student</td>';
                        } else {
                            echo '<td>' . htmlentities($row[$shrt[$i]]) . '</td>';
                        }
                    }
                    echo "</tr>\n";
                }
                mysqli_free_result($result);
            } catch (Exception $e) {
                echo "<script>alert('You are currently not enrolled in any courses. Please enroll.'); location.href='home.php'</script>";
            }
            ?>
        </table>
            <?php
            if($_SESSION['clientType'] == 'STDT'){
                echo "<br>Courses:<table><tr>
                <th>Course Name</th>
                <th>Course Code</th>
                <th>Description</th>
            </tr>";
            $result = DataBase::getInstance()->getClientCourses($clntID);
            try {
                while ($row = mysqli_fetch_array($result)) {
                    $shrt = array('Name', 'Code', 'Description');
                    for ($i = 0; $i < count($shrt); $i++) {
                        echo '<td>' . htmlentities($row[$shrt[$i]]) . '</td>';
                    }
                    echo "</tr>\n";
                }
                mysqli_free_result($result);
            } catch (Exception $e) {
                echo "<script>alert('You are currently not enrolled in any courses. Please enroll.'); location.href='home.php'</script>";
            }}
            ?>
        </table>
        <br>
        <form name="Edit Courses" action="editCourses">
            <input class="button animated" type ="submit" value ="Edit Courses"/>
        </form>
        <form name="ReturnToHomePage" action="index">
        <input class="button animated" type ="submit" value ="Logout"/>
    </form>
    </body>
</html>