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
 * Slider module install script
 *
 * @category    Images & Media
 * @package     Ultimate_Slider
 * @author      Rajasingh and Manikandan D
 * @modified    2015-11-21 04:54:53 PM IST
 */

/**
 *  @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;

/**
 * @var $model Ultimate_Slider_Model_Slider
 */
$model = Mage::getModel('ultimate_slider/slider');

/**
 * Set up data rows
 */
$dataRows = array(
    array(
            'title'         => 'Slider 1',
            'alt_text'      => 'Slider 1 Alt Text',
            'status'        => '1',
            'image'         => '1446822368_beautiful-abraham-lincoln-background-picture-new-best-hd-wallpapers-of-abraham.jpg',
            'video_url'     => '',
            'published_at'  => '2015-11-06',
         ),

    array(
            'title'         => 'Slider 2',
            'alt_text'      => 'Slider 2 Alt Text',
            'status'        => '1',
            'image'         => '',
            'video_url'     => 'http://www.dailymotion.com/embed/video/x3bdmew',
            'published_at'  => '2015-11-06',
         ),

    array(
            'title'         => 'Slider 3',
            'alt_text'      => 'Slider 3 Alt Text',
            'status'        => '1',
            'image'         => '1447324697_FullIQ-1.mp4',
            'video_url'     => '',
            'published_at'  => '2015-11-06',
         ),
);    

/**
 * Generate slider items
 */
foreach ($dataRows as $data) {
    $model->setData($data)->setOrigData()->save();
}
