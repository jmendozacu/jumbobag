<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.3.0 --');

try {
    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();

    $pages = [
        // Catalogue
        [
            'id' => 56,
            'content' => '<div data-configid="6417193/38088445" style="" class="issuuembed cms-catalogue-slider"></div>
<script type="text/javascript" src="//e.issuu.com/embed.js" async="true"></script>

<h2>Jumbo Bag Shooting - Summer Outdoor</h2>

<div class="cms-catalogue-video" style="text-align:center;">
<iframe class="cms-catalogue-video-player" src="//player.vimeo.com/video/148653987" width="960" height="540" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
</div>


<h2>Jumbo Bag Printed - Backstage shooting</h2>

<div class="cms-catalogue-video" style="text-align:center;">
<iframe class="cms-catalogue-video-player" src="//player.vimeo.com/video/89997102" width="960" height="540" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
</div>'
        ],
    ];

    foreach ($pages as $page) {
        Mage::getModel('cms/page')
            ->load($page['id'])
            ->addData([
                'content' => $page['content']
            ])
            ->save();
    }

    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Jumbobag_Core data upgrade 1.2.1 --');
