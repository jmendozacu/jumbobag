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
 * attribute model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Attribute
    extends Mage_Eav_Model_Entity_Attribute {
    const SCOPE_STORE                           = 0;
    const SCOPE_GLOBAL                          = 1;
    const SCOPE_WEBSITE                         = 2;

    const MODULE_NAME                           = 'Plugincompany_Storelocator';
    const ENTITY                                = 'plugincompany_storelocator_eav_attribute';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix                     = 'plugincompany_storelocator_entity_attribute';
    /**
     * Event object name
     *
     * @var string
     */
    protected $_eventObject                     = 'attribute';

    /**
     * Array with labels
     *
     * @var array
     */
    static protected $_labels                   = null;

    /**
     * constructor
     * @access protected
     * @return void
     * @author Milan Simek
     */
    protected function _construct(){
        $this->_init('plugincompany_storelocator/attribute');
    }

    /**
     * Processing object before save data
     * @access protected
     * @throws Mage_Core_Exception
     * @return Mage_Core_Model_Abstract
     * @author Milan Simek
     */
    protected function _beforeSave(){
        $this->setData('modulePrefix', self::MODULE_NAME);
        if (isset($this->_origData['is_global'])) {
            if (!isset($this->_data['is_global'])) {
                $this->_data['is_global'] = self::SCOPE_GLOBAL;
            }
        }
        if ($this->getFrontendInput() == 'textarea') {
            if ($this->getIsWysiwygEnabled()) {
                $this->setIsHtmlAllowedOnFront(1);
            }
        }
        return parent::_beforeSave();
    }

    /**
     * Processing object after save data
     * @access protected
     * @return Mage_Core_Model_Abstract
     * @author Milan Simek
     */
    protected function _afterSave(){
        /**
         * Fix saving attribute in admin
         */
        Mage::getSingleton('eav/config')->clear();
        return parent::_afterSave();
    }

    /**
     * Return is attribute global
     * @access public
     * @return integer
     * @author Milan Simek
     */
    public function getIsGlobal(){
        return $this->_getData('is_global');
    }

    /**
     * Retrieve attribute is global scope flag
     * @access public
     * @return bool
     * @author Milan Simek
     */
    public function isScopeGlobal(){
        return $this->getIsGlobal() == self::SCOPE_GLOBAL;
    }

    /**
     * Retrieve attribute is website scope website
     * @access public
     * @return bool
     * @author Milan Simek
     */
    public function isScopeWebsite(){
        return $this->getIsGlobal() == self::SCOPE_WEBSITE;
    }

    /**
     * Retrieve attribute is store scope flag
     * @access public
     * @return bool
     * @author Milan Simek
     */
    public function isScopeStore(){
        return !$this->isScopeGlobal() && !$this->isScopeWebsite();
    }

    /**
     * Retrieve store id
     * @access public
     * @return int
     * @author Milan Simek
     */
    public function getStoreId(){
        $dataObject = $this->getDataObject();
        if ($dataObject) {
            return $dataObject->getStoreId();
        }
        return $this->getData('store_id');
    }
    /**
     * Retrieve source model
     * @access public
     * @return Mage_Eav_Model_Entity_Attribute_Source_Abstract
     * @author Milan Simek
     */
    public function getSourceModel(){
        $model = $this->getData('source_model');
        if (empty($model)) {
            if ($this->getBackendType() == 'int' && $this->getFrontendInput() == 'select') {
                return $this->_getDefaultSourceModel();
            }
        }
        return $model;
    }

    /**
     * Retrieve not translated frontend label
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getFrontendLabel(){
        return $this->_getData('frontend_label');
    }

    /**
     * Get Attribute translated label for store
     * @access protected
     * @deprecated
     * @return string
     * @author Milan Simek
     */
    protected function _getLabelForStore(){
        return $this->getFrontendLabel();
    }

    /**
     * Initialize store Labels for attributes
     * @access public
     * @param mixed $storeId
     * @deprecated
     * @return void
     * @author Milan Simek
     */
    public static function initLabels($storeId = null){
        if (is_null(self::$_labels)) {
            if (is_null($storeId)) {
                $storeId = Mage::app()->getStore()->getId();
            }
            $attributeLabels = array();
            $attributes = Mage::getResourceSingleton('catalog/product')->getAttributesByCode();
            foreach ($attributes as $attribute) {
                if (strlen($attribute->getData('frontend_label')) > 0) {
                    $attributeLabels[] = $attribute->getData('frontend_label');
                }
            }
            self::$_labels = Mage::app()->getTranslator()->getResource()->getTranslationArrayByStrings($attributeLabels, $storeId);
        }
    }

    /**
     * Get default attribute source model
     * @access public
     * @return string
   	 * @author Milan Simek
     */
    public function _getDefaultSourceModel(){
        return 'eav/entity_attribute_source_table';
    }
}
