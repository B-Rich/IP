<?php
session_start();
if(array_key_exists("user", $_SESSION)){
    $sessionUser = $_SESSION['user'];
    $sessionUserNm = $_SESSION['userNm'];
    echo "Welcome, ". $sessionUserNm;
}else{
    header('Location: index');
    exit;
}
?>
<?php
echo "\n".$_POST['clientID']." ".$_POST['courseID'];
function randomizer($min = 0, $max = 3, $quantity = 4) {
    $choices = range($min, $max);
    shuffle($choices);
    return array_slice($choices, 0, $quantity);
}

function prepareQuestion($course) {
    try {
        $qFileName = ".\Questions\questions" . $course . ".qbf";
        $qBankBuffer = fopen($qFileName, "rw") or die("Unable to open file!");
        $qBank = preg_split('/$\R?^/m', fread($qBankBuffer, filesize($qFileName)));
        $numOfQuest = sizeof($qBank);
        //$questOrd = randomizer(0,$numOfQuest,$numOfQuest);
        $questOrd = randomizer(0, $numOfQuest - 1, $numOfQuest - 1);
        $chosenQuest = $qBank[$questOrd[0]];
        $questBuffer = explode(" # ", $chosenQuest);
        $quest = $questBuffer[0];
        echo '<form action="takeTest" method="POST"><fieldset><legend>Choose the correct answer:</legend>';
        echo "<h1>$quest</h1>";
        $potAns = explode(";", $questBuffer[1]);
        $potAnsOrd = randomizer();
        foreach ($potAnsOrd as &$ord)
            echo "<input type='radio' name='ans' value='$potAns[$ord]'>$potAns[$ord]<br>";
        $corrAns = $questBuffer[2];
        //echo "<input type='hidden' name='corrAns' value='$corrAns'>";
        echo '<input type="submit" value="Submit"></fieldset></form>';
        return $corrAns;
        //echo "<p>Returned correct:".$GLOBALS['corrAns']."</p>";
        //echo "<p>Returned correct: $corrAns</p>";
    } catch (Exception $exp) {
        
    }
}
//session_start();
$cAns = $_SESSION['corrAns'];
$_SESSION['corrAns'] = prepareQuestion("ECE111");
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $chosenAnswer = $_POST['ans'];
    echo "<p>Correct: $cAns</p>";
    echo "<p>Chosen: $chosenAnswer</p>";
    if ($chosenAnswer == $cAns) {
        echo "<p>Well done</p>";
    } else {
        echo "<p>Incorrect</p>";
    }
}
?>
