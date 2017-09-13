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
 * Attribute model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Resource_Attribute
    extends Mage_Eav_Model_Resource_Entity_Attribute {
    /**
     * after saving the attribute
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return  Plugincompany_Storelocator_Model_Resource_Attribute
     * @author Milan Simek
     */
    protected  function _afterSave(Mage_Core_Model_Abstract $object){
        $setup       = Mage::getModel('eav/entity_setup', 'core_write');
        $entityType  = $object->getEntityTypeId();
        $setId       = $setup->getDefaultAttributeSetId($entityType);
        $groupId     = $setup->getDefaultAttributeGroupId($entityType);
        $attributeId = $object->getId();
        $sortOrder   = $object->getPosition();

        $setup->addAttributeToGroup($entityType, $setId, $groupId, $attributeId, $sortOrder);
        return parent::_afterSave($object);
    }
    
}