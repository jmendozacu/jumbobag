<?php
$logged_in = (
  hash_hmac(
    "sha256",
    $_SERVER['PHP_AUTH_PW'],
    $_SERVER['PHP_AUTH_USER']
  ) === "725db54b7e01295ab40a9311cf7a454de5340bfe48d8dd2ab1f36d066eed2596"
);

if (!$logged_in) {
    header('WWW-Authenticate: Basic realm="My Realm"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'Unauthorized'.hash_hmac("sha256", $_SERVER['PHP_AUTH_PW'], "innersense-commit42");
    exit;
}

require_once 'app/Mage.php';
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$userModel = Mage::getModel('admin/user');
$userModel->setUserId(0);

function getProductAttributeId($code)
{
    return Mage::getModel('eav/entity_attribute')
        ->loadByCode(Mage_Catalog_Model_Product::ENTITY, $code)
        ->getId();
}

$attributeInnersenseId = getProductAttributeId('innersense_id');
$attributeNameId = getProductAttributeId('name');
$storeId = Mage::app()
    ->getWebsite()
    ->getDefaultGroup()
    ->getDefaultStoreId();

$query = "SELECT
  p.entity_id as product_id,
  p.sku as product_sku,
  n.value as product_name,
  inn.value as innersense_id,
  o.option_id as option_id,
  ot.title as option_title,
  v.option_id as option_value_id,
  vt.value as option_value_title,
  vr.sku as option_value_ref
FROM `catalog_product_entity` as p
INNER JOIN catalog_product_entity_varchar as n ON p.entity_id = n.entity_id
INNER JOIN catalog_product_entity_varchar as inn ON p.entity_id = inn.entity_id
INNER JOIN catalog_product_bundle_option as o ON o.parent_id = p.entity_id
INNER JOIN catalog_product_bundle_option_value as ot ON ot.option_id = o.option_id
INNER JOIN catalog_product_bundle_selection as v ON v.option_id = o.option_id
INNER JOIN catalog_product_entity_varchar as vt ON vt.entity_id = v.product_id
INNER JOIN catalog_product_entity as vr ON vr.entity_id = v.product_id
WHERE n.attribute_id = " . $attributeNameId . " AND inn.attribute_id = " . $attributeInnersenseId . " AND vt.attribute_id = " . $attributeNameId . " AND vt.store_id = " . $storeId . " AND inn.store_id = " . $storeId . " AND ot.store_id = " . $storeId . " AND n.store_id = " . $storeId . " AND vt.store_id = " . $storeId . " AND inn.value IS NOT NULL AND inn.value != ''

UNION

SELECT
  p.entity_id as product_id,
  p.sku as product_sku,
  n.value as product_name,
  inn.value as innersense_id,
  o.attribute_id as option_id,
  ot.value as option_title,
  v.option_id as option_value_id,
  vt.value as option_value_title,
  vt.value_id as option_value_ref
FROM `catalog_product_entity` as p
INNER JOIN catalog_product_entity_varchar as n ON p.entity_id = n.entity_id
INNER JOIN catalog_product_entity_varchar as inn ON p.entity_id = inn.entity_id
INNER JOIN catalog_product_super_attribute as o ON o.product_id = p.entity_id
INNER JOIN catalog_product_super_attribute_label as ot ON ot.product_super_attribute_id = o.product_super_attribute_id
INNER JOIN eav_attribute_option as v ON v.attribute_id = o.attribute_id
INNER JOIN eav_attribute_option_value as vt ON vt.option_id = v.option_id
WHERE n.attribute_id = " . $attributeNameId . " AND inn.attribute_id = " . $attributeInnersenseId . " AND vt.store_id = " . $storeId . " AND inn.store_id = " . $storeId . " AND ot.store_id = " . $storeId . " AND n.store_id = " . $storeId . " AND inn.value IS NOT NULL AND inn.value != ''

UNION

SELECT
  o.product_id as product_id,
  p.sku as product_sku,
  n.value as product_name,
  inn.value as innersense_id,
  o.option_id as option_id,
  ot.title as option_title,
  v.option_type_id as option_value_id,
  vt.title as option_value_title,
  v.sku as option_value_ref
FROM `catalog_product_option` as o
INNER JOIN catalog_product_option_type_value as v
    ON o.option_id = v.option_id
INNER JOIN catalog_product_entity_varchar as n
    ON o.product_id = n.entity_id AND n.attribute_id = " . $attributeNameId . "
INNER JOIN catalog_product_entity as p
    ON p.entity_id = o.product_id
INNER JOIN catalog_product_option_title as ot
    ON ot.option_id = o.option_id
INNER JOIN catalog_product_entity_varchar as inn
    ON inn.entity_id = o.product_id AND inn.attribute_id = " . $attributeInnersenseId . "
INNER JOIN catalog_product_option_type_title as vt
    ON v.option_type_id = vt.option_type_id
WHERE inn.value IS NOT NULL AND inn.value != '' AND vt.store_id = " . $storeId . " AND n.store_id = " . $storeId . " AND inn.store_id = " . $storeId . " AND ot.store_id = " . $storeId;

function mssafe_csv($stream, $data, $header = array())
{
    if ($fp = $stream) {
        $show_header = true;
        if (empty($header)) {
            $show_header = false;
            reset($data);
            $line = current($data);
            if (!empty($line)) {
                reset($line);
                $first = current($line);
                if (substr($first, 0, 2) == 'ID' && !preg_match('/["\\s,]/', $first)) {
                    array_shift($data);
                    array_shift($line);
                    if (empty($line)) {
                        fwrite($fp, "\"{$first}\"\r\n");
                    } else {
                        fwrite($fp, "\"{$first}\",");
                        fputcsv($fp, $line);
                        fseek($fp, -1, SEEK_CUR);
                        fwrite($fp, "\r\n");
                    }
                }
            }
        } else {
            reset($header);
            $first = current($header);
            if (substr($first, 0, 2) == 'ID' && !preg_match('/["\\s,]/', $first)) {
                array_shift($header);
                if (empty($header)) {
                    $show_header = false;
                    fwrite($fp, "\"{$first}\"\r\n");
                } else {
                    fwrite($fp, "\"{$first}\",");
                }
            }
        }
        if ($show_header) {
            fputcsv($fp, $header);
            fseek($fp, -1, SEEK_CUR);
            fwrite($fp, "\r\n");
        }
        foreach ($data as $line) {
            fputcsv($fp, $line);
            fseek($fp, -1, SEEK_CUR);
            fwrite($fp, "\r\n");
        }
    } else {
        return false;
    }
    return true;
}

$read = Mage::getSingleton('core/resource')->getConnection('core_read');
$results = $read->fetchAll($query);

$tmpfile = tmpfile();

/* straight dump to screen without formatting */
if (!mssafe_csv($tmpfile, $results, array("product_id", "product_sku", "product_name", "innersense_id", "option_id", "option_title", "option_value_id", "option_value_title", "option_value_ref"))) {
    echo "Error occured";
}

rewind($tmpfile);
$date = date('Y-m-d_H-i-s');

$stat = fstat($tmpfile);
$size = $stat["size"];

header('Content-Description: File Transfer');
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="export_innersense_'.$date.'.csv"');
header('Content-Transfer-Encoding: binary');
if ($size > 0) {
    header("Content-length: $size");
}
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
fpassthru($tmpfile);
