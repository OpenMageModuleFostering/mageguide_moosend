<?php
class MageGuide_Moosend_Model_Wrappers_Mailinglistswrapper extends Mage_Core_Model_Abstract 
{
	private $_httpClient;
	private $_apiEndpoint;
	private $_apiKey;
	
	public function initialize($httpClient, $apiEndpoint, $apiKey) {
		$this->_httpClient = $httpClient;
		$this->_apiEndpoint =$apiEndpoint;
		$this->_apiKey = $apiKey;
	}
	public function getActiveMailingLists ($page = 1, $pageSize = 10) {
		if (!is_numeric($page) || !is_numeric($pageSize)) {
			$mess = 'Page and pageSize must be integers';
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
		} else {
			return $this->getMailingLists($this->_httpClient, $this->_apiEndpoint, $page, $pageSize, $this->_apiKey);
		}
	}	
	public function getMailingLists($client, $endpoint, $page, $pageSize, $apiKey) {
		$request = NULL;
		$page = "{$page}/{$pageSize}.json";
		$methos = 'GET';
		$jsonData = $this->execute($page ,$methos , $request);
		
		$append = array(); 
		$mailList = isset($jsonData['MailingLists'])?$jsonData['MailingLists']:array();
		if(is_array($mailList)){
			foreach ($mailList as $mailingList) {
				$entry = MageGuide_Moosend_Model_Mailinglist::withJSON($mailingList);
				$append[]=$entry;
			}
		}
		if(!is_array($jsonData)){
				Mage::log('Error : '.$jsonData,NULL,'moosend_api.log',true);
				return $jsonData;
		}
		return $append;
	}
	
	public function newMailinglist($name,$confirmationPage = null,$redirectAfterUnsubscribePage = null) {
		if (empty($name)) {
			$mess = Mage::helper('moosend')->__('Name is a required parameter when calling');
			Mage::log($mess,NULL,'moosend_api.log',true);
		}  else {
			$request = array('Name'=>$name , 'ConfirmationPage'=>$confirmationPage,'RedirectAfterUnsubscribePage'=>$redirectAfterUnsubscribePage);
			$page = "create.json";
			$methos = 'POST';
			$jsonData = $this->execute($page ,$methos , $request);
			if(!is_array($jsonData)){
				Mage::log('Error : '.$jsonData,NULL,'moosend_api.log',true);
			}
		}
	}
	
	public function getSubscribers($mailingListID, $status = NULL , $since = NULL, $page = 1,$pageSize = 500) {
		if (empty($mailingListID)) {
			$mess =  'MailingListID is a required parameter when calling MailingListsWrapper::getSubscribers';
			Mage::log($mess,NULL,'moosend_api.log',true);
		} else {
			if($status){
			  $request['status'] = $status;
			}
			if($since){
			   $request['since'] = Mage::getModel('core/date')->date('Y-m-d', strtotime($since));
			}
			$request['page'] = $page;
			$request['pageSize'] = $pageSize;
			$view_page =  $mailingListID . '/subscribers.json';
			$method = 'GET';
			$jsonData = $this->execute($view_page ,$method , $request);
			if(!is_array($jsonData)){
				Mage::log('Error : '.$jsonData,NULL,'moosend_api.log',true);
			}
			return $jsonData;
		}
	}
	
	protected function getCallcontext($page , $method = 'POST')
	{
		$urlRequest = '/lists/'.$page;
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