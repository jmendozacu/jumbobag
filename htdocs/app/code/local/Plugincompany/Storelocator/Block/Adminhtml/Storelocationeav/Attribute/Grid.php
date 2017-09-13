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
 * Store Location EAV attributes grid
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocationeav_Attribute_Grid
    extends Mage_Eav_Block_Adminhtml_Attribute_Grid_Abstract {
    /**
     * Prepare storelocationeav attributes grid collection object
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocationeav_Attribute_Grid
     * @author Milan Simek
     */
    protected function _prepareCollection(){
        $collection = Mage::getResourceModel('plugincompany_storelocator/storelocationeav_attribute_collection')
            ->addVisibleFilter();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare storelocationeav attributes grid columns
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocationeav_Attribute_Grid
     * @author Milan Simek
     */
    protected function _prepareColumns() {

        $this->addColumn('attribute_code', array(
            'header'=>Mage::helper('eav')->__('Attribute Code'),
            'sortable'=>true,
            'index'=>'attribute_code'
        ));

        $this->addColumn('frontend_label', array(
            'header'=>Mage::helper('eav')->__('Attribute Label'),
            'sortable'=>true,
            'index'=>'frontend_label'
        ));


        $this->addColumn('frontend_input', array(
            'header'=>Mage::helper('eav')->__('Input Type'),
            'sortable'=>true,
            'index'=>'frontend_input'
        ));

        $this->addColumn('in_finder', array(
            'header'=>Mage::helper('eav')->__('Show In Finder'),
            'sortable'=>true,
            'index'=>'in_finder',
            'type' => 'options',
            'options' => array('No','Yes')
        ));

        $this->addColumn('in_store_page', array(
            'header'=>Mage::helper('eav')->__('Show In Store Page'),
            'sortable'=>true,
            'index'=>'in_store_page',
            'type' => 'options',
            'options' => array('No','Yes')
        ));

        $this->addColumn('is_required', array(
            'header'=>Mage::helper('eav')->__('Required'),
            'sortable'=>true,
            'index'=>'is_required',
            'type' => 'options',
            'options' => array(
                '1' => Mage::helper('eav')->__('Yes'),
                '0' => Mage::helper('eav')->__('No'),
            ),
            'align' => 'center',
        ));


        $this->addColumn('position', array(
            'header'=>Mage::helper('eav')->__('Sort Order'),
            'sortable'=>true,
            'index'=>'position'
        ));

        return $this;
    }
}
