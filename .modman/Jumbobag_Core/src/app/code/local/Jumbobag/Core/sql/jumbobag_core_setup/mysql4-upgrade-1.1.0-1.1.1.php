<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.1.1 --');

try {
    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();
    $query = "";
    foreach(["original", "printed", "flottant", "x-trem"] as $id) {
        $query .= "UPDATE cms_block SET content = REPLACE(content, 'article id=\"$id\"', 'article class=\"$id\"');";
    }
    Mage::log("-- Running query: $query");    
    $installer->run($query);
    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Jumbobag_Core data upgrade 1.1.1 --');
