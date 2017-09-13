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
 * Store Location EAV attribute edit block
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocationeav_Attribute_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container {
    /**
     * constructor
     * @access public
     * @author Milan Simek
     */
    public function __construct() {
        $this->_objectId = 'attribute_id';
        $this->_controller = 'adminhtml_storelocationeav_attribute';
        $this->_blockGroup = 'plugincompany_storelocator';

        parent::__construct();
        $this->_addButton(
            'save_and_edit_button',
            array(
                'label'     => Mage::helper('plugincompany_storelocator')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit()',
                'class'     => 'save'
            ),
            100
        );
        $this->_updateButton('save', 'label', Mage::helper('plugincompany_storelocator')->__('Save Attribute'));
        $this->_updateButton('save', 'onclick', 'saveAttribute()');

        if (!Mage::registry('entity_attribute')->getIsUserDefined()) {
            $this->_removeButton('delete');
        } else {
            $this->_updateButton('delete', 'label', Mage::helper('plugincompany_storelocator')->__('Delete Attribute'));
        }
    }
    /**
     * get the header text for the form
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getHeaderText(){
        if (Mage::registry('entity_attribute')->getId()) {
            $frontendLabel = Mage::registry('entity_attribute')->getFrontendLabel();
            if (is_array($frontendLabel)) {
                $frontendLabel = $frontendLabel[0];
            }
            return Mage::helper('plugincompany_storelocator')->__('Edit Store Attribute "%s"', $this->htmlEscape($frontendLabel));
        }
        else {
            return Mage::helper('plugincompany_storelocator')->__('New Store Attribute');
        }
    }
    /**
     * get validation url for form
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getValidationUrl(){
        return $this->getUrl('*/*/validate', array('_current'=>true));
    }
    /**
     * get save url for form
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getSaveUrl(){
        return $this->getUrl('*/'.$this->_controller.'/save', array('_current'=>true, 'back'=>null));
    }
}
