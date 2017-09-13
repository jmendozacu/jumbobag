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
 * Store location resource model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Resource_Storelocationproduct
    extends Mage_Core_Model_Mysql4_Abstract {
    /**
     * constructor
     * @access public
     * @author Milan Simek
     */
    public function _construct(){
        $this->_init('plugincompany_storelocator/storelocation_product', 'entity_id');
    }
   
     
    /**
     * Assign store location to store views
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return Plugincompany_Storelocator_Model_Resource_Storelocation
     * @author Milan Simek
     */
    public function _saveBulkProductOnLocationSave(Mage_Core_Model_Abstract $object){
        if(!$object->getId()){ return $this; }
        
        $table  = $this->getMainTable();
        
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
               
        $oldStores = $this->lookupStoreIdsFromLocationId($object->getId());
        $currentStoreIdsProductIds = $this->lookupIdsFromLocationId($object->getId());
        $oldProducts = $this->lookupProductIdsFromLocationId($object->getId());
        
        $insertStores = array_diff($newStores, $oldStores);
        $deleteStores = array_diff($oldStores, $newStores);
        
        
        if ($deleteStores) {
            $where = array(
                'storelocation_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $deleteStores
            );
            
            $this->massDelete($where);
        }
        
        //Try copying old items to new store, if just changing store
        if ($insertStores && !empty($oldProducts)) {
            $data = array();
            foreach($oldProducts as $productId){
                foreach ($insertStores as $storeId) {
                    $_insertData = array('store_id' => $storeId, 'product_id' => $productId);
                    if(!in_array($_insertData, $currentStoreIdsProductIds)){
                        $data[] = array(
                            'storelocation_id'  => (int) $object->getId(),
                            'store_id' => (int) $storeId,
                            'product_id' => (int) $productId
                        );
                        
                        $currentStoreIdsProductIds[] = $_insertData;
                    }

                }
            }
            
            $this->massImport($data);
            
        }
        
        
        
        // insert product
        if(!is_null($newProductids = $object->getStoreLocationBulkProductData())){
            
            $insertProducts = array_diff($newProductids, $oldProducts);
            $deleteProducts = array_diff($oldProducts, $newProductids);
            
            if ($deleteProducts) {
                $where = array(
                    'storelocation_id = ?' => (int) $object->getId(),
                    'product_id IN (?)' => $deleteProducts
                );

                $this->massDelete($where);
            }
            
            //Try copying old items to new store, if just changing store
            if ($insertProducts) {
                $data = array();
                foreach($insertProducts as $productId){
                    foreach ($newStores as $storeId) {
                        $_insertData = array('store_id' => $storeId, 'product_id' => $productId);
                        if(!in_array($_insertData, $currentStoreIdsProductIds)){
                            $data[] = array(
                                'storelocation_id'  => (int) $object->getId(),
                                'store_id' => (int) $storeId,
                                'product_id' => (int) $productId
                            );

                            $currentStoreIdsProductIds[] = $_insertData;
                        }

                    }
                }

                $this->massImport($data);

            }
            
        }
        
        return $this;
    }    
    
 
 
    /**
     * Assign store location to store views
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return Plugincompany_Storelocator_Model_Resource_Storelocation
     * @author Milan Simek
     */
    public function _saveBulkLocationOnProductSave($object){
        if(!$object->getId()){ return $this; }
        
        $table  = $this->getMainTable();

        $newStores = (array)$object->getStoreIds();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }

        $oldStores = $this->lookupStoreIdsFromProductId($object->getId());
        $currentStoreIdsLocationIds = $this->lookupIdsFromProductId($object->getId());
        $oldLocations = $this->lookupLocationIdsFromProductId($object->getId());

        $insertStores = array_diff($newStores, $oldStores);
        $deleteStores = array_diff($oldStores, $newStores);


        if ($deleteStores) {
            $where = array(
                'product_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $deleteStores
            );

            $this->massDelete($where);
        }

        //Try copying old items to new store, if just changing store
        if ($insertStores && !empty($oldLocations)) {
            $data = array();
            foreach($oldLocations as $locationId){
                foreach ($insertStores as $storeId) {
                    $_insertData = array('store_id' => $storeId, 'storelocation_id' => $locationId);
                    if(!in_array($_insertData, $currentStoreIdsLocationIds)){
                        $data[] = array(
                            'storelocation_id'  => (int) $locationId,
                            'store_id' => (int) $storeId,
                            'product_id' => (int) $object->getId()
                        );

                        $currentStoreIdsLocationIds[] = $_insertData;
                    }

                }
            }

            $this->massImport($data);

        }


        // insert product
        if(!is_null($newLocationids = $object->getProductBulkStoreLocationData())){

            $insertLocations = array_diff($newLocationids, $oldLocations);
            $deleteLocations = array_diff($oldLocations, $newLocationids);

            if ($deleteLocations) {
                $where = array(
                    'product_id = ?' => (int) $object->getId(),
                    'storelocation_id IN (?)' => $deleteLocations
                );
                
                $this->massDelete($where);
            }

            //Try copying old items to new store, if just changing store
            if ($insertLocations) {
                $data = array();
                foreach($insertLocations as $locationId){
                    foreach ($newStores as $storeId) {
                        $_insertData = array('store_id' => $storeId, 'product_id' => $locationId);
                        if(!in_array($_insertData, $currentStoreIdsLocationIds)){
                            $data[] = array(
                                'product_id'  => (int) $object->getId(),
                                'store_id' => (int) $storeId,
                                'storelocation_id' => (int) $locationId
                            );

                            $currentStoreIdsLocationIds[] = $_insertData;
                        }

                    }
                }

                $this->massImport($data);
            }

        }
        
        return $this;
    } 
    
    
    public function massDelete($where, $table=null){
        if(!$table){
            $table = $this->getMainTable();
        }
        
        try{
            return $this->_getWriteAdapter()->delete($table, $where);
        }catch(Exception $e){
            //return $e;
        }
    }
    
    public function massImport($data, $table=null){
        if(!$table){
            $table = $this->getMainTable();
        }
        
        try{
            return $this->_getWriteAdapter()->insertMultiple($table, $data);
        }catch(Exception $e){
            //return $e;
        }
    }
            
    /**
     * Get store/product ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupIdsFromLocationId($locationId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getMainTable(), array('store_id', 'product_id'))
            ->where('storelocation_id = ?',(int)$locationId);

        return $adapter->fetchAll($select);
    }
    
    
    /**
     * Get store/storelocation ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupIdsFromProductId($productId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getMainTable(), array('store_id', 'storelocation_id'))
            ->where('product_id = ?',(int)$productId);

        return $adapter->fetchAll($select);
    }
   
    
    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIdsFromLocationId($locationId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getMainTable(), array('store_id'))
            ->where('storelocation_id = ?',(int)$locationId);

        return $adapter->fetchCol($select);
    }
    
    
    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIdsFromProductId($productId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getMainTable(), array('store_id'))
            ->where('product_id = ?',(int)$productid);

        return $adapter->fetchCol($select);
    }

    
    /**
     * Get product ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupProductIdsFromLocationId($locationId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getMainTable(), array('product_id'))
            ->where('storelocation_id = ?',(int)$locationId);

        return $adapter->fetchCol($select);
    }  
    
    
    /**
     * Get location id to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupLocationIdsFromProductId($productId)
    {
        $adapter = $this->_getReadAdapter();

        $select  = $adapter->select()
            ->from($this->getMainTable(), array('storelocation_id'))
            ->where('product_id = ?',(int)$productId);

        return $adapter->fetchCol($select);
    }
   

    /**
     * Set store model
     *
     * @param Mage_Core_Model_Store $store
     * @return Mage_Cms_Model_Resource_Page
     */
    public function setStore($store)
    {
        $this->_store = $store;
        return $this;
    }

    /**
     * Retrieve store model
     *
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        return Mage::app()->getStore($this->_store);
    }
}
