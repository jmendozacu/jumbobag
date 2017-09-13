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
 * Store location list block
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author Milan Simek
 */
class Plugincompany_Storelocator_Block_Storelocation_Product_Store_List
    extends Mage_Core_Block_Template {
    /**
     * initialize
     * @access public
     * @author Milan Simek
     */
     public function __construct(){
        parent::__construct();
        
        if(!$this->showInProductTabs()){
            $this->setStorelocations(new Varien_Data_Collection()); 
            return;
        }
        
        if(!$storeId = Mage::app()->getRequest()->getParam('store_id', 0)){
            $storeId = Mage::app()->getStore()->getStoreId();
        }

        if(!$productId = Mage::app()->getRequest()->getParam('product_id', 0)){
            $productId = Mage::registry('current_product')  ? Mage::registry('current_product')->getId() : 0;
        }       
         
         $storelocations = Mage::getResourceModel('plugincompany_storelocator/storelocation_collection')
                         ->addStoreFilter((int)$storeId)
                         ->addFieldToFilter('status', 1)
                         ->addFieldToFilter('show_in_list',1);
        
         if((bool) Mage::getStoreConfig('plugincompany_storelocator/product_detail_page/manually_manage_inventory_locator')){
            $tableName = Mage::getSingleton('core/resource')->getTableName('plugincompany_storelocator/storelocation_product');
            $storelocations->getSelect()->joinLeft(
               array('product' => $tableName), 'product.storelocation_id = main_table.entity_id' 
            );

            $storelocations->addFieldToFilter('product_id', (int)$productId);
            $storelocations->addFieldToFilter('product.store_id', (int)$storeId);
         }
        
        $storelocations->setOrder('sort_order', 'asc');
        
        $this->setStorelocations($storelocations);
    }
    /**
     * prepare the layout
     * @access protected
     * @return Plugincompany_Storelocator_Block_Storelocation_List
     * @author Milan Simek
     */
    protected function _prepareLayout(){
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('plugincompany_storelocator/storelocation_product_store_list_pager', 'plugincompany_storelocator.storelocation.html.pager')
            ->setCollection($this->getStorelocations());
        $this->setChild('pager', $pager);
        return $this;
    }
    /**
     * get the pager html
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getPagerHtml(){
        return $this->getChildHtml('pager');
    }

    /**
     * Get the finder url for store
     *
     * @return mixed
     */
    public function getStoreFinderUrl()
    {
        return Mage::helper('plugincompany_storelocator/storelocation')->getStoreFinderUrl();
    }

    /**
     * get rating flag
     * @access public
     * @return bool
     * @author Milan Simek
     */
    public function isRatingEnable() {
        return Mage::helper('plugincompany_storelocator')->isRatingEnable();
    }
    
    protected function showInProductTabs(){
        return (bool) Mage::getStoreConfig('plugincompany_storelocator/product_detail_page/show_in_product_tabs')
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
