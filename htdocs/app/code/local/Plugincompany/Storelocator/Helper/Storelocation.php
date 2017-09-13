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
 * Store location helper
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Helper_Storelocation
    extends Mage_Core_Helper_Abstract {
    /**
     * get the url to the store locations list page
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getStorelocationsUrl(){
        if ($listKey = Mage::getStoreConfig('plugincompany_storelocator/storelist/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('plugincompany_storelocator/storelocation/index');
    }
    /**
     * check if breadcrumbs can be used
     * @access public
     * @return bool
     * @author Milan Simek
     */
    public function getUseBreadcrumbs(){
        return Mage::getStoreConfigFlag('plugincompany_storelocator/storelocation/breadcrumbs');
    }

    public function getStoreFinderUrl()
    {
        $url = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK, Mage::app()->getStore()->isCurrentlySecure());

        $prefix = Mage::getStoreConfig('plugincompany_storelocator/storelocation/url_prefix');
        $finderKey = Mage::getStoreConfig('plugincompany_storelocator/storefinder/url_finder');
        $suffix = Mage::getStoreConfig('plugincompany_storelocator/storelocation/url_suffix');

        if($prefix){
            $url .= $prefix . '/';
        }

        if($finderKey){
            $url .= $finderKey;
        }else{
            $url .= 'store-finder';
        }

        if($suffix){
            $url .= $suffix;
        }

        return $url;
    }

    public function getFormattedAddress(Plugincompany_Storelocator_Model_Storelocation $location, $wrapDiv = false, $html = true)
    {
        $sep = '<br>';
        if(!$html) $sep = "\n";

        $formatter = new Plugincompany_Storelocator_Model_Lib_Adamlc_AddressFormat_Format();
        try{
            $formatter->setLocale($location->getCountry());
        }catch(Exception $e){
            $formatter->setLocale('US');
        }

        try{
            $country = Mage::getModel('directory/country')->loadByCode($location->getCountry())->getName();
        }catch(Exception $e){
            $country = '';
        }

        $formatter['ADMIN_AREA'] = $location->getState();
        $formatter['LOCALITY'] = $location->getCity();
        $formatter['POSTAL_CODE'] = $location->getPostal();

        $address = $location->getAddress();
        if($a2 = $location->getAddress2()){
            $address = $address . $sep . $a2;
        }

        $formatter['STREET_ADDRESS'] = $address;
        $formatter['COUNTRY'] = $country;

        if($wrapDiv){
            $address = '<div>' . implode('</div><div>',explode("\n",$formatter->formatAddress(false))) . '</div>';
            if($location->getState() && !stristr($address,$location->getState())){
                $address .= "<div>{$location->getState()}</div>";
            }
            $address .= "<div>$country</div>";
            return $address;
        }

        $address = $formatter->formatAddress($html);
        if(!stristr($address,$location->getState()) && $location->getState()){
            $address .= $sep . $location->getState();
        }
        $address .= $sep . $country;
        return $address;
    }
    
    public function isStoreInventoryLocatorEnabled()
    {
        return (bool) Mage::getStoreConfig('plugincompany_storelocator/product_detail_page/enable_store_inventory_locator');
    }
    
    public function isManuallyManageInventoryLocatorEnabled()
    {
        return $this->isStoreInventoryLocatorEnabled() 
                && (bool) Mage::getStoreConfig('plugincompany_storelocator/product_detail_page/manually_manage_inventory_locator');
        
    }
    
    public function getProductPageStoreListingUrl(){
        return Mage::getUrl('plugincompany_storelocator/storelocation/productPageStoreListing',
                    array(
                        'store_id' => Mage::app()->getStore()->getStoreId(),
                        'product_id' => Mage::registry('current_product')  ? Mage::registry('current_product')->getId() : 0, 
                    )
                );
    }

    public function getProductPageStoreMapUrl(){
        return Mage::getUrl('plugincompany_storelocator/storelocation/locatorembed',
            array(
                'store_id' => Mage::app()->getStore()->getStoreId(),
                'product_id' => Mage::registry('current_product')  ? Mage::registry('current_product')->getId() : 0,
            )
        );
    }

}
