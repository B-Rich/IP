<?php
require_once ("DB/DataBase.php");
DataBase::getInstance()->removeItem($_POST['GameID'],$_POST['UserID']);
header('Location: customizeCollection.php');
?>