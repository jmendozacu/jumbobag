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
 * Router
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Controller_Router
    extends Mage_Core_Controller_Varien_Router_Abstract {
    /**
     * init routes
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Plugincompany_Storelocator_Controller_Router
     * @author Milan Simek
     */
    public function initControllerRouters($observer){
        $front = $observer->getEvent()->getFront();
        $front->addRouter('plugincompany_storelocator', $this);
        return $this;
    }
    /**
     * Validate and match entities and modify request
     * @access public
     * @param Zend_Controller_Request_Http $request
     * @return bool
     * @author Milan Simek
     */
    public function match(Zend_Controller_Request_Http $request){
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
        if(!Mage::helper('plugincompany_storelocator')->isEnabled()){
            return;
        }
        $urlKey = trim($request->getPathInfo(), '/');
        $check = array();
        $check['storelocation'] = new Varien_Object(array(
            'prefix'        => Mage::getStoreConfig('plugincompany_storelocator/storelocation/url_prefix'),
            'suffix'        => Mage::getStoreConfig('plugincompany_storelocator/storelocation/url_suffix'),
            'list_key'       => Mage::getStoreConfig('plugincompany_storelocator/storelist/url_rewrite_list'),
            'list_action'   => 'index',
            'model'         =>'plugincompany_storelocator/storelocation',
            'controller'    => 'storelocation',
            'action'        => 'view',
            'param'         => 'id',
            'check_path'    => 0
        ));
        foreach ($check as $key=>$settings) {
            if ($settings->getListKey()) {
                if ($urlKey == $settings->getListKey()) {
                    $request->setModuleName('plugincompany_storelocator')
                        ->setControllerName($settings->getController())
                        ->setActionName($settings->getListAction());
                    $request->setAlias(
                        Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                        $urlKey
                    );
                    return true;
                }
            }

            if ($settings['prefix']){
                $parts = explode('/', $urlKey);
                if ($parts[0] != $settings['prefix'] || count($parts) != 2){
                    continue;
                }
                $urlKey = $parts[1];
            }
            if ($settings['suffix']){
                $urlKey = substr($urlKey, 0 , -strlen($settings['suffix']));
            }


            $finderUrl = Mage::getStoreConfig('plugincompany_storelocator/storefinder/url_finder');
            if (!$finderUrl) {
                $finderUrl = 'store-finder';
            }

            if ($urlKey == $finderUrl) {
                $request->setModuleName('plugincompany_storelocator')
                    ->setControllerName($settings->getController())
                    ->setActionName('locator');
                $request->setAlias(
                    Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                    $urlKey
                );
                return true;
            }

            $model = Mage::getModel($settings->getModel());
            $id = $model->checkUrlKey($urlKey, Mage::app()->getStore()->getId());
            if ($id){
                if ($settings->getCheckPath() && !$model->load($id)->getStatusPath()) {
                    continue;
                }
                $request->setModuleName('plugincompany_storelocator')
                    ->setControllerName($settings->getController())
                    ->setActionName($settings->getAction())
                    ->setParam($settings->getParam(), $id);
                $request->setAlias(
                    Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                    $urlKey
                );
                return true;
            }
        }

        return false;
    }
}
