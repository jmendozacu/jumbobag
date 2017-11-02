<?php
Mage::log('-- Start Jumbobag_Core install 1.0.0 --');

try {
	/* @var $installer Mage_Core_Model_Resource_Setup */
	$installer = $this;
	$installer->startSetup();
	$installer->setConfigData('system/log/clean_after_day', 1);
	$installer->setConfigData('system/quotecleaner/scheduler_cron_expr', '25 1 * * *');
	$installer->setConfigData('system/quotecleaner/clean_quoter_older_than', 90);
	$installer->setConfigData('system/quotecleaner/clean_anonymous_quotes_older_than', 60);
	$installer->endSetup();
} catch (Exception $e) {
	Mage::logException($e);
}

Mage::log('-- End Jumbobag_Core install 1.0.0 --');
