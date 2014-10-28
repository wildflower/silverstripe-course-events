<?php

/**
 * @package shop
 */
class AddCourseForm extends Form {

	/**
	 * Populates quantity dropdown with this many values
	 *
	 * @var int
	 */
	protected $maxquantity = 0; 

	/**
	 * Fields that can be saved to an order item.
	 *
	 * @var array
	 */
	protected $saveablefields = array();


	public function __construct($controller, $name = "AddCourseForm") {
	//var_dump($controller->Course()->Cost);
	//var_dump($controller->Course());
	//var_dump($controller->ID);
	SS_Log::log("building the AddCourseForm Controller $this->Course()", SS_Log::WARN);
	
		$fields = new FieldList();

		if($this->maxquantity) {
			$values = array();
			$count = 1;

			while($count <= $this->maxquantity) {
				$values[$count] = $count;
				$count++;
			}

			$fields->push(new DropdownField('Quantity','Quantity', $values, 1));
		} else {
			$fields->push(new NumericField('Quantity','Quantity', 1));
		}
		//Need to set this dynamically from the $controller?
		$fields->push(new HiddenField('DateID','DateID', $controller->ID));
		
		$actions = new FieldList(
			new FormAction('addtocart',_t("AddCourseForm.ADDTOCART",'Add to Cart'))
		);

		$validator = new RequiredFields(array(
			'Quantity'
		));
		
		parent::__construct($controller,$name,$fields,$actions,$validator);
		$this->addExtraClass("addcourseform");

		$this->extend('updateAddCourseForm');
	}

	/**
	 * Choose maximum value to populate quantity dropdown
	 */
	public function setMaximumQuantity($qty) {
		$this->maxquantity = (int)$qty;

		return $this;
	}

	public function setSaveableFields($fields){
		$this->saveablefields = $fields;
	}

	public function addtocart($data,$form){
	SS_Log::log("addtocart from AddCourseForm $this", SS_Log::WARN);	
	$DateID = $this->request->requestVar('DateID');
	//echo "found the DateID: $DateID";
	
	//print_r($data);
	
		if($buyable = $this->getBuyable($data)){
			SS_Log::log("is Buyable $this", SS_Log::WARN);
			$cart = ShoppingCart::singleton();
			SS_Log::log("Start cart is Buyable $this", SS_Log::WARN);
			$saveabledata = (!empty($this->saveablefields)) ? Convert::raw2sql(array_intersect_key($data,array_combine($this->saveablefields,$this->saveablefields))) : $data;
			SS_Log::log("Savable is Buyable $this", SS_Log::WARN);
			//print_r($saveabledata);
			$quantity = isset($data['Quantity']) ? (int) $data['Quantity']: 1;
			print_r($quantity);
			SS_Log::log("Quantity is Buyable $this", SS_Log::WARN);
			$cart->add($buyable,$quantity,$saveabledata);
			SS_Log::log("after add to cart is Buyable $this", SS_Log::WARN);
			if(!ShoppingCart_Controller::config()->direct_to_cart_page){
				SS_Log::log("direct to cart Page is Buyable $this", SS_Log::WARN);
				$form->SessionMessage($cart->getMessage(),$cart->getMessageType());
			}
			ShoppingCart_Controller::direct($cart->getMessageType());
		}else{
		SS_Log::log("Isn't Buyable $this", SS_Log::WARN);
		}
	}

	public function getBuyable($data = null){
	SS_Log::log("In AddCourseForm getBuyable $this->controller dataRecord", SS_Log::WARN);
	//var_dump($data);
	
	//var_dump($this->controller->dataRecord);
		if($this->controller->dataRecord instanceof Buyable){
			SS_Log::log("In getBuyable instance of Buyable", SS_Log::WARN);
			return $this->controller->dataRecord;
		}
		print_r($this->request->requestVars());
		SS_Log::log("In AddCourseForm about to return the Buyable", SS_Log::WARN);
		return DataObject::get_by_id('CourseDateTime',(int) $this->request->requestVar("DateID")); //TODO: get buyable
	}

}
