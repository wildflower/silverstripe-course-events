<?php
class Course extends CalendarEvent {
	static $db = array (
		'Cost' => 'Currency'
	);

    static $has_many = array (

        'DateTimes' => 'CourseDateTime'

    );

	private static $datetime_class = "CourseDateTime";
	
	
	static $has_one = array (
		'CourseHolder' => 'CourseHolder'
		);
		
	static $can_be_root = false;
	
	//static $hide_ancestor = 'CalendarEvent';
	
    public function getCMSFields(){
	$f = parent::getCMSFields();
	$f->addFieldTotab("Root.Main", new 
	CurrencyField('Cost',_t('Course.COST','Course Cost')),'Content');
	if(!$this->ParentID) { 
		$f->push(new HiddenField("ParentID", "ParentID", Controller::curr()->CurrentPageID())); 
	} 

	return $f;
    
    }
 
}
class Course_Controller extends CalendarEvent_Controller {

	/*public function init() {
		parent::init();
		Requirements::themedCSS('calendar','event_calendar');		
	}*/
}
?>
