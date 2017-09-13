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
 * Slider Image helper
 *
 * @category    Images & Media
 * @package     Ultimate_Slider
 * @author      Rajasingh and Manikandan D
 */
class Ultimate_Slider_Helper_Image extends Mage_Core_Helper_Abstract
{

    /**
     * Resize images above 1300x900px
     *
     * @access public
     * @return string
     * @param image path, width and height
     */
    public function resizeImageAction($imageFile, $width, $height) {
        
        $image = new Varien_Image(Mage::getBaseDir('media') . DS . "wysiwyg" . DS . $imageFile);
        
        $img_width = $image->getOriginalWidth();
        $img_height = $image->getOriginalHeight();
        
        if (($img_width > $width) && ($img_height > $height)) {
            try {
                $image->constrainOnly(TRUE);
                $image->keepAspectRatio(TRUE);
                $image->keepFrame(FALSE);
                $image->resize($width, $height);
                $image->save(Mage::getBaseDir('media') . DS . "wysiwyg" . DS . $imageFile);
                return $imageFile;
            }
            catch(Exception $e) {
            }
        } 
        else {
            return $imageFile;
        }
    }
}
