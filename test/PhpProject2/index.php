<?php
   require_once ("DB/DataBase.php");
   //require_once ("DB/PDODataBase.php");
    $usrNmEmpty = false;
    $pwdEmpty = false;
    $signInOK = false;

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if ($_POST['user'] == "") {
            $usrNmEmpty = true;
        }
        
        if ($_POST['usrpwd'] == "") {
            $pwdEmpty = true;
        }
    }    

   if ($_SERVER ['REQUEST_METHOD'] == "POST"){
       /*$dbInst = Database::getInstance();
       //$dbInstPDO = $dbCon;
       //$signInOK = ($dbInstPDO->verifyClientPDO($_POST['user'], $_POST['usrpwd']));
            $signInOK = ($dbInst->verifyClient($_POST['user'], $_POST['usrpwd']));
            if($signInOK == true){
                session_start();
                $_SESSION['user'] = $_POST['user'];
                //$_SESSION['userFN'] = $_POST['userFN'];
                //$_SESSION['userMN'] = $_POST['userMN'];
                //$_SESSION['userLN'] = $_POST['userLN'];
                //header('Location: myCollection.php');*/
                //require ("DB/PDODataBase.php");
                $usrInfo = $_POST['user'];
                $usrpswd = $_POST['usrpwd'];
                //$loginQuery = $dbConPDO->query("SELECT 1 FROM dataTable WHERE (Username = '$usrInfo' OR Email = '$usrInfo') AND PIN = '$usrpswd'");
                require ("DB/PDODataBase.php");
                $secureLogin = $dbConPDO->prepare("SELECT 1 FROM dataTable WHERE (Username = :usr OR Email = :usr ) AND PIN = :pwd");
                $secureLogin->bindParam(':usr',$usrInfo);
                $secureLogin->bindParam(':pwd',$usrpswd);
                $secureLogin->execute();
                //$res = $loginQuery->rowCount();
                $res = $secureLogin->rowCount();
                if($res > 0){
                    $signInOK = true;
                    session_start();
                    $_SESSION['user'] = $_POST['user'];    
                    header('Location: myCollection.php');
                exit;
            }else{
                
            }
        }
        ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <body background="bckgrnd.jpg">
        <title>Welcome to GZ-Lan Game Store, Your number one source for games</title>
    </head>
    <body>
        <form name="Menu" action="myCollection.php.php" method="GET">
            <center><font face = "Candara" size = "32" color = "white">Welcome to GZ-Lan Game Store </font></center>
            <!input type="text" name="user"/>
            <!input type="submit" value="Go" />
        </form>
        <font face = "Candara" size = "12" color = "white">Still not a part of the GZ-Lan Community?</font> 
        <a href="signUp.php"><font face = "Candara" size = "6" color = "blue">Sign Up Now</font></a>
        <form name="logon" action="index.php" method="POST" >
            <font face = "Candara" size = "12" color = "white">Username or E-mail:</font> 
            <input type="text" name="user"/><br><br>
            <font face = "Candara" size = "12" color = "white">Password:</font>  
            <input type="password" name="usrpwd"/><br><br>
            <!input type="submit" value="Login"/>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST") {
                if (!$signInOK){
                    //echo"<script>alert('Record was saved.')</script>";
                    echo "<script>alert('Invalid name and/or password')</script>";
                }
            }
            ?>
            <input type="submit" value="Login"/>
            <?php
            if($usrNmEmpty || $pwdEmpty){
                    echo "<script>alert('Please enter your login credientials')</script>";
            }
            ?>
        </form>
    </body>
</html>
