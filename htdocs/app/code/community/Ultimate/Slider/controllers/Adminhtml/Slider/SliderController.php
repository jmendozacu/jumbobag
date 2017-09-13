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
 * Slider admin controller
 *
 * @category    Images & Media
 * @package     Ultimate_Slider
 * @author      Rajasingh and Manikandan D
 */
class Ultimate_Slider_Adminhtml_Slider_SliderController extends Mage_Adminhtml_Controller_Action
{
    
    /**
     * initiate the slider
     *
     * @access protected
     * @return Ultimate_Slider_Model_Slider
     */
    protected function _initSlider() {
        $sliderId = (int)$this->getRequest()->getParam('id');
        $slider = Mage::getModel('ultimate_slider/slider');
        if ($sliderId) {
            $slider->load($sliderId);
        }
        Mage::register('current_slider', $slider);
        return $slider;
    }
    
    /**
     * default action
     *
     * @access public
     * @return void
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('ultimate_slider')->__('Ultimate Slider'))->_title(Mage::helper('ultimate_slider')->__('Sliders'));
        $this->renderLayout();
    }
    
    /**
     * grid action
     *
     * @access public
     * @return void
     */
    public function gridAction() {
        $this->loadLayout()->renderLayout();
    }
    
    /**
     * edit slider - action
     *
     * @access public
     * @return void
     */
    public function editAction() {
        $sliderId = $this->getRequest()->getParam('id');
        $slider = $this->_initSlider();
        if ($sliderId && !$slider->getId()) {
            $this->_getSession()->addError(Mage::helper('ultimate_slider')->__('This slider no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        
        $data = Mage::getSingleton('adminhtml/session')->getSliderData(true);
        if (!empty($data)) {
            $slider->setData($data);
        }
        
        Mage::register('slider_data', $slider);
        $this->loadLayout();
        $this->_title(Mage::helper('ultimate_slider')->__('Ultimate Slider'))->_title(Mage::helper('ultimate_slider')->__('Sliders'));
        
        if ($slider->getId()) {
            $this->_title($slider->getSlider());
        } 
        else {
            $this->_title(Mage::helper('ultimate_slider')->__('Add slider'));
        }
        
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }
    
    /**
     * new slider action
     *
     * @access public
     * @return void
     */
    public function newAction() {
        $this->_forward('edit');
    }
    
    /**
     * redirect to homepage - action
     *
     * @access public
     * @return void
     */
    public function redirectAction() {
        $baseurl = Mage::getBaseUrl();
        $this->_redirectUrl($baseurl);
    }
    
    /**
     * save slider - action
     *
     * @access public
     * @return void
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost('slider')) {
            
            try {
                
                $slider = $this->_initSlider();
                
                // #----------------------------------------------------------#
                // # Delete Image if Delete option is selected on Edit Action #
                // #----------------------------------------------------------#
                
                if (isset($data['image']['delete']) && $data['image']['delete'] == 1) {
                    unlink(Mage::getBaseDir('media') . DS . "wysiwyg" . DS . $data['image']['value']);
                    $data['image'] = '';
                } 
                else {
                    unset($data['image']);
                }
                
                // #-----------#
                // # Add Image #
                // #-----------#
                
                if (isset($_FILES['image']['name']) && (file_exists($_FILES['image']['tmp_name']))) {
                    
                    if (($_FILES['image']['type'] == 'video/mp4') || 
                        ($_FILES['image']['type'] == 'image/jpeg') || 
                        ($_FILES['image']['type'] == 'image/jpg') || 
                        ($_FILES['image']['type'] == 'image/gif') || 
                        ($_FILES['image']['type'] == 'image/png')) {
                                                                    
                    try {
                        $uploader = new Varien_File_Uploader('image');
                        $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png', 'mp4'));
                        $uploader->setAllowRenameFiles(true);
                        $uploader->setFilesDispersion(false);
                        
                        $path = Mage::getBaseDir('media') . DS . "wysiwyg";
                        
                        $uploader->save($path, time() . "_" . $_FILES['image']['name']);
                        
                        $data['image'] = time() . "_" . $_FILES['image']['name'];
                        
                        if ($_FILES['image']['type'] != 'video/mp4') {

                            $imageHelper = Mage::helper('ultimate_slider/image');
                            $imageHelper->resizeImageAction($data['image'], '1280', '720');
                        }
                    }
                    catch(Exception $e) {
                    }

                    } else {

                        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ultimate_slider')->__('The slider could not be saved due to wrong media file type'));
                        Mage::getSingleton('adminhtml/session')->setSliderData($data);
                        $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                        return;

                    }

                }
                
                $slider->addData($data);
                $slider->save();
                
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ultimate_slider')->__('Slider was successfully saved'));
                
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $slider->getId()));
                    return;
                }
                
                $this->_redirect('*/*/');
                return;
            }
            catch(Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setSliderData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch(Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ultimate_slider')->__('The slider could not be saved'));
                Mage::getSingleton('adminhtml/session')->setSliderData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ultimate_slider')->__('Unable to find slider to save.'));
        $this->_redirect('*/*/');
    }    
    
    /**
     * delete slider - action
     *
     * @access public
     * @return void
     */
    public function deleteAction() {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $slider = Mage::getModel('ultimate_slider/slider');
                
                // # Delete Image #
                $loadSlider = $slider->load($this->getRequest()->getParam('id'));
                $imageLink = $loadSlider->getImage();
                unlink(Mage::getBaseDir('media') . DS . "wysiwyg" . DS . $imageLink);
                
                // # Delete Data #
                $slider->setId($this->getRequest()->getParam('id'))->delete();
                
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ultimate_slider')->__('Slider was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch(Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch(Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ultimate_slider')->__('There was an error deleting slider.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ultimate_slider')->__('Could not find slider to delete.'));
        $this->_redirect('*/*/');
    }
    
    /**
     * mass delete slider - action
     *
     * @access public
     * @return void
     */
    public function massDeleteAction() {
        $sliderIds = $this->getRequest()->getParam('slider');
        if (!is_array($sliderIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ultimate_slider')->__('Please select sliders to delete.'));
        } 
        else {
            try {
                foreach ($sliderIds as $sliderId) {
                    $slider = Mage::getModel('ultimate_slider/slider');
                    
                    // Delete Image #
                    $loadSlider = $slider->load($sliderId);
                    $imageLink = $loadSlider->getImage();
                    unlink(Mage::getBaseDir('media') . DS . "wysiwyg" . DS . $imageLink);
                    
                    // Delete Image #
                    
                    // Delete DB data #
                    $slider->setId($sliderId)->delete();
                    
                    // Delete DB data #
                    
                    
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('ultimate_slider')->__('Total of %d sliders were successfully deleted.', count($sliderIds)));
            }
            catch(Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch(Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ultimate_slider')->__('There was an error deleting sliders.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    
    /**
     * mass status change - action
     *
     * @access public
     * @return void
     */
    public function massStatusAction() {
        $sliderIds = $this->getRequest()->getParam('slider');
        if (!is_array($sliderIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ultimate_slider')->__('Please select sliders.'));
        } 
        else {
            try {
                foreach ($sliderIds as $sliderId) {
                    $slider = Mage::getSingleton('ultimate_slider/slider')->load($sliderId)->setStatus($this->getRequest()->getParam('status'))->setIsMassupdate(true)->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d sliders were successfully updated.', count($sliderIds)));
            }
            catch(Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch(Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ultimate_slider')->__('There was an error updating sliders.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    
    /**
     * export as csv - action
     *
     * @access public
     * @return void
     */
    public function exportCsvAction() {
        $fileName = 'slider.csv';
        $content = $this->getLayout()->createBlock('ultimate_slider/adminhtml_slider_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     */
    public function exportExcelAction() {
        $fileName = 'slider.xls';
        $content = $this->getLayout()->createBlock('ultimate_slider/adminhtml_slider_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    /**
     * export as xml - action
     *
     * @access public
     * @return void
     */
    public function exportXmlAction() {
        $fileName = 'slider.xml';
        $content = $this->getLayout()->createBlock('ultimate_slider/adminhtml_slider_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    
    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('ultimate_slider/slider');
    }        
}
