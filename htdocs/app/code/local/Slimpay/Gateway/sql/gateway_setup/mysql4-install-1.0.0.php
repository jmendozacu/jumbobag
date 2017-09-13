<?php
// Creating mandate table that will store all signed mandate :
// -> Customer reference
// -> Mandate Rum
// -> Date of signature
$installer = $this;
$installer->startSetup();

$installer->run("
    DROP TABLE IF EXISTS `{$this->getTable('slimpay_mandates')}`;
    CREATE TABLE `{$this->getTable('slimpay_mandates')}` (
      `id`          integer NOT NULL auto_increment,
      `reference`   varchar(80),
      `rum`         varchar(50),
      `mid`         varchar(50),
      `date`        varchar(50),
      PRIMARY KEY  (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    DROP TABLE IF EXISTS `{$this->getTable('slimpay_orders')}`;
    CREATE TABLE `{$this->getTable('slimpay_orders')}` (
        `id`                 integer NOT NULL auto_increment,
        `order_reference`    varchar(50) NOT NULL,
        `bo_order_reference` integer NOT NULL,
        `client_reference`   varchar(50) NOT NULL,
        `client_email`       varchar(255) NOT NULL,
        `order_amount`       varchar(50) DEFAULT 0,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
