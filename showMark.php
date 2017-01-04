<?php
session_start();
?>
<html>
<head></head>
<body>
<?php
echo '<script>';
echo 'alert("You have have scored' . $_SESSION['mark'] . 'out of 100.");';
echo 'location.href ="editCourses";';
echo '</script>';
?>
</body>
</html>