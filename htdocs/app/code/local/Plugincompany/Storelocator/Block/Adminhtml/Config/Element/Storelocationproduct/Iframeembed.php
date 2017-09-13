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
class Plugincompany_Storelocator_Block_Adminhtml_Config_Element_Storelocationproduct_Iframeembed extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    public function __construct()
    {
        $this->setTemplate('plugincompany/storelocator/config/iframeembed/storelocationproduct/iframeembed.phtml');
    }
    /**
     * Retrieve Element HTML fragment
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->toHtml();
    }


}
