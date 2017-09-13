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
 * Slider helper
 *
 * @category    Images & Media
 * @package     Ultimate_Slider
 * @author      Rajasingh and Manikandan D
 */
class Ultimate_Slider_Helper_Slider extends Mage_Core_Helper_Abstract
{
    /**
     * get the url to the sliders list page
     *
     * @access public
     * @return string
     */
    public function getSlidersUrl()
    {
        if ($listKey = Mage::getStoreConfig('ultimate_slider/slider/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('ultimate_slider/slider/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('ultimate_slider/slider/breadcrumbs');
    }

}
