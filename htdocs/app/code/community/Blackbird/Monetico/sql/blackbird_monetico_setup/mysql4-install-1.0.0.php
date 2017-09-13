<?php
/**
 * Blackbird Agency
 *
 * @category    Blackbird
 * Date: 05/06/17
 * Time: 11:45
 * @copyright   Copyright (c) 2017 Blackbird Agency. (http://black.bird.eu)
 * @author Jérémie Poisson (hello@bird.eu)
 */

/* @var $installer Blackbird_Monetico_Model_Mysql4_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("

CREATE TABLE `{$this->getTable('monetico_api_debug')}` (
  `debug_id` int(10) unsigned NOT NULL auto_increment,
  `debug_at` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `request_body` text,
  `response_body` text,
  PRIMARY KEY  (`debug_id`),
  KEY `debug_at` (`debug_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");




/*
 * Added from upgrade script
 */
$data = $installer->getConnection()->fetchAll("SHOW COLUMNS FROM `{$installer->getTable('sales_flat_quote_payment')}`;");

$columnExist = false;

foreach ($data as $row) {
    if ($row['Field'] == 'nbrech') {
        $columnExist = true;
        break;
    }
}

if (!$columnExist) {
    $installer->run("
        ALTER TABLE `{$installer->getTable('sales_flat_quote_payment')}`
        ADD COLUMN `nbrech` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'Monetico';
    ");
}






$installer->endSetup();