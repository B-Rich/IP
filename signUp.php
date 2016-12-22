<?php
require_once ("DB/DataBase.php");
$regUsr = false;
$usrNmEmpty = false;
$invalidPwd = false;
$pwdEmpty = false;
$pwdConfirmEmpty = false;


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if ($_POST['userFN'] == "" ||
            $_POST['userLN'] == "") {
        $usrNmEmpty = true;
    }
    $IDMD5 = substr(MD5($_POST['userFN'] . $_POST['userLN']),0,8);
    echo $IDMD5;
    echo $_POST['email'];
    $returnUsr = DataBase::getInstance()->checkExisting($IDMD5,$_POST['email']);
    if ($returnUsr) {
        $regUsr = true;
    }

    if ($_POST['pwrd'] == "") {
        $pwdEmpty = true;
    }
    if ($_POST['pwrdConfirm'] == "") {
        $pwdConfirmEmpty = true;
    }
    if ($_POST['pwrd'] !== $_POST['pwrdConfirm']) {
        $invalidPwd = true;
    }
    $validated = !$usrNmEmpty && !$regUsr && !$pwdEmpty && !$pwdConfirmEmpty && !$invalidPwd;
    
    if ($validated) {
        require ("DB/PDODataBase.php");
        try {
            $Query = "INSERT INTO clients SET FirstName =?, LastName=?,"
                    . "Gender=?, ID=?, DateOfBirth=?, "
                    . "Password=?, Email=?, Username=?, "
                    . "Profession=?, AccessType='Active'";
            $prepareQuery = $dbConPDO->prepare($Query);
            $temp = array($_POST['userFN'], $_POST['userLN'],
                $_POST['gender'], $IDMD5,
                $_POST['dob'], $_POST['pwrd'], $_POST['email'],
                $_POST['username'], $_POST['profession']);
            if ($prepareQuery->execute($temp)) {
                echo"<script>alert('Record was saved.'); location.href='index'</script>";
            } else {
                echo $prepareQuery->execute($temp);
            }
        } catch (PDOException $PDOe) {
            echo "Failure: " . $PDOe->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="main.css" rel="stylesheet" type="text/css">
        <title>Register an account</title>
    </head>
    <body background="bckgrnd.jpg">
        <form action="signUp" method="POST">
            <fieldset>
                <legend>Create new account:</legend>
                Username:<br>
                <input type="text" name="username" placeholder="e.g. John007"><br><br>                
                Firstname:<br>
                <input type="text" name="userFN" placeholder="e.g. John"><br><br>
                Lastname:<br>
                <input type="text" name="userLN" placeholder="e.g. Legend"><br><br>
                Date of birth:<br>
                <input type="text" name="dob" placeholder="e.g. 1995-1-1"><br><br>                
                <?php
                if ($usrNmEmpty) {
                    echo "<script>Name field(s) not filled, please fill it</script>";
                    echo ("<p>Name field(s) not filled, please fill it.</p>");
                    echo ("<br/>");
                }
                if ($regUsr) {
                    echo "<script>You cannot create multiple accounts</script>";
                    echo ("<p>You cannot create multiple accounts</p>");
                    echo ("<br/>");
                }
                ?>
                Gender:
                <select name="gender">
                    <option value="">Select</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select><br><br>
                Profession:
                <select name="profession">
                    <option value="">Select</option>
                    <option value="STDT">Student</option>
                    <option value="INST">Instructor</option>
                </select><br><br>
                Email:<br>
                <input type="text" name="email" placeholder="e.g. example@example.com"><br><br>
                <!--ID:<br>
                <input type="text" name="id" placeholder="e.g. 13S44, 09I55"><br><br> -->               
                Password:<br>
                <input type="password" name="pwrd" placeholder="Password must be at least 8 characters long"><br><br>
                <?php
                if ($pwdConfirmEmpty) {
                    echo "<script>alert('Please confirm your password')</script>";
                    echo ("<p>Please confirm your password</p>");
                    echo ("<br/>");
                }
                ?>
                Confirm password:<br>
                <input type="password" name="pwrdConfirm" placeholder="Re-enter your password"><br><br>
                <?php
                if ($pwdConfirmEmpty) {
                    echo "<script>alert('Please confirm your password code')</script>";
                    echo ("<p>Please confirm your password</p>");
                    echo ("<br/>");
                }
                if (!$pwdConfirmEmpty && $invalidPwd) {
                    echo "<script>alert('The pwrd codes are not matching')</script>";
                    echo("<p>The passwords are not matching</p>");
                    echo("<br/>");
                }
                ?>
                <input class="button animated" type="submit" value="Submit">
            </fieldset>
        </form>
    </body>
</html>