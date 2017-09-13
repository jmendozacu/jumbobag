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
class Plugincompany_Storelocator_Model_Cache_Core {

    const CACHE_ID_PREFIX = 'plugincompany_storelocator_json_';
    const CACHE_TAG_GROUP = 'plugincompany_storelocator_json';
    
    private $_cacheKey = null;

    private $_store = NULL;
    private $_lifeTime = false;
    private $_cache = null;

    /**
     * Constructor
     */
    public function __construct() {
        $this->_initCache();
    }

    /**
     * Load istance
     */
    public function _initCache() {
        $this->_cache = Mage::app()->getCacheInstance();
        return $this;
    }
    
    /**
     * Get Cache Id
     * @return string
     */
    public function getCacheId() {
        $this->_store = $this->_store > 0 ? $this->_store : Mage::app()->getStore()->getStoreId();
        
        if(!$this->_cacheKey){
            $this->_cacheKey = $this->_store;
        }

        return self::CACHE_ID_PREFIX . $this->_cacheKey;
    }
    
    /**
     * set Cache key
     * @return $this
     */
    public function setCacheKey($key){
        $this->_cacheKey = $key;
        return $this;
    }

    /**
     * Set Store Id
     * @param   int $id
     * @return Plugincompany_Storelocator_Model_Cache_Core
     */
    public function setStoreId($store) {
        $this->_store = (int) $store;
        return $this;
    }

    public function getLifeTime() {
        $lifetime = Mage::getStoreConfig('plugincompany_storelocator/cache/lifetime');
        $this->_lifeTime = ($lifetime > 0) ? $lifetime : $this->_lifeTime;
        return $this->_lifeTime;
    }

    /**
     * Retrieve cache object
     *
     * @return bool | string
     */
    public function getCache() {
        if (!$this->_cache) {
            $this->_initCache();
        }

        if (false !== ($data = $this->loadCache($this->getCacheId()))) {
            $data = unserialize($data);
        } else {
            return false;
        }

        return $data;
    }

    /**
     * Loading cache data
     *
     * @param   string $id
     * @return  mixed
     */
    public function loadCache($id) {
        return $this->_cache->load($id);
    }

    /**
     * Saving cache data
     *
     * @param   mixed $data
     * @param   array $lifeTime
     * @return  Mage_Core_Model_App
     */
    public function saveCache($data) {
        $this->_cache->save(serialize($data), $this->getCacheId(), array(self::CACHE_TAG_GROUP), $this->getLifeTime());
        return $this;
    }

    /**
     * Remove cache
     *
     * @param   string $id
     * @return  Mage_Core_Model_App
     */
    public function removeCache($id) {
        $this->setStoreId($id);

        $this->_cache->remove($this->getCacheId());
        return $this;
    }

    /**
     * Cleaning cache
     *
     * @param   array $tags
     * @return  Mage_Core_Model_App
     */
    public function cleanCache($tags = array()) {
        $this->_cache->clean($tags);
        return $this;
    }

}
