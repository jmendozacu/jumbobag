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
 * Adminhtml store location eav attribute edit page main tab
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocationeav_Attribute_Edit_Tab_Main
    extends Mage_Eav_Block_Adminhtml_Attribute_Edit_Main_Abstract {
    /**
     * Adding product form elements for editing attribute
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocationeav_Attribute_Edit_Tab_Main
     * @author Milan Simek
     */
    protected function _prepareForm(){
        parent::_prepareForm();
        $attributeObject = $this->getAttributeObject();
        $form = $this->getForm();
        $fieldset = $form->getElement('base_fieldset');
        $frontendInputElm = $form->getElement('frontend_input');
        $additionalTypes = array();
//        $additionalTypes = array(
//            array(
//                'value' => 'image',
//                'label' => Mage::helper('plugincompany_storelocator')->__('Image')
//            ),
//            array(
//                'value' => 'file',
//                'label' => Mage::helper('plugincompany_storelocator')->__('File')
//            )
//        );
        $response = new Varien_Object();
        $response->setTypes(array());
        Mage::dispatchEvent('adminhtml_storelocationeav_attribute_types', array('response'=>$response));
        $_hiddenFields = array();
        foreach ($response->getTypes() as $type) {
            $additionalTypes[] = $type;
            if (isset($type['hide_fields'])) {
                $_hiddenFields[$type['value']] = $type['hide_fields'];
            }
            if (isset($type['disabled_types'])) {
                $_disabledTypes[$type['value']] = $type['disabled_types'];
            }
        }
        Mage::register('attribute_type_hidden_fields', $_hiddenFields);
        Mage::register('attribute_type_disabled_types', $_disabledTypes);

        $frontendInputValues = array_merge($frontendInputElm->getValues(), $additionalTypes);
//        foreach($frontendInputValues as $k => $v){
//            if($v['value'] == 'boolean'){
//                unset($frontendInputValues[$k]);
//            }
//        }q1qaz
        $frontendInputElm->setValues($frontendInputValues);

        $yesnoSource = Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray();

        $fieldset->addField('is_global', 'hidden', array(
            'name'  => 'is_global',
            'label' => Mage::helper('plugincompany_storelocator')->__('Scope'),
            'title' => Mage::helper('plugincompany_storelocator')->__('Scope'),
            'value' => 1
        ), 'attribute_code');


        $fieldset->addField('position', 'text', array(
            'name'  => 'position',
            'label' => Mage::helper('plugincompany_storelocator')->__('Sort order'),
            'title' => Mage::helper('plugincompany_storelocator')->__('Position'),
            'note'  => Mage::helper('plugincompany_storelocator')->__('Sort order of the attribute on the store page.'),
        ), 'is_global');
        $fieldset->addField('note', 'text', array(
            'name'  => 'note',
            'label' => Mage::helper('plugincompany_storelocator')->__('Description'),
            'title' => Mage::helper('plugincompany_storelocator')->__('Note'),
            'note'  => Mage::helper('plugincompany_storelocator')->__('Description of the attribute in the admin form.'),
        ), 'position');

		$fieldset->removeField('is_unique');

        $fieldset->addField('in_finder', 'select', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Show on Store Finder page'),
            'name'  => 'in_finder',
            'note' => 'Attribute can be used as filter in the store locator (select / multi select only).',
            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('plugincompany_storelocator')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('plugincompany_storelocator')->__('No'),
                ),
            ),
        ));

        $fieldset->addField('in_store_page', 'select', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Show on store page'),
            'name'  => 'in_store_page',
            'note' => 'Show attribute on the front-end store page.',
            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('plugincompany_storelocator')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('plugincompany_storelocator')->__('No'),
                ),
            ),
        ));

        $field = $fieldset->addField('store_ids', 'multiselect', array(
            'name'  => 'store_ids[]',
            'label' => Mage::helper('plugincompany_storelocator')->__('Visible in Store Views'),
            'title' => Mage::helper('plugincompany_storelocator')->__('Visible in Store Views'),
            'required'  => true,
            'values'=> Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
        ));
        $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
        $field->setRenderer($renderer);

        
        // frontend properties fieldset
        $fieldset = $form->addFieldset('front_fieldset', array('legend'=>Mage::helper('plugincompany_storelocator')->__('Frontend Properties')));

        $fieldset->addField('is_wysiwyg_enabled', 'select', array(
            'name' => 'is_wysiwyg_enabled',
            'label' => Mage::helper('plugincompany_storelocator')->__('Enable WYSIWYG'),
            'title' => Mage::helper('plugincompany_storelocator')->__('Enable WYSIWYG'),
            'values' => $yesnoSource,
        ));



        Mage::dispatchEvent('plugincompany_storelocator_adminhtml_storelocationeav_attribute_edit_prepare_form', array(
            'form'      => $form,
            'attribute' => $attributeObject
        ));
        return $this;
    }
}
