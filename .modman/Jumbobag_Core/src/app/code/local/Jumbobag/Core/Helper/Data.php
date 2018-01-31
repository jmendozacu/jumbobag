<?php
class Jumbobag_Core_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getFixBandesBleues()
    {
        return Mage::registry('current_product')->getData('fixbandesbleues') == 1
            ? "fixbandesbleues"
            : "";
    }

    public function getThemeSublayout()
    {
        $subl = Mage::registry('current_product')->getAttributeText('jbag_theme_sublayout');
        
        return empty($subl)
            ? ""
            : "product-sublayout-".(strtolower(str_replace(" ", "-", $subl)));
    }
}
