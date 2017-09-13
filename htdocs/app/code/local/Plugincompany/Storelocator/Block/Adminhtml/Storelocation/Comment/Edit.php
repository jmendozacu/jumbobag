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
 * Store location comment admin edit form
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Comment_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container {
    /**
     * constructor
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function __construct(){
        parent::__construct();
        $this->_blockGroup = 'plugincompany_storelocator';
        $this->_controller = 'adminhtml_storelocation_comment';
        $this->_updateButton('save', 'label', Mage::helper('plugincompany_storelocator')->__('Save Store Comment'));
        $this->_updateButton('delete', 'label', Mage::helper('plugincompany_storelocator')->__('Delete Store Comment'));
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('plugincompany_storelocator')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    /**
     * get the edit form header
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getHeaderText(){
        if( Mage::registry('comment_data') && Mage::registry('comment_data')->getId() ) {
            return Mage::helper('plugincompany_storelocator')->__("Edit Store Comment '%s'", $this->htmlEscape(Mage::registry('comment_data')->getTitle()));
        }
        return '';
    }
}
