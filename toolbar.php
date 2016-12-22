<?php echo "<span class='welcome'>Welcome, " . $sessionUserNm . "</span>"; ?>
<html>
    <head>
        <style>
            ul {
                list-style-type: none;
                margin: 0;
                padding: 0;
                overflow: hidden;
                background-color: gray;
            }

            li {
                float: left;
            }

            li a {
                display: block;
                color: white;
                font-family: "Arial";
                text-align: center;
                padding: 14px 16px;
                text-decoration: none;
            }

            li a:hover {
                background-color: black;
            }

            .animated{
                transition: background-color .20s,color .20s,box-shadow .20s,opacity .20s,filter .20s,border .20s;
                transition-property: background-color, color, box-shadow, opacity, filter, border;
                transition-duration: 0.20s, 0.20s, 0.20s, 0.20s, 0.20s, 0.20s;
                transition-timing-function: initial, initial, initial, initial, initial, initial;
                transition-delay: initial, initial, initial, initial, initial, initial;
            }
        </style>


    </head>
    <body>
        <ul>
            <li><a class="animated" href="home">Home</a></li>
            <?php
            if ($_SESSION['clientType'] == 'STDT') {
                echo '<li><a class="animated" href="courseList">Courses</a></li>';
            }
            ?>
            <li><a class="animated" href="editCourses">View Courses</a></li>
            <li><a class="animated" href="logout">Logout</a></li>
        </ul>
        <br>
    </body>
</html>