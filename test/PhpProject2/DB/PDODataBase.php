<?php
try{
//$dbCon = new PDO('mysql:host=localhost;dbname=dataTable;charset=utf8mb4','root','');
$dbConPDO = new PDO('mysql:host=localhost;dbname=dataTable','root','',array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION));
//$dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//$dbCon->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
}catch(PDOException $PDOe){
    echo "Failue: " .$PDOe->getMessage();
}
?>

