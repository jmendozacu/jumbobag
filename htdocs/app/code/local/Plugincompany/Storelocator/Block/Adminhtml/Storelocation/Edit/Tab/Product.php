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
 * store selection tab
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Block_Adminhtml_Storelocation_Edit_Tab_Product
    extends Mage_Adminhtml_Block_Widget_Grid {

        public function __construct()
        {
            parent::__construct();
            $this->setId('store_location_products');
            $this->setDefaultSort('entity_id');
            $this->setUseAjax(true);
        }


        protected function _addColumnFilterToCollection($column)
        {
            // Set custom filter for in preset flag
            if ($column->getId() == 'in_preset') {
                $productIds = $this->_getSelectedProducts();
                if (empty($productIds)) {
                    $productIds = 0;
                }
                if ($column->getFilter()->getValue()) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$productIds));
                }
                elseif(!empty($productIds)) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$productIds));
                }
            }
            else {
                parent::_addColumnFilterToCollection($column);
            }
            return $this;
        }

        protected function _prepareCollection()
        {
            $collection = Mage::getModel('catalog/product')->getCollection()
                    ->addAttributeToSelect('name')
                    ->addAttributeToSelect('sku')
                    ->addAttributeToSelect('price');
            
            if($this->getRequest()->getParam('store')){
                $collection->addFilter('store_id', (int)$this->getRequest()->getParam('store'));
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
                'values'    => $this->_getSelectedProducts(),
                'align'     => 'center',
                'index'     => 'entity_id'
            ));
            
            $this->addColumn('entity_id', array(
                'header'    => Mage::helper('catalog')->__('ID'),
                'sortable'  => true,
                'width'     => '60',
                'index'     => 'entity_id'
            ));
            
            $this->addColumn('name', array(
                'header'    => Mage::helper('catalog')->__('Name'),
                'index'     => 'name'
            ));
            
            $this->addColumn('sku', array(
                'header'    => Mage::helper('catalog')->__('SKU'),
                'width'     => '80',
                'index'     => 'sku'
            ));

            $this->addColumn('type', array(
                'header'    => Mage::helper('catalog')->__('Type'),
                'width'     => 100,
                'index'     => 'type_id',
                'type'      => 'options',
                'options'   => Mage::getSingleton('catalog/product_type')->getOptionArray(),
            ));

            $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
                ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
                ->load()
                ->toOptionHash();

            $this->addColumn('set_name', array(
                'header'    => Mage::helper('catalog')->__('Attrib. Set Name'),
                'width'     => 130,
                'index'     => 'attribute_set_id',
                'type'      => 'options',
                'options'   => $sets,
            ));

            $this->addColumn('status', array(
                'header'    => Mage::helper('catalog')->__('Status'),
                'width'     => 90,
                'index'     => 'status',
                'type'      => 'options',
                'options'   => Mage::getSingleton('catalog/product_status')->getOptionArray(),
            ));

            $this->addColumn('visibility', array(
                'header'    => Mage::helper('catalog')->__('Visibility'),
                'width'     => 90,
                'index'     => 'visibility',
                'type'      => 'options',
                'options'   => Mage::getSingleton('catalog/product_visibility')->getOptionArray(),
            ));

            $this->addColumn('sku', array(
                'header'    => Mage::helper('catalog')->__('SKU'),
                'width'     => 80,
                'index'     => 'sku'
            ));

            $this->addColumn('price', array(
                'header'        => Mage::helper('catalog')->__('Price'),
                'type'          => 'currency',
                'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
                'index'         => 'price'
            ));

            return parent::_prepareColumns();
        }

        public function getGridUrl()
        {
            return $this->getUrl('*/*/storeProductGrid', array('_current'=>true));
        }

        protected function _getSelectedProducts()
        { 
            $products = $this->getStorelocationProductsGridAction();
            
            if (empty($products)) {
                $products = $this->_getOriginalProducts();
            }

            return $products;
        }


        protected function _getOriginalProducts()
        {
            $collection = Mage::getModel('plugincompany_storelocator/storelocationproduct')->getCollection()
                    ->addFieldToSelect('product_id')
                    ->addFilter('storelocation_id', (int)$this->getRequest()->getParam('id'));
         
            return $collection->getColumnValues('product_id');
        }
        
        
        public function getSelectedStoreLocationProducts(){
            return $this->_getOriginalProducts();
        }
}
