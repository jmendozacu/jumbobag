<?php
Mage::log('-- Start Jumbobag Ecotax data setup 0.1.0 --');

try {
    /* @var $installer Mage_Catalog_Model_Resource_Setup */
    $installer = Mage::getResourceModel('catalog/setup', 'catalog_setup');

    $installer->startSetup();

    $ecoTaxAttributeCode = 'ecotax';

    $installer->addAttribute('catalog_product', $ecoTaxAttributeCode, array(
        'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_WEBSITE,
        'input'         => 'price',
        'type'          => 'decimal',
        'label'         => 'Eco-participation',
        'visible'       => true,
        'required'      => false,
        'visible_on_front' => true,
        'user_defined'  =>  true
    ));

    $attributeModel = Mage::getModel('eav/entity_attribute');
    $attributeGroupModel = Mage::getModel('eav/entity_attribute_group');
    $attributeSetModel = Mage::getModel('eav/entity_attribute_set');
    $attributeSets = $attributeSetModel->getCollection();
    foreach($attributeSets as $set) {
        $entityTypeId = $set->getEntityTypeId();
        $attributeSetId = $set->getId();
        $priceGroup = $attributeGroupModel->getResourceCollection()
            ->setAttributeSetFilter($set->getId())
            ->addFieldToFilter('attribute_group_name', 'Prices')
            ->getFirstItem();
        $attributeGroupId = $priceGroup->getId();
        $installer->addAttributeToGroup(
            $entityTypeId,
            $attributeSetId,
            $attributeGroupId,
            $ecoTaxAttributeCode,
            '3'
        );

        $this->deleteTableRow(
            'eav/entity_attribute',
            'attribute_id',
            $attributeModel->loadByCode('catalog_product', 'deee')->getId(),
            'attribute_set_id',
            $attributeSetId
        );
    }

    $installer->endSetup();
} catch (Exception $e) {
    Mage::logException($e);
}

Mage::log('-- End Jumbobag Ecotax data setup 0.1.0 --');
