<?php
require 'test.php';
$qFileName = "questions.qbf";
$qBankBuffer = fopen($qFileName, "rw") or die("Unable to open file!");
$submit = false;
//addQuestion($qFileName,);
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $nQuest = $_POST['question'];
    $nQuestA1 = $_POST['choice1'];
    $nQuestA2 = $_POST['choice2'];
    $nQuestA3 = $_POST['choice3'];
    $nQuestA4 = $_POST['choice4'];
    echo $nQuest;
    $submit = true;
}
?>
<!DOCTYPE html>
	<html>
	<body>
	<form action="test.php" method="POST">
  	<fieldset>
    <legend>Add new question:</legend>
Question:<br>
    <input type="text" name="question" placeholder="e.g. What is 2 power 2"><br>
        <?php if($submit) echo $nQuest;?>
Choice 1:<br>
    <input type="text" name="choice1" placeholder="4"><br>
        <?php if($submit) echo $nQuesA1;?>
Choice 2:<br>
    <input type="text" name="choice2" placeholder="2"><br>
        <?php if($submit) echo $nQuesA2;?>
Choice 3:<br>
    <input type="text" name="choice3" placeholder="1"><br>
        <?php if($submit) echo $nQuesA3;?>
Choice 4:<br>
    <input type="text" name="choice4" placeholder="5"><br>
        <?php if($submit) echo $nQuesA4;?>
    <input type="submit" value="Submit">
  </fieldset>
</form>
</body>
</html>