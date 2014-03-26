<?php
class CourseHolder extends Calendar {

    static $has_many = array (
        'Courses' => 'Course',
	'Registrations' => 'ConferenceRegistration'
    ); 

    private static $allowed_children = array('Course'); 
    private static $default_child = "Course";
    //static $hide_ancestor = 'Calendar';
    
    public function getCMSFields() {
		$f = parent::getCMSFields();
		$config = GridFieldConfig::create();
		$config->addComponent(new GridFieldDataColumns());
		$gridField = new GridField('registrations', 'All Registrations', CourseRegistration::get(),GridFieldConfig_RecordEditor::create());
		
		
		$f->addFieldToTab("Root.Registrations",   $gridField );
		return $f;
	}

}
 
class CourseHolder_Controller extends Calendar_Controller {

	public function init() {
		parent::init();
		// You can include any CSS or JS required by your project here.
		// See: http://doc.silverstripe.org/framework/en/reference/requirements
		//Debug::message("Wow, that's great");
		//SS_Backtrace::backtrace();
	}
	
	static $allowed_actions = array (
		'register',
		'RegistrationForm'
	);
	
	public function register(SS_HTTPRequest $request) {
		if(!$request->requestVar('DateID')) {
			return Director::redirectBack();
		}
		return array();
	}
	
		public function RegistrationForm() {
		$date_id = (int) $this->getRequest()->requestVar('DateID');
		
		if(!$date = DataObject::get_by_id("CourseDateTime", $date_id)) {
			return $this->httpError(404);
		}
		$date_map = array();
		if($course = $date->Course()) {
			if($all_dates = $course->DateTimes()) {
				$date_map = $all_dates->map('ID','DateLabel');
			}
		}
		return new Form (
			$this,
			"RegistrationForm",
			new FieldList (
				new TextField('Name', _t('Course.Name','Name')),
				new EmailField('Email', _t('Course.EMAIl','Email')),
				new DropdownField('DateID', _t('Course.CHOOSEDATE','Choose a date'), $date_map, $date_id)
			),
			new FieldList (
				new FormAction('doRegister', _t('Course.REGISTER','Register'))
			),
			new RequiredFields('Name','Email','DateID')
		);
	}

public function doRegister($data, $form) {


   // Sanity check
    if(!isset($data['DateID'])) {
        return Director::redirectBack();
    }

    if(!$date = DataObject::get_by_id("CourseDateTime", (int) $data['DateID'])) {
        return $this->httpError(404);

    }


    $course = $date->Course();

    // Save the registration
    $form->saveInto($reg = new CourseRegistration());
    $reg->CourseHolderID = $date->Course()->ParentID;
    $reg->write();

 

    // Decrease the tickets available
    $date->TicketsAvailable--;
    $date->write();


    // Email the admin

    $email = new Email($data['Email'], "administrator@yoursite.com", "Event Registration: {$course->Title}");

    $email->ss_template = "CourseRegistration";

    $email->populateTemplate(array(
        'Registration' => $reg
    ));

    $email->send(); 

    $form->sessionMessage(_t('Course.THANKYOU','Thank you for signing up!'),'good');
    return Director::redirectBack();
}

}
?>