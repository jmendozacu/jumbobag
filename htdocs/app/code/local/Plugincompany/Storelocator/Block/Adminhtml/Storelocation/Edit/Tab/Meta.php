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
 * meta information tab
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Edit_Tab_Meta
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Edit_Tab_Meta
     * @author Milan Simek
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('storelocation');
        $this->setForm($form);
        $fieldset = $form->addFieldset('storelocation_meta_form', array('legend'=>Mage::helper('plugincompany_storelocator')->__('Store Meta Data')));
        $fieldset->addField('meta_title', 'text', array(
            'label' => Mage::helper('plugincompany_storelocator')->__('Meta title'),
            'name'  => 'meta_title',
        ));
        $fieldset->addField('meta_description', 'textarea', array(
            'name'      => 'meta_description',
            'label'     => Mage::helper('plugincompany_storelocator')->__('Meta description'),
          ));
          $fieldset->addField('meta_keywords', 'textarea', array(
            'name'      => 'meta_keywords',
            'label'     => Mage::helper('plugincompany_storelocator')->__('Meta keywords'),
          ));
          $form->addValues(Mage::registry('current_storelocation')->getData());
        return parent::_prepareForm();
    }
}
