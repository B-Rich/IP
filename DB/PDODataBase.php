<?php
try{
$dbConPDO = new PDO('mysql:host=localhost;dbname=XamTable','root','',array(PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION));
}catch(PDOException $PDOe){
    echo "Failue: " .$PDOe->getMessage();
}
?>

