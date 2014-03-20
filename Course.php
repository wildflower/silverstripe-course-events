<?php
<<<<<<< HEAD
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
=======
class CourseDateTime extends CalendarDateTime {
	static $db = array (
		'TicketsAvailable' => 'Int'
	);

	static $has_one = array (
		'Course' => 'Course'
		);
		
	public function extendTable(){
		$this->addTabletitles(array(
		'TicketsAvailable' => _t('Course.TICKETSAVAILABLE','Tickets available')
		));	
	}
	
	public function CanRegister() {
		return $this->TicketsAvailable > 0;
		}
		
	public function RegisterLink(){
		return $this->Course()->Parent()->Link("register")."?DateID=$this->ID";
	}
	
}
?>
>>>>>>> 95dacc12f454a96dac626ff70f9bf1f3eef472df
