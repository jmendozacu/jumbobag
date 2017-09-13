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
 * Storelocator setup
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Resource_Setup
    extends Mage_Catalog_Model_Resource_Setup {
    /**
	 * get the default entities for storelocator module - used at installation
	 * @access public
	 * @return array()
	 * @author Milan Simek
	 */
	public function getDefaultEntities(){
        $entities = array();
        $entities['plugincompany_storelocator_storelocationeav'] = array(
            'entity_model'                  => 'plugincompany_storelocator/storelocationeav',
            'attribute_model'               => 'plugincompany_storelocator/resource_eav_attribute',
            'table'                         => 'plugincompany_storelocator/storelocationeav',
            'additional_attribute_table'    => 'plugincompany_storelocator/eav_attribute',
            'entity_attribute_collection'   => 'plugincompany_storelocator/storelocationeav_attribute_collection',
            'attributes'        => array()
        );
        return $entities;
    }
}
