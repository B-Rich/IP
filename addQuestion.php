<?php
session_start();
if (array_key_exists("user", $_SESSION)) {
    $sessionUser = $_SESSION['user'];
    $sessionUserNm = $_SESSION['userNm'];
} else {
    header('Location: index');
    exit;
}
include("toolbar.php");
require_once ("DB/DataBase.php");
$submit = false;
echo "<h1>" . $_SESSION['crsCode'] . ":  " . $_SESSION['crsName'] . "</h1>";
$qFileName = ".\Questions\questions" . $_SESSION['crsCode'] . ".qbf";
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nQuest = $_POST['question'];
    $nQuestA1 = $_POST['choice1'];
    $nQuestA2 = $_POST['choice2'];
    $nQuestA3 = $_POST['choice3'];
    $nQuestA4 = $_POST['choice4'];
    /* $t = $_POST['corrAns'];
      echo $t."\n"; */
    switch ($_POST['corrAns']) {
        case "a1":
            $corrAns = $nQuestA1;
            break;
        case "a2":
            $corrAns = $nQuestA2;
            break;
        case "a3":
            $corrAns = $nQuestA3;
            break;
        case "a4":
            $corrAns = $nQuestA4;
            break;
        default:
            $corrAns = "Oops";
    }
    $newQuest = $nQuest . "? # " . $nQuestA1 . ";" . $nQuestA2 . ";" . $nQuestA3 . ";" . $nQuestA4 . " # " . $corrAns;
//echo $newQuest;
    DataBase::addQuestion($qFileName, $newQuest);
    $submit = true;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <script src="qFormVald.js"></script>
        <link href = "main.css" rel = "stylesheet" type = "text/css">
        <title><?php echo "" . $_SESSION['crsCode'] . ":  " . $_SESSION['crsName'] . " add question";?></title>
    </head>
    <body>
        <form class="form" name="qForm" action="addQuestion" onsubmit="return checkQForm()" method="POST">
            <fieldset class="fs_border">
                <legend>Add new question:</legend>
                Question:<br>
                <input type="text" name="question" placeholder="e.g. What is 2 power 2"><br>
                Choice 1:<br>
                <input type="radio" name="corrAns" value="a1">
                <input type="text" name="choice1" placeholder="4"><br>
                Choice 2:<br>
                <input type="radio" name="corrAns" value="a2">
                <input type="text" name="choice2" placeholder="2"><br>
                Choice 3:<br>
                <input type="radio" name="corrAns" value="a3">
                <input type="text" name="choice3" placeholder="1"><br>
                Choice 4:<br>
                <input type="radio" name="corrAns" value="a4">
                <input type="text" name="choice4" placeholder="5"><br>
                <input class="button" type="submit" value="Submit">
            </fieldset>
        </form>
        <br><br>
        <a href="editCourses"><button class="button">Back</button></a>
    </body>
</html>