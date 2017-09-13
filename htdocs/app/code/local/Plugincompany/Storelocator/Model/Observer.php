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
class Plugincompany_Storelocator_Model_Observer {

    public function addEnabledLayoutHandleIfApplicable(Varien_Event_Observer $observer)
    {
        $layout = $observer->getEvent()->getLayout();
        if(Mage::helper('plugincompany_storelocator')->isEnabled()){
            $this
                ->addStoreFinderLayoutHandle($layout)
                ->addCustomerAccountLayoutHandle($layout)
            ;
        }
    }
    
    private function addStoreFinderLayoutHandle($layout)
    {
        $layout->getUpdate()
            ->addHandle('storefinderenabled');
        return $this;
    }
    
    private function addCustomerAccountLayoutHandle($layout)
    {
        $handles = $layout->getUpdate()->getHandles();
        $matches = preg_grep('/^customer_account_/',$handles);
        if(count($matches)){
            $layout->getUpdate()
                ->addHandle('customer_account_storefinderenabled');
        }
        return $this;
    }
        
    
    public function addStoreFinderLink(Varien_Event_Observer $observer)
    {
        if(!$this->isEnabled()){
            return;
        }
        $menu = $observer->getMenu();
        $tree = $menu->getTree();

        //Store list link in topmenu

        if (Mage::getStoreConfig('plugincompany_storelocator/storelist/showlistlink')) {

            $title = Mage::getStoreConfig('plugincompany_storelocator/storelist/listlinktitle');
            if (!$title) {
                $title = 'Stores';
            }

            $node = new Varien_Data_Tree_Node(array(
            'name'   => $title,
            'id'     => 'storelistlink',
            'url'    => Mage::helper('plugincompany_storelocator/storelocation')->getStoreLocationsUrl()
        ),  'id', $tree, $menu);
            $menu->addChild($node);
        }

        //Store Finder link in topmenu

        if (Mage::getStoreConfig('plugincompany_storelocator/storefinder/showfinderlink')) {

            $title = Mage::getStoreConfig('plugincompany_storelocator/storefinder/finderlinktitle');
            if (!$title) {
                $title = 'Find a store';
            }

            $node = new Varien_Data_Tree_Node(array(
                'name'   => $title,
                'id'     => 'storefinderlink',
                'url'    => Mage::helper('plugincompany_storelocator/storelocation')->getStoreFinderUrl()
            ),  'id', $tree, $menu);
            $menu->addChild($node);
        }
    }
    
    public function catalogProductSaveAfter(Varien_Event_Observer $observer){
        if(!Mage::helper('plugincompany_storelocator/storelocation')->isManuallyManageInventoryLocatorEnabled()){
            return $this;
        }
        
        $actionInstance = Mage::app()->getFrontController()->getAction();
        
        if ($actionInstance && $actionInstance->getFullActionName() === 'adminhtml_catalog_product_save') {
            $product = $observer->getProduct();
        
            //update location product
            $links = Mage::app()->getRequest()->getPost('links');

            if (isset($links['product_storelocations'])) {
                $product->setProductBulkStoreLocationData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['product_storelocations']));                    
            }
            
            Mage::getModel('plugincompany_storelocator/storelocationproduct')->updateLocationOnProductSave($product);
        }
        
        return $this;
    }
    
    private function isEnabled()
    {
        return Mage::helper('plugincompany_storelocator')->isEnabled();
    }
}