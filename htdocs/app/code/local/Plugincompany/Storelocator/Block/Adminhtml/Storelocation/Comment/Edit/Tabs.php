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
 * Store location comment admin edit tabs
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Comment_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs {
    /**
     * Initialize Tabs
     * @access public
     * @author Milan Simek
     */
    public function __construct() {
        parent::__construct();
        $this->setId('storelocation_comment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('plugincompany_storelocator')->__('Store Comment'));
    }
    /**
     * before render html
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Edit_Tabs
     * @author Milan Simek
     */
    protected function _beforeToHtml(){
        $this->addTab('form_storelocation_comment', array(
            'label'        => Mage::helper('plugincompany_storelocator')->__('Store Comment'),
            'title'        => Mage::helper('plugincompany_storelocator')->__('Store Comment'),
            'content'     => $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_comment_edit_tab_form')->toHtml(),
        ));
        if (!Mage::app()->isSingleStoreMode()){
            $this->addTab('form_store_storelocation_comment', array(
                'label'        => Mage::helper('plugincompany_storelocator')->__('Store Views'),
                'title'        => Mage::helper('plugincompany_storelocator')->__('Store Views'),
                'content'     => $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_comment_edit_tab_stores')->toHtml(),
            ));
        }
        return parent::_beforeToHtml();
    }
    /**
     * Retrieve store location entity
     * @access public
     * @return Plugincompany_Storelocator_Model_Storelocation_Comment
     * @author Milan Simek
     */
    public function getComment(){
        return Mage::registry('current_comment');
    }
}
