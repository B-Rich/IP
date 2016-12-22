<?php
require_once ("DB/DataBase.php");
DataBase::getInstance()->removeCourse($_POST['courseID'],$_POST['clientID']);
/*if(DataBase::getInstance()->removeCourse($_POST['courseID'],$_POST['clientID']))
  echo "<script>alert('I was successfully removed from your courses')</script>";*/
//redirection
header('Location: editCourses');
?>