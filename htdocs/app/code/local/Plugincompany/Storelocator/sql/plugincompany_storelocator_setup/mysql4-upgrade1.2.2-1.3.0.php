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
    ALTER TABLE `{$prefix}plugincompany_storelocator_storelocation_comment` 
    ADD COLUMN `rating` TINYINT(4) NOT NULL DEFAULT 0 AFTER `created_at`;
";

$this->run($sql);

$this->endSetup();
