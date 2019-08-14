<?php
class MageGuide_Moosend_Model_Observer extends Mage_Core_Helper_Abstract
{
	public function addSubscriber(Varien_Event_Observer $observer) 
	{
		if(Mage::helper('moosend')->isMoosendModuleEnabled()){
			if(Mage::helper('moosend')->getMoosendMoosendSysType() == MageGuide_Moosend_Model_Moosend_Moosendsystype::TYPE_CUSTOMER ||  Mage::helper('moosend')->getMoosendMoosendSysType() == MageGuide_Moosend_Model_Moosend_Moosendsystype::TYPE_BOTH){
				$event = $observer->getEvent();
				$customer = $event->getCustomer();
				$email = $customer->getEmail();
				$name = $customer->getName();
				$moosendApi = Mage::getModel('moosend/moosendapi');
				$response = $moosendApi->initialize();
				if($email && $response) {
					   $data = array('name'=>$name,'email'=>$email,'customFields'=>'');
					   if($moosendApi->apiSubscrib()){
							$moosendApi->apiSubscrib()->addSubscriber($data);
					   }
				}
			}
		}
	}
	public function removeSubscriber($observer)
	{
		if(Mage::helper('moosend')->isMoosendModuleEnabled()){
			if(Mage::helper('moosend')->getMoosendMoosendSysType() == MageGuide_Moosend_Model_Moosend_Moosendsystype::TYPE_CUSTOMER ||  Mage::helper('moosend')->getMoosendMoosendSysType() == MageGuide_Moosend_Model_Moosend_Moosendsystype::TYPE_BOTH){
				$event = $observer->getEvent();
				$customer = $event->getCustomer();
				$email = $customer->getEmail();
				$moosendApi = Mage::getModel('moosend/moosendapi');
				$response = $moosendApi->initialize();
				if($email && $response) {
					   if($moosendApi->apiSubscrib()){
							$moosendApi->apiSubscrib()->removeSubscriber($email);
					   }
				}
			}
		}
		
	}
	public function addNewsletterSubscriber(Varien_Event_Observer $observer) 
	{
		
		if(Mage::helper('moosend')->isMoosendModuleEnabled()){
			if(Mage::helper('moosend')->getMoosendMoosendSysType() == MageGuide_Moosend_Model_Moosend_Moosendsystype::TYPE_NEWSLETTER ||  Mage::helper('moosend')->getMoosendMoosendSysType() == MageGuide_Moosend_Model_Moosend_Moosendsystype::TYPE_BOTH){
				$event = $observer->getEvent();
				$newsletter = $event->getDataObject();
				$email = $newsletter['subscriber_email'];
				$status = $newsletter['subscriber_status'];
				$moosendApi = Mage::getModel('moosend/moosendapi');
				//print_r($newsletter->getData());
				$response = $moosendApi->initialize();
			    if($email && $response && $status == Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED) {
					//print_r($status.'='.Mage_Newsletter_Model_Subscriber::STATUS_SUBSCRIBED.'<=email=>'.$email); exit;
					   $data = array('name'=>'','email'=>$email,'customFields'=>'');
					   if($moosendApi->apiNewsletterSubscrib()){
							 $moosendApi->apiNewsletterSubscrib()->addSubscriber($data);
					   }
				}elseif($email && $response && $status == Mage_Newsletter_Model_Subscriber::STATUS_UNSUBSCRIBED) {
					   if($moosendApi->apiNewsletterSubscrib()){
							 $moosendApi->apiNewsletterSubscrib()->unsubscribe($email);
					   }
					  
				}
			}
		}
	}
	public function removeNewsletterSubscriber($observer) 
	{
		if(Mage::helper('moosend')->isMoosendModuleEnabled()){
			if(Mage::helper('moosend')->getMoosendMoosendSysType() == MageGuide_Moosend_Model_Moosend_Moosendsystype::TYPE_NEWSLETTER ||  Mage::helper('moosend')->getMoosendMoosendSysType() == MageGuide_Moosend_Model_Moosend_Moosendsystype::TYPE_BOTH){
				$event = $observer->getEvent();
				$newsletter = $event->getDataObject();
				$email = $newsletter['subscriber_email'];
				$moosendApi = Mage::getModel('moosend/moosendapi');
				$response = $moosendApi->initialize();
			   if($email && $response) {
						if($moosendApi->apiNewsletterSubscrib()){
							 $moosendApi->apiNewsletterSubscrib()->removeSubscriber($email);
					   }
				}
			}
		}
	}
	public function synchronizeNewsletterSubscriber() 
	{
		if(Mage::helper('moosend')->isMoosendModuleEnabled()){
			if(Mage::helper('moosend')->getMoosendMoosendSysType() == MageGuide_Moosend_Model_Moosend_Moosendsystype::TYPE_NEWSLETTER ||  Mage::helper('moosend')->getMoosendMoosendSysType() == MageGuide_Moosend_Model_Moosend_Moosendsystype::TYPE_BOTH){
			$moosendApi = Mage::getModel('moosend/newsletter')->getMoosendNewsletterCollection();
			Mage::log('Cron Run : '.date('Y-m-d H:i:s'),NULL,'moosend_api.log',true);
			}
		}
	}
	protected function getCustomerId($email) 
	{
		$customer = Mage::getModel("customer/customer"); 
		$customer->setWebsiteId(Mage::app()->getWebsite()->getId()); 
		$customer->loadByEmail($email);
		return $customer->getEmail();
	}
	
		
}