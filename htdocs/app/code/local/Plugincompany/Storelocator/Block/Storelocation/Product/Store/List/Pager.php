<?php
/**
 *
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
 *
 */
class Plugincompany_Storelocator_Block_Storelocation_Product_Store_List_Pager extends Mage_Page_Block_Html_Pager {

    public function getPagerUrl($params=array())
    {
        $urlParams = array();
        $urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = false;
        $urlParams['_query']    = $params;
        $urlParams['product_id'] = $this->getRequest()->getParam('id');
        $url = Mage::getUrl('plugincompany_storelocator/storelocation/productPageStoreListing', $urlParams);
        return $url;
    }

}