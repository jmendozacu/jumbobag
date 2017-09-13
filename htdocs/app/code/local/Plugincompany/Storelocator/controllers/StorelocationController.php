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
 * Store location front contrller
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_StorelocationController
    extends Mage_Core_Controller_Front_Action {
    /**
      * default action
      * @access public
      * @return void
      * @author Milan Simek
      */
    public function indexAction(){
         $this->loadLayout();
         $this->_initLayoutMessages('catalog/session');
         $this->_initLayoutMessages('customer/session');
         $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('storelocator-list');
        }
         if (Mage::helper('plugincompany_storelocator/storelocation')->getUseBreadcrumbs()){
             if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                 $breadcrumbBlock->addCrumb('home', array(
                            'label'    => Mage::helper('plugincompany_storelocator')->__('Home'),
                            'link'     => Mage::getUrl(),
                        )
                 );
                 $breadcrumbBlock->addCrumb('storelocations', array(
                            'label'    => Mage::helper('plugincompany_storelocator')->__('Store locations'),
                            'link'    => '',
                    )
                 );
             }
         }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('plugincompany_storelocator/storelist/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('plugincompany_storelocator/storelist/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('plugincompany_storelocator/storelist/meta_description'));
        }
        $this->renderLayout();
    }
    /**
     * init Store location
     * @access protected
     * @return Plugincompany_Storelocator_Model_Entity
     * @author Milan Simek
     */
    protected function _initStorelocation(){
        $storelocationId   = $this->getRequest()->getParam('id', 0);
        $storelocation     = Mage::getModel('plugincompany_storelocator/storelocation')
                        ->setStoreId(Mage::app()->getStore()->getId())
                        ->load($storelocationId);
        if (!$storelocation->getId()){
            return false;
        }
        elseif (!$storelocation->getStatus()){
            return false;
        }
        
        $location = Mage::getModel('plugincompany_storelocator/rating');
        $storelocation->setData('rating', $location->getLocationRating($storelocation->getId()));
        
        return $storelocation;
    }
    /**
      * view store location action
      * @access public
      * @return void
      * @author Milan Simek
      */
    public function viewAction(){
        $storelocation = $this->_initStorelocation();
        if (!$storelocation) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_storelocation', $storelocation);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('storelocator-storelocation' . $storelocation->getId());
            $root->addBodyClass('storelocator-view');
        }
        if (Mage::helper('plugincompany_storelocator/storelocation')->getUseBreadcrumbs()){
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                $breadcrumbBlock->addCrumb('home', array(
                            'label'    => Mage::helper('plugincompany_storelocator')->__('Home'),
                            'link'     => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb('storelocations', array(
                            'label'    => Mage::helper('plugincompany_storelocator')->__('Store locations'),
                            'link'    => Mage::helper('plugincompany_storelocator/storelocation')->getStorelocationsUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb('storelocation', array(
                            'label'    => $storelocation->getLocname(),
                            'link'    => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            if ($storelocation->getMetaTitle()){
                $headBlock->setTitle($storelocation->getMetaTitle());
            }
            else{
                $headBlock->setTitle($storelocation->getLocname());
            }
            $headBlock->setKeywords($storelocation->getMetaKeywords());
            $headBlock->setDescription($storelocation->getMetaDescription());
        }
        $this->renderLayout();
    }
    /**
     * Submit new comment action
     *
     */
    public function commentpostAction() {
        $data   = $this->getRequest()->getPost();
        $storelocation = $this->_initStorelocation();
        $session    = Mage::getSingleton('core/session');
        if ($storelocation) {
            if ($storelocation->getAllowComments()) {
                if ((Mage::getSingleton('customer/session')->isLoggedIn() || Mage::getStoreConfigFlag('plugincompany_storelocator/storepage/allow_guest_comment'))){
                    $comment    = Mage::getModel('plugincompany_storelocator/storelocation_comment')->setData($data);
                    $validate = $comment->validate();
                    if ($validate === true) {
                        try {
                            $comment->setStorelocationId($storelocation->getId())
                                ->setStatus(Plugincompany_Storelocator_Model_Storelocation_Comment::STATUS_PENDING)
                                ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                ->setStores(array(Mage::app()->getStore()->getId()))
                                ->save();
                            $session->addSuccess($this->__('Your comment has been accepted for moderation.'));
                        }
                        catch (Exception $e) {
                            $session->setStorelocationCommentData($data);
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    }
                    else {
                        $session->setStorelocationCommentData($data);
                        if (is_array($validate)) {
                            foreach ($validate as $errorMessage) {
                                $session->addError($errorMessage);
                            }
                        }
                        else {
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    }
                }
                else {
                    $session->addError($this->__('Guest comments are not allowed'));
                }
            }
            else {
                $session->addError($this->__('This store location does not allow comments'));
            }
        }
        $this->_redirectReferer();
    }

    public function locatorAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('storelocator-storelocation storelocator-storefinder');
        }
        if (Mage::helper('plugincompany_storelocator/storelocation')->getUseBreadcrumbs()){
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                $breadcrumbBlock->addCrumb('home', array(
                        'label'    => Mage::helper('plugincompany_storelocator')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb('storelocations', array(
                        'label'    => Mage::helper('plugincompany_storelocator')->__('Store locations'),
                        'link'    => Mage::helper('plugincompany_storelocator/storelocation')->getStorelocationsUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb('storefinder', array(
                        'label'    => Mage::helper('plugincompany_storelocator')->__('Store Finder'),
                        'link'    => '',
                    )
                );
            }
        }

        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('plugincompany_storelocator/storefinder/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('plugincompany_storelocator/storefinder/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('plugincompany_storelocator/storefinder/meta_description'));
        }

        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        $this->renderLayout();
    }

    public function locatorembedAction()
    {
        header("Access-Control-Allow-Origin: *");
        Mage::register('include_all_libs',true);
        $this->loadLayout();
        $this->renderLayout();
    }
    
    
    public function productPageStoreListingAction()
    {
        $this->getResponse()->setBody(
            $this
                ->getLayout()
                ->createBlock('plugincompany_storelocator/storelocation_product_store_list')
                ->setTemplate('plugincompany_storelocator/storelocation/list.phtml')
                ->toHtml()
        );
    }
    

    public function storesjsonAction()
    {
        if(!$storeId = Mage::app()->getRequest()->getParam('store_id', 0)){
            $storeId = Mage::app()->getStore()->getStoreId();
        }

        if(!$productId = Mage::app()->getRequest()->getParam('product_id', 0)){
            $productId = Mage::registry('current_product') ? Mage::registry('current_product')->getId() : 0;
        }
        
        if(Mage::helper('plugincompany_storelocator')->isCacheEnabled()){
            $cache = Mage::getModel('plugincompany_storelocator/cache_core');
            $cache->setStoreId($storeId);
            
            if($productId){
                $cache->setCacheKey("s{$storeId}p{$productId}");
            }

            if(!$json = $cache->getCache()){
                $json = $this->_processJson($storeId, $productId);
                $cache->saveCache($json);
            }
        }
        else{
           $json = $this->_processJson($storeId, $productId); 
        }
        
        $this->getResponse()->setBody($json);
    }
    
    
    private function _processJson($storeId, $productId){
        $ratingenabled = Mage::helper('plugincompany_storelocator')->isRatingEnable();
        $stores = Mage::getModel('plugincompany_storelocator/storelocation')->getCollection();
        $stores->addStoreFilter($storeId);
        $stores->addFieldToFilter('status', 1);
        $stores->addFieldToFilter('show_in_finder', 1);
        
        
        if((bool) $productId && (bool) Mage::getStoreConfig('plugincompany_storelocator/product_detail_page/manually_manage_inventory_locator')){
            $tableName = Mage::getSingleton('core/resource')->getTableName('plugincompany_storelocator/storelocation_product');
            $stores->getSelect()->joinLeft(
               array('product' => $tableName), 'product.storelocation_id = main_table.entity_id'
            );

            $stores->addFieldToFilter('product_id', (int)$productId);
            $stores->addFieldToFilter('product.store_id', (int)$storeId);
         }

        
        $response = array();
        
        $locationRating = Mage::getModel('plugincompany_storelocator/rating');
             
        foreach ($stores as $store) {
            $eavModel = Mage::getModel('plugincompany_storelocator/storelocationeav')->load($store->getId());
            $store
                ->addData($eavModel->getData());

            $store->setData('pageurl', $store->getStorelocationUrl());
            $store->setData('rating', $ratingenabled ? $locationRating->getLocationRating($store->getId()) : 0);
            $store->setData('ratingenabled', $ratingenabled);
            $response[] = $store->getData();
        }
        
        return json_encode($response);
    }

}
