<?php
class MageGuide_Moosend_Block_Adminhtml_System_Config_Form extends MageGuide_W2all_Block_Adminhtml_System_Config_Form
{
	public function getModuleName()
    {
		$module_full_name = parent::getModuleName();
		$module_name = explode("MageGuide_",$module_full_name);
		if(isset($module_name[1])){
			return strtolower($module_name[1]);
		}else{
        	return Mage::getConfig()->getNode('default/moosend/moosend_general/module_name');
		}
    }
}