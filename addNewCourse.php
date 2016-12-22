<?php
//require 'test.php';
require_once ("DB/DataBase.php");
$submit = false;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
$qFileName = ".\Questions\questions".$_POST['crsCode'].".qbf";
if(DataBase::getInstance()->addNewCourse($_POST['crsName'],$_POST['crsCode'],$_POST['crsDesc']))
    fopen($qFileName, 'a');
//DataBase::addQuestion($qFileName, $newQuest);
$submit = true;
header('Location: addQuestion');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link href="main.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <form action="addNewCourse" method="POST">
            <fieldset>
                <legend>Add new course:</legend>
                Course name:<br>
                <input type="text" name="crsName" placeholder="e.g. Internet Programming"><br>
                Course code:<br>
                <input type="text" name="crsCode" placeholder="CSE334"><br>
                Course Description:<br>
                <input type="text" name="crsDesc" placeholder="Discusses....."><br>
                <input type="submit" value="Add course">
            </fieldset>
        </form>
        <a href="editCourses"><button>Back</button></a>
    </body>
</html>