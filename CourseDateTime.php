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
	
	public function RegistrationForm() {
		$date_id = (int) $this->getRequest()->requestVar('DateID');
		
		if(!$date = DataObject::get_by_id("ConferenceDateTime", $date_id)) {
			return $this->httpError(404);
		}
		$date_map = array();
		if($conference = $date->Conference()) {
			if($all_dates = $conference->DateTimes()) {
				$date_map = $all_dates->toDropdownMap('ID','DateLabel');
			}
		}
		return new Form (
			$this,
			"RegistrationForm",
			new Fieldset (
				new TextField('Name', _t('Conference.Name','Name')),
				new EmailField('Email', _t('Conference.EMAIl','Email')),
				new DropdownField('DateID', _t('Conference.CHOOSEDATE','Choose a date'), $date_map, $date_id)
			),
			new FieldSet (
				new FormAction('doRegister', _t('Conference.REGISTER','Register'))
			),
			new RequiredFields('Name','Email','DateID')
		);
	}
	
	public function getDateLabel() {
		return $this->obj('StartDate')->Format('d-m-Y').", " . $this->obj('StartTime')->Nice24() . " : (" . sprintf(_t('Conference.TICKETSREMAINING','%d tickets remaining'), $this->TicketsAvailable).")";

	}

}
?>

