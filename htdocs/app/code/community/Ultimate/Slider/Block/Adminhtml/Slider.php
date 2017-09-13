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
 * Slider admin block
 *
 * @category    Images & Media
 * @package     Ultimate_Slider
 * @author      Rajasingh and Manikandan D
 */
class Ultimate_Slider_Block_Adminhtml_Slider extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     */
    public function __construct()
    {   
        $this->_controller         = 'adminhtml_slider';
        $this->_blockGroup         = 'ultimate_slider';
        parent::__construct();

        # Grid container header and add button declaration
        # -------------------------------------------------
        $this->_headerText         = Mage::helper('ultimate_slider')->__('Ultimate Slider');
        $this->_updateButton('add', 'label', Mage::helper('ultimate_slider')->__('Add New Slider'));
    }
}
