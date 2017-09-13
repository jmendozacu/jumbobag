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
class Plugincompany_Storelocator_Block_Page_Html_Pager extends Mage_Page_Block_Html_Pager
{

    /**
     * Get pager url for frontend list pages
     *
     * @param array $params
     * @return string
     */
    public function getPagerUrl($params=array())
    {
        $paramString = http_build_query($params);

        $storeLocation = Mage::registry('current_storelocation');
        $url = $storeLocation->getStorelocationUrl() . '?' . $paramString;
        return $url;
    }

}