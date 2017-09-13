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
 * Store location image field renderer helper
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Helper_Image
    extends Varien_Data_Form_Element_Image {
    /**
     * get the url of the image
     * @access protected
     * @return string
     * @author Milan Simek
     */
    protected function _getUrl(){
        $url = false;
        if ($this->getValue()) {
            $url = Mage::helper('plugincompany_storelocator/storelocation_image')->getImageBaseUrl().$this->getValue();
        }
        return $url;
    }
}
