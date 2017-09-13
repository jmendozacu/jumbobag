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
 * Store location edit form tab
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Importexport_Tab_Store_Export
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Edit_Tab_Form
     * @author Milan Simek
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('storelocation_');
        $form->setFieldNameSuffix('storelocation');
        $this->setForm($form);
        $fieldset = $form->addFieldset('storelocation_form', array('legend'=>Mage::helper('plugincompany_storelocator')->__('Export Store Locations')));

        $fieldset->addField('export', 'button', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Export CVS file'),
            'name'  => 'export',
            'value' => 'Export',
            'onclick' => 'window.location=\'' . Mage::helper('adminhtml')->getUrl('adminhtml/storelocator_importexport/export') . '\'',
            'class' => 'form-button'
        ));
        return parent::_prepareForm();
    }
}
