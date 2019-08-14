<?php
class MageGuide_Moosend_Helper_Data extends MageGuide_W2all_Helper_Data
{
	
	public function isMoosendModuleEnabled() {
		return trim(Mage::getStoreConfig('moosend/moosend_general/enabled'));
	}
	public function getMoosendApikey() {
		return trim(Mage::getStoreConfig('moosend/moosend_general/moosend_apikey'));
	}
	public function getMoosendUsername() {
		return trim(Mage::getStoreConfig('moosend/moosend_general/moosend_username'));
	}
	public function getMoosendPassword() {
		return trim(Mage::getStoreConfig('moosend/moosend_general/moosend_password'));
	}
	public function getMoosendApiEndpoint() {
		$apiEndpoint = trim(Mage::getStoreConfig('moosend/moosend_general/moosend_apiendpoint'));
		return ($apiEndpoint)?$apiEndpoint:'http://api.moosend.com/v2';
	}
	public function getMoosendMailinglistId() {
		return trim(Mage::getStoreConfig('moosend/moosend_general/moosend_mailinglist'));
	}
	public function getMoosendNewsletterMailinglistId() {
		return trim(Mage::getStoreConfig('moosend/moosend_general/moosend_newsletter_mailinglist'));
	}
	public function getMoosendMoosendSysType() {
		return trim(Mage::getStoreConfig('moosend/moosend_general/moosend_moosendsystype'));
	}
	public function getMoosendDefaultStore() {
		return trim(Mage::getStoreConfig('moosend/moosend_general/moosend_store'));
	}
}