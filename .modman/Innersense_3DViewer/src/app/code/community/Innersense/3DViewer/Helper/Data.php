<?php

class Innersense_3DViewer_Helper_Data extends Mage_Core_Helper_Abstract
{
    const VIEWER_BASE_URL_CONFIG_PATH = 'viewer3d/settings/base_url';
    const IS_ENABLED_CONFIG_PATH = 'viewer3d/settings/enabled';
    const IS_DEBUG_ENABLED_CONFIG_PATH = 'viewer3d/settings/debug';
    const INIT_OPTIONS_CONFIG_PATH = 'viewer3d/settings/init_options';

    public function isViewerEnabledFor(Mage_Catalog_Model_Product $product)
    {
        $innersenseId = $product->getInnersenseId();
        return !empty($innersenseId);
    }

    public function getViewerUrlFor(Mage_Catalog_Model_Product $product)
    {
        $id = $this->getProductViewerId($product);
        return Mage::getStoreConfig(static::VIEWER_BASE_URL_CONFIG_PATH) . '?id=' . $id;
    }

    private function getProductViewerId(Mage_Catalog_Model_Product $product)
    {
        if (!$this->isViewerEnabledFor($product)) {
            throw new LogicException(
                $this->__('No configuration available for the product "%s"', $product->getSku())
            );
        }
        return $product->getInnersenseId();
    }

    public function isDebugModeEnabled()
    {
        return Mage::getStoreConfigFlag(static::IS_DEBUG_ENABLED_CONFIG_PATH);
    }

    public function isModuleEnabled($moduleName = null)
    {
        return parent::isModuleEnabled($moduleName) && Mage::getStoreConfigFlag(static::IS_ENABLED_CONFIG_PATH);
    }

    public function getInitOptions()
    {
        return Mage::getStoreConfig(static::INIT_OPTIONS_CONFIG_PATH);
    }
}
