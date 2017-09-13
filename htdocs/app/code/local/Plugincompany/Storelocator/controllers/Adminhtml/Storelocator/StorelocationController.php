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
 * Store location admin controller
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Adminhtml_Storelocator_StorelocationController
    extends Plugincompany_Storelocator_Controller_Adminhtml_Storelocator {
    /**
     * init the storelocation
     * @access protected
     * @return Plugincompany_Storelocator_Model_Storelocation
     */
    protected function _initStorelocation(){
        $storelocationId  = (int) $this->getRequest()->getParam('id');
        $storelocation    = Mage::getModel('plugincompany_storelocator/storelocation');
        if ($storelocationId) {
            $storelocation->load($storelocationId);
        }
        Mage::register('current_storelocation', $storelocation);
        return $storelocation;
    }
     /**
     * default action
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('plugincompany_storelocator')->__('Store Locator'))
             ->_title(Mage::helper('plugincompany_storelocator')->__('Store locations'));
        $this->renderLayout();
    }
    /**
     * grid action
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function gridAction() {
        $this->loadLayout()->renderLayout();
    }
    /**
     * edit store location - action
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function editAction() {
        $storelocationId    = $this->getRequest()->getParam('id');
        $storelocation      = $this->_initStorelocation();
        if ($storelocationId && !$storelocation->getId()) {
            $this->_getSession()->addError(Mage::helper('plugincompany_storelocator')->__('This store location no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getStorelocationData(true);
        if (!empty($data)) {
            $storelocation->setData($data);
        }
        Mage::register('storelocation_data', $storelocation);
        $this->loadLayout();
        $this->_title(Mage::helper('plugincompany_storelocator')->__('Store Locator'))
             ->_title(Mage::helper('plugincompany_storelocator')->__('Store locations'));
        if ($storelocation->getId()){
            $this->_title($storelocation->getLocname());
        }
        else{
            $this->_title(Mage::helper('plugincompany_storelocator')->__('Add Store Location'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }
    /**
     * new store location action
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function newAction() {
        $this->_forward('edit');
    }
    /**
     * save store location - action
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost('storelocation')) {
            try {
                $storelocation = $this->_initStorelocation();
                $storelocation->addData($data);
                $imageName = $this->_uploadAndGetName('image', Mage::helper('plugincompany_storelocator/storelocation_image')->getImageBaseDir(), $data);
                $storelocation->setData('image', $imageName);
                
                $storelocation->save();
                
                if(Mage::helper('plugincompany_storelocator/storelocation')->isManuallyManageInventoryLocatorEnabled()){
                    //update location product
                    $links = $this->getRequest()->getPost('links');

                    if (isset($links['storelocation_products'])) {
                        $storelocation->setStoreLocationBulkProductData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['storelocation_products']));                    
                    }
                    
                    Mage::getModel('plugincompany_storelocator/storelocationproduct')->updateProductOnLocationSave($storelocation);
                }
                
                
                           
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('plugincompany_storelocator')->__('Store location was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $storelocation->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                if (isset($data['image']['value'])){
                    $data['image'] = $data['image']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setStorelocationData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['image']['value'])){
                    $data['image'] = $data['image']['value'];
                }
                echo $e->getMessage();exit;
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_storelocator')->__('There was a problem saving the store location.'));
                Mage::getSingleton('adminhtml/session')->setStorelocationData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_storelocator')->__('Unable to find store location to save.'));
        $this->_redirect('*/*/');
    }
    /**
     * delete store location - action
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0) {
            try {
                $storelocation = Mage::getModel('plugincompany_storelocator/storelocation');
                $storelocation->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('plugincompany_storelocator')->__('Store location was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_storelocator')->__('There was an error deleting store location.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_storelocator')->__('Could not find store location to delete.'));
        $this->_redirect('*/*/');
    }
    /**
     * mass delete store location - action
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function massDeleteAction() {
        $storelocationIds = $this->getRequest()->getParam('storelocation');
        if(!is_array($storelocationIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_storelocator')->__('Please select store locations to delete.'));
        }
        else {
            try {
                foreach ($storelocationIds as $storelocationId) {
                    $storelocation = Mage::getModel('plugincompany_storelocator/storelocation');
                    $storelocation->setId($storelocationId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('plugincompany_storelocator')->__('Total of %d store locations were successfully deleted.', count($storelocationIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_storelocator')->__('There was an error deleting store locations.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass status change - action
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function massStatusAction(){
        $storelocationIds = $this->getRequest()->getParam('storelocation');
        if(!is_array($storelocationIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_storelocator')->__('Please select store locations.'));
        }
        else {
            try {
                foreach ($storelocationIds as $storelocationId) {
                $storelocation = Mage::getSingleton('plugincompany_storelocator/storelocation')->load($storelocationId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d store locations were successfully updated.', count($storelocationIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('plugincompany_storelocator')->__('There was an error updating store locations.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * export as csv - action
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function exportCsvAction(){
        $fileName   = 'storelocation.csv';
        $content    = $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as MsExcel - action
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function exportExcelAction(){
        $fileName   = 'storelocation.xls';
        $content    = $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as xml - action
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function exportXmlAction(){
        $fileName   = 'storelocation.xml';
        $content    = $this->getLayout()->createBlock('plugincompany_storelocator/adminhtml_storelocation_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    
    /**
     * Product grid for AJAX request.
     * Sort and filter result for example.
     */
    public function storeProductAction()
    {   $this->loadLayout();
         $this->getLayout()->getBlock('storelocator.edit.tab.product')
              ->setStorelocationProductsGridAction($this->getRequest()->getPost('storelocation_products', null));
        $this->renderLayout();
    }
    
    /**
     * Product grid for AJAX request.
     * Sort and filter result for example.
     */
    public function storeProductGridAction()
    {   $this->loadLayout();
         $this->getLayout()->getBlock('storelocator.edit.tab.product')
              ->setStorelocationProductsGridAction($this->getRequest()->getPost('storelocation_products', null));
        $this->renderLayout();
    }
    
    
    /**
     * Store Location grid for AJAX request.
     * Sort and filter result for example.
     */
    public function storeLocationAction()
    {   $this->loadLayout();
         $this->getLayout()->getBlock('storelocator.edit.tab.storelocations')
              ->setProductsStorelocationGridAction($this->getRequest()->getPost('product_storelocations', null));
        $this->renderLayout();
    }
    
    /**
     * Store Location grid for AJAX request.
     * Sort and filter result for example.
     */
    public function storeLocationGridAction()
    {   $this->loadLayout();
         $this->getLayout()->getBlock('storelocator.edit.tab.storelocations')
              ->setProductsStorelocationGridAction($this->getRequest()->getPost('product_storelocations', null));
        $this->renderLayout();
    }
    
    
    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     * @author Milan Simek
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('cms/plugincompany_storelocator/storelocation');
    }
}
