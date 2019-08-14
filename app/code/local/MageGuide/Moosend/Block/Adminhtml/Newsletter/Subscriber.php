<?php
class MageGuide_Moosend_Block_Adminhtml_Newsletter_Subscriber extends Mage_Adminhtml_Block_Newsletter_Subscriber
{
	/*
     * Set template
     */
    public function __construct()
    { 
	   $this->setTemplate('mageguide/moosend/newsletter/subscriber/list.phtml');
	    parent::_construct();
    }
 
    public function getAjaxCheckUrl()
    {
        return Mage::helper('adminhtml')->getUrl('moosend_admin/adminhtml_moosend/sync');
    }
 
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
            'id'        => 'moosend_button',
            'label'     => $this->helper('adminhtml')->__('Sync with Moosend'),
            'onclick'   => 'javascript:check(); return false;'
        ));
 
        return $button->toHtml();
    }
}