<?php
class MageGuide_Moosend_Model_Wrappers_Subscriberswrapper extends Mage_Core_Model_Abstract 
{
	private $_httpClient;
	private $_apiEndpoint;
	private $_apiKey;
	private $_mailingListID;
	
	public function initialize($httpClient, $mailingListID, $apiEndpoint, $apiKey) {
		$this->_httpClient = $httpClient;
		$this->_apiEndpoint =$apiEndpoint;
		$this->_mailingListID = $mailingListID;
		$this->_apiKey = $apiKey;
	}
	public function getSubscriberByEmail($email) {
		if (empty($this->_mailingListID)) {
			$mess = Mage::helper('moosend')->__('MailingListID and email are required arguments when add members');
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
		} elseif (is_numeric($this->_mailingListID)) {
			$mess = Mage::helper('moosend')->__('Please provide a valid MailingListID and email when calling SubscribersWrapper::getByEmail. MailingListID and email must be strings');
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
		} else {
			
			$request = array('email'=>$email);
			$page = 'view.json';
			$methos = 'GET';
			$jsonData = $this->execute($page ,$methos , $request);
			if(!is_array($jsonData)){
				Mage::log('Error : '.$email.' '.$jsonData,NULL,'moosend_api.log',true);
			}
			return $jsonData;			
		}
	}
	public function addSubscriber($member) {
		if (empty($this->_mailingListID)) {
			$mess = Mage::helper('moosend')->__('MailingListID is a required');
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
		} elseif (is_numeric($this->_mailingListID)) {
			$mess = Mage::helper('moosend')->__('Please provide a valid MailingListID');
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
		} else {
			$page = 'subscribe.json';
			$methos = 'POST';
			$jsonData = $this->execute($page ,$methos , $member);
			if(!is_array($jsonData)){
				Mage::log('Error : '.$jsonData,NULL,'moosend_api.log',true);
			}
		}
	}
	public function unsubscribe($email) {
		if (empty($this->_mailingListID)) {
			$mess = Mage::helper('moosend')->__('MailingListID is a required');
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
		}  else  {
			$request = array('email'=>$email);
			$page = 'unsubscribe.json';
			$methos = 'POST';
			$jsonData = $this->execute($page ,$methos , $request);
			if(!is_array($jsonData)){
				Mage::log('Error : '.$jsonData,NULL,'moosend_api.log',true);
			}
		}
	}
	public function removeSubscriber($email) {
		if (empty($this->_mailingListID)) {
			$mess = Mage::helper('moosend')->__('MailingListID is a required');
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
		} elseif (is_numeric($this->_mailingListID)) {
			$mess = Mage::helper('moosend')->__('Please provide a valid MailingListID');
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
		} else {
			$request = array('email'=>$email);
			$page = 'remove.json';
			$methos = 'POST';
			$jsonData = $this->execute($page ,$methos ,$request);
			if(!is_array($jsonData)){
				Mage::log('Error : '.$jsonData,NULL,'moosend_api.log',true);
			}
		}
	}
	/* Add Multiple Subscriber 
	
	Pass Request like this
	
	$subscribers['Subscribers[0]'] = array('name'=>'Name 1','email'=>'email1@example.com','customFields'=>'');
    $subscribers['Subscribers[1]'] = array('name'=>'Name 2','email'=>'email2@example.com','customFields'=>'');
	
	*/
	public function addMultipleSubscribers($subscribers) {
		
		if (empty($this->_mailingListID)) {
			$mess = Mage::helper('moosend')->__('MailingListID is a required');
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
		} elseif (is_numeric($this->_mailingListID)) {
			$mess = Mage::helper('moosend')->__('Please provide a valid MailingListID');
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
		} else {
			$page = 'subscribe_many.json';
			$methos = 'POST';
			$jsonData = $this->execute($page ,$methos , $subscribers);
			
			if(!is_array($jsonData)){
				Mage::log('Error : '.$jsonData,NULL,'moosend_api.log',true);
			}
		}
	}
	protected function getCallcontext($page , $method = 'POST')
	{
		$urlRequest = '/subscribers/' . $this->_mailingListID . '/'.$page;
		$callContext = Mage::getModel('moosend/callcontext');
		$callContext->initialize($this->_httpClient, $method , $this->_apiEndpoint, $urlRequest , $this->_apiKey);
		return $callContext;
	}
	protected function execute($page , $method='POST' , $request = NULL)
	{
		$callContext = $this->getCallcontext($page , $method);
		$url = $callContext->getEndpoint() . $callContext->getPath();
		$method = $callContext->getMethod();
		return $this->_httpClient->setApiKey($this->_apiKey)->setMethod($method)->setParams($request)->setUrl($url)->makeRequest()->getResponse();
	}
		
}