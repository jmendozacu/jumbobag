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
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Importexport_Tabs
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
        $this->setTitle(Mage::helper('plugincompany_storelocator')->__('Import / Export Stores'));
    }
    /**
     * before render html
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Edit_Tabs
     * @author Milan Simek
     */
    protected function _beforeToHtml(){
        $this->addTab('import', array(
            'label'        => Mage::helper('plugincompany_storelocator')->__('Import Store Locations'),
            'title'        => Mage::helper('plugincompany_storelocator')->__('Import Store Locations'),
            'content'     => $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_importexport_tab_store_import')->toHtml(),
        ));

        $this->addTab('export', array(
            'label'        => Mage::helper('plugincompany_storelocator')->__('Export Store Locations'),
            'title'        => Mage::helper('plugincompany_storelocator')->__('Export Store Locations'),
            'content'     => $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_importexport_tab_store_export')->toHtml(),
        ));

        if(Mage::helper('plugincompany_storelocator/storelocation')->isManuallyManageInventoryLocatorEnabled()){
            $this->addTab('import_store_products', array(
                'label'        => Mage::helper('plugincompany_storelocator')->__('Import Store Products'),
                'title'        => Mage::helper('plugincompany_storelocator')->__('Import Store Products'),
                'content'     => $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_importexport_tab_product_import')->toHtml(),
            ));
        }

        return parent::_beforeToHtml();
    }
}
