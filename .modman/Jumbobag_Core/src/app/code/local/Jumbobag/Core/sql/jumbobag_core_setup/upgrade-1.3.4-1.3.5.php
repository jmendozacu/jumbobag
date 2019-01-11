<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.3.5 --');

try {
    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();

    $blocks = [
        // original-jumbobag
        [
            'id' => 44,
            'data' => [
                'content' => '
                <article class="original">
  <div class="product-image col-md-4 hidden-desktop">
    <img src="{{skin url="images/original.png"}}" alt="original" />
  </div>
<div class="product-image col-md-4 hidden-mobile hidden-tablet">
<div class="ani-image-con">
<div class="ani-img-inner-box or-floor-image wow fadeInUp animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/original-floor-image.png" /></div>
<div class="ani-img-inner-box or-plant-image wow fadeInLeft animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/original-plant-image.png" /></div>
<div class="ani-img-inner-box or-poof-image wow fadeInDown animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/original-poof-image.png" /></div>
<div class="ani-img-inner-box or-price-image wow bounceIn animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/original-imge-price-tag.png" /></div>
<div class="ani-img-inner-box or-table-image wow fadeInRightBig animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/original-table-image.png" /></div>
<div class="ani-img-inner-box or-best-image wow bounceIn animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/original-best-seller-image.png" /></div>
<div class="ani-img-inner-box or-bl-text-image wow bounceIn animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/original-bl-price-image.png" /></div>
</div>
</div>
<div class="product-text col-md-4">
<h2>Le Jumbobag Original</h2>
<p>The Original est le <strong>pouf g&eacute;ant</strong> incontournable de la marque Jumbo Bag. Enti&egrave;rement <strong>imperm&eacute;able</strong> : il peut aussi bien &ecirc;tre utilis&eacute; en tant que <strong>coussin d&rsquo;ext&eacute;rieur</strong> ou comme <strong>pouf design</strong> en int&eacute;rieur. De plus sa housse amovible lui permet d&rsquo;&ecirc;tre lav&eacute; en machine. Un choix de 16 couleurs est propos&eacute; pour s&rsquo;adapter parfaitement &agrave; votre <strong>d&eacute;coration</strong>.</p>
<a href="/pouf-coussin-geant-jumbobag-original.html">Voir la collection</a></div>
</article>
            ']
        ],
        // home-collection
        [
            'id' => 45,
            'data' => [
                'content' => '
                <article class="printed">
  <div class="product-image col-md-4 hidden-desktop">
    <img src="{{skin url="images/printed.png"}}" alt="printed" />
  </div>
<div class="product-text col-md-4">
<h2>La collection d\'imprim&eacute;s</h2>
<p>The Original Jumbobag Printed est LE <strong>pouf design</strong> et <strong>tendance</strong> qu&rsquo;il vous faut. Il se fondra facilement dans votre <strong>d&eacute;coration int&eacute;rieure</strong> gr&acirc;ce &agrave; une palette de plus de 30 motifs. Avec son tissu <strong>imperm&eacute;able</strong> le pouf imprim&eacute; Jumbobag peut &eacute;galement &ecirc;tre une super <strong>id&eacute;e d&eacute;co</strong> pour votre <strong>jardin</strong>.</p>
<a href="/pouf-coussin-geant-jumbobag-original-printed.html">Voir la collection</a></div>
<div class="product-image col-md-4 hidden-mobile hidden-tablet">
<div class="ani-image-con-two">
<div class="ani-img-inner-box di-red-image wow fadeIn animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/di-red-image.png" /></div>
<div class="ani-img-inner-box di-blue-image wow fadeIn animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/di-blue-image.png" /></div>
<div class="ani-img-inner-box di-print-image wow fadeInRight animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/di-printed-image.png" /></div>
<div class="ani-img-inner-box di-green-image wow fadeIn animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/di-green-image.png" /></div>
<div class="ani-img-inner-box di-bl-price-image wow bounceIn animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/di-price-image.png" /></div>
</div>
</div>
</article>
                '
            ]
        ],
        // home-les-poufs
        [
            'id' => 46,
            'data' => [
                'content' => '
                <article class="flottant">
  <div class="product-image col-md-4 hidden-desktop">
    <img src="{{skin url="images/flottant.png"}}" alt="Flottant" />
  </div>
  <div class="product-image col-md-4  hidden-mobile hidden-tablet">
    <div class="ani-image-con-three">
      <div class="ani-img-inner-box fl-tree-image wow fadeInLeft animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/fl-tree-image.png" /></div>
      <div class="ani-img-inner-box fl-sun-image wow fadeInUpside animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/fl-sun-image.png" /></div>
      <div class="ani-img-inner-box fl-pool-image wow fadeInDown animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/fl-pool-image.png" /></div>
      <div class="ani-img-inner-box fl-swimbag-image wow fadeInDown animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/fl-swim-bag-image.png" /></div>
      <div class="ani-img-inner-box fl-extra-image wow fadeInLeft animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/fl-extra-image.png" /></div>
      <div class="ani-img-inner-box fl-price-image wow bounceIn animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/fl-bl-price-image.png" /></div>
    </div>
  </div>
  <div class="product-text col-md-4">
    <h2>Les poufs flottants</h2>
    <p>Découvrez la gamme <strong>Swimming bag</strong> ! Jumbobag propose différents types de <strong>poufs flottants</strong> allant du <strong>coussin géant</strong> au <strong>donut</strong> pour s’adapter à
    toutes les <strong>piscines</strong>. <strong>Design</strong> et colorés nos coussins flottants sauront se fondre avec l’ambiance de votre choix.</p>
    <a href="/products/pouf/demo-poufs-flottants.html">Voir la collection</a>
  </div>
</article>
                '
            ]
        ],
        // home-la-collection
        [
            'id' => 47,
            'data' => [
                'content' => '
                 <article class="x-trem">
                        <div class="product-image col-md-4 hidden-desktop">
                          <img src="{{skin url="images/xtrem.png"}}" alt="x-trem" />
                        </div>
                        <div class="product-text col-md-4">
                          <h2>La collection X-Trem</h2>
                          <p>Jumbo bag présente sa collection X-trem ! Entièrement <strong>fabriquée en France</strong> les poufs de cette collection ont été conçus pour une <strong>utilisation intensive</strong>.  En tissus <strong>Sunbrella®</strong>
                             ou simili <strong>cuir</strong> les trois produits phares de la marque sont déclinés : Le <strong>coussin géant</strong> The Original, le Swimming Bag, le cube ainsi que le <strong>pouf poire</strong> Scuba XXL. A découvrir sans attendre. </p>
                          <a href="/products/pouf/demo-collection-xtrem.html">Voir la collection</a>
                        </div>
                        <div class="product-image col-md-4 hidden-mobile hidden-tablet">
                          <div class="ani-image-con-four">
	<div class="ani-img-inner-box xt-xtrem-image wow flipInX animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/xt-xtrem-image.png" /></div>
	<div class="ani-img-inner-box xt-plane-image wow fadeInLeft animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/xt-plane-image.png" /></div>
	<div class="ani-img-inner-box xt-fr-image wow fadeInUp animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/xt-fr-image.png" /></div>
	<div class="ani-img-inner-box xt-bl-text-image wow fadeInUpRotate animated"><img src="{{config path="web/secure/base_url"}}skin/frontend/jumbobag/default/images/xt-bl-text-image.png" /></div>
</div>
                        </div>
                      </article>
                '
            ]
        ],
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

Mage::log('-- End Jumbobag_Core data upgrade 1.3.5 --');
