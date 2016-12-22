<?php
//require ("DB/PDODataBase.php");
require_once ("DB/DataBase.php");
$accNumUsed = false;
$usrNmEmpty = false;
$invalidPwd = false;
$pwdEmpty = false;
$pwdConfirmEmpty = false;


 if ($_SERVER['REQUEST_METHOD'] == "POST") {
//if(isset($_POST['SignUp'])){
    if ($_POST['userFN'] == "" ||
            $_POST['userMN'] == "" ||
            $_POST['userLN'] == "") {
        $usrNmEmpty = true;
    }

    $accNum = DataBase::getInstance()->getClientID($_POST['userFN'], $_POST['userMN'], $_POST['userLN']);
    if ($accNum) {
        $accNumUsed = true;
    }

    if ($_POST['PIN'] == "") {
        $pwdEmpty = true;
    }
    if ($_POST['PINConfirm'] == "") {
        $pwdConfirmEmpty = true;
    }
    if ($_POST['PIN'] !== $_POST['PINConfirm']) {
        $invalidPwd = true;
    }
    if (!$usrNmEmpty && !$accNumUsed &&
            !$pwdEmpty && !$pwdConfirmEmpty && !$invalidPwd) {
        require ("DB/PDODataBase.php");
        try{
            $Query = "INSERT INTO dataTable SET FirstName =?, MiddleName=?, LastName=?,"
                    . "Gender=?, Address=?, ContactNumber=?, DateOfBirth=?, "
                    . "PIN=?, AccountNum=?, Email=?, Username=?, AccessType='Active', Balance = 0";
            $prepareQuery = $dbConPDO->prepare($Query);
            $accNumMD5 = MD5($_POST['userFN'].$_POST['userMN'].$_POST['userLN']);
            //echo $accNumMD5;
            //echo $_POST['DateOfBirth'];
            $temp = array($_POST['userFN'],$_POST['userMN'],$_POST['userLN'],
                          $_POST['Gender'],$_POST['Address'],$_POST['ContactNumber'],
                          $_POST['DateOfBirth'],$_POST['PIN'], $accNumMD5, $_POST['Email'],
                          $_POST['Username']);
            if($prepareQuery->execute($temp)){
                echo"<script>alert('Record was saved.'); location.href='index.php'</script>";
            }else{
                echo $prepareQuery->execute($temp);
                echo "<font size =12>FAIL</font>";
            }
        } catch (PDOException $PDOe) {
            echo "Failue: ". $PDOe->getMessage();
        }
        /*session_start();
        $_SESSION['userFN'] = $_POST['userFN'];
        $_SESSION['userMN'] = $_POST['userMN'];
        $_SESSION['userLN'] = $_POST['userLN'];
        //header('Location: myCollection.php');
        header('Location: index.php');
        exit;*/
    }
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>"
    <head><meta charset=UTF-8"></head>
    <body background="bckgrnd.jpg">
    <!center>
        <font face = "Candara" color = "white"> Greetings, customer!</font><br>
        <form action ="signUp.php" method ="POST">
            <font face = "Candara" color = "white">Username : </font>
            <input type = "text" name ="Username"/><br><br>
            <font face = "Candara" color = "white">First Name : </font>
            <input type = "text" name ="userFN"/><br><br>
            <font face = "Candara" color = "white">Middle Name : </font>
            <input type = "text" name ="userMN"/><br><br>
            <font face = "Candara" color = "white">Last Name : </font>
            <input type = "text" name ="userLN"/><br><br>
            <?php
            if ($usrNmEmpty) {
                echo "<script>Name field(s) not filled, please fill it</script>";
                echo ("Name field(s) not filled, please fill it.");
                echo ("<br/>");
            }
            if ($accNumUsed) {
                echo "<script>You cannot create multiple accounts</script>";
                echo ("You cannot create multiple accounts");
                echo ("<br/>");
            }
            ?>
            <font face = "Candara" color = "white">Gender :</font>
            <select name="Gender">
                <option value="">Select...</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select><br><br>
            <font face = "Candara" color = "white">Address : </font></font>
            <input type = "text" name ="Address"/><br><br>
            <font face = "Candara" color = "white">Contact Number : </font>
            <input type = "text" name ="ContactNumber"/><br><br>
            <font face = "Candara" color = "white">Date of Birth : </font>
            <input type = "text" name ="DateOfBirth"/><br><br> <!input type = "text" name ="DateOfBirth"/<brbr>
            <font face = "Candara" color = "white">Email : </font>
            <input type = "text" name ="Email"/><br><br>
            <!PIN : <input type="PIN" pattern="[0-9]*" name ="PIN"/>
            <font face = "Candara" color = "white">PIN : </font>
            <input type="PIN" pattern="[0-9]*{4}" name ="PIN"/><br><br>
            <?php
            if ($pwdEmpty) {
                echo "<script>alert('Enter the desired PIN code')</script>";
                echo ("Enter the desired PIN code");
                echo ("<br/>");
            }
            ?>
            <font face = "Candara" color = "white">Confirm PIN : </font>
            <input type = "PIN" name ="PINConfirm"/><br><br>
            <input type ="submit" name="SignUp" value ="Sign up"/><br><br>
            <!/center>
            <?php
            if ($pwdConfirmEmpty) {
                echo "<script>alert('Please confirm your PIN code')</script>";
                echo ("Please confirm your PIN code");
                echo ("<br/>");
            }
            if (!$pwdConfirmEmpty && $invalidPwd) {
                echo "<script>alert('The PIN codes are not matching')</script>";
                echo("<div>The PIN codes are not matching</div>");
                echo("<br/>");
            }
            ?>
            <a href="index.php"><font face = "Candara" size = "6" color = "blue">Back to main page</font></a>
        </form>
    </body>
</html>