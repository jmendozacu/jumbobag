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
 * Store location admin block
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */

class Plugincompany_Storelocator_Block_Adminhtml_Cache_Additional extends Mage_Adminhtml_Block_Template
{
    /**
     * Get clean cache url
     *
     * @return string
     */
    public function getCleanExternalCacheUrl()
    {
        return $this->getUrl('*/storelocator_storecache/clean');
    }
    /**
     * Check if block can be displayed
     *
     * @return bool
     */
    public function canShowButton()
    {
        return Mage::helper('plugincompany_storelocator')->isCacheEnabled() && Mage::getSingleton('admin/session')->isAllowed('plugincompany_storelocator');
    }
}