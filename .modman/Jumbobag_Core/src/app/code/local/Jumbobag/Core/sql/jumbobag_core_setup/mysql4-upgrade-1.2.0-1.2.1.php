<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.2.1 --');

try {
    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();
    $query = "UPDATE catalog_product_entity_text 
      SET `value` = REPLACE(`value`, '<a href=\"#\">En savoir plus</a>', '')
      WHERE attribute_id = 199 AND entity_type_id = 4 AND `value` like '<div class=\"content_remplissage\">%'
    ;";
    Mage::log("-- Running query: $query");    
    $installer->run($query);
    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Jumbobag_Core data upgrade 1.2.1 --');
