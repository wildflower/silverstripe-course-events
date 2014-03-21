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
		//$gridField = new GridField('registrations', 'All Registrations', CourseRegistration::get());
		//$f->addFieldToTab("Root.Content.Registrations",   $gridField );
		return $f;
	}

}
 
class CourseHolder_Controller extends Calendar_Controller {
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

    $email = new Email($data['Email'], "administrator@yoursite.com", "Event Registration: {$conference->Title}");

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