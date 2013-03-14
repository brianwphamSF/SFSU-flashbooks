<?php
/*
    Document   : BrowseByCourse.php
    Created on : Nov 10, 2010
    Author     : Wilson Kwok
*/
session_start();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
   $schoolName = $_GET["schoolName"];
}
?>
<?php include "layout/header.php"; ?>
<?php include "layout/sidebar-left.php"; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link href="css/BrowseByCourse.css" type="text/css" rel="stylesheet" media="all"/>
    </head>
    <body>
        <div class="course-mainbody">
            <?php
            require_once("Includes/BrowseByCourseOps.php");
            $course = BrowseByCourseOps::getInstance()->retrieveCourses($schoolName);
            if (is_null($course)) {
                echo '<div class="course-error">';
                echo 'No courses found.<br/><br/>';
                echo '</div>';
            }
            else
                BrowseByCourseOps::getInstance()->displayCourses($schoolName, $course);
            ?>
        </div>
    </body>
</html>
<?php include "layout/footer.php"; ?>