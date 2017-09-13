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
 * Store location admin grid block
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Grid
    extends Mage_Adminhtml_Block_Widget_Grid {
    /**
     * constructor
     * @access public
     * @author Milan Simek
     */
    public function __construct(){
        parent::__construct();
        $this->setId('storelocationGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    /**
     * prepare collection
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Grid
     * @author Milan Simek
     */
    protected function _prepareCollection(){
        $collection = Mage::getModel('plugincompany_storelocator/storelocation')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * prepare grid collection
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Grid
     * @author Milan Simek
     */
    protected function _prepareColumns(){
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('plugincompany_storelocator')->__('Id'),
            'index'        => 'entity_id',
            'type'        => 'number'
        ));
        $this->addColumn('locname', array(
            'header'    => Mage::helper('plugincompany_storelocator')->__('Store name'),
            'align'     => 'left',
            'index'     => 'locname',
        ));
        $this->addColumn('status', array(
            'header'    => Mage::helper('plugincompany_storelocator')->__('Status'),
            'index'        => 'status',
            'type'        => 'options',
            'options'    => array(
                '1' => Mage::helper('plugincompany_storelocator')->__('Enabled'),
                '0' => Mage::helper('plugincompany_storelocator')->__('Disabled'),
            )
        ));
  /*      $this->addColumn('lat', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('Latitude'),
            'index' => 'lat',
            'type'=> 'text',

        ));
        $this->addColumn('lng', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('Longitude'),
            'index' => 'lng',
            'type'=> 'text',

        ));*/
        $this->addColumn('address', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('Address line 1'),
            'index' => 'address',
            'type'=> 'text',

        ));
 /*       $this->addColumn('address2', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('Address Line 2'),
            'index' => 'address2',
            'type'=> 'text',

        ));*/
        $this->addColumn('city', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('City'),
            'index' => 'city',
            'type'=> 'text',

        ));
/*        $this->addColumn('state', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('State'),
            'index' => 'state',
            'type'=> 'text',

        ));*/
        $this->addColumn('postal', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('Postal code'),
            'index' => 'postal',
            'type'=> 'text',

        ));
/*        $this->addColumn('phone', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('Phone'),
            'index' => 'phone',
            'type'=> 'text',

        ));
        $this->addColumn('web', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('Website'),
            'index' => 'web',
            'type'=> 'text',

        ));*/
/*        $this->addColumn('hours1', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('Opening hours line 1'),
            'index' => 'hours1',
            'type'=> 'text',

        ));
        $this->addColumn('hours2', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('Opening hours line 2'),
            'index' => 'hours2',
            'type'=> 'text',

        ));
        $this->addColumn('hours3', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('Opening hours line 3'),
            'index' => 'hours3',
            'type'=> 'text',

        ));*/
        $this->addColumn('url_key', array(
            'header' => Mage::helper('plugincompany_storelocator')->__('URL key'),
            'index'  => 'url_key',
        ));

        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn('store_id', array(
                'header'=> Mage::helper('plugincompany_storelocator')->__('Store views'),
                'index' => 'store_id',
                'type'  => 'store',
                'store_all' => true,
                'store_view'=> true,
                'sortable'  => false,
                'filter_condition_callback'=> array($this, '_filterStoreCondition'),
            ));
        }

        $this->addColumn('sort_order', array(
            'header' => Mage::helper('plugincompany_storelocator')->__('Sort order'),
            'index'  => 'sort_order',
            'type'   => 'number'
        ));

        $this->addColumn('created_at', array(
            'header'    => Mage::helper('plugincompany_storelocator')->__('Created on'),
            'index'     => 'created_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('plugincompany_storelocator')->__('Updated on'),
            'index'     => 'updated_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('action',
            array(
                'header'=>  Mage::helper('plugincompany_storelocator')->__('Action'),
                'width' => '100',
                'type'  => 'action',
                'getter'=> 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('plugincompany_storelocator')->__('Edit'),
                        'url'   => array('base'=> '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter'=> false,
                'is_system'    => true,
                'sortable'  => false,
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('plugincompany_storelocator')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('plugincompany_storelocator')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('plugincompany_storelocator')->__('XML'));
        return parent::_prepareColumns();
    }
    /**
     * prepare mass action
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Grid
     * @author Milan Simek
     */
    protected function _prepareMassaction(){
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('storelocation');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('plugincompany_storelocator')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('plugincompany_storelocator')->__('Are you sure?')
        ));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('plugincompany_storelocator')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'status' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('plugincompany_storelocator')->__('Status'),
                        'values' => array(
                                '1' => Mage::helper('plugincompany_storelocator')->__('Enabled'),
                                '0' => Mage::helper('plugincompany_storelocator')->__('Disabled'),
                        )
                )
            )
        ));
        return $this;
    }
    /**
     * get the row url
     * @access public
     * @param Plugincompany_Storelocator_Model_Storelocation
     * @return string
     * @author Milan Simek
     */
    public function getRowUrl($row){
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    /**
     * get the grid url
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getGridUrl(){
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    /**
     * after collection load
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Grid
     * @author Milan Simek
     */
    protected function _afterLoadCollection(){
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
    /**
     * filter store column
     * @access protected
     * @param Plugincompany_Storelocator_Model_Resource_Storelocation_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Grid
     * @author Milan Simek
     */
    protected function _filterStoreCondition($collection, $column){
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
