<?php
class CourseDateTime extends CalendarDateTime {
	static $db = array (
		'TicketsAvailable' => 'Int'
	);

	static $has_one = array (
		'Course' => 'Course'		
		);
		
	public function getCMSFields() { 
		$fields = parent::getCMSFields(); 
		$fields->push(new NumericField('TicketsAvailable', _t('CalendarDateTime.TICKETS','Tickets Available')));		
		//Debug::message(" the parent is : ".$this->ParentID );
		$fields->push(new HiddenField("EventID", "EventID", Controller::curr()->CurrentPageID())); 
		
		return $fields;
	}
	
	public function CanRegister() {
		return $this->TicketsAvailable > 0;
		}
		
	public function RegisterLink(){
		return $this->Course()->Parent()->Link("register")."?DateID=$this->ID";
	}
	
	public function getDateLabel() {
		return $this->obj('StartDate')->Format('d-m-Y').", " . $this->obj('StartTime')->Nice24() . " : (" . sprintf(_t('Conference.TICKETSREMAINING','%d tickets remaining'), $this->TicketsAvailable).")";

	}

}
?>

