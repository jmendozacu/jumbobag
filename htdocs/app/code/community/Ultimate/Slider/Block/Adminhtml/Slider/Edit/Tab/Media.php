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
class Ultimate_Slider_Block_Adminhtml_Slider_Edit_Tab_Media extends Mage_Adminhtml_Block_Widget_Form
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

        $fieldset = $form->addFieldset('media_fieldset', array(
            'legend'    => Mage::helper('ultimate_slider')->__('Slider Image'), 'class' => 'fieldset-wide'
        ));


        $fieldset->addField('image', 'image', array(
            'name'      => 'image',
            'label'     => Mage::helper('ultimate_slider')->__('Image'),
            'title'     => Mage::helper('ultimate_slider')->__('Image'),
            'note'      => $this->__('If image, the dimensions must be higher than 1300x900(WxH). If video, it only accepts .mp4 video and less than 80 mb size'),
        ));

        $fieldset->addField('video_url', 'text', array(
                'name'  => 'video_url',
                'label' => Mage::helper('ultimate_slider')->__('Slider Video'),
                'note'  => $this->__('Please enter a valid embed url from websites like youtube, vimeo, etc.,'),

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
