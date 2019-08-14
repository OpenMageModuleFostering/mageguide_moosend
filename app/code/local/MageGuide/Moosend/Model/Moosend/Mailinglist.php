<?php
class MageGuide_Moosend_Model_Moosend_Mailinglist extends Mage_Core_Model_Abstract 
{
	public function toOptionArray()
    {
		$moosendApi = Mage::getModel('moosend/moosendapi');
        $response = $moosendApi->initialize();
		$optionArry = array();
		if($response){
			$data = $moosendApi->apiMailList()->getActiveMailingLists(1,10);
			if(is_array($data)){
				foreach($data as $list){
					$optionArry[] = array('value' => $list->getId() , 'label' => $list->getName());
				}
			}
			/*if(!is_array($data)){
				$optionArry[] = array('value' => '' , 'label' => $data);
			}*/
		}
		return $optionArry;
    }
}