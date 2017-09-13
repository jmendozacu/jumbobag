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
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Edit_Tab_Info
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
        $fieldset = $form->addFieldset('storelocation_info', array('legend'=>Mage::helper('plugincompany_storelocator')->__('Store Page Settings')));
        $fieldset->addType('image', Mage::getConfig()->getBlockClassName('plugincompany_storelocator/adminhtml_storelocation_helper_image'));
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();

        $fieldset->addField('locname', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Store / location name'),
            'name'  => 'locname',
            'required'  => true,
            'class' => 'required-entry',
        ));

        $fieldset->addField('phone', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Phone number'),
            'name'  => 'phone',
        ));

        $fieldset->addField('fax', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Fax number'),
            'name'  => 'fax',
        ));

        $fieldset->addField('email', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('E-mail address'),
            'name'  => 'email',
        ));

        $fieldset->addField('web', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Website'),
            'name'  => 'web',
        ));

        $fieldset->addField('hours1', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Opening hours line 1'),
            'name'  => 'hours1',
        ));

        $fieldset->addField('hours2', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Opening hours line 2'),
            'name'  => 'hours2',
        ));

        $fieldset->addField('hours3', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Opening hours line 3'),
            'name'  => 'hours3',
        ));


        $fieldset->addField('description', 'editor', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Store description'),
            'name'  => 'description',
            'config' => $wysiwygConfig,
        ));

        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Image'),
            'name'  => 'image',
        ));


        $fieldset->addField('url_key', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Store URL key'),
            'name'  => 'url_key',
        ));

        $fieldset->addField('allow_comment', 'select', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Allow comments'),
            'name'  => 'allow_comment',
            'comment' => 'If set to Yes, customers are allowed to submit comments on a store page.',
            'values'=> Mage::registry('current_storelocation')->getFlatAttributeOptions('allow_comment')
        ));


        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Status'),
            'name'  => 'status',
            'values'=> Mage::registry('current_storelocation')->getFlatAttributeOptions('status')
        ));

        if (Mage::app()->isSingleStoreMode()){
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_storelocation')->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset = $form->addFieldset('storelocation_list', array('legend'=>Mage::helper('plugincompany_storelocator')->__('Front-end Display Settings')));


        $fieldset->addField('show_in_list', 'select', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Show in Store Locations'),
            'name'  => 'show_in_list',
            'note' => 'Show the store on the store locations list page.',
            'values'=> Mage::registry('current_storelocation')->getFlatAttributeOptions('show_in_list')
        ));

        $fieldset->addField('show_in_finder', 'select', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Show in Store Finder'),
            'name'  => 'show_in_finder',
            'note' => 'Show the store on the store locator page.',
            'values'=> Mage::registry('current_storelocation')->getFlatAttributeOptions('show_in_finder')
        ));

        $fieldset->addField('sort_order', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Sort order'),
            'name' => 'sort_order',
            'note' => 'Position on the Store Locations and Store Finder page.'
            )
        );

        $fieldset->addField('use_image_not_map', 'select', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Use store image instead of map'),
            'name'  => 'use_image_not_map',
            'values'=> Mage::registry('current_storelocation')->getFlatAttributeOptions('use_image_not_map'),
            'note' => 'Show an uploaded store image on the store locations list page instead of the default Google Maps image.'
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

