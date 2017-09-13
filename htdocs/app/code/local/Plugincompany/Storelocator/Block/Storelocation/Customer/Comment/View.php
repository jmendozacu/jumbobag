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
 * Store location customer comments list
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Storelocation_Customer_Comment_View
    extends Mage_Customer_Block_Account_Dashboard {
    /**
     * get current comment
     * @access public
     * @return Plugincompany_Storelocator_Model_Storelocation_Comment
     * @author Milan Simek
     */
    public function getComment() {
        return Mage::registry('current_comment');
    }
    /**
     * get current store location
     * @access public
     * @return Plugincompany_Storelocator_Model_Storelocation
     * @author Milan Simek
     */
    public function getStorelocation() {
        return Mage::registry('current_storelocation');
    }
}
