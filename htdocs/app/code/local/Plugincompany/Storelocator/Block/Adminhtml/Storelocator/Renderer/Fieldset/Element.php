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
 * fieldset element renderer
 * @category   Plugincompany
 * @package    Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocator_Renderer_Fieldset_Element
    extends Mage_Adminhtml_Block_Widget_Form_Renderer_Fieldset_Element {
    /**
     * Initialize block template
     * @access protected
     * @author Milan Simek
     */
    protected function _construct() {
        $this->setTemplate('plugincompany_storelocator/form/renderer/fieldset/element.phtml');
    }

    /**
     * Retrieve data object related with form
     * @access public
     * @return mixed
     * @author Milan Simek
     */
    public function getDataObject() {
        return $this->getElement()->getForm()->getDataObject();
    }

    /**
     * Retrieve associated with element attribute object
     * @access public
     * @return Plugincompany_Storelocator_Model_Resource_Eav_Attribute
     * @author Milan Simek
     */
    public function getAttribute() {
        return $this->getElement()->getEntityAttribute();
    }

    /**
     * Retrieve associated attribute code
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getAttributeCode(){
        return $this->getAttribute()->getAttributeCode();
    }

    /**
     * Check "Use default" checkbox display availability
     * @access public
     * @return bool
     * @author Milan Simek
     */
    public function canDisplayUseDefault(){
        if ($attribute = $this->getAttribute()) {
            if (!$this->isScopeGlobal($attribute)
                && $this->getDataObject()
                && $this->getDataObject()->getId()
                && $this->getDataObject()->getStoreId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check default value usage fact
     * @access public
     * @return bool
     * @author Milan Simek
     */
    public function usedDefault(){
        $defaultValue = $this->getDataObject()->getAttributeDefaultValue($this->getAttribute()->getAttributeCode());
        return !$defaultValue;
    }

    /**
     * Disable field in default value using case
     * @access public
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocator_Renderer_Fieldset_Element
     * @author Milan Simek
     */
    public function checkFieldDisable(){
        if ($this->canDisplayUseDefault() && $this->usedDefault()) {
            $this->getElement()->setDisabled(true);
        }
        return $this;
    }

    /**
     * Retrieve label of attribute scope
     * GLOBAL | WEBSITE | STORE
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getScopeLabel(){
        $html = '';
        $attribute = $this->getElement()->getEntityAttribute();
        if (!$attribute || Mage::app()->isSingleStoreMode()) {
            return $html;
        }
        if ($this->isScopeGlobal($attribute)) {
            $html.= Mage::helper('plugincompany_storelocator')->__('[GLOBAL]');
        }
        elseif ($this->isScopeWebsite($attribute)) {
            $html.= Mage::helper('plugincompany_storelocator')->__('[WEBSITE]');
        }
        elseif ($this->isScopeStore($attribute)) {
            $html.= Mage::helper('plugincompany_storelocator')->__('[STORE VIEW]');
        }
        return $html;
    }

    /**
     * Retrieve element label html
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getElementLabelHtml(){
        return $this->getElement()->getLabelHtml();
    }

    /**
     * Retrieve element html
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getElementHtml(){
        return $this->getElement()->getElementHtml();
    }
    /**
     * check if an attribute is global
     * @access public
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @return bool
     * @author Milan Simek
     */
    public function isScopeGlobal($attribute){
        return $attribute->getIsGlobal() == 1;
    }
    /**
     * check if an attribute has website scope
     * @access public
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @return bool
     * @author Milan Simek
     */
    public function isScopeWebsite($attribute){
        return $attribute->getIsGlobal() == 2;
    }
    /**
     * check if an attribute has store view scope
     * @access public
     * @param Mage_Eav_Model_Entity_Attribute $attribute
     * @return bool
     * @author Milan Simek
     */
    public function isScopeStore($attribute){
        return !$this->isScopeGlobal($attribute) && !$this->isScopeWebsite($attribute);
    }
}
