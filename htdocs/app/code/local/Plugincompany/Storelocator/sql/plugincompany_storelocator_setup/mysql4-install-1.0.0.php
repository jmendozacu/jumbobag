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
$connection = Mage::getSingleton('core/resource');
$prefix = Mage::getConfig()->getTablePrefix();

$sql = "
DROP TABLE IF EXISTS {$prefix}plugincompany_storelocator_storelocation;
DROP TABLE IF EXISTS {$prefix}plugincompany_storelocator_storelocation_store;
DROP TABLE IF EXISTS {$prefix}plugincompany_storelocator_storelocation_comment;
DROP TABLE IF EXISTS {$prefix}plugincompany_storelocator_storelocation_comment_store;
";

$this->run($sql);

$sql = "
CREATE TABLE `{$prefix}plugincompany_storelocator_storelocation` (
	`entity_id` INT NOT NULL auto_increment COMMENT 'Store location ID'
	,`locname` VARCHAR(255) NOT NULL COMMENT 'Location name'
	,`lat` VARCHAR(255) NOT NULL COMMENT 'Latitude'
	,`lng` VARCHAR(255) NOT NULL COMMENT 'Longitude'
	,`address` VARCHAR(255) NOT NULL COMMENT 'Address line 1'
	,`address2` VARCHAR(255) NOT NULL COMMENT 'Address Line 2'
	,`city` VARCHAR(255) NOT NULL COMMENT 'City'
	,`state` VARCHAR(255) NOT NULL COMMENT 'State'
	,`country` VARCHAR(255) NOT NULL COMMENT 'country'
	,`postal` VARCHAR(255) NOT NULL COMMENT 'Postal code / zip code'
	,`phone` VARCHAR(255) NOT NULL COMMENT 'Phone'
	,`email` VARCHAR(255) NOT NULL COMMENT 'Email'
	,`fax` VARCHAR(255) NOT NULL COMMENT 'Fax'
	,`web` VARCHAR(255) NOT NULL COMMENT 'Website'
	,`hours1` VARCHAR(255) NOT NULL COMMENT 'Opening hours line 1'
	,`hours2` VARCHAR(255) NOT NULL COMMENT 'Opening hours line 2'
	,`hours3` VARCHAR(255) NOT NULL COMMENT 'Opening hours line 3'
	,`image` VARCHAR(255) NULL COMMENT 'Image'
	,`description` TEXT NOT NULL COMMENT 'Store description'
	,`url_key` VARCHAR(255) NULL COMMENT 'URL key'
	,`meta_title` VARCHAR(255) NULL COMMENT 'Meta title'
	,`meta_keywords` TEXT NULL COMMENT 'Meta keywords'
	,`meta_description` TEXT NULL COMMENT 'Meta description'
	,`allow_comment` INT NULL COMMENT 'Allow Comment'
	,`sort_order` INT NULL COMMENT 'Sort Order'
	,`use_image_not_map` INT NULL COMMENT 'Use Image Not Map'
	,`show_in_finder` INT NULL COMMENT 'Show in finder'
	,`show_in_list` INT NULL COMMENT 'Show in list'
	,`status` INT NULL COMMENT 'Store location Status'
	,`updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Store location Modification Time'
	,`created_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Store location Creation Time'
	,PRIMARY KEY (`entity_id`)
	) COMMENT = 'Store location Table' ENGINE = INNODB charset = utf8 COLLATE = utf8_general_ci
";

$this->run($sql);

$sql = "
CREATE TABLE `{$prefix}plugincompany_storelocator_storelocation_store` (
	`storelocation_id` INT NOT NULL COMMENT 'Store location ID'
	,`store_id` SMALLINT UNSIGNED NOT NULL COMMENT 'Store ID'
	,PRIMARY KEY (
		`storelocation_id`
		,`store_id`
		)
	,INDEX `IDX_PLUGINCOMPANY_STORELOCATOR_STORELOCATION_STORE_STORE_ID`(`store_id`)
	,CONSTRAINT `FK_DF1983206D0CE044CF473E4B5EB4FC9F` FOREIGN KEY (`storelocation_id`) REFERENCES `{$prefix}plugincompany_storelocator_storelocation`(`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
	,CONSTRAINT `FK_1E5E7BC8C4BC2908F8A7754AA8653364` FOREIGN KEY (`store_id`) REFERENCES `{$prefix}core_store`(`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
	) COMMENT = 'Store locations To Store Linkage Table' ENGINE = INNODB charset = utf8 COLLATE = utf8_general_ci
";

$this->run($sql);

$sql = "
CREATE TABLE `{$prefix}plugincompany_storelocator_storelocation_comment` (
	`comment_id` INT NOT NULL auto_increment COMMENT 'Store location Comment ID'
	,`storelocation_id` INT NOT NULL COMMENT 'Store location ID'
	,`title` VARCHAR(255) NOT NULL COMMENT 'Comment Title'
	,`comment` TEXT NOT NULL COMMENT 'Comment'
	,`status` SMALLINT NOT NULL COMMENT 'Comment status'
	,`customer_id` INT UNSIGNED NULL COMMENT 'Customer id'
	,`name` VARCHAR(255) NOT NULL COMMENT 'Customer name'
	,`email` VARCHAR(255) NOT NULL COMMENT 'Customer email'
	,`updated_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Store location Comment Modification Time'
	,`created_at` TIMESTAMP NULL DEFAULT NULL COMMENT 'Store location Comment Creation Time'
	,PRIMARY KEY (`comment_id`)
	,CONSTRAINT `FK_0BC96E289E4DFD06401BD248066D80BE` FOREIGN KEY (`storelocation_id`) REFERENCES `{$prefix}plugincompany_storelocator_storelocation`(`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
	,CONSTRAINT `FK_FE64BF85E67CE83467499CBA15158FAC` FOREIGN KEY (`customer_id`) REFERENCES `{$prefix}customer_entity`(`entity_id`) ON DELETE SET NULL ON UPDATE CASCADE
	) COMMENT = 'Store location Comments Table' ENGINE = INNODB charset = utf8 COLLATE = utf8_general_ci
";

$this->run($sql);

$sql = "
CREATE TABLE `{$prefix}plugincompany_storelocator_storelocation_comment_store` (
	`comment_id` INT NOT NULL COMMENT 'Comment ID'
	,`store_id` SMALLINT UNSIGNED NOT NULL COMMENT 'Store ID'
	,PRIMARY KEY (
		`comment_id`
		,`store_id`
		)
	,INDEX `453040AD86B2B94ADF0606B0C595C56D`(`store_id`)
	,CONSTRAINT `FK_E9BACF0702509C7FB7190A8AFC2DA3A1` FOREIGN KEY (`comment_id`) REFERENCES `{$prefix}plugincompany_storelocator_storelocation_comment`(`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE
	,CONSTRAINT `FK_D25E6DAAD737A67A0D6200929A7591B2` FOREIGN KEY (`store_id`) REFERENCES `{$prefix}core_store`(`store_id`) ON DELETE CASCADE ON UPDATE CASCADE
	) COMMENT = 'Store locations Comments To Store Linkage Table' ENGINE = INNODB charset = utf8 COLLATE = utf8_general_ci
";

$this->run($sql);

$this->endSetup();
