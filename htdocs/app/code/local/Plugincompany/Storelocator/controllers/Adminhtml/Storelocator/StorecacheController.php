<?php
/*
 * Created by:  Milan Simek
 * Company:     Plugin Company
 *
 * LICENSE: http://plugin.company/docs/magento-extensions/magento-extension-license-agreement
 *
 * YOU WILL ALSO FIND A PDF COPY OF THE LICENSE IN THE DOWNLOADED ZIP FILE
 *
 * FOR QUESTIONS AND SUPPORT
 * PLEASE DON'T HESITATE TO CONTACT US AT:
 *
 * SUPPORT@PLUGIN.COMPANY
 */
/**
 * Store location admin controller
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Adminhtml_Storelocator_StorecacheController
    extends Mage_Adminhtml_Controller_Action {
    
    /**
     * Retrieve session model
     *
     * @return Mage_Adminhtml_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('adminhtml/session');
    }
    
    public function cleanAction()
    {
        try {
            if (Mage::helper('plugincompany_storelocator')->isCacheEnabled()) {
                $cache = Mage::getModel('plugincompany_storelocator/cache_core');
                $cache->cleanCache(array(Plugincompany_Storelocator_Model_Cache_Core::CACHE_TAG_GROUP));
                
                $this->_getSession()->addSuccess(
                    Mage::helper('plugincompany_storelocator')->__('The store locator cache has been cleaned.')
                );
            }
        }
        catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        catch (Exception $e) {
            $this->_getSession()->addException(
                $e,
                Mage::helper('plugincompany_storelocator')->__('An error occurred while clearing the locator cache cache.')
            );
        }
        
        $this->_redirect('*/cache/index');
    }

    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     * @author Milan Simek
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('plugincompany_storelocator');
    }
}
