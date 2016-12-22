<?php
require_once ("DB/DataBase.php");
DataBase::getInstance()->addItem($_POST['GameID'],$_POST['UserID']);
//redirection
header('Location: customizeCollection.php');
?>