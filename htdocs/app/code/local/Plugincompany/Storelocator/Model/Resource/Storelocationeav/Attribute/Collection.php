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
 * Store Location EAV attribute collection model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Resource_Storelocationeav_Attribute_Collection
    extends Mage_Eav_Model_Resource_Entity_Attribute_Collection {
	/**
	 * init attribute select
	 * @access protected
	 * @return Plugincompany_Storelocator_Model_Resource_Storelocationeav_Attribute_Collection
	 * @author Milan Simek
	 */
	protected function _initSelect() {
        $this->getSelect()->from(array('main_table' => $this->getResource()->getMainTable()))
            ->where('main_table.entity_type_id=?', Mage::getModel('eav/entity')->setType('plugincompany_storelocator_storelocationeav')->getTypeId())
            ->join(
                array('additional_table' => $this->getTable('plugincompany_storelocator/eav_attribute')),
                'additional_table.attribute_id=main_table.attribute_id'
            );
        return $this;
    }
    /**
     * set entity type filter
     * @access public
     * @param string $typeId
     * @return Plugincompany_Storelocator_Model_Resource_Storelocationeav_Attribute_Collection
     * @author Milan Simek
     */
 	public function setEntityTypeFilter($typeId) {
        return $this;
    }
	/**
     * Specify filter by "is_visible" field
     * @access public
     * @return Plugincompany_Storelocator_Model_Resource_Storelocationeav_Attribute_Collection
     * @author Milan Simek
     */
    public function addVisibleFilter() {
        return $this->addFieldToFilter('additional_table.is_visible', 1);
    }
	/**
     * Specify filter by "is_editable" field
     * @access public
     * @return Plugincompany_Storelocator_Model_Resource_Storelocationeav_Attribute_Collection
     * @author Milan Simek
     */
    public function addEditableFilter() {
        return $this->addFieldToFilter('additional_table.is_editable', 1);
    }
}
