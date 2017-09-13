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
 * Storelocator module install script
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('plugincompany_storelocator/storelocation_product'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Entity ID')
    ->addColumn('product_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Product ID')
    ->addColumn('storelocation_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Location ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Store ID')
    ->addIndex($this->getIdxName('plugincompany_storelocator/storelocation_product', array('product_id')),
        array('product_id'))
    ->addIndex($this->getIdxName('plugincompany_storelocator/storelocation_product', array('storelocation_id')),
        array('storelocation_id'))
    ->addForeignKey($this->getFkName('plugincompany_storelocator/storelocation_product', 'product_id', 'catalog/product', 'entity_id'),
        'product_id', $this->getTable('catalog/product'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($this->getFkName('plugincompany_storelocator/storelocation_product', 'store_id', 'core/store', 'store_id'),
        'store_id', $this->getTable('core/store'), 'store_id', 
         Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($this->getFkName('plugincompany_storelocator/storelocation_product', 'storelocation_id', 'plugincompany_storelocator/storelocation', 'entity_id'), 
        'storelocation_id', $this->getTable('plugincompany_storelocator/storelocation'), 'entity_id', 
        Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Store Product Location');
$this->getConnection()->createTable($table);


$this->installEntities();

$this->endSetup();
