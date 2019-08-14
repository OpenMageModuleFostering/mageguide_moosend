<?php
class MageGuide_Moosend_Model_Response extends Mage_Core_Model_Abstract 
{
	private $Code;
	private $Error;
	private $Context;
	
	public function getResponce(array $jsonData) {		
		$this->Code = $jsonData['Code'];
		$this->Error = $jsonData['Error'];
		$this->Context = $jsonData['Context'];
	}

	public function getCode() {
		return $this->Code;
	}
	
	public function getError() {
		return $this->Error;
	}
	
	public function getContext() {
		return $this->Context;
	}
}