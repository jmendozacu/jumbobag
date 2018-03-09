<?php
Mage::log('-- Start Jumbobag Ecotax sql setup 0.1.0 --');

try {
    /* @var $installer Quadra_Cybermut_Model_Mysql4_Setup */
    $installer = $this;

    $installer->startSetup();

    $installer->getConnection()->addColumn(
        $this->getTable('sales_flat_quote_item'),
        'ecotax',
        'DECIMAL( 12, 4 ) NOT NULL DEFAULT 0'
    );
    $installer->getConnection()->addColumn(
        $this->getTable('sales_flat_order_item'),
        'ecotax',
        'DECIMAL( 12, 4 ) NOT NULL DEFAULT 0'
    );

    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Jumbobag Ecotax sql setup 0.1.0 --');
