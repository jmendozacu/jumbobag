<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.3.6 --');

try {
    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();

    $blocks = [
        // original-jumbobag
        [
            'id' => 48,
            'data' => [
                'content' => <<<CONTENT
<h2>On parle de nous</h2>
<div class="home-references">
  <div><a href="https://issuu.com/jumbobag/docs/fr_150705_ouest_france_jumbo_bag_pr" target="_blank"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/ouest-france.jpg" alt="Ouest France" /></a></div>
  <div><a href="http://www.luniversdelamaison-lemag.com/index.php/exterieur/mobilier/item/1405-mobilier-outdoor-avec-jumbo-bag-faites-swinguer-vos-espaces-exterieurs" target="_blank"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/univers-maison.jpg" alt="L'Univers de la Maison" /></a></div>
  <div><a href="https://issuu.com/maisonscreoles/docs/mcr87_webzine/80" target="_blank"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/maisons-creoles.jpg" alt="Maisons Créoles" /></a></div>
  <div><a href="https://issuu.com/jumbobag/docs/fr_150715_femmeactuelle_swimming_ba/4" target="_blank"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/femme-actuelle.jpg" alt="Femme Actuelle" /></a></div>
  <div><a href="https://www.ladepeche.fr/article/2015/08/10/2157338-le-swimmingbag-un-canape-sur-l-eau.html" target="_blank"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/la-depeche.jpg" alt="La Dépêche" /></a></div>
</div>
CONTENT
            ]
        ],
        // home-collection
        [
            'id' => 55,
            'data' => [
                'content' => <<<CONTENT
<h2>They talk about us</h2>
<div class="home-references">
  <div><a href="https://issuu.com/jumbobag/docs/fr_150705_ouest_france_jumbo_bag_pr" target="_blank"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/ouest-france.jpg" alt="Ouest France" /></a></div>
  <div><a href="http://www.luniversdelamaison-lemag.com/index.php/exterieur/mobilier/item/1405-mobilier-outdoor-avec-jumbo-bag-faites-swinguer-vos-espaces-exterieurs" target="_blank"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/univers-maison.jpg" alt="L'Univers de la Maison" /></a></div>
  <div><a href="https://issuu.com/maisonscreoles/docs/mcr87_webzine/80" target="_blank"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/maisons-creoles.jpg" alt="Maisons Créoles" /></a></div>
  <div><a href="https://issuu.com/jumbobag/docs/fr_150715_femmeactuelle_swimming_ba/4" target="_blank"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/femme-actuelle.jpg" alt="Femme Actuelle" /></a></div>
  <div><a href="https://www.ladepeche.fr/article/2015/08/10/2157338-le-swimmingbag-un-canape-sur-l-eau.html" target="_blank"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/la-depeche.jpg" alt="La Dépêche" /></a></div>
</div>
CONTENT
            ]
        ]
    ];

    foreach ($blocks as $b) {
        Mage::getModel('cms/block')
            ->load($b['id'])
            ->addData($b['data'])
            ->save();
    }

    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Jumbobag_Core data upgrade 1.3.6 --');
