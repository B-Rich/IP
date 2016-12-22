<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <body background="bckgrnd.jpg">
        <font face = "Candara" color = "white" size = "10">My Collection</font> 
            <?php echo $_GET['user'] . "<br/>"; ?>
        <?php
        require_once("DB/DataBase.php");
        //$accNum = DataBase::getInstance()->getClientID($_GET['user']);
        $accNum = 58486;//DataBase::getInstance()->getClientID($_GET['userFN'],$_GET['userMN'],$_GET['userLN']);
        if (!$accNum) {
            //exit("The client " . $_GET['user'] ." is not found. Please check the spelling and try again");
            exit("The client " . $_GET['userFN'] . " " . $_GET['userFN'] ." ". $_GET['userFN'] ." is not found. Please check the spelling and try again");
        }
        ?>
        <table border="black">
            <tr>
                <th><font face = "Candara" color = "white" size = "10">Name</font></th>
                <th><font face = "Candara" color = "white" size = "10">Status</font></th>
                <th><font face = "Candara" color = "white" size = "10">Metascore</font></th>
                <th><font face = "Candara" color = "white" size = "10">Last Played</font></th>
            </tr>
            <?php
            $result = DataBase::getInstance()->getInfo($accNum);
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr><td>" . htmlentities($row['FirstName']) . "</td>";
                echo "<tr><td>" . htmlentities($row['MiddleName']) . "</td>";
                echo "<tr><td>" . htmlentities($row['LastName']) . "</td>";
                echo "<tr><td>" . htmlentities($row['Gender']) . "</td>";
                echo "<tr><td>" . htmlentities($row['Address']) . "</td>";
                echo "<tr><td>" . htmlentities($row['ContactNumber']) . "</td>";
                echo "<tr><td>" . htmlentities($row['DateOfBirth']) . "</td>";
                echo "<td>" . htmlentities($row['Balance']) . "</td></tr>\n";
            }
            mysqli_free_result($result);
            ?>
        </table>
        <form name="Customize Collection" action="customizeCollection.php">
            <input type ="submit" value ="Customize Collection"/>
        </form>
    </body>
</html>