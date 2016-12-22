<?php
require_once ("DB/DataBase.php");
//DataBase::getInstance()->addCourse($_POST['courseID'],$_POST['clientID']);
if(DataBase::getInstance()->addCourse($_POST['courseID'],$_POST['clientID']))
  echo "<script>alert('I was successfully added to your courses')</script>";
//redirection
header('Location: editCourses');
?>