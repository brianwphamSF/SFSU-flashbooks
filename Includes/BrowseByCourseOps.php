<?php
/*
    Document   : BrowseByCourseOps.php
    Created on : Nov 10, 2010
    Author     : Wilson Kwok
*/
  class BrowseByCourseOps {
  // single instance of self shared among all instances
    private static $instance = null;

    //This method must be static, and must return an instance of the object if the object
    //does not already exist.
    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
    // thus eliminating the possibility of duplicate objects.
    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }

    public function retrieveCourses($schoolName) {
        require_once("Includes/db.php");
        $course = BookDB::getInstance()->retrieveCourses($schoolName);
        return $course;
    }

    public function displayCourses($schoolName, $course) {
        $count = 1;
        echo '<font class="school-name">'.$schoolName.' - </font>';
        echo '<font class="instruction">Please click on the links below to view textbook(s) for the courses.</font>';
        echo '<br/><br/>';
        echo '<table><tr>';
        while ($row = mysql_fetch_array($course)) {            
            $courseName = $row["COURSE_NM"];
            echo '<td class="course-columns">';
            echo '<a style="text-decoration: none" href="SearchResults.php?searchCategories=searchByCourse&searchKeyword='.$courseName.'">'.$courseName.'</a>';
            echo '</td>';
            if (($count % 6) == 0)
                echo '</tr><tr>';
            $count++;
        }
        echo '</tr></table>';
    }
  }
?>