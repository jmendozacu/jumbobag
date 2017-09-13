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
 * Store location comments resource model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Resource_Storelocation_Comment
    extends Mage_Core_Model_Mysql4_Abstract {
    /**
     * constructor
     * @access public
     * @author Milan Simek
     */
    public function _construct(){
        $this->_init('plugincompany_storelocator/storelocation_comment', 'comment_id');
    }

    /**
     * Get store ids to which specified item is assigned
     * @access public
     * @param int $storelocationId
     * @return array
     * @author Milan Simek
     */
    public function lookupStoreIds($commentId){
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('plugincompany_storelocator/storelocation_comment_store'), 'store_id')
            ->where('comment_id = ?',(int)$commentId);
        return $adapter->fetchCol($select);
    }
    /**
     * Perform operations after object load
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return Plugincompany_Storelocator_Model_Resource_Storelocation_Comment
     * @author Milan Simek
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object){
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Plugincompany_Storelocator_Model_Storelocation_Comment $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object){
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('storelocator_storelocation_comment_store' => $this->getTable('plugincompany_storelocator/storelocation_comment_store')),
                $this->getMainTable() . '.comment_id = storelocator_storelocation_comment_store.comment_id',
                array()
            )
            ->where('storelocator_storelocation_comment_store.store_id IN (?)', $storeIds)
            ->order('storelocator_storelocation_comment_store.store_id DESC')
            ->limit(1);
        }
        return $select;
    }
    /**
     * Assign store location comments to store views
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return Plugincompany_Storelocator_Model_Resource_Storelocation_Comment
     * @author Milan Simek
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object){
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('plugincompany_storelocator/storelocation_comment_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'comment_id = ?'  => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'comment_id'  => (int) $object->getId(),
                    'store_id'    => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }
}
