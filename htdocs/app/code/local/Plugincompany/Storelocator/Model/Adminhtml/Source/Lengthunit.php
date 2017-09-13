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
 * Admin source yes/no/default model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Adminhtml_Source_Lengthunit
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {

    /**
     * get possible values
     * @access public
     * @return array
     * @author Milan Simek
     */
    public function toOptionArray(){
        return array(
            array(
                'label' => Mage::helper('plugincompany_storelocator')->__('Kilometers'),
                'value' => 'km'
            ),
            array(
                'label' => Mage::helper('plugincompany_storelocator')->__('Miles'),
                'value' => 'm'
            )
        );
    }
    /**
     * Get list of all available values
     * @access public
     * @return array
     * @author Milan Simek
     */
    public function getAllOptions() {
        return $this->toOptionArray();
    }
}