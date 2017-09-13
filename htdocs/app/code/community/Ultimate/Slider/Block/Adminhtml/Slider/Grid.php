<?php
/**
 * Ultimate_Slider extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Images & Media
 * @package        Ultimate_Slider
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Slider admin grid block
 *
 * @category    Images & Media
 * @package     Ultimate_Slider
 * @author      Rajasingh and Manikandan D
 */
class Ultimate_Slider_Block_Adminhtml_Slider_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('sliderGrid');
        $this->setDefaultSort('slider_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Ultimate_Slider_Block_Adminhtml_Slider_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('ultimate_slider/slider')->getCollection();        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Ultimate_Slider_Block_Adminhtml_Slider_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('slider_id', array(
            'header'    => Mage::helper('ultimate_slider')->__('ID'),
            'width'     => '50px',
            'index'     => 'slider_id',
        ));

        $this->addColumn('title', array(
            'header'    => Mage::helper('ultimate_slider')->__('Slider Title'),
            'index'     => 'title',
        ));

        $this->addColumn('alt_text', array(
            'header'    => Mage::helper('ultimate_slider')->__('Slider Alternate Text'),
            'index'     => 'alt_text',
        ));

        $this->addColumn('image', array(
            'header'    => Mage::helper('ultimate_slider')->__('Slider Media Path'),
            'index'     => 'image',
        ));

        $this->addColumn('video_url', array(
            'header'    => Mage::helper('ultimate_slider')->__('Slider Embed URL Path'),
            'index'     => 'video_url',
        ));

        $this->addColumn('published_at', array(
            'header'   => Mage::helper('ultimate_slider')->__('Published On'),
            'sortable' => true,
            'width'    => '170px',
            'index'    => 'published_at',
            'type'     => 'date',
        ));

        $this->addColumn('created_at', array(
            'header'   => Mage::helper('ultimate_slider')->__('Created'),
            'sortable' => true,
            'width'    => '170px',
            'index'    => 'created_at',
            'type'     => 'datetime',
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('ultimate_slider')->__('Status'),
            'index'     => 'status',
            'type'      => 'options',
            'options'   => array(
                    1 => 'Enabled',
                    2 => 'Disabled',
            ),

        ));


        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('ultimate_slider')->__('Action'),
                'width'   => '100px',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(array(
                        'caption' => Mage::helper('ultimate_slider')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                )),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
                'index'     => 'slider',
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('ultimate_slider')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('ultimate_slider')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('ultimate_slider')->__('XML'));
        return parent::_prepareColumns();
    }    

    /**
     * get the row url
     *
     * @param Ultimate_Slider_Model_Slider
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    # MassAction block declaration 
    # ----------------------------

    /**
     * prepare mass action
     *
     * @access protected
     * @return Ultimate_Slider_Block_Adminhtml_Slider_Grid
     * @author Manikandan D
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('slider_id');
        $this->getMassactionBlock()->setFormFieldName('slider');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('ultimate_slider')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('ultimate_slider')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('ultimate_slider')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('ultimate_slider')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('ultimate_slider')->__('Enabled'),
                            '0' => Mage::helper('ultimate_slider')->__('Disabled'),
                        )
                    )
                )
            )
        );
        return $this;
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Ultimate_Slider_Block_Adminhtml_Slider_Grid
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
