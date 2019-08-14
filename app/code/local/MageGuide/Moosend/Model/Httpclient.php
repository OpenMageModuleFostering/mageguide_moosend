<?php
class MageGuide_Moosend_Model_Httpclient extends MageGuide_Moosend_Model_Response
{
	
	protected $apiKey;
	protected $body;
	protected $ch;
	protected  $params;
	protected $status;
	protected $method;
	protected  $url;
	
	public function __construct() {
		if ( !(function_exists('curl_init') && function_exists('curl_setopt')) ){
			$mess = "cURL is needed!";
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
		}
		
		$this->apiKey = null;
		$this->body = null;
		$this->ch = curl_init();
		$this->params = null;
		$this->status = null;
		$this->method = 'GET';
		$this->url = null;
		$this->response = null;
	
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_USERAGENT, 'moosend-api-{0}-{1}' . phpversion () . php_uname ( 'v' ));
		curl_setopt($this->ch, CURLOPT_TIMEOUT, 180000);
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	}
	
	public function __destruct() {
		if (is_resource($this->ch)) {
	            curl_close($this->ch);
	     }
	}
	
	public function setApiKey($apiKey) {
		$this->apiKey = $apiKey;
		return $this;
	}
	
	public function getBody() {
		return $this->body;
	}
	
	public function getParams() {
		return ($this->params);
	}
	
	public function setParams($params) {
			$this->params = $params;
			
			if ($this->method == 'POST') {
				curl_setopt($this->ch, CURLOPT_POSTFIELDS, (json_encode($this->params)));
			}
			return $this;
	}
	
	public function getStatusCode() {
		return $this->status;
	}
	
	public function getMethod() {
		return $this->method;
	}
	
	public function setMethod($method) {
		$this->method = $method;
		return $this;
	}
	
	public function setUrl($url) {
		$this->url = $url;
		$this->url .= '?' . "apikey={$this->apiKey}";
		
		if (!empty($this->params) && $this->method == 'GET') {
			$this->url .= '&' . http_build_query($this->params);
		}
		return $this;
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function makeRequest() {
		curl_setopt($this->ch, CURLOPT_URL, $this->url);
		curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $this->method);	
		$this->body = curl_exec($this->ch);
		$this->status = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);

		if (curl_error($this->ch)) {
			$mess = 'Error connecting API: ' . curl_error($this->ch);
			Mage::log($mess,NULL,'moosend_api.log',true);
		}
		return $this;
	}
	
	public function getResponse() {
		if ($this->status !== 200) {
			$mess = Mage::helper('moosend')->__('Something went wrong...  Status: ' . $this->status .' Body: ' . $this->body);
			Mage::log('Error : '.$mess,NULL,'moosend_api.log',true);
		}
		
		$jsonRawResponse = json_decode($this->body, true);
		
		$jsonResponse = Mage::getModel('moosend/response');
		$jsonResponse->getResponce($jsonRawResponse);
		if($jsonResponse->getContext()){
			return $jsonResponse->getContext();
		}elseif($jsonResponse->getCode()){
			return $jsonResponse->getCode(). '  '.$jsonResponse->getError();
		}
	}	
}