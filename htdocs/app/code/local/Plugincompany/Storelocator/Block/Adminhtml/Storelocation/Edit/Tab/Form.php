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
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Edit_Tab_Form
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
        $fieldset = $form->addFieldset('storelocation_form', array('legend'=>Mage::helper('plugincompany_storelocator')->__('Store Location')));
        $fieldset->addType('image', Mage::getConfig()->getBlockClassName('plugincompany_storelocator/adminhtml_storelocation_helper_image'));
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();


        $fieldset->addField('fulladdress', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Full address (for autofill)'),
            'name'  => 'fulladdress',
        ));

        $fieldset->addField('address', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Address line 1'),
            'name'  => 'address',
            'required'  => true,
            'class' => 'required-entry',
        ));

        $fieldset->addField('address2', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Address Line 2'),
            'name'  => 'address2',
        ));

        $fieldset->addField('city', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('City'),
            'name'  => 'city',
            'required'  => true,
            'class' => 'required-entry',
        ));

        $fieldset->addField('postal', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Postal code / zip code'),
            'name'  => 'postal',
            'required'  => true,
            'class' => 'required-entry',
        ));

        $fieldset->addField('state', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('State'),
            'name'  => 'state',
        ));

        $fieldset->addField('country', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Country'),
            'name'  => 'country',
            'required'  => true,
            'class' => 'required-entry',
        ));

        $fieldset->addField('lat', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Latitude'),
            'name'  => 'lat',
            'required'  => true,
            'class' => 'required-entry',
        ));

        $fieldset->addField('lng', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Longitude'),
            'name'  => 'lng',
            'required'  => true,
            'class' => 'required-entry',
        ));

        $formValues = Mage::registry('current_storelocation')->getDefaultValues();
        if (!is_array($formValues)){
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getStorelocationData()){
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getStorelocationData());
            Mage::getSingleton('adminhtml/session')->setStorelocationData(null);
        }
        elseif (Mage::registry('current_storelocation')){
            $formValues = array_merge($formValues, Mage::registry('current_storelocation')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}

