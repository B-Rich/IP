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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <body background="bckgrnd.jpg">
        <title>Game Library</title>
        </head>
    <body>
        <table border="black">
            <tr>
                <th><font face = "Candara" color = "white" size = "10">Game Title</font></th>
                <th><font face = "Candara" color = "white" size = "10">Release Date</font></th>
                <th><font face = "Candara" color = "white" size = "10">Platform</font></th>
                <th><font face = "Candara" color = "white" size = "10">Tags</font></th>
                <th><font face = "Candara" color = "white" size = "10">Price($)</font></th>
                <th><font face = "Candara" color = "white" size = "10">Rating</font></th>
                <th><font face = "Candara" color = "white" size = "10">Option(s)</font></th>
            </tr>
            <?php
            require_once ("DB/DataBase.php");
            $usr = DataBase::getInstance()->getClientID($sessionUser);
            $result = DataBase::getInstance()->getCollection();
            while($row = mysqli_fetch_array($result)):
                echo '<th><font face = "Candara" color = "green" size = "5">' . htmlentities($row['Title']).'</font></th>';
                echo '<th><font face = "Candara" color = "green" size = "5">' . htmlentities($row['ReleaseDate']).'</font></th>';  
                echo '<th><font face = "Candara" color = "green" size = "5">' . htmlentities($row['Platform']).'</font></th>';
                echo '<th><font face = "Candara" color = "green" size = "5">' . htmlentities($row['Tags']).'</font></th>';
                echo '<th><font face = "Candara" color = "green" size = "5">' . htmlentities($row['Price']).'</font></th>';
                echo '<th><font face = "Candara" color = "green" size = "5">' . htmlentities($row['Rating']).'</font></th>';
                $GameID = $row['GameID'];
                //echo "<td>GameID=" . $GameID . "</td>";
            ?>
            <td>
                <form name ="customizeCollection" action ="addItem.php" method="POST">
                    <input type ="hidden" name ="GameID" value ="<?php echo $GameID;?>"/>
                    <input type ="hidden" name ="UserID" value ="<?php echo $usr;?>"/>
                    <input type ="submit" name ="cstColl" value ="Add(Imp)"/>
                </form>
                <form name ="customizeCollection" action ="addItemToWishlist.php" method="POST">
                    <input type ="hidden" name ="GameID" value ="<?php echo $GameID;?>"/>
                    <input type ="hidden" name ="UserID" value ="<?php echo $usr;?>"/>
                    <input type ="submit" name ="cstColl" value ="Add to Wishlist(Imp)"/>
                </form>
            </td>
            <?php
            echo "</tr>\n";
            endwhile;
            mysqli_free_result($result);
            ?>
        </table>
        <form name="AddItemToCollection" action="customizeCollection.php">
            <input type ="submit" value ="Previous Page"/>
        </form>
        <form name="ReturnToHomePage" action="index.php">
            <input type ="submit" value ="Return To Home Page"/>
        </form>
    </body>
</html>
