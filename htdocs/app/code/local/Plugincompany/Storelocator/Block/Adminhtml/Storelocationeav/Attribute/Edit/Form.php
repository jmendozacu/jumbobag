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
 * Store Location EAV attribute add/edit block
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocationeav_Attribute_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare form
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocationeav_Attribute_Edit_Form
     * @author Milan Simek
     */
    protected function _prepareForm() {
        $form = new Varien_Data_Form(array('id' => 'edit_form', 'action' => $this->getUrl('adminhtml/storelocator_storelocationeav_attribute/save'), 'method' => 'post'));
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
