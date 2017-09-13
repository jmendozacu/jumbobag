<?php
/*
 * Created by:  Milan Simek
 * Company:     Plugin Company
 *
 * LICENSE: http://plugin.company/docs/magento-extensions/magento-extension-license-agreement
 *
 * YOU WILL ALSO FIND A PDF COPY OF THE LICENSE IN THE DOWNLOADED ZIP FILE
 *
 * FOR QUESTIONS AND SUPPORT
 * PLEASE DON'T HESITATE TO CONTACT US AT:
 *
 * SUPPORT@PLUGIN.COMPANY
 */
/**
 * Storelocator module install script
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
$this->startSetup();
$prefix = Mage::getConfig()->getTablePrefix();

$sql = "
CREATE TABLE `{$prefix}plugincompany_storelocator_storelocation_product` (
  `entity_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Entity ID',
  `product_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Product ID',
  `storelocation_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Location ID',
  `store_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT 'Store ID',
  PRIMARY KEY (`entity_id`),
  KEY `IDX_PLUGINCOMPANY_STORELOCATOR_STORELOCATION_PRODUCT_PRODUCT_ID` (`product_id`),
  KEY `D87575D3FAA58036B1B16A19BDE313A8` (`storelocation_id`),
  KEY `FK_7CCACA1C39EF3444C7701AA805585BD6` (`store_id`),
  CONSTRAINT `FK_CE0DEA55D4FC05076CF8F95C0B888435` FOREIGN KEY (`product_id`) REFERENCES `{prefix}catalog_product_entity` (`entity_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_7CCACA1C39EF3444C7701AA805585BD6` FOREIGN KEY (`store_id`) REFERENCES `{prefix}core_store` (`store_id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_7C4FE6D8A300BEC7DDB12CC2D4F4AC5B` FOREIGN KEY (`storelocation_id`) REFERENCES `{prefix}plugincompany_storelocator_storelocation` (`entity_id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Store Product Location';

";

$this->run($sql);

$this->endSetup();
