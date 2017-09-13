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
 * Store location comment form block
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author Milan Simek
 */
class Plugincompany_Storelocator_Block_Storelocation_Comment_Form
    extends Mage_Core_Block_Template {
    /**
     * initialize
     * @access public
     * @author Milan Simek
     */
    public function __construct() {
        $customerSession = Mage::getSingleton('customer/session');
        parent::__construct();
        $data =  Mage::getSingleton('customer/session')->getStorelocationCommentFormData(true);
        $data = new Varien_Object($data);
        // add logged in customer name as nickname
        if (!$data->getName()) {
            $customer = $customerSession->getCustomer();
            if ($customer && $customer->getId()) {
                $data->setName($customer->getFirstname());
                $data->setEmail($customer->getEmail());
            }
        }
        $this->setAllowWriteCommentFlag($customerSession->isLoggedIn() || Mage::getStoreConfigFlag('plugincompany_storelocator/storepage/allow_guest_comment'));
        if (!$this->getAllowWriteCommentFlag()) {
            $this->setLoginLink(
                Mage::getUrl('customer/account/login/', array(
                    Mage_Customer_Helper_Data::REFERER_QUERY_PARAM_NAME => Mage::helper('core')->urlEncode(
                        Mage::getUrl('*/*/*', array('_current' => true)) .
                        '#comment-form')
                    )
                )
            );
        }
        $this->setCommentData($data);
    }
    /**
     * get current Store location
     * @access public
     * @return Plugincompany_Storelocator_Model_Storelocation
     * @author Milan Simek
     */
    public function getStorelocation() {
        return Mage::registry('current_storelocation');
    }
    /**
     * get form action
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getAction() {
        return Mage::getUrl('plugincompany_storelocator/storelocation/commentpost', array('id' => $this->getStorelocation()->getId()));
    }
    /**
     * get rating flag
     * @access public
     * @return bool
     * @author Milan Simek
     */
    public function isRatingEnable() {
        return Mage::helper('plugincompany_storelocator')->isRatingEnable();
    }
}
