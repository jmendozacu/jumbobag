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
 * Store Location EAV helper
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Helper_Storelocationeav
    extends Mage_Core_Helper_Abstract {
    /**
     * get base files dir
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getFileBaseDir(){
        return Mage::getBaseDir('media').DS.'storelocationeav'.DS.'file';
    }
    /**
     * get base file url
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getFileBaseUrl(){
        return Mage::getBaseUrl('media').'storelocationeav'.'/'.'file';
    }
    /**
	 * get storelocationeav attribute source model
	 * @access public
	 * @param string $inputType
	 * @return mixed (string|null)
	 * @author Milan Simek
	 */
 	public function getAttributeSourceModelByInputType($inputType){
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['source_model'])) {
            return $inputTypes[$inputType]['source_model'];
        }
        return null;
    }
    /**
	 * get attribute input types
	 * @access public
	 * @param string $inputType
	 * @return array()
	 * @author Milan Simek
	 */
	public function getAttributeInputTypes($inputType = null){
        $inputTypes = array(
            'multiselect'   => array(
                'backend_model'     => 'eav/entity_attribute_backend_array',
                'source_model'      => 'eav/entity_attribute_source_table'
            ),
            'boolean'       => array(
                'source_model'      => 'eav/entity_attribute_source_boolean'
            ),
            'file'			=> array(
            	'backend_model'		=> 'plugincompany_storelocator/storelocationeav_attribute_backend_file'
            ),
            'image'			=> array(
            	'backend_model'		=> 'plugincompany_storelocator/storelocationeav_attribute_backend_image'
            ),
        );

        if (is_null($inputType)) {
            return $inputTypes;
        } else if (isset($inputTypes[$inputType])) {
            return $inputTypes[$inputType];
        }
        return array();
    }
    /**
	 * get storelocationeav attribute backend model
	 * @access public
	 * @param string $inputType
	 * @return mixed (string|null)
	 * @author Milan Simek
	 */
 	public function getAttributeBackendModelByInputType($inputType){
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['backend_model'])) {
            return $inputTypes[$inputType]['backend_model'];
        }
        return null;
    }
    /**
     * filter attribute content
     * @access public
     * @param Plugincompany_Storelocator_Model_Storelocationeav $storelocationeav
     * @param string $attributeHtml
     * @param string @attributeName
     * @return string
     * @author Milan Simek
     */
	public function storelocationeavAttribute($storelocationeav, $attributeHtml, $attributeName){
        $attribute = Mage::getSingleton('eav/config')->getAttribute(Plugincompany_Storelocator_Model_Storelocationeav::ENTITY, $attributeName);
        if ($attribute && $attribute->getId() && !$attribute->getIsWysiwygEnabled()) {
			if ($attribute->getFrontendInput() == 'textarea') {
            	$attributeHtml = nl2br($attributeHtml);
            }
        }
        if ($attribute->getIsWysiwygEnabled()) {
            $attributeHtml = $this->_getTemplateProcessor()->filter($attributeHtml);
        }
        return $attributeHtml;
    }
    /**
     * get the template processor
     * @access protected
     * @return Mage_Catalog_Model_Template_Filter
     * @author Milan Simek
     */
	protected function _getTemplateProcessor(){
        if (null === $this->_templateProcessor) {
            $this->_templateProcessor = Mage::helper('catalog')->getPageTemplateProcessor();
        }
        return $this->_templateProcessor;
    }
    
    public function isAttrEnabled($attr){
        $storeIds = explode(',',$attr->getStoreIds());
        if(in_array(0,$storeIds) || in_array(Mage::app()->getStore()->getId(),$storeIds)){
            return true;
        }
        return false;
    }
}
