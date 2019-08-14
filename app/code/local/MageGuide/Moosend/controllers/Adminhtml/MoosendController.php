<?php 
class MageGuide_Moosend_Adminhtml_MoosendController extends Mage_Adminhtml_Controller_Action 
{
    public function syncAction()
    {
		if( Mage::helper('moosend')->isMoosendModuleEnabled()){
			if(Mage::helper('moosend')->getMoosendMoosendSysType() == MageGuide_Moosend_Model_Moosend_Moosendsystype::TYPE_NEWSLETTER ||  Mage::helper('moosend')->getMoosendMoosendSysType() == MageGuide_Moosend_Model_Moosend_Moosendsystype::TYPE_BOTH){
				$moosendApi = Mage::getModel('moosend/newsletter')->getMoosendNewsletterCollection();
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Sync successfully !'));
				Mage::getSingleton('adminhtml/session')->setFormData(false);
				$message['suc'] = 1;
			}else{
				$message['mes'] = '<ul class="messages"><li class="error-msg"><ul><li><span>'.Mage::helper('adminhtml')->__('Please select Moosend sync for 1.Subscribers only or 2. Both from system config').'</span></li></ul></li></ul>';
			   $message['error'] = 1;
			}
		}
		else{
			$message['mes'] = '<ul class="messages"><li class="error-msg"><ul><li><span>'.Mage::helper('adminhtml')->__('Moosend sync is not config from admin').'</span></li></ul></li></ul>';
			$message['error'] = 1;
			
		}
		 Mage::app()->getResponse()->setBody(json_encode($message));
		
    }		
}