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
 * Store Location EAV admin attribute block
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocationeav_Attribute
    extends Mage_Adminhtml_Block_Widget_Grid_Container {
    /**
     * constructor
     * @access public
     * @author Milan Simek
     */
    public function __construct(){
        $this->_controller = 'adminhtml_storelocationeav_attribute';
        $this->_blockGroup = 'plugincompany_storelocator';
        $this->_headerText = Mage::helper('plugincompany_storelocator')->__('Manage Custom Store Attributes');
        parent::__construct();
        $this->_updateButton('add', 'label', Mage::helper('plugincompany_storelocator')->__('Add New Attribute'));
    }
}
