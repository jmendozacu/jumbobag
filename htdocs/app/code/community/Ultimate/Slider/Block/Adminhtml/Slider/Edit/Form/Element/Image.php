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
 * Custom image form element that generates correct thumbnail image URL
 *
 * @category    Images & Media
 * @package     Ultimate_Slider
 * @author      Rajasingh and Manikandan D
 */
class Ultimate_Slider_Block_Adminhtml_Slider_Edit_Form_Element_Image extends Varien_Data_Form_Element_Image
{
    /**
     * Get image preview url
     *
     * @return string
     */
    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            $url = Mage::helper('ultimate_slider/image')->getBaseUrl() . '/' . $this->getValue();
        }
        return $url;
    }
}
