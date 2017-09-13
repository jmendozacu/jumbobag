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
 * Store Location EAV model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Storelocationeav
    extends Mage_Catalog_Model_Abstract {
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'plugincompany_storelocator_storelocationeav';
    const CACHE_TAG = 'plugincompany_storelocator_storelocationeav';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'plugincompany_storelocator_storelocationeav';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'storelocationeav';
    /**
     * constructor
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function _construct(){
        parent::_construct();
        $this->_init('plugincompany_storelocator/storelocationeav');
    }
    /**
     * before save store location eav
     * @access protected
     * @return Plugincompany_Storelocator_Model_Storelocationeav
     * @author Milan Simek
     */
    protected function _beforeSave(){
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()){
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }
    /**
     * save storelocationeav relation
     * @access public
     * @return Plugincompany_Storelocator_Model_Storelocationeav
     * @author Milan Simek
     */
    protected function _afterSave() {
        return parent::_afterSave();
    }
    /**
     * Retrieve parent 
     * @access public
     * @return null|Plugincompany_Storelocator_Model_Storelocation
     * @author Milan Simek
     */
    public function getParentStorelocation(){
        if (!$this->hasData('_parent_storelocation')) {
            if (!$this->getStorelocationId()) {
                return null;
            }
            else {
                $storelocation = Mage::getModel('plugincompany_storelocator/storelocation')->load($this->getStorelocationId());
                if ($storelocation->getId()) {
                    $this->setData('_parent_storelocation', $storelocation);
                }
                else {
                    $this->setData('_parent_storelocation', null);
                }
            }
        }
        return $this->getData('_parent_storelocation');
    }
    /**
     * Retrieve default attribute set id
     * @access public
     * @return int
     * @author Milan Simek
     */
    public function getDefaultAttributeSetId() {
        return $this->getResource()->getEntityType()->getDefaultAttributeSetId();
    }
    /**
     * get attribute text value
     * @access public
     * @param $attributeCode
     * @return string
     * @author Milan Simek
     */
    public function getAttributeText($attributeCode) {
        $text = $this->getResource()
            ->getAttribute($attributeCode)
            ->getSource()
            ->getOptionText($this->getData($attributeCode));
        if (is_array($text)){
            return implode(', ',$text);
        }
        return $text;
    }
    /**
     * get default values
     * @access public
     * @return array
     * @author Milan Simek
     */
    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
}
