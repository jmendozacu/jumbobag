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
* Slider front contrller
*
* @category    Images & Media
* @package     Ultimate_Slider
* @author      Rajasingh and Manikandan D
*/
class Ultimate_Slider_IndexController extends Mage_Core_Controller_Front_Action
{   
    /**
     * get the layout and template on the ultimate_slider page
     *
     * @access public
     */
	public function indexAction(){

		$this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('ultimate_slider/slider')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('ultimate_slider')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'sliders',
                    array(
                        'label' => Mage::helper('ultimate_slider')->__('Sliders'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('ultimate_slider/slider')->getSlidersUrl());
        }
        $this->renderLayout();
	}
}