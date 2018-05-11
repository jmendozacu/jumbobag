<?php
try {
    /** @var Mage_Core_Model_Resource_Setup $installer */
    $installer = $this;
    $installer->startSetup();

    $installer->setConfigData('aminvisiblecaptcha/general/enabled', '1');
    $installer->setConfigData('aminvisiblecaptcha/general/captcha_key', '6LdRqFgUAAAAAJI3xoCiw9Etb9spTjyHsa8DZMcg');
    $installer->setConfigData('aminvisiblecaptcha/general/captcha_secret', '6LdRqFgUAAAAACGVM1u3zyHu1eL3Ms1ngq_bRZsR');

    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}
