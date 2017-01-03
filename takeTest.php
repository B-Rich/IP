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

function checkJSON($JSONString) {
//header("Content-type: application/json");
    json_decode(stripslashes(file_get_contents("php://input")));
//JSON_ERROR_NONE = 0
    return (json_last_error() == 0);
}

function pasreJSON($JSONString) {
    if (checkJSON($JSONString)) {
//header("Content-type: application/json");
        return json_decode(stripslashes(file_get_contents("php://input")));
    } else {
        return FALSE;
    }
}

function prepareQuestion($chosenQuest) {
    try {
        $questBuffer = explode(" # ", $chosenQuest);
        $quest = $questBuffer[0];
        echo '<!DOCTYPE HTML><html><head><script src="qFormVald.js"></script>'
        . '<script type="text/javascript" src="jquery-3.1.1.js"></script>'
        . '<script type="text/javascript">window.onload=function(){xamTimer(10,$("#time"));}</script>'
        . '<link href = "main.css" rel = "stylesheet" type = "text/css"></head><body>';
        echo '<canvas id="timercanvas" style="width: 0; height=0">None</canvas><div id = "time"> </div>';
        echo '<form name = "qaForm" action = "takeTest" onsubmit = "return checkQAForm()" method = "POST">'
        . '<fieldset><legend>Choose the correct answer:</legend>';
        echo "<h1>$quest</h1>";
        $potAns = explode(";", $questBuffer[1]);
        $potAnsOrd = randomizer();
        foreach ($potAnsOrd as &$ord)
            echo "<input class='chkbox' type='radio' name='ans' id='$potAns[$ord]' value='$potAns[$ord]'>"
            . "<label for = '$potAns[$ord]' class='lbl'> $potAns[$ord]</label><br><br>";
        $corrAns = $questBuffer[2];
        echo '<input class = "button" type = "submit" value = "Submit"></fieldset></form></body></html>';
        return $corrAns;
    } catch (Exception $exp) {
        
    }
}

//session_start();
echo "<title>Question " . ($qNum + 1) . " out " . $_SESSION['numOfQuest'] . "</title>";
echo "<br> Question " . ($qNum + 1) . " out " . $_SESSION['numOfQuest'];
//echo $_SESSION['numOfQuest'];
$cAns = $_SESSION['corrAns'];
if ($_SESSION['qNum'] < ($_SESSION['numOfQuest'] - 1)) {
    $chosenQuest = $qBank[$questOrd[$qNum]];
    $_SESSION['corrAns'] = prepareQuestion($chosenQuest);
    echo "<div id='ansDiv' class='ansDiv'><p>Correct: " . $_SESSION['corrAns'] . "</p></div>";
    echo '<br>Show answer<input id = "showAns" type = "checkbox" onclick = "showAnswer2()">';
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['ans'])) {
        $chosenAnswer = $_POST['ans'];
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
            $mark = ($corrAnsNum / $numOfQuest) * 100;
            require_once("DB/DataBase.php");
            DataBase::getInstance()->updateMark($_SESSION['crsID'], $_SESSION['clientID'], $mark);
            echo "<script>alert('You have $corrAnsNum correct answers out of $numOfQuest.'); "
            . "location.href='editCourses'</script>";
        }
    }
}
?>
