<?php

/**
 * Lengow sync api controller
 *
 * @category    Lengow
 * @package     Lengow_Export
 * @author      Team Connector <team-connector@lengow.com>
 * @copyright   2016 Lengow SAS
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Lengow_Sync_ApiController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->getResponse()->setBody('Please specify an action');
    }

    public function checkAction()
    {
        $_helper_export = Mage::helper('lenexport/security');
        $_helper_api = Mage::helper('lensync/api');
        if ($_helper_export->checkIp()) {
            $return = array(
                'magento_version' => Mage::getVersion(),
                'lengow_version' => $_helper_api->getVersion()
            );
            $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($return));
        } else {
            $this->getResponse()->setHeader('HTTP/1.1', '403 Forbidden');
            $this->getResponse()->setBody(
                Mage::helper('lenexport')->__('Unauthorised IP : %s', $_SERVER['REMOTE_ADDR'])
            );
        }
    }
}
