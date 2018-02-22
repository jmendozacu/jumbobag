<?php

class LMB_EDI_Helper_EDI {

    static public function link_rewrite($str) {
        $purified = '';
        $length = Mage::helper('core/string')->strlen($str);
        /* if ($utf8_decode)
          $str = utf8_decode($str); */
        for ($i = 0; $i < $length; $i++) {
            $char = Mage::helper('core/string')->substr($str, $i, 1);
            if (Mage::helper('core/string')->strlen(htmlentities($char)) > 1) {
                $entity = htmlentities($char, ENT_COMPAT, 'UTF-8');
                $purified .= $entity{1};
            } elseif (preg_match('|[[:alpha:]]{1}|u', $char))
                $purified .= $char;
            elseif (preg_match('<[[:digit:]]|-{1}>', $char))
                $purified .= $char;
            elseif ($char == ' ')
                $purified .= '_';
        }
        return trim(strtolower($purified));
    }

    public static function refresh_cache() {
        $ver = Mage::getVersion();

        try {
            Mage::getResourceModel('catalog/category_flat')->rebuild();
        } catch (Exception $e) {
            LMB_EDI_Model_EDI::error($e->getMessage());
        }

        try {
            Mage::getResourceModel('catalog/product_flat_indexer')->rebuild();
        } catch (Exception $e) {
            LMB_EDI_Model_EDI::error($e->getMessage());
        }

        if ($ver >= 1.3 && $ver < 1.4) {
            $groups = Mage::getModel('customer/group')->getCollection();
            $prefix = Mage::getModel('core/config')->init()->getTablePrefix();
            $products = Mage::getSingleton('catalog/product')->getCollection()->addAttributeToSelect('*');
            $stores = Mage::app()->getStores(false, true);
            foreach ($stores as $store) {
                foreach ($products as $product) {
                    if ($product->getSpecialPrice() != "") {
                        $price = $product->getSpecialPrice();
                    } else {
                        $price = $product->getPrice();
                    }
                    foreach ($groups as $group) {
                        $field = "display_price_group_" . $group->getCustomerGroupId();
                        try {
                            $sql = "ALTER TABLE {$prefix}catalog_product_flat_" . $store->getStoreId() . " ADD `" . $field . "` DECIMAL( 12, 4 ) NULL DEFAULT NULL ";
                            Mage::getSingleton("core/resource")->getConnection("core_read")->query($sql);
                        } catch (Exception $e) {

                        }

                        $sql = "UPDATE {$prefix}catalog_product_flat_" . $store->getStoreId() . " SET `" . $field . "` = '" . $price . "' WHERE `entity_id` ='" . $product->getEntityId() . "'";
                        Mage::getSingleton("core/resource")->getConnection("core_read")->query($sql);
                    }
                }
            }
        }
    }
}

?>
