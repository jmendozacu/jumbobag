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
 * Store location collection resource model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Resource_Storelocation_Collection
    extends Mage_Core_Model_Mysql4_Collection_Abstract {
    protected $_joinedFields = array();
    protected $_isStoreJoined = false;
    
    /**
     * constructor
     * @access public
     * @return void
     * @author Milan Simek
     */
    protected function _construct(){
        parent::_construct();
        $this->_init('plugincompany_storelocator/storelocation');
        $this->_map['fields']['store'] = 'store_table.store_id';
    }
    /**
     * Add filter by store
     * @access public
     * @param int|Mage_Core_Model_Store $store
     * @param bool $withAdmin
     * @return Plugincompany_Storelocator_Model_Resource_Storelocation_Collection
     * @author Milan Simek
     */
    public function addStoreFilter($store, $withAdmin = true){
        if (!isset($this->_joinedFields['store'])){
            if ($store instanceof Mage_Core_Model_Store) {
                $store = array($store->getId());
            }
            if (!is_array($store)) {
                $store = array($store);
            }
            if ($withAdmin) {
                $store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
            }
            $this->addFilter('store', array('in' => $store), 'public');
            $this->_joinedFields['store'] = true;
        }
        return $this;
    }
    
    /**
     * Adds specific store id into array
     * @access public
     * @param array $storeId
     * @param bool $withAdmin
     * @return Plugincompany_Storelocator_Model_Resource_Storelocation_Comment_Storelocation_Collection
     * @author Milan Simek
     */
    public function setStoreFilter($storeId, $withAdmin = false) {
        
        $adapter = $this->getConnection();
        if (!is_array($storeId)) {
            $storeId = array($storeId === null ? -1 : $storeId);
        }
        if (empty($storeId)) {
            return $this;
        }
        if (!$this->_isStoreJoined) {
            $this->getSelect()
                ->distinct(true)
                ->join(
                    array('storelocation_store'=>$this->getTable('plugincompany_storelocator/storelocation_store')),
                    'main_table.entity_id = storelocation_store.storelocation_id',
                    array())
        //        ->group('main_table.rating_id')
                ;
            $this->_isStoreJoined = true;
        }
        $inCond = $adapter->prepareSqlCondition('storelocation_store.store_id', array(
            'in' => $storeId
        ));
        $this->getSelect()
            ->where($inCond);
        
        return $this;
    }
    
    
    /**
     * Join store relation table if there is store filter
     * @access protected
     * @return Plugincompany_Storelocator_Model_Resource_Storelocation_Collection
     * @author Milan Simek
     */
    protected function _renderFiltersBefore(){
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                array('store_table' => $this->getTable('plugincompany_storelocator/storelocation_store')),
                'main_table.entity_id = store_table.storelocation_id',
                array()
            )->group('main_table.entity_id');
            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        return parent::_renderFiltersBefore();
    }
    /**
     * get storelocations as array
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Milan Simek
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='locname', $additional=array()){
        return parent::_toOptionArray($valueField, $labelField, $additional);
    }
    /**
     * get options hash
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @return array
     * @author Milan Simek
     */
    protected function _toOptionHash($valueField='entity_id', $labelField='locname'){
        return parent::_toOptionHash($valueField, $labelField);
    }
    /**
     * Get SQL for get record count.
     * Extra GROUP BY strip added.
     * @access public
     * @return Varien_Db_Select
     * @author Milan Simek
     */
    public function getSelectCountSql(){
        $countSelect = parent::getSelectCountSql();
        $countSelect->reset(Zend_Db_Select::GROUP);
        return $countSelect;
    }
}
