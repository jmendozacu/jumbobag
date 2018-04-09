<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.3.0 --');

try {
    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();

    $pages = [
        // faq [fr]
        [
            'id' => 23,
            'data' => [
                'identifier' => 'info'
            ]
        ],
        // faq [en]
        [
            'id' => 24,
            'data' => [
                'identifier' => 'info'
            ]
        ],
    ];

    foreach ($pages as $page) {
        Mage::getModel('cms/page')
            ->load($page['id'])
            ->addData($page['data'])
            ->save();
    }

    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Jumbobag_Core data upgrade 1.2.1 --');
