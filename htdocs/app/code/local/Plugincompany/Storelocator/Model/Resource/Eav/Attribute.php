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
 * Attribute resource model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Resource_Eav_Attribute
    extends Mage_Eav_Model_Entity_Attribute {
    const MODULE_NAME   = 'Plugincompany_Storelocator';
    const ENTITY        = 'plugincompany_storelocator_eav_attribute';

    protected $_eventPrefix = 'plugincompany_storelocator_entity_attribute';
    protected $_eventObject = 'attribute';

    /**
     * Array with labels
     * @var array
     */
    static protected $_labels = null;
    /**
     * constructor
     * @access protected
     * @return void
     * @author Milan Simek
     */
    protected function _construct() {
        $this->_init('plugincompany_storelocator/attribute');
    }
    /**
     * check if scope is store view
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function isScopeStore() {
        return $this->getIsGlobal() == Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE;
    }
    /**
     * check if scope is website
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function isScopeWebsite() {
        return $this->getIsGlobal() == Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE;
    }
    /**
     * check if scope is global
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function isScopeGlobal() {
        return (!$this->isScopeStore() && !$this->isScopeWebsite());
    }
    /**
     * get backend input type
     * @access public
     * @param string $type
     * @return string
     * @author Milan Simek
     */
    public function getBackendTypeByInput($type) {
        switch ($type){
            case 'file':
                //intentional fallthrough
            case 'image':
                return 'varchar';
                break;
            default:
                return parent::getBackendTypeByInput($type);
            break;
        }
    }
    /**
     * don't delete system attributes
     * @access public
     * @param string $type
     * @return string
     * @author Milan Simek
     */
    protected function _beforeDelete(){
        if (!$this->getIsUserDefined()){
            throw new Mage_Core_Exception(Mage::helper('plugincompany_storelocator')->__('This attribute is not deletable'));
        }
        return parent::_beforeDelete();
    }

    protected function _beforeSave()
    {
        if(is_array($this->getStoreIds())){
            $this->setStoreIds(implode(',',$this->getStoreIds()));
        }
        return parent::_beforeSave();
    }
}
