<?php
/**
 *
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
 *
 */
class Plugincompany_Storelocator_Block_Adminhtml_Catalog_Product_Tab_Storelocation_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    protected $_preset;

    public function __construct()
    {
        parent::__construct();

        $this->setId('store_location_grid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }

    public function getProduct()
    {
        if(Mage::registry('current_product')){
            return Mage::registry('current_product');
        }
        $productId = $this->getRequest()->getParam('id');
        $params = $this->getRequest()->getParams();
        return Mage::getModel('catalog/product')->load($productId);
    }

    protected function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in preset flag
        if ($column->getId() == 'in_preset') {
            $locationIds = $this->_getSelectedLocations();
            if (empty($locationIds)) {
                $locationIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$locationIds));
            }
            elseif(!empty($locationIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$locationIds));
            }
        }
        else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }

    protected function _prepareCollection()
    {
        //$this->setDefaultFilter(array('is_connected'=>1));
        $collection = Mage::getModel('plugincompany_storelocator/storelocation')
                        ->getCollection()
                        ->addFieldToSelect('*');
        
        if($this->getRequest()->getParam('store')){
            $collection->addStoreFilter((int)$this->getRequest()->getParam('store'));
        }

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('in_preset', array(
            'header_css_class' => 'a-center',
            'type'      => 'checkbox',
            'name'      => 'in_preset',
            'values'    => $this->_getSelectedLocations(),
            'align'     => 'center',
            'index'     => 'entity_id'
        ));
        
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

        $this->addColumn('address', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('Address line 1'),
            'index' => 'address',
            'type'=> 'text',

        ));

        $this->addColumn('city', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('City'),
            'index' => 'city',
            'type'=> 'text',

        ));

        $this->addColumn('postal', array(
            'header'=> Mage::helper('plugincompany_storelocator')->__('Postal code'),
            'index' => 'postal',
            'type'=> 'text',

        ));

        $this->addColumn('url_key', array(
            'header' => Mage::helper('plugincompany_storelocator')->__('URL key'),
            'index'  => 'url_key',
        ));

        /*
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
         * 
         */
        
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
        
        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/storelocator_storelocation/storeLocationGrid', array('_current'=>true));
    }

    protected function _getSelectedLocations()
    {
        $products = $this->getRequest()->getPost('product_storelocations');
        
        if (empty($products)) {
            $products = $this->_getOriginalItems();
        }

        return $products;
    }


    protected function _getOriginalItems()
    {
        $collection = Mage::getModel('plugincompany_storelocator/storelocationproduct')->getCollection()
                    ->addFieldToSelect('storelocation_id')
                    ->addFilter('product_id', (int)$this->getRequest()->getParam('id'));
        
        if($this->getRequest()->getParam('store')){
            $collection->addFilter('store_id', (int)$this->getRequest()->getParam('store'));
        }

        return $collection->getColumnValues('storelocation_id');
    }

    public function getSelectedProductsStoreLocation(){
        return $this->_getOriginalItems();
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
