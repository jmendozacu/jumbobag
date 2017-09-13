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
 * Store location front contrller
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Js_TemplateController
    extends Mage_Core_Controller_Front_Action {

    public function loadInfowindowAction(){
        $this->loadLayout()
            ->getLayout()->getBlock('root')
            ->setTemplate('plugincompany_storelocator/storelocation/js/templates/infowindow-description.phtml');
        $this->renderLayout();
    }

    public function loadKmlInfowindowAction(){
        $this->loadLayout()
            ->getLayout()->getBlock('root')
            ->setTemplate('plugincompany_storelocator/storelocation/js/templates/kml-infowindow-description.phtml');
        $this->renderLayout();
    }

    public function loadLocationListAction(){
        $this->loadLayout()
            ->getLayout()->getBlock('root')
            ->setTemplate('plugincompany_storelocator/storelocation/js/templates/location-list-description.phtml');
        $this->renderLayout();
    }

    public function loadKmlLocationListAction(){
        $this->loadLayout()
            ->getLayout()->getBlock('root')
            ->setTemplate('plugincompany_storelocator/storelocation/js/templates/kml-location-list-description.phtml');
        $this->renderLayout();
    }
}
