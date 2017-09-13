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
class Plugincompany_Storelocator_Model_Storelocation
    extends Mage_Core_Model_Abstract {
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'plugincompany_storelocator_storelocation';
    const CACHE_TAG = 'plugincompany_storelocator_storelocation';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'plugincompany_storelocator_storelocation';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'storelocation';
    /**
     * constructor
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function _construct(){
        parent::_construct();
        $this->_init('plugincompany_storelocator/storelocation');
    }
    
    /**
     * load store ids
     * @access public
     * @return Plugincompany_Storelocator_Model_Storelocation
     * @author Milan Simek
     */
    public function getStoreIds(){
        return $this->_getResource()->getStoreIds($this);
    }
    
    /**
     * before save store location
     * @access protected
     * @return Plugincompany_Storelocator_Model_Storelocation
     * @author Milan Simek
     */
    protected function _beforeSave(){
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()){
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);

        $eavModel = Mage::getModel('plugincompany_storelocator/storelocationeav');
        $data = $this->getData();
        unset($data['entity_id']);
        unset($data['attribute_set_id']);
        unset($data['stores']);
        unset($data['entity_type_id']);
        unset($data['status']);
        unset($data['store_id']);
        foreach($data as $k => $v){
            if(is_array($v)){
                $data[$k] = implode(',',$v);
            }
        }
        $eavModel
            ->setId($this->getId())
            ->addData($data)
            ->save();

        return $this;
    }
    /**
     * get the url to the store location details page
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getStorelocationUrl(){
        if ($this->getUrlKey()){
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('plugincompany_storelocator/storelocation/url_prefix')){
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('plugincompany_storelocator/storelocation/url_suffix')){
                $urlKey .= $suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('plugincompany_storelocator/storelocation/view', array('id'=>$this->getId()));
    }
    /**
     * check URL key
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author Milan Simek
     */
    public function checkUrlKey($urlKey, $active = true){
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    /**
     * get the storelocation Store description
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getDescription(){
        $description = $this->getData('description');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($description);
        return $html;
    }

    /**
     * save storelocation relation
     * @access public
     * @return Plugincompany_Storelocator_Model_Storelocation
     * @author Milan Simek
     */
    protected function _afterSave() {
        return parent::_afterSave();
    }

    protected function _afterLoad() {
        $eavModel = Mage::getModel('plugincompany_storelocator/storelocationeav')->load($this->getId());
        $this
            ->addData($eavModel->getData());

        return parent::_afterLoad();
    }
    /**
     * check if comments are allowed
     * @access public
     * @return array
     * @author Milan Simek
     */
    public function getAllowComments() {
        if ($this->getData('allow_comment') == Plugincompany_Storelocator_Model_Adminhtml_Source_Yesnodefault::NO) {
            return false;
        }
        if ($this->getData('allow_comment') == Plugincompany_Storelocator_Model_Adminhtml_Source_Yesnodefault::YES) {
            return true;
        }
        return Mage::getStoreConfigFlag('plugincompany_storelocator/storepage/allow_comment');
    }

    /**
     * check whether image or map should be shown in store list
     * @access public
     * @return array
     * @author Milan Simek
     */
    public function getUseImageNotMap() {
        if ($this->getData('use_image_not_map') == Plugincompany_Storelocator_Model_Adminhtml_Source_Yesnodefault::NO) {
            return false;
        }
        if ($this->getData('use_image_not_map') == Plugincompany_Storelocator_Model_Adminhtml_Source_Yesnodefault::YES) {
            return true;
        }
        return Mage::getStoreConfigFlag('plugincompany_storelocator/storelist/use_image_not_map');
    }

    /**
     * get default values
     * @access public
     * @return array
     * @author Milan Simek
     */
    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        $values['allow_comment'] = Plugincompany_Storelocator_Model_Adminhtml_Source_Yesnodefault::USE_DEFAULT;
        $values['use_image_not_map'] = Plugincompany_Storelocator_Model_Adminhtml_Source_Yesnodefault::USE_DEFAULT;
        $values['sort_order'] = 0;
        $values['show_in_finder'] = 1;
        $values['show_in_list'] = 1;
        return $values;
    }

    public function getCustomAttributes(){
        $entity = Mage::getModel('eav/entity_type')->load('plugincompany_storelocator_storelocationeav', 'entity_type_code');
        $attributes = Mage::getResourceModel('eav/entity_attribute_collection')
            ->setEntityTypeFilter($entity->getEntityTypeId());
        $attributes->getSelect()->order('additional_table.position', 'DESC');
        
        $helper = Mage::helper('plugincompany_storelocator/storelocationeav');
        $result = array();
        foreach($attributes as $attr){
            if(!$helper->isAttrEnabled($attr)) continue;
            if($val = $attr->getFrontend()->getValue($this)){
                $result[$attr->getStoreLabel()] = $val;
            }
        }
        if(empty($result)){
            $result = false;
        }
        return $result;
    }

    public function getFlatAttributeOptions($code){
        switch($code){
            case 'allow_comment':
            case 'use_image_not_map':
                 $options = Mage::getModel('plugincompany_storelocator/adminhtml_source_yesnodefault')->toOptionArray();
                break;
            case 'status':
                $options = array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('plugincompany_storelocator')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('plugincompany_storelocator')->__('Disabled'),
                    ),
                );
                break;
            case 'show_in_list':
            case 'show_in_finder':
                $options = array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('plugincompany_storelocator')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('plugincompany_storelocator')->__('No'),
                    )
                );
                break;
            default:
                $options = false;
        }
        return $options;
    }

    /**
     * Get average store location rating
     * Based on comments  / customer ratings
     *
     * @return float
     */
    public function getAverageRating(){
        return Mage::getSingleton('plugincompany_storelocator/rating')->getLocationRating($this->getId());
    }
}
