<?php
session_start();
if (array_key_exists("user", $_SESSION)) {
    $sessionUser = $_SESSION['user'];
    $sessionUserNm = $_SESSION['userNm'];
    //echo "Welcome, " . $sessionUserNm;
} else {
    header('Location: index');
    exit;
}
?>
<?php

echo "\n" . $_SESSION['clientID'] . " " . $_SESSION['courseID'];
$qBank = $_SESSION['qBank'];
$questOrd = $_SESSION['questOrd'];
$qNum = $_SESSION['qNum'];

function randomizer($min = 0, $max = 3, $quantity = 4) {
    $choices = range($min, $max);
    shuffle($choices);
    return array_slice($choices, 0, $quantity);
}

function prepareQuestion($chosenQuest) {
    try {
        $questBuffer = explode(" # ", $chosenQuest);
        $quest = $questBuffer[0];
        echo '<!DOCTYPE HTML><html><head><script src="qFormVald.js"></script>'
        . '<link href="main.css" rel="stylesheet" type="text/css"></head>';
        echo '<form name="qaForm" action="takeTest" onsubmit="return checkQAForm()" method="POST">'
        . '<fieldset><legend>Choose the correct answer:</legend>';
        echo "<h1>$quest</h1>";
        $potAns = explode(";", $questBuffer[1]);
        $potAnsOrd = randomizer();
        foreach ($potAnsOrd as &$ord)
            echo "<input type='radio' name='ans' value='$potAns[$ord]'>$potAns[$ord]<br>";
        $corrAns = $questBuffer[2];
        echo '<input class="button" type="submit" value="Submit"></fieldset></form></body></html>';
        return $corrAns;
    } catch (Exception $exp) {
        
    }
}

//session_start();
echo "<title>Question ".($qNum+1)." out ".$_SESSION['numOfQuest']."</title>";
echo "<br> Question ".($qNum+1)." out ".$_SESSION['numOfQuest'];
//echo $_SESSION['numOfQuest'];
$cAns = $_SESSION['corrAns'];
if ($_SESSION['qNum'] < ($_SESSION['numOfQuest'] - 1)) {
    $chosenQuest = $qBank[$questOrd[$qNum]];
    $_SESSION['corrAns'] = prepareQuestion($chosenQuest);
    echo "<div id='ansDiv' style='display: none;'><p>Correct: ".$_SESSION['corrAns']."</p></div>";
    echo '<br>Show answer<input id="showAns" type="checkbox" onclick="showAnswer()">';
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['ans'])) {
        $chosenAnswer = $_POST['ans'];
        /*echo "<div id='ansDiv' style='display: none;'><p>Correct: $cAns</p>";
        echo "<p>Chosen: $chosenAnswer</p></div>";*/
        if ($chosenAnswer == $cAns) {
            echo "<p>Well done</p>";
            $_SESSION['corrAnsNum'] ++;
        } else {
            echo "<p>Incorrect</p>";
        }
        if ($_SESSION['qNum'] < ($_SESSION['numOfQuest'] - 1)) {
            $_SESSION['qNum'] ++;
        } else {
            $corrAnsNum = $_SESSION['corrAnsNum'];
            $numOfQuest = $_SESSION['numOfQuest'];
            $mark = ($corrAnsNum/$numOfQuest)*100;
            require_once("DB/DataBase.php");
            DataBase::getInstance()->updateMark($_SESSION['crsID'],$_SESSION['clientID'],$mark);
            echo "<script>alert('You have $corrAnsNum correct answers out of $numOfQuest.'); "
            . "location.href='editCourses'</script>";
        }
    }
}
?>
