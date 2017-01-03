<?php
require_once ("DB/DataBase.php");
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

if ($_SERVER ['REQUEST_METHOD'] == "POST") {
    $usrInfo = $_POST['user'];
    $usrpswd = $_POST['usrpwd'];
    require ("DB/PDODataBase.php");
    $secureLogin = $dbConPDO->prepare("SELECT 1 FROM clients WHERE (Username = :usr OR Email = :usr ) AND Password = :pwd");
    $secureLogin->bindParam(':usr', $usrInfo);
    $secureLogin->bindParam(':pwd', $usrpswd);
    $secureLogin->execute();
    $res = $secureLogin->rowCount();
    if ($res > 0) {
        $signInOK = true;
        session_start();
        $_SESSION['user'] = $_POST['user'];
        $_SESSION['userNm'] = DataBase::getInstance()->getClientID($_POST['user'],TRUE);
        $_SESSION['clientType'] = DataBase::getInstance()->getClientType($_POST['user']);
        header('Location: home');
        exit;
    } else {
        
    }
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="main.css" rel="stylesheet" type="text/css">
    <title>Online course testing</title>
</head>
<body> <!--background="bckgrnd.jpg"-->
    <form name="Menu" action="myCollection" method="GET">
        <center><h1>Welcome to Meshos Online Testing Service </h1></center>
    </form>
    <h2>Still not a part of the community?</h2><a href="signUp"><h3>Enroll yourself today!</h3></a>
    <form name="logon" action="index" method="POST" >
        <fieldset>
        <legend>Enter your login information:</legend>
        Username or E-mail:
        <input type="text" name="user"/><br><br>
        Password:
        <input type="password" name="usrpwd"/><br><br>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (!$signInOK) {
                echo "<script>alert('Invalid name and/or password')</script>";
            }
        }
        ?>
        <input class="button animated" type="submit" value="Login">
        <?php
        if ($usrNmEmpty || $pwdEmpty) {
            echo "<script>alert('Please enter your login credientials')</script>";
        }
        ?>
        </fieldset>
    </form>
</body>
</html>
