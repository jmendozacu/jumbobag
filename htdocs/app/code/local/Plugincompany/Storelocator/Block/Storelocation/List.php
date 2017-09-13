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
 * Store location list block
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author Milan Simek
 */
class Plugincompany_Storelocator_Block_Storelocation_List
    extends Mage_Core_Block_Template {
    /**
     * initialize
     * @access public
     * @author Milan Simek
     */
     public function __construct(){
        parent::__construct();
         $storelocations = Mage::getResourceModel('plugincompany_storelocator/storelocation_collection')
                         ->addStoreFilter(Mage::app()->getStore())
                         ->addFieldToFilter('status', 1)
                         ->addFieldToFilter('show_in_list',1)
         ;
        $storelocations->setOrder('sort_order', 'asc');

        $this->setStorelocations($storelocations);
    }
    /**
     * prepare the layout
     * @access protected
     * @return Plugincompany_Storelocator_Block_Storelocation_List
     * @author Milan Simek
     */
    protected function _prepareLayout(){
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'plugincompany_storelocator.storelocation.html.pager')
        ;

        //add limit array
        $limits = array();
        $allowed = explode(',',Mage::getStoreConfig('plugincompany_storelocator/storelist/limits'));
        foreach($allowed as $val){
            if(!is_numeric($val) && $val != 'all'){
                continue;
            }
            if($val == 'all'){
                $limits['all'] = $this->__('All');
            }else{
                $limits[$val] = $val;
            }
        }
        $pager->setAvailableLimit($limits);

        //set default limit
        $default = Mage::getStoreConfig('plugincompany_storelocator/storelist/limit_default');
        $default = $default ? $default : 10;
        if (!$this->getRequest()->getParam($pager->getLimitVarName())) {
            $pager->setLimit($default);
        }

        $pager->setCollection($this->getStorelocations());

        $this->setChild('pager', $pager);

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

    /**
     * Get the finder url for store
     *
     * @return mixed
     */
    public function getStoreFinderUrl()
    {
        return Mage::helper('plugincompany_storelocator/storelocation')->getStoreFinderUrl();
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
