<?php
/**
 *
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
 *
 */
class Plugincompany_Storelocator_Block_Adminhtml_Catalog_Product_Tab_Storelocation extends Mage_Adminhtml_Block_Widget_Grid
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _getProduct()
    {
        return Mage::registry('current_product');
    }


    //Label to be shown in the tab
    public function getTabLabel()
    {
        return Mage::helper('core')->__('Store Locations');
    }

    public function getTabTitle()
    {
        return Mage::helper('core')->__('Store Locations');
    }

    public function canShowTab()
    {
        if ($this->_getProduct()->getId() 
                && Mage::helper('plugincompany_storelocator/storelocation')->isManuallyManageInventoryLocatorEnabled()
        ){
            return true;
        }
        return false;
    }

    public function isHidden()
    {
        return false;
    }

    public function getTabUrl()
    {
        return $this->getUrl('adminhtml/storelocator_storelocation/storeLocation', array('_current' => true));
    }
    
    
    public function getTabClass()
    {
        return 'ajax';
    }
}