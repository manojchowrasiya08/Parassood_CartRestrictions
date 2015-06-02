<?php
/**
* @category    ParasSood
* @package     Parassood_BrowserCacheInvalidator
* @author      paras89@live.com
*/
class Parassood_CartRestrictions_Model_Observer
{

    /**
     * Event observer for event sales_quote_save_after
     * @param $observer
     */
    public function validateCartItems($observer)
    {

        $quote = $observer->getQuote();
        $appliedRuleIds = $quote->getAppliedRuleIds();
        if(isset($appliedRuleIds)){
            $appliedRuleIds = explode(',',$appliedRuleIds);
            if(in_array(Mage::getStoreConfig('checkout/cart_restrictions/promotion_rule'),$appliedRuleIds)){
                if(!Mage::getSingleton('checkout/session')->getRedirected()) {

                    $errorMessage = Mage::getStoreConfig('checkout/cart_restrictions/error_message');
                    $errorMessages = explode("\n",$errorMessage);
                    foreach($errorMessages as $error){
                        Mage::getSingleton('checkout/session')->addError($error);
                    }
                    Mage::getSingleton('checkout/session')->setRedirected(true);
                    Mage::app()->getResponse()->setRedirect(Mage::getUrl('checkout/cart'));
                    Mage::app()->getresponse()->sendResponse();
                    return $this;
                }

                Mage::getSingleton('checkout/session')->setRedirected(false);

            }
        }

        return $this;

    }
}