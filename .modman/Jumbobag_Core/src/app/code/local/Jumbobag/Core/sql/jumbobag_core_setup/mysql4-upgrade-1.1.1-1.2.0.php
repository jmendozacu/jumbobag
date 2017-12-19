<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.2.0 --');

try {
    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();
    $query = "";
    foreach([23, 24] as $id) {
        $query .= "UPDATE cms_page SET content = REPLACE(content, '.hide(700)', '.hide()') WHERE page_id = {$id};";
        $query .= "UPDATE cms_page SET content = REPLACE(content, '.show(700)', '.show()') WHERE page_id = {$id};";
    }
    Mage::log("-- Running query: $query");    
    $installer->run($query);
    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Jumbobag_Core data upgrade 1.2.0 --');
