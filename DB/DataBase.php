<?php

class DataBase extends mysqli {

    private static $inst = null;
    private $UserID = "root";
    private $Pwd = "";
    private $dBID = "XamTable";
    private $Server = "localhost";

    public static function getInstance() {
        if (!self::$inst instanceof self) {
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
        parent::__construct($this->Server, $this->UserID, $this->Pwd, $this->dBID);
        if (mysqli_connect_error()) {
            exit('Connection error (' .
                    mysqli_connect_errno() . ') ' .
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

    public function getClientID($sessionUser,$name=FALSE) {
        //echo $sessionUser;
        //$check = strpos($sessionUser,"@");
        /* if(strpos($sessionUser,"@") == NULL){
          echo "Username";
          $sessionUser = substr(md5($sessionUser),0,8);
          echo $sessionUser;
          } */
        $ID = $this->query(
                "SELECT ID FROM clients WHERE "
                . "Username ='" . $sessionUser . "' OR "
                . "Email ='" . $sessionUser . "'");
        if($name){
            $ID = $this->query(
                "SELECT FirstName FROM clients WHERE "
                . "Username ='" . $sessionUser . "' OR "
                . "Email ='" . $sessionUser . "'");
        }
        if ($ID->num_rows > 0) {
            $trgtTuple = $ID->fetch_row();
            return $trgtTuple[0];
        } else
        //return "Not found"; 
            return "Error";
    }
    public static function randomizer($min=0, $max=3, $quantity=4) {
        $choices = range($min, $max);
        shuffle($choices);
        return array_slice($choices, 0, $quantity);
    }

    public function checkExisting($clntID,$clntMail) {
        return $this->query("SELECT 1 FROM `clients` WHERE ID = " . $clntID . " OR Email = " . $clntMail);
    }

    public function removeCourse($crsID, $clntID) {
        require ("DB/PDODataBase.php");
        $statement = "DELETE FROM enrolled_in WHERE courseID =? AND clientID =? LIMIT 1";
        $queryPDO = $dbConPDO->prepare($statement);
        if ($queryPDO->execute(array($crsID, $clntID))) {
            echo "<script>alert('Course successfully removed from collection')</script>";
            return TRUE;
        } else {
            echo "<script>alert('Error!')</script>";
            return FALSE;
        }
    }

    //public static function addQuestion($newQuest) {
    public static function addQuestion($questFile, $newQuest) {
        file_put_contents($questFile, "\n" . $newQuest, FILE_APPEND | LOCK_EX);
    }

    public static function prepareQuestion($chosenQuest) {
        $questBuffer = explode(" # ", $chosenQuest);
        $quest = $questBuffer[0];
        print($quest . "\n");
        $potAns = explode(";", $questBuffer[1]);

        foreach ($potAns as &$ans)
            print($ans . "\n");

        $corrAns = $questBuffer[2];
        print($corrAns . "\n");
    }

    public function getClientCourses($clntID) {
        return $this->query("SELECT * FROM courses JOIN enrolled_in ON courses.ID = enrolled_in.courseID WHERE clientID = '" . $clntID . "'");
    }
    
    public function getCourseName($crsCode) {
        return $this->query("SELECT Name FROM courses WHERE ID = '" . $crsCode . "'");
    }
    
    public function addCourse($crsID, $clntID,$test) {
	$test = 'Yolo';
        require ("DB/PDODataBase.php");
        $statement = "INSERT INTO enrolled_in(clientID,courseID) SELECT ?, ? "
                . "WHERE NOT EXISTS(SELECT * FROM enrolled_in WHERE courseID = ? AND clientID = ?)";
        $queryPDO = $dbConPDO->prepare($statement);
        if ($queryPDO->execute(array($clntID, $crsID, $crsID, $clntID))) {
            echo "<script>alert('$test successfully added to your courses')</script>";
            return TRUE;
        } else {
            echo "<script>alert('Error!')</script>";
            return FALSE;
        }
    }
    
    public function updateMark($crsID, $clntID,$mark) {
        require ("DB/PDODataBase.php");
        $statement = "UPDATE `enrolled_in` SET `mark`=? WHERE `courseID`=? AND `clientID`=?";
        $queryPDO = $dbConPDO->prepare($statement);
        if ($queryPDO->execute(array($mark, $crsID, $clntID))) {
            echo "<script>alert('Mark successfully updated')</script>";
            return TRUE;
        } else {
            echo "<script>alert('Error!')</script>";
            return FALSE;
        }
    }
    
    public function addNewCourse($crsNm, $crsCode,$crsDesc) {
        require ("DB/PDODataBase.php");
        try{
        $statement = "INSERT INTO courses(Name,Code,Description) SELECT ?, ?, ? "
                . "WHERE NOT EXISTS(SELECT * FROM courses WHERE Name = ? AND Code = ?)";
        $queryPDO = $dbConPDO->prepare($statement);
        if ($queryPDO->execute(array($crsNm, $crsCode, $crsDesc, $crsNm, $crsCode))) {
            echo "<script>alert('$crsNm successfully added to available courses')</script>";
            return TRUE;
        } else {
            echo "<script>alert('Error!')</script>";
            return FALSE;
        }}catch(Exception $e){
            echo "<script>alert('Error!')</script>";
        }
    }

    public function getCourses() {
        return $this->query("SELECT * FROM `courses`");
    }
    
    public function viewEnrolled($courseID) {
        //"SELECT * FROM `enrolled_in` JOIN `courses` ON courseID = ID WHERE courseID = $courseID"
        //return $this->query("SELECT * FROM `enrolled_in` JOIN `courses` ON courseID=ID WHERE `courses`.`Code` = $crsCode");
        return $this->query("SELECT FirstName, LastName, mark FROM `enrolled_in` "
                . "JOIN `clients` ON clientID =`clients`.`ID` WHERE courseID = $courseID");
    }

    public function getClientInfo($clntID) {
        return $this->query("SELECT * FROM `clients` WHERE ID = '" . $clntID . "'");
    }
    
    public function getClients() {
        return $this->query("SELECT * FROM `clients` ");
    }
    
    
    public function getCN($crsCode) {
        return $this->query("SELECT `Name` FROM `courses` WHERE `Code` = $crsCode");
    }

}
