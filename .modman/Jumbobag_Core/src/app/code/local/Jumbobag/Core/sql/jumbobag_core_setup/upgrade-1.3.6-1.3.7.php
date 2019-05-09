<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.3.7 --');

try {
    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();

    $block = Mage::getModel('cms/block');
    $block->setTitle('[Home] Posts instagram');
    $block->setIdentifier('home-instagram');
    $block->setStores(array(0));
    $block->setIsActive(1);
    $block->setContent(
<<<CONTENT
https://www.instagram.com/p/BHuqw45Dgf3/
https://www.instagram.com/p/Bwj-MtOHa6V/
https://www.instagram.com/p/BuC1OzIjota/
https://www.instagram.com/p/Bn1BLSYlD3L/
CONTENT
    );
    $block->save();

    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Jumbobag_Core data upgrade 1.3.7 --');
