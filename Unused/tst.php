<?php
    try{
        if($tstPDO = new PDO('mysql:host=localhost; dbname=datatable','root','')){
            echo 'Success, connection established';
            echo '<br>';
            //$statement = "INSERT INTO gameTable VALUES(?,'TestTitle7','2016-04-15','Windows','TestTag7',0.0,'Average')";
            //$queryPDO->bindParam(1, 'Osama');
            $statement = "SELECT * FROM gameTable";
            foreach($tstPDO->query($statement,PDO::FETCH_ASSOC) as $tuple){
                //implode displays results
                echo implode(':', $tuple).PHP_EOL;
            }
            //$statement = "SELECT 1 FROM dataTable WHERE (Username = ? OR Email = ?) AND PIN = ?";
            //$statement = "DELETE FROM collection WHERE GameID =? AND AccountNum =? LIMIT 1";
            $statement = "INSERT INTO collection(AccountNum,GameID) SELECT ?, ? WHERE NOT EXISTS(SELECT * FROM collection WHERE GameID = ? AND AccountNum = ?)";
            $queryPDO = $tstPDO->prepare($statement);
            //if($queryPDO->execute(array('testUID','test@testmail.com',5555))){
            if($queryPDO->execute(array('58486','0007','0007','58486'))){
                //echo '<script>$queryPDO->fetchAll()</script>';
                echo "<script>alert('Game successfully added to collection')</script>";
                echo 'Success';
            }  else{
                echo 'Failed';
            }
        }
    } catch (Exception $ex) {
        echo 'Failed: ' . $ex->getMessage();
    }
?>

