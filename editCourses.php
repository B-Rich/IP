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

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_SESSION['clientType'] == 'INST') {
        $_SESSION['crsID'] = $_POST['crsID'];
        $_SESSION['crsCode'] = $_POST['crsCode'];
        $_SESSION['crsName'] = $_POST['crsName'];
        if ($_POST['goto'] == 'addQuestion') {
            header('Location: addQuestion');
        } elseif ($_POST['goto'] == 'viewEnrolled') {
            header('Location: viewEnrolled');
        } else {
            header('Location: index');
        }
    } else {
        $_SESSION['crsID'] = $_POST['crsID'];
        $_SESSION['clientID'] = $_POST['clientID'];
        $_SESSION['courseID'] = $_POST['courseID'];
        if ($_POST['goto'] == 'takeTest') {
            echo True;
            require_once ("DB/DataBase.php");
            $qFileName = ".\Questions\questions" . $_SESSION['crsCode'] . ".qbf";
            $qBankBuffer = fopen($qFileName, "rw") or die("Unable to open file!");
            $_SESSION['qBank'] = preg_split('/$\R?^/m', fread($qBankBuffer, filesize($qFileName)));
            $_SESSION['numOfQuest'] = sizeof($_SESSION['qBank']);
            $_SESSION['questOrd'] = DataBase::randomizer(0, $_SESSION['numOfQuest'] - 1, $_SESSION['numOfQuest'] - 1);
            $_SESSION['qNum'] = 0;
            $_SESSION['corrAnsNum'] = 0;
            header('Location: takeTest');
        }
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="main.css" rel="stylesheet" type="text/css">
    <title>Edit your courses</title>
</head>
<body>
    <table>
        <tr>
        <tr>
            <th>Course Name</th>
            <th>Course Code</th>
            <th>Description</th>
            <th>Options</th>
        </tr>
        <?php
        require_once ("DB/DataBase.php");
        $clntID = DataBase::getInstance()->getClientID($sessionUser);
        //add function
        if ($_SESSION['clientType'] == 'INST') {
            $result = DataBase::getInstance()->getCourses();
        } else {
            $result = DataBase::getInstance()->getClientCourses($clntID);
        }
        while ($row = mysqli_fetch_array($result)):
            $shrt = array('Name', 'Code', 'Description');
            for ($i = 0; $i < count($shrt); $i++) {
                echo '<td>' . htmlentities($row[$shrt[$i]]) . '</td>';
            }
            //echo"<tr><td></tr>";
            $crsCode = $row['Code'];
            $crsName = $row['Name'];
            $crsID = $row['ID'];
            ?>
            <td>
                <?php
                if ($_SESSION['clientType'] == 'INST') {
                    echo "<form name='Add Question' action='editCourses' method='POST'>
                            <input type ='hidden' name ='crsID' value ='$crsID'>
                            <input type ='hidden' name ='crsCode' value ='$crsCode'>
                            <input type ='hidden' name ='crsName' value ='$crsName'>
                            <input type ='hidden' name ='goto' value ='addQuestion'>
                            <input type ='submit' class='button' value ='Add Question'/>
                        </form>";
                    echo "<form name='Enrolled Students' action='editCourses' method='POST'>
                            <input type ='hidden' name ='crsID' value ='$crsID'>                        
                            <input type ='hidden' name ='crsCode' value ='$crsCode'>
                            <input type ='hidden' name ='crsName' value ='$crsName'>
                            <input type ='hidden' name ='goto' value ='viewEnrolled'>
                            <input type ='submit' class='button' value ='Enrolled Students'/>
                        </form>";
                } else {
                    echo "<form name ='Remove Course' action ='removeCourse' method='POST'>                    
                    <input type ='hidden' name ='courseID' value ='$crsCode'/>
                    <input type ='hidden' name ='clientID' value ='$clntID'/>
                    <input type ='hidden' name ='goto' value ='removeCourse'>
                    <input type ='submit'  class='button'  value ='Remove'/>
                </form>
                <form name ='Take test' action ='editCourses' method='POST'>
                    <input type ='hidden' name ='crsID' value ='$crsID'>      
                    <input type ='hidden' name ='courseID' value ='$crsCode'/>
                    <input type ='hidden' name ='clientID' value ='$clntID'/>
                    <input type ='hidden' name ='goto' value ='takeTest'>
                    <input type ='submit' class='button' value ='Take Test'/>
                </form>";
                }
                ?>
            </td>
            <?php
            echo "</tr>\n";
        endwhile;
        //echo "<p>Course=" . $_SESSION['crsCode'] . "</p>";
        mysqli_free_result($result);
        ?>
    </table>
    <?php
    if ($_SESSION['clientType'] == 'INST') {
        echo '<br><form name="Add new course" action="addNewCourse">
                  <input type ="submit" class="button animated" value ="Add new course"/></form>';
    } else {
        echo '<br><form name="AddItemToCollection" action="courseList">
        <input type ="submit" class="button animated" value ="Add course"/></form>';
    }
    ?> 
    <a href="home"><button class="button animated">Back</button></a>
    <br><br>
    <a href="index"><button class="button animated">Logout</button></a>
</body>
</html>
