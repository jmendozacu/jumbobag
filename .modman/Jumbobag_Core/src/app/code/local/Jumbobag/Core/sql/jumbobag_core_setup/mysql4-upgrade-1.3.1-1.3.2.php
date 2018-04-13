<?php
Mage::log('-- Start Jumbobag_Core upgrade 1.3.2 --');

try {
    /* @var $installer Mage_Core_Model_Resource_Setup */
    $installer = $this;
    $installer->startSetup();

    $blocks = [
        // shopper_footer_information [en]
        [
            'id' => 19,
            'data' => [
                'content' => '<div class="informations col-sm-3 footer_to_hide">
        <div class="responsive-footer">
          <h4>
              <a href="/info">Information</a>
          </h4>
          <ul class="to_hide_reaction">
            <li><a href="/catalogue">Catalog 2018</a></li>
            <li><a href="/manufacturing">Manufacturing</a></li>
            <li><a href="/info">FAQ</a></li>
            <li><a href="/shipping">Shipping</a></li>
            <li><a href="/payment-mode">Payment mode</a></li>
            <li><a href="/legal-notice">Legal notice</a></li>
            <li><a href="/cgv">GTCs</a></li>
            <li><a href="/dataprotect">Protection des données</a></li>
            <li><a href="/retractinfo">Droit de rétractation</a></li>
            <li><a href="/concours">Règlement Jeu Concours</a></li>
          </ul>
        </div>
      </div>'
            ]
        ],
        // shopper_footer_contact [en]
        [
            'id' => 25,
            'data' => [
                'content' => '<div class="contact col-sm-3 footer_to_hide">
        <div class="responsive-footer">
          <h4>
              <a href="mailto:contact@jumbobag.fr">Contact</a>
          </h4>
          <div class="to_hide_reaction">
            <p>Jumbo Bag</p>
            <p>12 rue Ferrando</p>
            <p>31400 Toulouse France</p>
            <br />
            <p>+33(0)9 72 60 09 18</p>
            <br />
            <p><a href="mailto:contact@jumbobag.fr">contact@jumbobag.fr</a></p>
          </div>
        </div>
      </div>'
            ]
        ],
        // shopper_footer_press [en]
        [
            'id' => 37,
            'data' => [
                'content' => '  <div class="presses col-sm-3">
        <div class="responsive-footer">
          <div class="presse-medical footer_to_hide">
            <h4 class="presse-title">
                <a href="mailto:presse@jumbobag.fr">Presse</a>
            </h4>
            <ul class="to_hide_reaction">
              <li><a href="/on-parle-de-nous">About us</a></li>
              <li><a href="/contact-presse">Press contact</a></li>
            </ul>
          </div>
          <div class="presse-medical footer_to_hide">
            <h4 class="medical-title">
                <a href="/safebag">Medical</a>
            </h4>
            <ul class="to_hide_reaction">
              <li><a href="/safebag">Safebag</a></li>
            </ul>
          </div>
        </div>
      </div>'
            ]
        ],
        // shopper_footer_information [fr]
        [
            'id' => 6,
            'data' => [
                'content' => '<div class="informations col-sm-3 footer_to_hide">
        <div class="responsive-footer">
          <h4>
              <a href="/info">Information</a>
          </h4>
          <ul class="to_hide_reaction">
            <li><a href="/catalogue">Catalogue 2018</a></li>
            <li><a href="/manufacturing">Fabrication</a></li>
            <li><a href="/info">FAQ</a></li>
            <li><a href="/shipping">Livraison</a></li>
            <li><a href="/payment-mode">Modes de règlement</a></li>
            <li><a href="/legal-notice">Mentions légales</a></li>
            <li><a href="/cgv">CGV</a></li>
            <li><a href="/dataprotect">Protection des données</a></li>
            <li><a href="/retractinfo">Droit de rétractation</a></li>
            <li><a href="/concours">Règlement Jeu Concours</a></li>
          </ul>
        </div>
      </div>'
            ]
        ],
        // shopper_footer_contact [fr]
        [
            'id' => 5,
            'data' => [
                'content' => '<div class="contact col-sm-3 footer_to_hide">
        <div class="responsive-footer">
          <h4>
              <a href="mailto:contact@jumbobag.fr">Contact</a>
          </h4>
          <div class="to_hide_reaction">
            <p>Jumbo Bag</p>
            <p>12 rue Ferrando</p>
            <p>31400 Toulouse France</p>
            <br />
            <p>+33(0)9 72 60 09 18</p>
            <br />
            <p><a href="mailto:contact@jumbobag.fr">contact@jumbobag.fr</a></p>
          </div>
        </div>
      </div>'
            ]
        ],
        // shopper_footer_press [fr]
        [
            'id' => 40,
            'data' => [
                'content' => '<div class="presses col-sm-3">
        <div class="responsive-footer">
          <div class="presse-medical footer_to_hide">
            <h4 class="presse-title">
                <a href="mailto:presse@jumbobag.fr">Presse</a>
            </h4>
            <ul class="to_hide_reaction">
              <li><a href="/on-parle-de-nous">On parle de nous</a></li>
              <li><a href="/contact-presse">Contact de presse</a></li>
            </ul><
          </div>
          <div class="presse-medical footer_to_hide">
            <h4 class="medical-title">
                <a href="/safebag">Medical</a>
            </h4>
            <ul class="to_hide_reaction">
              <li><a href="/safebag">Safebag</a></li>
            </ul>
          </div>
        </div>
      </div>'
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

Mage::log('-- End Jumbobag_Core data upgrade 1.3.2 --');
