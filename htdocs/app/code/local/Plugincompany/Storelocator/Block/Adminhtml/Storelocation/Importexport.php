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
 * Store location admin edit form
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Importexport
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
        $this->_controller = 'adminhtml_storelocation';
        $this->removeButton('save');
        $this->removeButton('delete');
        $this->removeButton('reset');
    }
    /**
     * get the edit form header
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getHeaderText(){
        return Mage::helper('plugincompany_storelocator')->__('Import / Export Store Locations');
    }
}
