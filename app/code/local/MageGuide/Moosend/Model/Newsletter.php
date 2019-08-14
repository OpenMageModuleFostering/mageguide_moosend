<?php
class MageGuide_Moosend_Model_Newsletter extends Mage_Core_Model_Abstract 
{
	protected $resource;
	protected $connection;
	protected $newsletter_subscriber;
	
	public function __construct()
	{
		$this->resource = Mage::getSingleton('core/resource');
		$this->connection = $this->resource->getConnection('core_write');
		$this->newsletter_subscriber = $this->resource->getTableName('newsletter_subscriber');
	}
	protected function getNewsletterCollection() 
	{
		$collection =	Mage::getModel('newsletter/subscriber')->getCollection();
		$collection->getSelect()->where(" subscriber_status = ".Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED." OR subscriber_status = ". Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED);
		return $collection;
	}
	public function getMoosendNewsletterCollection() 
	{
		if(!Mage::helper('moosend')->isMoosendModuleEnabled()){
			return true;
		}
		$moosendApi = Mage::getModel('moosend/moosendapi');
        $response = $moosendApi->initialize();
		$page = 1;
		$pageSize = 500;
        if($response) {
			  
			   if($moosendApi->apiMailList()){
			    	$this->addSubscriber($moosendApi->apiNewsletterSubscrib());
			   }
			   if($moosendApi->apiMailList()){
				  do {
						$data = $this->getSubscriber($moosendApi->apiMailList(),$page, $pageSize);
						if($data){
							break;
						}
						$page++;
					} while (1);
			   }
        }
	}
	protected function getSubscriber($newsLetter , $page, $pageSize){
		
		 $data = $newsLetter->getSubscribers(Mage::helper('moosend')->getMoosendNewsletterMailinglistId(),NULL , NULL ,$page, $pageSize);
		if(isset($data['Subscribers']) && is_array($data['Subscribers']) && count($data['Subscribers'])>0){
			foreach($data['Subscribers'] as $key=>$value){
				if($value['SubscribeType'] == MageGuide_Moosend_Model_Moosendapi::MOOSEND_STATUS_SUBSCRIBED ) {
				   $this->insertNewsletter(trim($value['Email']), $value['SubscribeType']);
				}elseif($value['SubscribeType'] == MageGuide_Moosend_Model_Moosendapi::MOOSEND_STATUS_UNSUBSCRIBE ) {
				   $this->insertNewsletter(trim($value['Email']) , $value['SubscribeType']);
				}elseif($value['SubscribeType'] == MageGuide_Moosend_Model_Moosendapi::MOOSEND_STATUS_REMOVED){
					$this->deleteNewsletter(trim($value['Email']));
				}
			 }
			 return false;
			 
		 }else{
			 return true;
		 }
	}
	protected function addSubscriber($subscrib){
		 $newsletter = $this->getNewsletterCollection();
		 if($newsletter->count()>0){
			 $i=0;
			 $unsubscribeArray = array();
			 foreach($newsletter as $value){
				 if($value->getSubscriberEmail()){
				   $data['Subscribers['.$i.']'] = array('name'=>'','email'=>$value->getSubscriberEmail(),'customFields'=>'');
					if($value->getSubscriberStatus() == Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED) {
						$unsubscribeArray[] = $value->getSubscriberEmail();
					}
					$i++;
				 }
				}
			 $subscrib->addMultipleSubscribers($data);
			 foreach($unsubscribeArray as $unsubscribe){
					$subscrib->unsubscribe($unsubscribe);
				}
			 
		 }
	}
	protected function insertNewsletter($email , $status = 1){
			$sqlEmail = "SELECT subscriber_email FROM ".$this->newsletter_subscriber." WHERE subscriber_email= ?";
		    $getEmail = $this->connection->fetchOne($sqlEmail,array($email));
			if($status == MageGuide_Moosend_Model_Moosendapi::MOOSEND_STATUS_SUBSCRIBED){
				$type = Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED;
			}elseif($status == MageGuide_Moosend_Model_Moosendapi::MOOSEND_STATUS_UNSUBSCRIBE){
				$type = Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED;
			}
			if($getEmail){
				$date = array('subscriber_status'  =>  $type);
				$condition = array('subscriber_email = ?' => $email);
				$this->connection->update($this->newsletter_subscriber, $date , $condition);
			}else{
				$fields = array();
				$fields['subscriber_email'] = $email;
				$fields['store_id'] = Mage::helper('moosend')->getMoosendDefaultStore();
				$fields['customer_id'] = 0;
				$fields['subscriber_status'] = $type;
				$fields['subscriber_confirm_code'] =  Mage::getModel('newsletter/subscriber')->randomSequence();
				$this->connection->insert($this->newsletter_subscriber,$fields);	
			}
	}
	public function deleteNewsletter($email)
	{
		$condition = array($this->connection->quoteInto('subscriber_email= ?', $email));
		$this->connection->delete($this->newsletter_subscriber, $condition);
	}
	
}