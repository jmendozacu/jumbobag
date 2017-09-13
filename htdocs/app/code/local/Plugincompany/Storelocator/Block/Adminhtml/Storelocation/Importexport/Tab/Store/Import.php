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
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Importexport_Tab_Store_Import
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

        $fieldset = $form->addFieldset('storelocation_import',
            array(
                'legend'=>Mage::helper('plugincompany_storelocator')->__('Import Store Locations'),
                 'class'=> 'fieldset-wide'
            ));
        

        $fieldset->addField('store_import', 'file', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Select CSV file'),
            'name'  => 'store_import'
        ));

        $fieldset->addField('geocode', 'checkbox', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Retrieve Geocode locations'),
            'name'  => 'geocode',
            'note'  => $this->__('If checked, the store location coordinates will be automatically retrieved using the Google Maps API.'),
            'value' => 1
        ));

        $fieldset->addField('doimport', 'button', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Import CSV file'),
            'name'  => 'runimport',
            'value' => 'Import',
            'onclick' => 'runImport()',
            'class' => 'form-button'
        ));

        $fieldset = $form->addFieldset('storelocation_import_info',
            array(
                'legend'=>Mage::helper('plugincompany_storelocator')->__('Import Instructions'),
                'class' => 'fieldset-wide'
            ));

        $fieldset->addField('importinfo', 'note', array(
            'name'  => 'importinfo',
            'text' => $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_importexport_tab_store_info_import')->toHtml(),
        ));

        return parent::_prepareForm();
    }
}
