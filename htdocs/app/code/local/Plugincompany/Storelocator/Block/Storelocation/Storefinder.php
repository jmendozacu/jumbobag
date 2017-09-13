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
class Plugincompany_Storelocator_Block_Storelocation_Storefinder extends Mage_Core_Block_Template {

    public function getFilterAttributes(){
        $entity = Mage::getModel('eav/entity_type')->load('plugincompany_storelocator_storelocationeav', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entity->getEntityTypeId())
            ->addFieldToFilter('in_finder',1)
            ->addFieldToFilter('frontend_input',array('in'=>array('select','boolean','multiselect')))
            ->addFieldToFilter('additional_table.store_ids',
                array(
                    array('finset' => 0),
                    array('finset' => Mage::app()->getStore()->getId())
                )
            )
        ;
        $attributes->getSelect()->order('additional_table.position', 'ASC');
        return $attributes;
    }

    public function getFilterJSON(){
        $entity = Mage::getModel('eav/entity_type')->load('plugincompany_storelocator_storelocationeav', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entity->getEntityTypeId())
            ->addFieldToFilter('in_finder',1)
        ;
        $attributes->getSelect()->order('additional_table.position', 'ASC');
        $data = array();
        foreach($attributes as $attr){
            $data[$attr->getAttributeCode()] = $attr->getAttributeCode() . '-filters-container';
        }
        return json_encode($data);
    }

    public function canGeoCode(){
        if(Mage::getStoreConfigFlag('plugincompany_storelocator/storefinder/auto_geocode')
            && $this->canProductPageGeoCode()){
            return 'true';
        }
        return 'false';
    }

    public function canProductPageGeoCode(){
        if(!$this->getRequest()->getParam('product_id')){
            return true;
        }
        if(Mage::getStoreConfigFlag('plugincompany_storelocator/product_detail_page/geocode')){
            return true;
        }
        return false;
    }

    public function canMaxDistance(){
        if(Mage::getStoreConfigFlag('plugincompany_storelocator/storefinder/max_distance')){
            return 'true';
        }
        return 'false';
    }
    
    public function canNameFilter(){
        if(Mage::getStoreConfigFlag('plugincompany_storelocator/storefinder/name_filter')){
            return 'true';
        }
        return 'false';
    }

    public function getLengthUnitText(){
        $l = Mage::getStoreConfig("plugincompany_storelocator/storefinder/lengthunit");
        if($l == 'km'){
            return $this->__('Kilometers');
        }
        return $this->__('Miles');
    }

    protected function _includeAllLibs(){
        return Mage::registry('include_all_libs');
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
    
    /**
     * Check if SSL enabled
     * @access public
     * @return bool
     * @author Milan Simek
     */
    public function isSecureUrl(){
        return Mage::app()->getStore()->isCurrentlySecure();
    }
    
    /**
     * Get json url
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getDataLocation(){
        if(!$storeId = Mage::app()->getRequest()->getParam('store_id', 0)){
            $storeId = Mage::app()->getStore()->getStoreId();
        }

        if(!$productId = Mage::app()->getRequest()->getParam('product_id', 0)){
            $productId = Mage::registry('current_product')  ? Mage::registry('current_product')->getId() : 0;
        }
        
        $urlFilter = '';
        if((bool)$storeId && (bool)$productId && (bool) Mage::getStoreConfig('plugincompany_storelocator/product_detail_page/manually_manage_inventory_locator')){
            $urlFilter = "store_id/{$storeId}/product_id/{$productId}/";
        }
        
        return Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, $this->isSecureUrl()) . '/plugincompany_storelocator/storelocation/storesjson/' . $urlFilter;
    }

    public function isProductPage(){
        return Mage::app()->getRequest()->getParam('product_id', 0);
    }
}