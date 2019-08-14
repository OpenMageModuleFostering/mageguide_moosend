<?php
class MageGuide_Moosend_Model_Moosend_Moosendsystype extends Mage_Core_Model_Abstract 
{
	const TYPE_CUSTOMER	= 1;
    const TYPE_NEWSLETTER	= 2;
	const TYPE_BOTH	= 3;
	public function toOptionArray()
    {
		$optionArry = array();
		
		$optionArry[] = array('value' => self::TYPE_CUSTOMER , 'label' => Mage::helper('moosend')->__('Customers only'));
		$optionArry[] = array('value' => self::TYPE_NEWSLETTER , 'label' => Mage::helper('moosend')->__('Subscribers only'));
		$optionArry[] = array('value' => self::TYPE_BOTH , 'label' => Mage::helper('moosend')->__('Both'));
		
		return $optionArry;
    }
}