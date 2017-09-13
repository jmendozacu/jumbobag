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
 * Store location admin controller
 *
 * @category    Plugincompany
 * @package     Plugincompany_Storelocator
 * @author      Milan Simek
 */
class Plugincompany_Storelocator_Adminhtml_Storelocator_ImportexportController
    extends Plugincompany_Storelocator_Controller_Adminhtml_Storelocator {

    protected $_geoCode;
    protected $_geoRequestCount;
    
    protected $imported = null;
    protected $error = null;
    protected $updated = null;
    protected $post = array();
    /**
     * default action
     * @access public
     * @return void
     * @author Milan Simek
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('plugincompany_storelocator')->__('Import / Export Store Locations'))
            ->_title(Mage::helper('plugincompany_storelocator')->__('Import / Export Store locations'));
        $this->renderLayout();
    }

    public function importAction(){
        $this->post = $this->getRequest()->getPost();
        $data = null;
        
        if(array_key_exists('storelocation', $_FILES) && array_key_exists('tmp_name', $_FILES['storelocation'])){
            $postFileArray = $_FILES['storelocation']['tmp_name'];
            
            if(array_key_exists('store_import', $postFileArray) && $postFileArray['store_import'] != null){
                $data = $this->_getUploadedCsv($postFileArray['store_import']);
                $this->importStoreLocations($data);  
            }
            else if(array_key_exists('product_import', $postFileArray) && $postFileArray['product_import'] != null){
                $data = $this->_getUploadedCsv($postFileArray['product_import']);
                $this->importStoreProducts($data);
            }
           
        }
        
        
        if(!$data){
            $this->error = 'files into';
        }

        $session = Mage::getSingleton('core/session');
        if($this->imported){
            $session->addSuccess("Successfully imported $this->imported store locations.");
        }
        if($this->updated){
            $session->addSuccess("Successfully updated $this->updated store locations.");
        }
        if($this->error){
            $session->addError("Failed to import $this->error store locations.");
        }
        $this->_redirect('*/*');
    }
    
    
    private function importStoreProducts($data){
        $cacheLocationArray = array();

        $header = array_shift($data);
        $modelStoreLoctionProduct = Mage::getModel('plugincompany_storelocator/storelocationproduct');
        $modelStoreLocation = Mage::getModel('plugincompany_storelocator/storelocation');
        $modelProduct = Mage::getModel('catalog/product');
        
        $itemToAdd = array();
        $itemToDelete = array();
     
        foreach($data as $row){
            if(empty($row)){
                continue;
            }
           
            //action - add = 1/delete = 0
            $isDeletedRow = 0;
            
            $productIds = array();
            $storelocationIds = array();
            
            foreach($row as $k => $val){
                if(empty($val)){
                    continue;
                }
                     
                //assume adding unless connected == no
                 if (strcasecmp($header[$k], 'connected' ) == 0 && strcasecmp($val, 'no') == 0){
                     // location to delete flag
                     $isDeletedRow = 1;
                 }
                 
                 //get product id
                 if (strcasecmp($header[$k], 'product_id' ) == 0){
                     $productIds[] = (int)$val;
                 }
                 else if (strcasecmp($header[$k], 'sku' ) == 0){
                    $collection = $modelProduct->getCollection();
                    $collection->addFieldToFilter('name', array('like' => "$val"));
                    
                    foreach($collection as $product){
                       $productIds[] = $product->getId();
                    }
                    
                    unset($collection);
                 }
                 
                 //get store location id
                 if (strcasecmp($header[$k], 'store_location_id' ) == 0){
                     
                     if(!array_key_exists($cacheLocationArray, $val)){
                        $storelocation = $modelStoreLocation->load((int)$val);

                        if($storelocation->getId()){
                           $cacheLocationArray[$storelocation->getId()] = (array)$storelocation->getStoreIds();
                           $storelocationIds[] = $val;
                        }
                        
                        unset($storelocation);
                     }
                     else{
                          $storelocationIds[] = $val;
                     }

                 }
                 else if (strcasecmp($header[$k], 'store_location_name' ) == 0){
                     $collection = $modelStoreLocation->getCollection();
                     $collection->addFieldToFilter('locname', array('like' => "$val"));
                     
                     foreach($collection as $storelocation){
                        $cacheLocationArray[$storelocation->getId()] = (array)$storelocation->getStoreIds();
                        $storelocationIds[] = $storelocation->getId();
                     } 
                                     
                     unset($collection);
                 }

            }
                        
            //loop to find id combination for add/delete items
            if(!empty($productIds) && !empty($storelocationIds)){
                if(!$isDeletedRow){
                    foreach($productIds as $pid){
                        foreach($storelocationIds as $locationid){
                            foreach($cacheLocationArray[$locationid] as $storeid){
                                $itemToAdd[] = array(
                                    'product_id' => $pid,
                                    'storelocation_id' => $locationid,
                                    'store_id' => $storeid,
                                );
                            }
                        }
                    }
                }
                
                if($isDeletedRow){
                    foreach($productIds as $pid){
                        foreach($storelocationIds as $locationid){
                            foreach($cacheLocationArray[$locationid] as $storeid){
                                $itemToDelete[] = array(
                                    'product_id' => $pid,
                                    'storelocation_id' => $locationid,
                                    'store_id' => $storeid,
                                );
                            }
                        }
                    }
                }
            }
            
            
        }
     
        if(!empty($itemToDelete)){
            $modelStoreLoctionProduct->massImportLocationDelete($itemToDelete);
        }
        
        if(!empty($itemToAdd)){
            $modelStoreLoctionProduct->massImportLocationAdd($itemToAdd);
        }
    }
    
    
    private function importStoreLocations($data){
        if (!empty($this->post['storelocation']['geocode'])) {
            $this->_geoCode = Mage::getModel('plugincompany_storelocator/lib_geocode');
            if($apiKey = Mage::helper('plugincompany_storelocator')->getApiKey()){
                $this->_geoCode->setApiKey($apiKey);
            }
        }
        $header = array_shift($data);
        $model = Mage::getModel('plugincompany_storelocator/storelocation');
        $eavAttributes = Mage::getResourceModel('plugincompany_storelocator/storelocationeav_attribute_collection')
            ->addVisibleFilter()
        ;
        $eavCodes = $eavAttributes->getColumnValues('attribute_code');

        foreach($data as $row){
            if(empty($row)){
                continue;
            }
            $rData = array();
            foreach($row as $k => $v){
                if($header[$k] == 'image'){
                    $rData[$header[$k]] = $this->_processImage($v);
                }
                elseif(in_array($header[$k],$eavCodes)){
                    $rData[$header[$k]] = $this->_getEavAttributeOptionValues($header[$k],$v);
                }
                else{
                    $rData[$header[$k]] = $this->_getFlatAttributeOptionValues($header[$k],$v);
                }
            }


            if ((empty($rData['lat']) || empty($rData['lng'])) && is_object($this->_geoCode)) {
                $coords = $this->_getLatLng($rData);
                if($coords){
                    $rData['lat'] = $coords['lat'];
                    $rData['lng'] = $coords['lng'];
                }
            }

            $model->setData(array());

            try{
                if(!empty($rData['entity_id']) && is_numeric($rData['entity_id'])){
                    $model->load($rData['entity_id']);
                    $model
                        ->addData($rData)
                        ->save()
                    ;
                    $this->updated++;
                }else{
                    unset($rData['entity_id']);
                    $model
                        ->addData($rData)
                        ->save();
                    $this->imported++;
                }

            }catch(Exception $e){
                $this->error++;
            }
        }
    }
    
    
    protected function _getLatLng($data){
        $address = $data['address'];
        if(!empty($data['address2'])){
            $address .= ', ' . $data['address2'];
        }
        if(!empty($data['city'])){
            $address .= ', ' . $data['city'];
        }
        if(!empty($data['postal'])){
            $address .= ', ' . $data['postal'];
        }
        if(!empty($data['state'])){
            $address .= ', ' . $data['state'];
        }
        if(!empty($data['country'])){
            $address .= ', ' . $data['country'];
        }

        $response = $this->_geoCode->setAddress($address)->geocode();

        if($response && !empty($response['results'][0]['geometry']['location'])){
            $coords = $response['results'][0]['geometry']['location'];
        }

        $this->_geoRequestCount++;
        if($this->_geoRequestCount % 5 == 0){
            sleep(1);
        }

        if($coords){
            return $coords;
        }

        return false;
    }

    protected function _processImage($path){
        if(substr($path,0,7) != '/import'){
            return $path;
        }
        $baseDir = Mage::getBaseDir();
        $img = $baseDir . DS . 'media' . DS . 'storelocation' . DS . str_replace('/',DS,$path);

        $name = array_pop(explode('/', $path));

        $_FILES['image'] = array(
            'name' => $name,
            'tmp_name' => $img,
            'error' => 0
        );

        $uploader = new Plugincompany_Storelocator_Model_Import_Image('image');
        $uploader->setAllowRenameFiles(true);
        $uploader->setFilesDispersion(true);
        $uploader->setAllowCreateFolders(true);
        $result = $uploader->save(Mage::helper('plugincompany_storelocator/storelocation_image')->getImageBaseDir());
        return $result['file'];
    }

    protected function _getEavAttributeOptionValues($code,$text){
        $model = Mage::getModel('plugincompany_storelocator/resource_eav_attribute');
        $model->loadByCode('plugincompany_storelocator_storelocationeav',$code);

        if(!in_array($model->getFrontendInput(),array('select','boolean','multiselect'))){
            return $text;
        }

        $text = explode(',', $text);
        foreach($text as $k => $v){
            $text[$k] = $model->getSource()->getOptionId($v);
        }

        $text = implode(',', $text);

        return $text;
    }

    protected function _getFlatAttributeOptionValues($code,$text){
        $model = Mage::getModel('plugincompany_storelocator/storelocation');
        $options = $model->getFlatAttributeOptions($code);

        if(!$options){
            return $text;
        }

        $text = explode(',', $text);

        foreach($text as $k => $tv){
            foreach($options as $v){
                if($v['label'] == $tv){
                    $text[$k] = $v['value'];
                }
            }
        }

        $text = implode(',', $text);
        return $text;
    }

    protected function _getUploadedCsv($csv){
 
        $file = fopen($csv,"r");

        $data = array();
        while(! feof($file))
        {
            $data[] = fgetcsv($file);
        }
        fclose($file);
        return $data;
    }

    public function exportAction(){
        $ids = Mage::getModel('plugincompany_storelocator/storelocation')->getCollection()->getAllIds();
        $model = Mage::getModel('plugincompany_storelocator/storelocation');

        //generate header array
        $eavAttributes = Mage::getResourceModel('plugincompany_storelocator/storelocationeav_attribute_collection')
            ->addVisibleFilter()
        ;

        $codes = $eavAttributes->getColumnValues('attribute_code');

        $header = array_unique(array_merge(array_keys($model->load($ids[0])->getData()), $codes));

        foreach ($header as $k => $v) {
            if(in_array($v,array('entity_type_id','attribute_set_id','updated_at','created_at','in_finder','in_store_page'))){
                unset($header[$k]);
            }

        }

        $rows = array($header);
        foreach($ids as $id){
            $location = Mage::getModel('plugincompany_storelocator/storelocation')->load($id);
            $row = array_flip($header);
            foreach($row as $k => $v){
                $row[$k] = null;
            }
            $data = $location->getData();
            foreach($data as $k => $v){
                if(!in_array($k,$header)) continue;

                //eav attribute
                if(in_array($k,$codes)){
                    $v = $this->_getEavAttributeText($k,$v);
                }else{
                    $v = $this->_getFlatAttributeText($k,$v,$model);
                }
                if (is_array($v)) {
                    $v = implode(',', $v);
                }

                $row[$k] = $v;
            }
            $rows[] = $row;
        }

        $this->_sendCsv($rows);
    }


    protected function _getEavAttributeText($code,$value){
        $model = Mage::getModel('plugincompany_storelocator/resource_eav_attribute');

        $model->loadByCode('plugincompany_storelocator_storelocationeav',$code);

        if(!in_array($model->getFrontendInput(),array('select','boolean','multiselect'))){
            return $value;
        }


        $value = explode(',', $value);
        foreach($value as $k => $v){
            $options = $model->getSource()->getAllOptions();
            $value[$k] = $model->getSource()->getOptionText($v);
        }
        $value = implode(',', $value);

        return $value;
    }

    protected function _getFlatAttributeText($code,$value,$model){
        $options = $model->getFlatAttributeOptions($code);
        if(!$options){
            return $value;
        }
        if(is_array($value)){
            foreach($value as $k => $v){
                foreach($options as $option){
                    if($option['value'] == $v){
                        $value[$k] = $option['label'];
                    }
                }
            }
            $value = implode(',', $value);
        }else{
            foreach($options as $option){
                if((string)$option['value'] == $value){
                    $value = $option['label'];
                }
            }
        }
        return $value;
    }

    protected function _sendCsv($rows){
        $title = "store_location_export_" . date('Y_m_d_His');
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename=' . $title . '.csv');
        header('Pragma: no-cache');
        $out = fopen('php://output', 'w');
        foreach($rows as $row){
            fputcsv($out, $row);
        }
        fclose($out);
    }

    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     * @author Milan Simek
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('cms/plugincompany_storelocator/storelocation');
    }
}


