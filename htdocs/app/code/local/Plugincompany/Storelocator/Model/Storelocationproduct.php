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
 * Store location model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Storelocationproduct
    extends Mage_Core_Model_Abstract {

    protected $_eventPrefix = 'plugincompany_storelocator_storelocationproduct';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'storelocationproduct';
    /**
     * constructor
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function _construct(){
        parent::_construct();
        $this->_init('plugincompany_storelocator/storelocationproduct');
    }
    
    
    public function updateProductOnLocationSave(Mage_Core_Model_Abstract $object){
        return $this->_getResource()->_saveBulkProductOnLocationSave($object);
    }
    
    
    public function updateLocationOnProductSave(Mage_Core_Model_Abstract $object){
        return $this->_getResource()->_saveBulkLocationOnProductSave($object);
    }
    
    public function massImportLocationDelete($where){
        return $this->_getResource()->massDelete($where);
    }
     
    public function massImportLocationAdd($where){
        
        print_r($where);
        return $this->_getResource()->massImport($where);
    }
   
}
