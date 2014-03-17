<?php
class CourseHolder extends Calendar {

    static $has_many = array (
        'Courses' => 'Course'
    ); 

    static $allowed_children = array('Course'); 
    static $hide_ancestor = 'Calendar';

}
 
class CourseHolder_Controller extends Calendar_Controller {

}
?>
