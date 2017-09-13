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
$this->dropTable($this->getTable('plugincompany_storelocator/storelocation'));
$this->dropTable($this->getTable('plugincompany_storelocator/storelocation_store'));
$this->dropTable($this->getTable('plugincompany_storelocator/storelocation_comment'));
$this->dropTable($this->getTable('plugincompany_storelocator/storelocation_comment_store'));
$table = $this->getConnection()
    ->newTable($this->getTable('plugincompany_storelocator/storelocation'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store location ID')
    ->addColumn('locname', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Location name')

    ->addColumn('lat', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Latitude')

    ->addColumn('lng', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Longitude')

    ->addColumn('address', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Address line 1')

    ->addColumn('address2', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Address Line 2')

    ->addColumn('city', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'City')

    ->addColumn('state', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'State')

    ->addColumn('country', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'country')

    ->addColumn('postal', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Postal code / zip code')

    ->addColumn('phone', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Phone')

    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Email')

    ->addColumn('fax', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Fax')

    ->addColumn('web', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Website')

    ->addColumn('hours1', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Opening hours line 1')

    ->addColumn('hours2', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Opening hours line 2')

    ->addColumn('hours3', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
        ), 'Opening hours line 3')

    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Image')

    ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        'nullable'  => false,
        ), 'Store description')

    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        ), 'Enabled')

    ->addColumn('url_key', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'URL key')

    ->addColumn('meta_title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        ), 'Meta title')

    ->addColumn('meta_keywords', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Meta keywords')

    ->addColumn('meta_description', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
        ), 'Meta description')

    ->addColumn('allow_comment', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        ), 'Allow Comment')

    ->addColumn('sort_order', Varien_Db_Ddl_Table::TYPE_INTEGER, 4, array(
    ), 'Sort Order')

    ->addColumn('use_image_not_map', Varien_Db_Ddl_Table::TYPE_INTEGER, 1, array(
    ), 'Use Image Not Map')

    ->addColumn('show_in_finder', Varien_Db_Ddl_Table::TYPE_INTEGER, 1, array(
    ), 'Show in finder')

    ->addColumn('show_in_list', Varien_Db_Ddl_Table::TYPE_INTEGER, 1, array(
    ), 'Show in list')

     ->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        ), 'Store location Status')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
            ), 'Store location Modification Time')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        ), 'Store location Creation Time')


    ->setComment('Store location Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('plugincompany_storelocator/storelocation_store'))
    ->addColumn('storelocation_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Store location ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($this->getIdxName('plugincompany_storelocator/storelocation_store', array('store_id')), array('store_id'))
    ->addForeignKey($this->getFkName('plugincompany_storelocator/storelocation_store', 'storelocation_id', 'plugincompany_storelocator/storelocation', 'entity_id'), 'storelocation_id', $this->getTable('plugincompany_storelocator/storelocation'), 'entity_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($this->getFkName('plugincompany_storelocator/storelocation_store', 'store_id', 'core/store', 'store_id'), 'store_id', $this->getTable('core/store'), 'store_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Store locations To Store Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('plugincompany_storelocator/storelocation_comment'))
    ->addColumn('comment_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ), 'Store location Comment ID')
    ->addColumn('storelocation_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => false,
        ), 'Store location ID')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable'  => false,
        ), 'Comment Title')
    ->addColumn('comment', Varien_Db_Ddl_Table::TYPE_TEXT, '64k', array(
            'nullable'  => false,
        ), 'Comment')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
            'nullable'  => false,
        ), 'Comment status')
    ->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
            'nullable'  => true,
        ), 'Customer id')
    ->addColumn('name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable'  => false,
        ), 'Customer name')
    ->addColumn('email', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
            'nullable'  => false,
        ), 'Customer email')
    ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(), 'Store location Comment Modification Time')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(), 'Store location Comment Creation Time')
    ->addForeignKey(
        $this->getFkName(
            'plugincompany_storelocator/storelocation_comment',
            'storelocation_id',
            'plugincompany_storelocator/storelocation',
            'entity_id'
        ),
        'storelocation_id', $this->getTable('plugincompany_storelocator/storelocation'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey(
        $this->getFkName(
            'plugincompany_storelocator/storelocation_comment',
            'customer_id',
            'customer/entity',
            'entity_id'
        ),
        'customer_id', $this->getTable('customer/entity'), 'entity_id',
        Varien_Db_Ddl_Table::ACTION_SET_NULL, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Store location Comments Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('plugincompany_storelocator/storelocation_comment_store'))
    ->addColumn('comment_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'nullable'  => false,
        'primary'   => true,
        ), 'Comment ID')
    ->addColumn('store_id', Varien_Db_Ddl_Table::TYPE_SMALLINT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
        'primary'   => true,
        ), 'Store ID')
    ->addIndex($this->getIdxName('plugincompany_storelocator/storelocation_comment_store', array('store_id')), array('store_id'))
    ->addForeignKey($this->getFkName('plugincompany_storelocator/storelocation_comment_store', 'comment_id', 'plugincompany_storelocator/storelocation_comment', 'comment_id'), 'comment_id', $this->getTable('plugincompany_storelocator/storelocation_comment'), 'comment_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->addForeignKey($this->getFkName('plugincompany_storelocator/storelocation_comment_store', 'store_id', 'core/store', 'store_id'), 'store_id', $this->getTable('core/store'), 'store_id', Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Store locations Comments To Store Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
