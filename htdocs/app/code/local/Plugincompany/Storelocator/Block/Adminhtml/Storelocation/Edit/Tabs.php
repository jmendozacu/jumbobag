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
 * Store location admin edit tabs
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs {
    /**
     * Initialize Tabs
     * @access public
     * @author Milan Simek
     */
    public function __construct() {
        parent::__construct();
        $this->setId('storelocation_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('plugincompany_storelocator')->__('Store Location'));
    }
    /**
     * before render html
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Edit_Tabs
     * @author Milan Simek
     */
    protected function _beforeToHtml(){
        $this->addTab('form_info_storelocation', array(
            'label'        => Mage::helper('plugincompany_storelocator')->__('Store Information'),
            'title'        => Mage::helper('plugincompany_storelocator')->__('Store Information'),
            'content'     => $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_edit_tab_info')->toHtml(),
        ));
        $this->addTab('form_storelocation', array(
            'label'        => Mage::helper('plugincompany_storelocator')->__('Store Location'),
            'title'        => Mage::helper('plugincompany_storelocator')->__('Store Location'),
            'content'     => $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_edit_tab_form')->toHtml(),
        ));

        $entity = Mage::getModel('eav/entity_type')->load('plugincompany_storelocator_storelocationeav', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entity->getEntityTypeId());
        $attributes->getSelect()->order('additional_table.position', 'ASC');

        $this->addTab('info', array(
            'label'     => Mage::helper('plugincompany_storelocator')->__('Custom Store Attributes'),
            'content'   => $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_edit_tab_attributes')
                    ->setAttributes($attributes)
                    ->toHtml(),
        ));

        if(Mage::helper('plugincompany_storelocator/storelocation')->isManuallyManageInventoryLocatorEnabled()){
            $this->addTab('form_store_storelocationproduct', array(
                'label'        => Mage::helper('plugincompany_storelocator')->__('Store Products'),
                'title'        => Mage::helper('plugincompany_storelocator')->__('Store Products'),
                //'content'     => $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_edit_tab_product')->toHtml(),
                'class'     => 'ajax',
                'url'       => $this->getUrl('adminhtml/storelocator_storelocation/storeProduct', array('_current' => true)), 
            ));
        }

        $this->addTab('form_meta_storelocation', array(
            'label'        => Mage::helper('plugincompany_storelocator')->__('Store Meta Data'),
            'title'        => Mage::helper('plugincompany_storelocator')->__('Store Meta Data'),
            'content'     => $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_edit_tab_meta')->toHtml(),
        ));

        if (!Mage::app()->isSingleStoreMode()){
            $this->addTab('form_store_storelocation', array(
                'label'        => Mage::helper('plugincompany_storelocator')->__('Store Views'),
                'title'        => Mage::helper('plugincompany_storelocator')->__('Store Views'),
                'content'     => $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_edit_tab_stores')->toHtml(),
            ));
        }
        return parent::_beforeToHtml();
    }
    /**
     * Retrieve store location entity
     * @access public
     * @return Plugincompany_Storelocator_Model_Storelocation
     * @author Milan Simek
     */
    public function getStorelocation(){
        return Mage::registry('current_storelocation');
    }
}
