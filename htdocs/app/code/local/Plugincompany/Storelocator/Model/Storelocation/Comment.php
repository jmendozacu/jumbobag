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
 * Store location comment model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Storelocation_Comment
    extends Mage_Core_Model_Abstract {
    const STATUS_PENDING  = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'plugincompany_storelocator_storelocation_comment';
    const CACHE_TAG = 'plugincompany_storelocator_storelocation_comment';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'plugincompany_storelocator_storelocation_comment';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'comment';
    /**
     * constructor
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function _construct(){
        parent::_construct();
        $this->_init('plugincompany_storelocator/storelocation_comment');
    }
    /**
     * before save store location comment
     * @access protected
     * @return Plugincompany_Storelocator_Model_Storelocation_Comment
     * @author Milan Simek
     */
    protected function _beforeSave(){
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()){
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }
    /**
     * validate comment
     * @access public
     * @return array|bool
     * @author Milan Simek
     */
    public function validate() {
        $errors = array();

        if(Mage::helper('plugincompany_storelocator')->isRatingEnable() && $this->getRating() != 0){
            $rating  = new Zend_Validate_Between(1, 5);

            if (!$rating->isValid($this->getRating())) {
                $errors[] = Mage::helper('review')->__('Rating must be between 1 and 5');
            }
        }
        

        if (!Zend_Validate::is($this->getTitle(), 'NotEmpty')) {
            $errors[] = Mage::helper('review')->__('Comment title can\'t be empty');
        }

        if (!Zend_Validate::is($this->getName(), 'NotEmpty')) {
            $errors[] = Mage::helper('review')->__('Your name can\'t be empty');
        }

        if (!Zend_Validate::is($this->getComment(), 'NotEmpty')) {
            $errors[] = Mage::helper('review')->__('Comment can\'t be empty');
        }

        if (empty($errors)) {
            return true;
        }
        return $errors;
    }
}
