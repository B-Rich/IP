<?php
class DataBase extends mysqli{
    private static $inst = null;
    private $UserID = "root";
    private $Pwd = "";
    private $dBID = "dataTable";
    private $Server = "localhost";
    
    public static function getInstance(){
        if(!self::$inst instanceof self){
            self::$inst = new self;
        }
        return self::$inst;
    }
    
    public function __clone() {
        trigger_error('Cannot create another instance', E_USER_ERROR);
    }
    
    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }
    
    public function __construct() {
        parent::__construct($this->Server, $this->UserID, 
                $this->Pwd, $this->dBID);
        if(mysqli_connect_error()){
            exit('Connection error (' . 
                    mysqli_connect_errno() . ') '.
                    mysqli_connect_error());
        }
        //parent::set_charset('UTF-8');
    }
    
    //revisit
    function dateForSQL($date) {
        if ($date == "")
            return null;
        else {
            $dateParts = date_parse($date);
            return $dateParts['year'] * 10000 + $dateParts['month'] * 100 + $dateParts['day'];
        }
    }
    
    //Email login?
    /*public function getClientID($Fname,$Mname,$Lname){
        $CFname = $this->real_escape_string($Fname);
        $CMname = $this->real_escape_string($Mname);
        $CLname = $this->real_escape_string($Lname);
        $ID = $this->query(
                "SELECT AccountNum FROM dataTable WHERE "
                . "FirstName ='" . $CFname . "' AND "
                . "MiddleName ='" . $CMname . "' AND "
                . "LastName ='" . $CLname . "'" );
        if ($ID->num_rows > 0){
            $trgtTuple = $ID->fetch_row();
            return $trgtTuple[0];
        } else
            //return "Not found"; 
            return null;
    }*/
    public function getClientID($sessionUser){
        $ID = $this->query(
                "SELECT AccountNum FROM dataTable WHERE "
                . "Username ='" . $sessionUser . "' OR "
                . "Email ='" . $sessionUser . "'" );
        if ($ID->num_rows > 0){
            $trgtTuple = $ID->fetch_row();
            return $trgtTuple[0];
        } else
            //return "Not found"; 
            return null;
    }
    
    public function getBalance($accNum){
        /*$CANum = $this->real_escape_string($accNum);
        return $this->query("SELECT Balance FROM dataTable WHERE AccountNum=" . $accNum);
        if ($SCANum->num_rows > 0){
            $trgtTuple = $SCANum->fetch_row();
            return $trgtTuple[0];
        } else
            return "Not found"; 
            return null;*/
        return $this->query("SELECT Balance FROM dataTable WHERE AccountNum=" . $accNum);
    }
    
    public function depositFunds($accNum, $addedFunds){
        $currBalance = $this->getBalance($accNum);
        $newBalance = $currBalance + $addedFunds;
        $this->query("UPDATE dataTable SET Balance = '"
                . $newBalance . "'" . "WHERE AccountNum = "
                . $accNum);
        $this->query("INSERT INTO transactionTable (AccountNum, EventTime, Amount)" .
                " VALUES (" . $accNum . ", '" . $addedFunds . "', "
                . $this->dateForSQL(time()) . ")");
    }
    
    public function withdrawFunds($accNum, $withdrawnFunds){
        $currBalance = $this->getBalance($accNum);
        $newBalance = $currBalance - $withdrawnFunds;
        if($newBalance < 0){
            trigger_error('Insufficent Funds', E_USER_ERROR);
            return;
        }else{
        $this->query("UPDATE dataTable SET Balance = '"
                . $newBalance . "'" . "WHERE AccountNum = "
                . $accNum);
        $this->query("INSERT INTO transactionTable (AccountNum, Amount, EventTime)" .
                " VALUES (" . $accNum . ", '" . $withdrawnFunds . "', "
                . $this->format_date_for_sql(time()) . ")");
        }
    }
    
    public function transferFunds($accNumFrom, $accNumTo, $funds){
        $currBalance = $this->getBalance($accNumFrom);
        $newBalance = $currBalance - $funds;
        if($newBalance < 0){
            trigger_error('Insufficent Funds', E_USER_ERROR);
            return;
        }else{
        /*$this->query("UPDATE dataTable SET Balance = '"
                . $newBalance . "'" . "WHERE AccountNum = "
                . $accNumFrom);
        $this->query("UPDATE dataTable SET Balance = '"
                . $newBalance . "'" . "WHERE AccountNum = "
                . $accNumTo);
        $this->query("INSERT INTO transactionTable (AccountNum, Amount, EventTime)" .
                " VALUES (" . $accNumFrom . ", '" . $funds . "', "
                . $this->format_date_for_sql(time()) . ")");*/
        $this->depositFunds($accNumTo, $funds);
        $this->withdrawFunds($accNumFrom, $funds);
        }
    }
            
    /*public function verifyClient($accNum, $PWD){
        $result = $this->query("SELECT 1 FROM dataTable WHERE AccountNum = "
                        . $accNum . " AND PIN = " . $PWD);
        return $result->data_seek(0);
    }*/
    
    public function verifyClient($accNum, $PWD){
        require ("DB/PDODataBase.php");
        $result = $dbConPDO->query("SELECT 1 FROM dataTable WHERE "
                . "(Username = ". $accNum ." OR Email = ". $accNum .") AND PIN =" . $PWD);
        return $result->data_seek(0);
    }
    
    public function removeItem($gameID,$accNum){
            require ("DB/PDODataBase.php");
            $statement = "DELETE FROM collection WHERE GameID =? AND AccountNum =? LIMIT 1";
            $queryPDO = $dbConPDO->prepare($statement);
            if($queryPDO->execute(array($gameID,$accNum))){
                echo "<script>alert('Game successfully removed from collection')</script>";
            }else{
                echo "<script>alert('Error!')</script>";
            }
    }
    
    public function addItem($gameID,$accNum){
            require ("DB/PDODataBase.php");
            $statement = "INSERT INTO collection(AccountNum,GameID) SELECT ?, ? "
                    . "WHERE NOT EXISTS(SELECT * FROM collection WHERE GameID = ? AND AccountNum = ?)";
            $queryPDO = $dbConPDO->prepare($statement);
            if($queryPDO->execute(array($accNum,$gameID,$gameID,$accNum,))){
                echo "<script>alert('Game successfully added to your collection')</script>";
            }else{
                echo "<script>alert('Error!')</script>";
            }
    }
    
    public function addItemToWishlist($gameID,$accNum){
            require ("DB/PDODataBase.php");
            /*$statement = "INSERT INTO Wishlist(AccountNum,GameID) SELECT ?, ? "
                    . "WHERE NOT EXISTS((SELECT * FROM collection WHERE GameID = ? AND AccountNum = ?) AND "
                    . "(SELECT * FROM Wishlist WHERE GameID = ? AND AccountNum = ?))";*/
            $statement = "INSERT INTO Wishlist(AccountNum,GameID) SELECT :accN, :gmID "
                    . "WHERE NOT EXISTS(SELECT * FROM collection WHERE GameID = :gmID AND AccountNum = :accN) AND "
                    . "NOT EXISTS(SELECT * FROM Wishlist WHERE GameID = :gmID AND AccountNum = :accN)";
            $queryPDO = $dbConPDO->prepare($statement);
            $queryPDO->bindParam(':accN',$accNum);
            $queryPDO->bindParam(':gmID',$gameID);
            //if($queryPDO->execute(array($accNum,$gameID,$gameID,$accNum,$gameID,$accNum))){
            if($queryPDO->execute()){
                echo "<script>alert('Game successfully added to your collection')</script>";
            }else{
                echo "<script>alert('Error!, either you already have the game or it is in your wishlist')</script>";
            }
    }
    
    public function getUsrCollection($accNum){
        //return $this->query("SELECT * FROM gameTable NATURAL JOIN collection WHERE AccountNum = " . $accNum);
        return $this->query("SELECT * FROM gameTable NATURAL JOIN collection WHERE AccountNum = '" . $accNum . "'");
    }
    
    public function getCollection(){
        return $this->query("SELECT * FROM gameTable");
    }
    
    public function getInfo($accNum) {
        return $this->query("SELECT * FROM dataTable WHERE AccountNum = " . $accNum);
    }
    
    public function getGameInfoByName($gameNm) {
        return $this->query("SELECT * FROM gameTable WHERE GameTitle LIKE '% " . $gameNm . "%'");
    }
    
    public function getGameInfoByCompany($gameCmpny) {
        return $this->query("SELECT * FROM gameTable WHERE GameCompany LIKE '% " . $gameCmpny . "%'");
    }
    
    public function getGameInfoByPrice($gamePrc) {
        return $this->query("SELECT * FROM gameTable WHERE GamePrice = " . $gamePrc);
    }
    
}
