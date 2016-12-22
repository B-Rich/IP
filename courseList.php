<?php
session_start();
if(array_key_exists("user", $_SESSION)){
    $sessionUser = $_SESSION['user'];
    $sessionUserNm = $_SESSION['userNm'];
}else{
    header('Location: index');
    exit;
}
include("toolbar.php");
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="main.css" rel="stylesheet" type="text/css">
        <title>View available courses</title>
        </head>
    <body>
        <table>
            <tr>
                <th>Course Name</th>
                <th>Course Code</th>
                <th>Description</th>
            </tr>
            <?php
            require_once ("DB/DataBase.php");
            $clntID = DataBase::getInstance()->getClientID($sessionUser);
            $result = DataBase::getInstance()->getCourses();
            while($row = mysqli_fetch_array($result)):
                $shrt = array('Name','Code','Description');
                for($i = 0; $i < count($shrt); $i++){
                 echo '<td>'.htmlentities($row[$shrt[$i]]).'</td>';
                }
                $crsID = $row['ID'];
                //echo "<td>GameID=" . $crsID . "</td>";
            ?>
            <td>
                <form name ="customizeCollection" action ="addCourse" method="POST">
                    <input type ="hidden" name ="courseID" value ="<?php echo $crsID;?>"/>
                    <input type ="hidden" name ="clientID" value ="<?php echo $clntID;?>"/>
                    <input class="button animated" type ="submit" name ="addCourse" value ="Add course"/>
                </form>
            </td>
            <?php
            echo "</tr>\n";
            endwhile;
            mysqli_free_result($result);
            ?>
        </table>
        <br>
        <form name="AddItemToCollection" action="editCourses">
            <input class="button animated" type ="submit" value ="Previous Page"/>
        </form>
        <form name="ReturnToHomePage" action="index">
            <input class="button animated" type ="submit" value ="Return To Home Page"/>
        </form>
    </body>
</html>
