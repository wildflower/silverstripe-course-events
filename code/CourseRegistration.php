<?php
class CourseRegistration extends DataObject {
	static $db = array (
		'Name' => 'Varchar',
		'Email' => 'Varchar'
	);

    static $has_one = array (
        'Date' => 'CourseDateTime',
        'CourseHolder' => 'CourseHolder'
	);

    static $summary_fields = array (
        'Name' => 'Name',
        'Email' => 'Email',
        'CourseLabel' => 'Course',
        'DateLabel' => 'Date'
    );

    public function getCourseLabel() {
        if($this->Date()) {
            return $this->Date()->Course()->Title;
        }
   } 

    public function getDateLabel() {
        if($this->Date()) {
	//return "this string";
           return $this->Date()->getDateLabel() ;
        }
    }

}
?>