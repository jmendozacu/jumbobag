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
class Plugincompany_Storelocator_Block_Storelocation_Customer_Comment_List
    extends Mage_Customer_Block_Account_Dashboard {
    /**
     * Storelocation comments collection
     * @var Plugincompany_Storelocator_Model_Resource_Storelocation_Comment_Storelocation_Collection
     */
    protected $_collection;

    /**
     * Initializes collection
     * @access public
     * @author Milan Simek
     */
    protected function _construct() {
        $this->_collection = Mage::getResourceModel('plugincompany_storelocator/storelocation_comment_storelocation_collection');
        $this->_collection
            ->setStoreFilter(Mage::app()->getStore()->getId(), true)
            ->addFieldToFilter('main_table.status', 1) //only active entities

            ->addStatusFilter(Plugincompany_Storelocator_Model_Storelocation_Comment::STATUS_APPROVED) //only approved comments
            ->addCustomerFilter(Mage::getSingleton('customer/session')->getCustomerId()) //only my comments
            ->setDateOrder();
    }

    /**
     * Gets collection items count
     * @access public
     * @return int
     * @author Milan Simek
     */
    public function count() {
        return $this->_collection->getSize();
    }

    /**
     * Get html code for toolbar
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getToolbarHtml() {
        return $this->getChildHtml('toolbar');
    }

    /**
     * Initializes toolbar
     * @access protected
     * @return Mage_Core_Block_Abstract
     * @author Milan Simek
     */
    protected function _prepareLayout()
    {
        $toolbar = $this->getLayout()->createBlock('page/html_pager', 'customer_storelocation_comments.toolbar')
            ->setCollection($this->getCollection());

        $this->setChild('toolbar', $toolbar);
        return parent::_prepareLayout();
    }

    /**
     * Get collection
     * @access protected
     * @return Plugincompany_Storelocator_Model_Resource_Storelocation_Comment_Storelocation_Collection
     * @author Milan Simek
     */
    protected function _getCollection() {
        return $this->_collection;
    }

    /**
     * Get collection
     * @access public
     * @return Plugincompany_Storelocator_Model_Resource_Storelocation_Comment_Storelocation_Collection
     * @author Milan Simek
     */
    public function getCollection() {
        return $this->_getCollection();
    }

    /**
     * Get review link
     * @access public
     * @param mixed $comment
     * @return string
     * @author Milan Simek
     */
    public function getCommentLink($comment) {
        if ($comment instanceof Varien_Object){
            $comment = $comment->getCtCommentId();
        }
        return Mage::getUrl('plugincompany_storelocator/storelocation_customer_comment/view/', array('id'=>$comment));
    }

    /**
     * Get product link
     * @access public
     * @param mixed $comment
     * @return string
     * @author Milan Simek
     */
    public function getStorelocationLink($comment) {
        return $comment->getStorelocationUrl();
    }

    /**
     * Format date in short format
     * @access public
     * @param $date
     * @return string
     * @author Milan Simek
     */
    public function dateFormat($date) {
        return $this->formatDate($date, Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
    }
}
