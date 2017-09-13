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
 * Store Location EAV resource model
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Model_Resource_Storelocationeav
    extends Mage_Catalog_Model_Resource_Abstract {

    /**
     * constructor
     * @access public
     * @author Milan Simek
     */
    public function __construct() {
        $resource = Mage::getSingleton('core/resource');
        $this->setType('plugincompany_storelocator_storelocationeav')
            ->setConnection(
                $resource->getConnection('storelocationeav_read'),
                $resource->getConnection('storelocationeav_write')
            );

    }
    /**
     * wrapper for main table getter
     * @access public
     * @return string
     * @author Milan Simek
     */
    public function getMainTable() {
        return $this->getEntityTable();
    }
}
