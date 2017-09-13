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
 * Slider list block
 *
 * @category    Images & Media
 * @package     Ultimate_Slider
 * @author      Rajasingh and Manikandan D
 * @modified    2015-11-21 06:41:51 PM IST
 */
class Ultimate_Slider_Block_Slider_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     */
    public function _construct()
    {
        parent::_construct();
        $sliders = Mage::getResourceModel('ultimate_slider/slider_collection');
        $sliders->addFieldToFilter('status', 1);
        $sliders->setOrder('slider_id', 'asc');
        $this->setSliders($sliders);
    }

    /**
     * retrieve data to template
     *
     * @access public
     */
    public function retrieveData(){

        if(!Mage::getStoreConfigFlag('ultimate_slider/slider/enabled')){
            return false;
        }

        if(Mage::getStoreConfig('ultimate_slider/slider/items_per_slide')<=3){
            $setLimit = 3;
        } else {
            $setLimit = Mage::getStoreConfigFlag('ultimate_slider/slider/items_per_slide');
        }
        
        $sliders = Mage::getModel('ultimate_slider/slider')->getCollection()
                         ->setPageSize($setLimit)
                         ->addFieldToFilter('status', array("eq" => "1"))
                         ->addFieldToFilter('published_at', array("lteq" => date("Y-m-d")))
                         ->addOrder('published_at', 'desc')
                         ->getData();

        return $sliders;        
    }

}
