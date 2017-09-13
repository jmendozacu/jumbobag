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
 * @var $installer Mage_Core_Model_Resource_Setup
 */
    $installer = $this;

/**
 * Ultimate_Slider module install script
 *
 * @category    Images & Media
 * @package     Ultimate_Slider
 */
    $this->startSetup();
    $table = $this->getConnection()
    ->newTable($this->getTable('ultimate_slider/slider'))
    ->addColumn('slider_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'identity' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'Entity id')
    ->addColumn('title', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => true,
    ), 'Slider Title')
    ->addColumn('alt_text', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable' => true,
        'default'  => null,
    ), 'Slider Alternate Text')
    ->addColumn('status', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'nullable' => true,
        'default'  => 0, //Changed From null
    ), 'Enable Status')
    ->addColumn('image', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
        'default'  => null,
    ), 'Slider image media path')
    ->addColumn('video_url', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable' => true,
        'default'  => null,
    ), 'Slider embed URL path')
    ->addColumn('published_at', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
        'nullable' => true,
        'default'  => null,
    ), 'Slider publish date')
    ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_TIMESTAMP, null, array(
        'nullable' => true,
        'default'  => null,
    ), 'Creation Time')
    ->addIndex($this->getIdxName(
            $this->getTable('ultimate_slider/slider'),
            array('published_at'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX
        ),
        array('published_at'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_INDEX)
    )
    ->setComment('Ultimate Slider');
    $this->getConnection()->createTable($table);

$this->endSetup();
