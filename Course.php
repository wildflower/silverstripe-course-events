<?php
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
