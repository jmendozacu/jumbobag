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
class Plugincompany_Storelocator_Block_Storelocation_Product_Store_Locator
extends Mage_Core_Block_Template {

    protected function showInProductTabs(){
        return (bool) Mage::getStoreConfig('plugincompany_storelocator/product_detail_page/show_locator')
        && (bool) Mage::getStoreConfig('plugincompany_storelocator/product_detail_page/enable_store_inventory_locator');
    }

    protected function _toHtml()
    {
        if(!$this->showInProductTabs()){
            return '';
        }

        return $this->renderView();
    }

}