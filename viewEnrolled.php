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
echo "<span>".$_SESSION['crsCode'].": ".$_SESSION['crsName']."</span><br>";
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
        <title>Enrolled Students</title>
        <?php
        require_once("DB/DataBase.php");
        $clntID = DataBase::getInstance()->getClientID($sessionUser);
        //echo "<br><br>$clntID";
        if (!$clntID) {
            exit("The client " . $sessionUser . " is not found. Please check the spelling and try again");
        }
        ?>
        <br>
        <table>
            <tr>
                <th>Student Name</th>
                <!--th>Mark</th>-->
            </tr>
            <?php
            $result = DataBase::getInstance()->viewEnrolled($_SESSION['crsID']);
            try {
                while ($row = mysqli_fetch_array($result)) {
                    $Name = $row['FirstName']." ".$row['LastName'];
                    //echo '<td>' . htmlentities($Name) . '</td>';
                    echo '<td><div class="markTTip">'.htmlentities($Name).
                            '<span class="markText">&nbsp' . htmlentities($row['mark']) . '</span></div></td>';
                    //echo '<td class="markToolTip">' . htmlentities($row['mark']) . '</td>';
                    echo "</tr>\n";
                }
                mysqli_free_result($result);
            } catch (Exception $e) {
                echo "<script>alert('There are currently no students enrolled in this course.'); location.href='editCourses'</script>";
            }
            ?>
        </table>
        <br>
        <a href="editCourses"><button class="button animated">Back</button></a>
        <br><br>
        <form name="ReturnToHomePage" action="index">
        <input class="button animated" type ="submit" value ="Logout"/>
    </form>
    </body>
</html>