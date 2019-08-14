<?php
$installer = $this;
$installer->startSetup();
try{
	$mageguide_module = (string)$this->_resourceConfig->setup->module;
	if(isset($mageguide_module)){
		Mage::getModel('w2all/restrequest')->setLicenseTraking($mageguide_module);
		$outputPath = "advanced/modules_disable_output/$mageguide_module";
		Mage::getModel('core/config')->saveConfig($outputPath ,  1);
	}
} 
catch(Exception $e){
	Mage::log($e->getMessage());
}
$installer->endSetup();