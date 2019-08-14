<?php
class MageGuide_Moosend_Model_Moosendapi extends Mage_Core_Model_Abstract 
{
	public $campaigns;
	public $subscribers;
	public $mailingLists;
	public $segments;
		
	private $apiEndpoint;
	private $_apiKey;
	private $_httpClient;
	private $_mailingListID;
	private $_newsletterMailingListID;
	
	const MOOSEND_STATUS_SUBSCRIBED = 1;
	const MOOSEND_STATUS_UNSUBSCRIBE = 2;
	const MOOSEND_STATUS_REMOVED = 4;
	

	function initialize() {
		if(Mage::helper('moosend')->isMoosendModuleEnabled() && Mage::helper('moosend')->getMoosendApikey() != '' && Mage::helper('moosend')->getMoosendApiEndpoint() != ''){
			$this->_apiKey = Mage::helper('moosend')->getMoosendApikey();
			$this->apiEndpoint = Mage::helper('moosend')->getMoosendApiEndpoint();
			$this->_mailingListID = Mage::helper('moosend')->getMoosendMailinglistId();
			$this->_newsletterMailingListID = Mage::helper('moosend')->getMoosendNewsletterMailinglistId();
			if (empty($this->_apiKey)) {
				$mess = Mage::helper('moosend')->__('apiKey is a required parameter when a creating MoosendApi instance');
				Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
				return false;
			} elseif (is_numeric($this->_apiKey)) {
				$mess = Mage::helper('moosend')->__('Please provide a valid API key. API key must be a string');
				Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
				return false;
			}
			$this->_httpClient = Mage::getModel('moosend/httpclient');
			return true;
		}else{
			$mess = Mage::helper('moosend')->__('1. Enable Moosend Module , 2. ApiKey is a required');
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
			return false;
		}
		
	}
	public function apiMailList() {
		$mailingLists = Mage::getModel('moosend/wrappers_mailinglistswrapper');
		$mailingLists->initialize($this->_httpClient, $this->apiEndpoint, $this->_apiKey);
		return $mailingLists;
	}
	public function apiSubscrib() {
		if($this->_mailingListID){
			$subscribers = Mage::getModel('moosend/wrappers_subscriberswrapper');
			$subscribers->initialize($this->_httpClient, $this->_mailingListID , $this->apiEndpoint, $this->_apiKey);
			return $subscribers;
		}else{
			$mess = Mage::helper('moosend')->__('Please Select Mailing list from system configuration');
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
			return false;
		}
	}
	public function apiNewsletterSubscrib() {
		if($this->_newsletterMailingListID){
			$subscribers = Mage::getModel('moosend/wrappers_subscriberswrapper');
			$subscribers->initialize($this->_httpClient, $this->_newsletterMailingListID , $this->apiEndpoint, $this->_apiKey);
			return $subscribers;
		}else{
			$mess = Mage::helper('moosend')->__('Please Select Newsletter Mailing list from system configuration');
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
			return false;
		}
	}
}