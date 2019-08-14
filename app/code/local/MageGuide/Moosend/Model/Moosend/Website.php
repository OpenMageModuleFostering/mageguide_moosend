<?php
class MageGuide_Moosend_Model_Moosend_Website extends Mage_Core_Model_Abstract 
{
	 public function toOptionArray()
    {
		$stores = array();
		foreach (Mage::app()->getWebsites() as $website) {
			$scope['website_' . $website->getCode()] = $website->getName();
			
			foreach ($website->getGroups() as $group) {
				
				foreach ($group->getStores() as $store) {
					$stores[] = array(
						'label' => $store->getName(),
						'value' => $store->getId()
					);
				}
			}
		}
		return $stores;
	}
}