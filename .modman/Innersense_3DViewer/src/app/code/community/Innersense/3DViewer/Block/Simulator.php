<?php

class Innersense_3DViewer_Block_Simulator extends Mage_Core_Block_Template
{
    public $_template = 'innersense/simulator.phtml';

    public function isEnabled()
    {
        $helper = $this->viewerHelper();
        return $helper->isModuleEnabled()
            && $helper->isViewerEnabledFor($this->getProduct());
    }

    public function getProduct()
    {
        $product = Mage::registry('current_product');
        if (!$product) {
            Mage::log('3DViewer was loaded in a context without any product', Zend_Log::ERR);
            $product = new Mage_Catalog_Model_Product();
        }
        return $product;
    }

    public function getViewerUrl()
    {
        return $this->viewerHelper()->getViewerUrlFor($this->getProduct());
    }

    public function isDebugModeEnabled()
    {
        return $this->viewerHelper()->isDebugModeEnabled();
    }

    /**
     * @return Innersense_3DViewer_Helper_Data
     */
    private function viewerHelper()
    {
        return $this->helper('innersense_3dviewer');
    }
}
