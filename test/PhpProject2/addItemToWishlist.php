<?php
require_once ("DB/DataBase.php");
DataBase::getInstance()->addItemToWishlist($_POST['GameID'],$_POST['UserID']);
//redirection
header('Location: customizeCollection.php');
?>