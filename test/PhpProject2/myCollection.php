<?php
session_start();
if(array_key_exists("user", $_SESSION)){
    $sessionUser = $_SESSION['user'];
    echo "Welcome, ".$_SESSION['user'];
}else{
    header('Location: index.php');
    exit;
}
?>
<?php
   require_once ("DB/DataBase.php");
   if ($_SERVER ['REQUEST_METHOD'] == "POST"){
                session_start();
                $_SESSION['user'] = $_POST['user'];
                header('Location: customizeCollection.php');
                exit;
            }
   ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <body background="bckgrnd.jpg">
        <title>Your collection</title>
        <font face = "Candara" color = "white" size = "10">My Collection</font> 
            <?php echo $sessionUser . "<br/>"; ?>
        <?php
        require_once("DB/DataBase.php");
        $accNum = DataBase::getInstance()->getClientID($sessionUser);
        echo $accNum;
        //$accNum = DataBase::getInstance()->getClientID($_GET['userFN'],$_GET['userMN'],$_GET['userLN']);
        if (!$accNum) {
            exit("The client " . $sessionUser ." is not found. Please check the spelling and try again");
            //exit("The client " . $_GET['userFN'] . " " . $_GET['userFN'] ." ". $_GET['userFN'] ." is not found. Please check the spelling and try again");
        }
        //name-status-metascore-last played
        ?>
        <table border="black">
            <tr>
                <th><font face = "Candara" color = "white" size = "10">Game Title</font></th>
                <th><font face = "Candara" color = "white" size = "10">Release Date</font></th>
                <th><font face = "Candara" color = "white" size = "10">Platform</font></th>
                <th><font face = "Candara" color = "white" size = "10">Tags</font></th>
            </tr>
            <?php
            $result = DataBase::getInstance()->getUsrCollection($accNum);
            try{
            while($row = mysqli_fetch_array($result)){
                echo '<th><font face = "Candara" color = "green" size = "5">' . htmlentities($row['Title']).'</font></th>';
                echo '<th><font face = "Candara" color = "green" size = "5">' . htmlentities($row['ReleaseDate']).'</font></th>';  
                echo '<th><font face = "Candara" color = "green" size = "5">' . htmlentities($row['Platform']).'</font></th>';
                echo '<th><font face = "Candara" color = "green" size = "5">' . htmlentities($row['Tags']).'</font></th>';
                echo"<tr><td></tr>";
            }
            mysqli_free_result($result);
            }catch(Exception $e){echo "<script>alert('You currently have no items in your collection, go get some.'); location.href='myCollection.php'</script>";}
            ?>
        </table>
        <form name="Customize Collection" action="customizeCollection.php">
            <input type ="submit" value ="Customize Collection"/>
        </form>
    </body>
</html>