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
 * Store location comments admin grid block
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Comment_Grid
    extends Mage_Adminhtml_Block_Widget_Grid {
    /**
     * constructor
     * @access public
     * @author Milan Simek
     */
    public function __construct(){
        parent::__construct();
        $this->setId('storelocationCommentGrid');
        $this->setDefaultSort('ct_comment_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    /**
     * prepare collection
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Comment_Grid
     * @author Milan Simek
     */
    protected function _prepareCollection(){
        $collection = Mage::getResourceModel('plugincompany_storelocator/storelocation_comment_storelocation_collection');
        $collection->addStoreData();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * prepare grid collection
     * @access protected
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Comment_Grid
     * @author Milan Simek
     */
    protected function _prepareColumns(){
        $this->addColumn('ct_comment_id', array(
            'header'        => Mage::helper('plugincompany_storelocator')->__('Id'),
            'index'         => 'ct_comment_id',
            'type'          => 'number',
            'filter_index'  => 'ct.comment_id',
        ));
        $this->addColumn('locname', array(
            'header'        => Mage::helper('plugincompany_storelocator')->__('Store name'),
            'index'         => 'locname',
            'filter_index'  => 'main_table.locname',
        ));
        $this->addColumn('rating', array(
            'header'        => Mage::helper('plugincompany_storelocator')->__('Rating'),
            'index'         => 'rating',
            'type'          => 'number',
            
        ));
        $this->addColumn('ct_title', array(
            'header'        => Mage::helper('plugincompany_storelocator')->__('Comment title'),
            'index'         => 'ct_title',
            'filter_index'  => 'ct.title',
        ));
        $this->addColumn('ct_name', array(
            'header'        => Mage::helper('plugincompany_storelocator')->__('Submitter name'),
            'index'         => 'ct_name',
            'filter_index'  => 'ct.name',
        ));
        $this->addColumn('ct_email', array(
            'header'        => Mage::helper('plugincompany_storelocator')->__('Submitter e-mail'),
            'index'         => 'ct_email',
            'filter_index'  => 'ct.email',
        ));
        $this->addColumn('ct_status', array(
            'header'        => Mage::helper('plugincompany_storelocator')->__('Status'),
            'index'         => 'ct_status',
            'filter_index'  => 'ct.status',
            'type'          => 'options',
            'options'       => array(
                    Plugincompany_Storelocator_Model_Storelocation_Comment::STATUS_PENDING  => Mage::helper('plugincompany_storelocator')->__('Pending'),
                    Plugincompany_Storelocator_Model_Storelocation_Comment::STATUS_APPROVED => Mage::helper('plugincompany_storelocator')->__('Approved'),
                    Plugincompany_Storelocator_Model_Storelocation_Comment::STATUS_REJECTED => Mage::helper('plugincompany_storelocator')->__('Rejected'),
            )
        ));
        $this->addColumn('ct_created_at', array(
            'header'        => Mage::helper('plugincompany_storelocator')->__('Created on'),
            'index'         => 'ct_created_at',
            'width'         => '120px',
            'type'          => 'datetime',
            'filter_index'  => 'ct.created_at',
        ));
        $this->addColumn('ct_updated_at', array(
            'header'        => Mage::helper('plugincompany_storelocator')->__('Updated on'),
            'index'         => 'ct_updated_at',
            'width'         => '120px',
            'type'          => 'datetime',
            'filter_index'  => 'ct.updated_at',
        ));
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn('stores', array(
                'header'=> Mage::helper('plugincompany_storelocator')->__('Store views'),
                'index' => 'stores',
                'type'  => 'store',
                'store_all' => true,
                'store_view'=> true,
                'sortable'  => false,
                'filter_condition_callback'=> array($this, '_filterStoreCondition'),
            ));
        }
        $this->addColumn('action',
            array(
                'header'=>  Mage::helper('plugincompany_storelocator')->__('Action'),
                'width' => '100',
                'type'  => 'action',
                'getter'=> 'getCtCommentId',
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
        $this->setMassactionIdField('ct_comment_id');
        $this->setMassactionIdFilter('ct.comment_id');
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->getMassactionBlock()->setFormFieldName('comment');
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
                            Plugincompany_Storelocator_Model_Storelocation_Comment::STATUS_PENDING  => Mage::helper('plugincompany_storelocator')->__('Pending'),
                            Plugincompany_Storelocator_Model_Storelocation_Comment::STATUS_APPROVED => Mage::helper('plugincompany_storelocator')->__('Approved'),
                            Plugincompany_Storelocator_Model_Storelocation_Comment::STATUS_REJECTED => Mage::helper('plugincompany_storelocator')->__('Rejected'),
                        )
                )
            )
        ));
        return $this;
    }
    /**
     * get the row url
     * @access public
     * @param Plugincompany_Storelocator_Model_Storelocation_Comment
     * @return string
     * @author Milan Simek
     */
    public function getRowUrl($row){
        return $this->getUrl('*/*/edit', array('id' => $row->getCtCommentId()));
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
     * filter store column
     * @access protected
     * @param Plugincompany_Storelocator_Model_Resource_Storelocation_Comment_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Comment_Grid
     * @author Milan Simek
     */
    protected function _filterStoreCondition($collection, $column){
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->setStoreFilter($value);
        return $this;
    }
}
