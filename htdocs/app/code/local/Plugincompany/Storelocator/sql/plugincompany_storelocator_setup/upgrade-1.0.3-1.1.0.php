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
    ->newTable($this->getTable('plugincompany_storelocator/storelocationeav'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Entity ID')
    ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Entity Type ID')
    ->addColumn('attribute_set_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'default'   => '0',
        ), 'Attribute Set ID')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Creation Time')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Update Time')
    ->addIndex($this->getIdxName('plugincompany_storelocator/storelocationeav', array('entity_type_id')),
        array('entity_type_id'))
    ->addIndex($this->getIdxName('plugincompany_storelocator/storelocationeav', array('attribute_set_id')),
        array('attribute_set_id'))
    ->addForeignKey($this->getFkName('plugincompany_storelocator/storelocationeav', 'entity_type_id', 'eav/entity_type', 'entity_type_id'),
        'entity_type_id', $this->getTable('eav/entity_type'), 'entity_type_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Store Location EAV Table');
$this->getConnection()->createTable($table);

$storelocationeavEav = array();
$storelocationeavEav['int'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_INTEGER,
    'length'    => null,
    'comment'   => 'Store Location EAV Datetime Attribute Backend Table'
);

$storelocationeavEav['varchar'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => 255,
    'comment'   => 'Store Location EAV Varchar Attribute Backend Table'
);

$storelocationeavEav['text'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'length'    => '64k',
    'comment'   => 'Store Location EAV Text Attribute Backend Table'
);

$storelocationeavEav['datetime'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_DATETIME,
    'length'    => null,
    'comment'   => 'Store Location EAV Datetime Attribute Backend Table'
);

$storelocationeavEav['decimal'] = array(
    'type'      => Varien_Db_Ddl_Table::TYPE_DECIMAL,
    'length'    => '12,4',
    'comment'   => 'Store Location EAV Datetime Attribute Backend Table'
);

foreach ($storelocationeavEav as $type => $options) {
    $table = $this->getConnection()
        ->newTable($this->getTable(array('plugincompany_storelocator/storelocationeav', $type)))
        ->addColumn('value_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
            ), 'Value ID')
        ->addColumn('entity_type_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
            ), 'Entity Type ID')
        ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
            ), 'Attribute ID')
        ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
            ), 'Store ID')
        ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
            ), 'Entity ID')
        ->addColumn('value', $options['type'], $options['length'], array(
            ), 'Value')
        ->addIndex(
            $this->getIdxName(
                array('plugincompany_storelocator/storelocationeav', $type),
                array('entity_id', 'attribute_id', 'store_id'),
                Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
            ),
            array('entity_id', 'attribute_id', 'store_id'),
            array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE))
        ->addIndex($this->getIdxName(array('plugincompany_storelocator/storelocationeav', $type), array('store_id')),
            array('store_id'))
        ->addIndex($this->getIdxName(array('plugincompany_storelocator/storelocationeav', $type), array('entity_id')),
            array('entity_id'))
        ->addIndex($this->getIdxName(array('plugincompany_storelocator/storelocationeav', $type), array('attribute_id')),
            array('attribute_id'))
        ->addForeignKey(
            $this->getFkName(
                array('plugincompany_storelocator/storelocationeav', $type),
                'attribute_id',
                'eav/attribute',
                'attribute_id'
            ),
            'attribute_id', $this->getTable('eav/attribute'), 'attribute_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->addForeignKey(
            $this->getFkName(
                array('plugincompany_storelocator/storelocationeav', $type),
                'entity_id',
                'plugincompany_storelocator/storelocationeav',
                'entity_id'
            ),
            'entity_id', $this->getTable('plugincompany_storelocator/storelocationeav'), 'entity_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->addForeignKey($this->getFkName(array('plugincompany_storelocator/storelocationeav', $type), 'store_id', 'core/store', 'store_id'),
            'store_id', $this->getTable('core/store'), 'store_id',
            Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
        ->setComment($options['comment']);
    $this->getConnection()->createTable($table);
}
$table = $this->getConnection()
    ->newTable($this->getTable('plugincompany_storelocator/eav_attribute'))
    ->addColumn('attribute_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Attribute ID')
    ->addColumn('is_global', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Attribute scope')
    ->addColumn('position', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Attribute position')
    ->addColumn('is_wysiwyg_enabled', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Attribute uses WYSIWYG')
    ->addColumn('is_visible', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Attribute is visible')
    ->addColumn('in_finder', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Attribute is filterable in finder')
    ->addColumn('in_store_page', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(), 'Attribute is visible in store page')
    ->setComment('Storelocator attribute table');
$this->getConnection()->createTable($table);

$this->installEntities();

$this->endSetup();
