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
 * Store Location EAV collection resource model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Resource_Storelocationeav_Collection
    extends Mage_Catalog_Model_Resource_Collection_Abstract {
    protected $_joinedFields = array();
    /**
     * constructor
     * @access public
     * @return void
     * @author Milan Simek
     */
    protected function _construct(){
        parent::_construct();
        $this->_init('plugincompany_storelocator/storelocationeav');
    }
    /**
     * get storelocationeavs as array
     * @access protected
     * @param string $valueField
     * @param string $labelField
     * @param array $additional
     * @return array
     * @author Milan Simek
     */
    protected function _toOptionArray($valueField='entity_id', $labelField='customer_attr_one', $additional=array()){
        $this->addAttributeToSelect('customer_attr_one');
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
    protected function _toOptionHash($valueField='entity_id', $labelField='customer_attr_one'){
        $this->addAttributeToSelect('customer_attr_one');
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
