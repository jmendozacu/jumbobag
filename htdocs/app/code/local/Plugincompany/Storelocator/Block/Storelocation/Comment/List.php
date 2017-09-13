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
 * Store location comment list block
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author Milan Simek
 */
class Plugincompany_Storelocator_Block_Storelocation_Comment_List
    extends Mage_Core_Block_Template {
    /**
     * initialize
     * @access public
     * @author Milan Simek
     */
     public function __construct(){
         parent::__construct();
         $storelocation = $this->getStorelocation();
         $comments = Mage::getResourceModel('plugincompany_storelocator/storelocation_comment_collection')
             ->addFieldToFilter('storelocation_id', $storelocation->getId())
                         ->addStoreFilter(Mage::app()->getStore())
             ->addFieldToFilter('status', 1);
        $comments->setOrder('created_at', 'asc');
        $this->setComments($comments);
    }
    /**
     * prepare the layout
     * @access protected
     * @return Plugincompany_Storelocator_Block_Storelocation_Comment_List
     * @author Milan Simek
     */
    protected function _prepareLayout(){
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('plugincompany_storelocator/page_html_pager', 'plugincompany_storelocator.storelocation.html.pager')
            ->setCollection($this->getComments());
        $this->setChild('pager', $pager);
        $this->getComments()->load();
        return $this;
    }
    /**
     * get the pager html
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getPagerHtml(){
        return $this->getChildHtml('pager');
    }
    public function getStorelocation() {
        return Mage::registry('current_storelocation');
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