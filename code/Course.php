<?php
class Course extends CalendarEvent {
	static $db = array (
		'Cost' => 'Currency'
	);

    static $has_many = array (

        'DateTimes' => 'CourseDateTime'

    );

	static $has_one = array (
		'CourseHolder' => 'CourseHolder'
		);
		
	static $can_be_root = false;
	
	//static $hide_ancestor = 'CalendarEvent';
	
    public function getCMSFields(){
	$f = parent::getCMSFields();
	$f->addFieldTotab("Root.Main", new 
	CurrencyField('Cost',_t('Course.COST','Course Cost')),'Content');
	
	return $f;
    
    }
 
}
class Course_Controller extends CalendarEvent_Controller {

}
?>
