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
		'CourseHolder' => 'CourseHolder',
		'Image' => 'Image'
		);
		
	static $can_be_root = false;
	
	//static $hide_ancestor = 'CalendarEvent';
	
    public function getCMSFields(){
	$f = parent::getCMSFields();
	$f->addFieldTotab("Root.Main", new 	CurrencyField('Cost',_t('Course.COST','Course Cost')),'Content');
	$f->addFieldToTab('Root.Images',UploadField::create('Image', _t('Product.IMAGE', 'Product Image')));
	if(!$this->ParentID) { 
		$f->push(new HiddenField("ParentID", "ParentID", Controller::curr()->CurrentPageID())); 
	} 	
			
	return $f;
    
    }
 
     
}
class Course_Controller extends CalendarEvent_Controller {
	
	static $allowed_actions = array (
		'Booking',
		'RegistrationForm',
		'Form'
	);
	public $formclass = "AddCourseForm"; //allow overriding the type of form used
	
	public function Form() {
	SS_Log::log("Form function in Course $this", SS_Log::WARN);
		$formclass = $this->formclass;
		$form = new $formclass($this,"Form");
		$this->extend('updateForm', $form);		
		return $form;
	}

	/*public function Booking(SS_HTTPRequest $request) {
	Debug::show($request);
	$date = $request->getVar('date');
	$trip = $request->param('URLSegment');
	echo $date;
	print_r($trip);
	// get course id from trip name
	// get course date time from $date
	
	//SS_Log::log("Form Function in Course ", SS_Log::WARN);
	//SS_Log::log("I should be writing stuff to the cart here? ", SS_Log::WARN);
	//SS_Backtrace::backtrace();
	//$this->redirect(Director::baseURL() . 'cart');
		
	}*/
}
?>
