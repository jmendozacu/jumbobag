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
 * Store location model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Rating
    extends Mage_Core_Model_Abstract {

    
     /**
     * Look up rating by location Id
     * @return float
     * @author Milan Simek
     */
    public function getLocationRating($locationId, $storeId = null){
        $locations = $this->loadCollectionArray($storeId);
        
        return (is_array($locations) && array_key_exists($locationId, $locations)) ? $locations[$locationId] : 0;
    }
    
    /**
     * Load rating collection and return array(location_id => avg_rating) 
     * @return Array
     * @author Milan Simek
     */
    public function loadCollectionArray($storeId = null){
        if(!is_int($storeId)){
            $storeId = Mage::app()->getStore()->getStoreId();
        }
        
        if(!$this->getLocationRatingCache()){
            $storeCommentTable = Mage::getSingleton('core/resource')->getTableName('plugincompany_storelocator/storelocation_comment_store'); 
            $collection = Mage::getResourceModel('plugincompany_storelocator/storelocation_comment_collection');
            $collection->addFieldToSelect(array('storelocation_id'));
            $collection->addFieldToFilter('rating', array('gt' => 0));
            $collection->addFieldToFilter('status', array('eq' => 1));

            $collection->getSelect()
                    ->reset(Zend_Db_Select::COLUMNS)
                    ->joinLeft( array('store_comment'=> $storeCommentTable), 'store_comment.comment_id = main_table.comment_id', NULL)
                    ->columns(array('avg_rating' => new Zend_Db_Expr ('avg(rating)'), 'storelocation_id'))
                    ->group('storelocation_id');
                        
            $collection->addFieldToFilter('store_comment.store_id', array('eq' => $storeId));

            $locationRating = array();
            foreach($collection as $location){
                $locationRating[$location->getStorelocationId()] = round($location->getAvgRating(), 2);
            }
            
           $this->setLocationRatingCache($locationRating);
        }
        
        return $this->getLocationRatingCache();
    }
}
