<?php
/**
 * Ultimate_Slider extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Images & Media
 * @package        Ultimate_Slider
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Slider edit form tab
 *
 * @category    Images & Media
 * @package     Ultimate_Slider
 * @author      Rajasingh and Manikandan D
 */
class Ultimate_Slider_Block_Adminhtml_Slider_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Ultimate_Slider_Block_Adminhtml_Slider_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('slider_');
        $form->setFieldNameSuffix('slider');
        
        $this->setForm($form);
        $fieldset = $form->addFieldset('slider_form', array(
            'legend' => Mage::helper('ultimate_slider')->__('Slider Details'))
        );

        $fieldset->addField('title', 'text', array(
                'name'  => 'title',
                'label' => Mage::helper('ultimate_slider')->__('Slider Title'),
                'note'	=> $this->__('Please enter a title for the slider'),
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField('alt_text', 'text', array(
                'name'  => 'alt_text',
                'label' => Mage::helper('ultimate_slider')->__('Slider Alternate Text'),
                'note'  => $this->__('Please enter a alternate text for the media. Displayed in html alt attribute.'),
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField('published_at', 'date', array(
                'name'     => 'published_at',
                'format'   => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                'image'    => $this->getSkinUrl('images/grid-cal.gif'),
                'label'    => Mage::helper('ultimate_slider')->__('Publishing Date'),
                'title'    => Mage::helper('ultimate_slider')->__('Publishing Date'),
                'required' => true,
                'class' => 'required-entry',
                'note'  => $this->__('Enter a future date for scheduled publishing and todays date for immediate publishing.'),
        ));

        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('ultimate_slider')->__('Status'),
                'name'   => 'status',
                'required' => true,
                'class' => 'required-entry',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('ultimate_slider')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('ultimate_slider')->__('Disabled'),
                    ),
                ),
            )
        );

        $formValues = Mage::registry('current_slider')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getSliderData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getSliderData());
            Mage::getSingleton('adminhtml/session')->setSliderData(null);
        } elseif (Mage::registry('current_slider')) {
            $formValues = array_merge($formValues, Mage::registry('current_slider')->getData());
        }  

        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
