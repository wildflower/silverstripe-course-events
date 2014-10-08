<?php
class CourseDateTime extends CalendarDateTime  implements Buyable{
	static $db = array (
		'TicketsAvailable' => 'Int'
	);

	static $has_one = array (
		'Course' => 'Course'				
		);

	private static $order_item = "CourseDateTime_OrderItem";
	private static $default_parent = 'Course';
	
	public function getCMSFields() { 
		$fields = parent::getCMSFields(); 
		$fields->push(new NumericField('TicketsAvailable', _t('CalendarDateTime.TICKETS','Tickets Available')));		
		//Debug::message(" the parent is : ".$this->ParentID );
		$fields->push(new HiddenField("EventID", "EventID", Controller::curr()->CurrentPageID())); 
		
		return $fields;
	}
	
	/**
	 * Checks if the buyable can be purchased. If a buyable cannot be purchased
	 * then the method should return a {@link ShopBuyableException} containing
	 * the messaging.
	 *
	 * @throws ShopBuyableException
	 *
	 * @return boolean
	 */
	 
	public function canPurchase($member = null, $quantity = 1)
	{
		if($this->TicketsAvailable - $quantity >= 0){
		SS_Log::log("CourseDateTime canPurchase tickets: $this->TicketsAvailable quantity requested: $quantity", SS_Log::WARN);		
		return true;	
		}else{
		return false;
		}
		
	}
	
	/**
	 * Create a new OrderItem to add to an order.
	 *
	 * @param int $quantity
	 * @param boolean $write
	 * @return OrderItem new OrderItem object
	 */
	public function createItem($quantity = 1, $filter = array())
	{
	SS_Log::log("CourseDateTime createItem ", SS_Log::WARN);
		$orderitem = self::config()->order_item;
		$item = new $orderitem();
		$item->CourseDateTimeID = $this->ID;
		if($filter){
			//TODO: make this a bit safer, perhaps intersect with allowed fields
			$item->update($filter);
		}
		$item->Quantity = $quantity;
		
		return $item;
	}
	
	/**
	 * The price the customer gets this buyable for, with any additional 
	 * additions or subtractions.
	 *
	 * @return ShopCurrency
	 */
	public function sellingPrice()
	{		
	//SS_Log::log("CourseDateTime selling Price", SS_Log::WARN);	
		return $this->Course()->Cost;
		
	}	
	
	public function Title()
	{		
	//SS_Log::log("CourseDateTime selling Price", SS_Log::WARN);	
		return $this->Course()->Title;
		
	}	
	
	/**
	 * If the product does not have an image, and a default image
	 * is defined in SiteConfig, return that instead.
	 * (copied from Product.php)
	 * @return Image
	 */
	public function Image() {
		$image = $this->Course()->getComponent('Image');
		if ($image && $image->exists() && file_exists($image->getFullPath()))
		{
			//SS_Log::log("CourseDateTime Course Image", SS_Log::WARN);	
			return $image;
		}
		$image = SiteConfig::current_site_config()->DefaultProductImage();		
		if ($image && $image->exists() && file_exists($image->getFullPath()))
		{
			//SS_Log::log("CourseDateTime Default Product Image", SS_Log::WARN);	
			return $image;
		}
		return $this->model->Image->newObject();
	}
	
	public function Parent(){
		var_dump($this);
		return $this->Course();
	
	}
	public function CanRegister() {
		if($this->StartDate > getdate())
		return true;
		}
		
	public function RegisterLink(){	
		return $this->Course()->Parent()->Link("register")."?DateID=$this->ID";
	}
	
	public function AddToCartLink(){	
		return $this->Course()->Parent()->Link("booking")."/DateID/$this->ID";
	}
	
	public function OrderLink(){	
		return $this->Course()->Parent()->Link("order")."/".$this->Course()->URLSegment."/".$this->obj('StartDate')->Format('d-m-Y');
	}
	
	public function getDateLabel() {
		return $this->obj('StartDate')->Format('d-m-Y').", " . $this->obj('StartTime')->Nice24() . " : (" . sprintf(_t('Conference.TICKETSREMAINING','%d tickets remaining'), $this->TicketsAvailable).")";

	}

	public function forTemplate()	{
		return $this->XML();
	}
	
		
	public function XML(){
		return Convert::raw2xml($this->value);
     }
     
     public $formclass = "AddCourseForm"; //allow overriding the type of form used

	public function Form() {
	SS_Log::log("Form function CourseDateTime $this", SS_Log::WARN);
	
		$formclass = $this->formclass;		
		$form = new $formclass($this,"Form");		
		$this->extend('updateForm', $form);
		return $form;
	}
}

class CourseDateTime_Controller extends Page_Controller {

}

class CourseDateTime_OrderItem extends OrderItem {

private static $has_one = array(
		'CourseDateTime' => 'CourseDateTime'
	);

private static $buyable_relationship = "CourseDateTime";
/*
	 * Event handler called when an order is fully paid for.
	 * set CourseRegsitration values , decrement tickets and send confirmation emails
	 */
	public function onPayment() {
		$this->extend('onPayment');
	}
	
	
	/**
	 * Get related product
	 *  - live version if in cart, or
	 *  - historical version if order is placed
	 *
	 * @param boolean $forcecurrent - force getting latest version of the product.
	 * @return Product
	 */
	public function Product($forcecurrent = false) {
	//SS_Log::log("CourseDateTime_OrderItem Product $this", SS_Log::WARN);
			$product = CourseDateTime_OrderItem::get()->byID($this->ID);
			//var_dump($this);
			//var_dump($product);
		return $product;
	}
	
	public function Link(){   
		$link = $this->CourseDateTime()->Course()->Link();
		$this->extend('updateLink', $link);
		return $link;
	}
	
	public function TableTitle() {
		$product = $this->CourseDateTime();
		$tabletitle = ($product) ? $product->Title : $this->i18n_singular_name();
		$this->extend('updateTableTitle', $tabletitle);
		return $tabletitle;
	}	

	public function variations(){	
	//I need to return an object for the CartForm.php -> editableItems
		return $this;
	}
	public function exists(){
	//I need to return false to say that variations don't exist for this OrderItem
	return false;
	}
}
?>

