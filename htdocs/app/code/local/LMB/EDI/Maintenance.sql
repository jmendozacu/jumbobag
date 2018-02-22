/* *********** Correction de problèmes sur les variantes ************************** */
-- Contrôle si il existe des incohérences sur les enfants de variantes (attribut_set != du parent)
SELECT cpe.entity_id, cpe2.entity_id,cpe.attribute_set_id, cpe2.attribute_set_id
FROM `catalog_product_entity` cpe
JOIN catalog_product_super_link cpsl ON cpsl.product_id = cpe.entity_id
JOIN catalog_product_entity cpe2 on cpsl.parent_id = cpe2.entity_id
WHERE cpe.type_id = 'simple' AND cpe.attribute_set_id != cpe2.attribute_set_id;

-- Corrige le problème ci dessus
UPDATE `catalog_product_entity` cpe
JOIN catalog_product_super_link cpsl ON cpsl.product_id = cpe.entity_id
JOIN catalog_product_entity cpe2 on cpsl.parent_id = cpe2.entity_id
set cpe.attribute_set_id = cpe2.attribute_set_id
WHERE cpe.type_id = 'simple' AND cpe.attribute_set_id != cpe2.attribute_set_id;




