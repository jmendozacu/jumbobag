<?php
//require_once(dirname(__FILE__).'../public/classes/SGPopup.php');
class Sygnoos_Popupbuilder_Helper_SgFunctions extends Mage_Core_Helper_Abstract
{
    public function sgCreateRadioElements($radioElements, $checkedValue, $addDiv)
    {
        $content = '';
        for ($i = 0; $i < count($radioElements); $i++) {
            $checked        = '';
            $br             = '';
            $firstDiv       = '';
            $radioElement   = @$radioElements[$i];
            $name           = @$radioElement['name'];
            $label          = @$radioElement['label'];
            $value          = @$radioElement['value'];
            $additionalHtml = @$radioElement['additionalHtml'];
            $newLine        = @$radioElement['newline'];
            if ($newLine) {
                $br = '<br>';
            }
            if ($checkedValue == $value) {
                $checked = 'checked';
            }
            if ($addDiv == true) {
                $firstDiv = '<div class="col-md-4 sg-popuop-vertical-checkbox ">';
            }
            $content .= $firstDiv . '<input class="radio-btn-fix" type="radio" name="' . $name . '" value="' . $value . '" ' . $checked . '>';
            $content .= $additionalHtml . $br;
        }
        return $content;
    }
    public static function getUserIpAdress()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public static function getCountryName($ip)
    {
        if(empty($_COOKIE['SG_POPUP_COUNTRY_NAME'])) {

            $ctx = stream_context_create(
                array('http'=>
                    array(
                        'timeout' => 2, 
                    )
                )
            );

            $details = @file_get_contents(SG_IP_TO_COUNTRY_SERVICE_URL.$ip."&".SG_IP_TO_COUNTRY_SERVICE_THOKEN,false, $ctx);
         
            if($details == false) {
                return true;
            }

            $dataIp = json_decode($details, true);

            if(!is_array($dataIp)) {
                return true;
            }

            $counrty = @$dataIp['country'];
            SetCookie("SG_POPUP_COUNTRY_NAME", $counrty);
        }
        else {
            $counrty = $_COOKIE['SG_POPUP_COUNTRY_NAME'];
        }
        
        return $counrty;
    }
    
    public function createRadiobuttons($elements, $name, $newLine, $selectedInput, $class)
    {
        $str = "";
        foreach ($elements as $key => $elment) {
            $breakLine = "";
            $infoIcon  = "";
            $title     = "";
            $value     = "";
            $infoIcon  = "";
            $checked   = "";
            if (isset($elment["title"])) {
                $title = $elment["title"];
            }
            if (isset($elment["value"])) {
                $value = $elment["value"];
            }
            if ($newLine) {
                $breakLine = "<br>";
            }
            if (isset($elment["info"])) {
                $infoIcon = $elment['info'];
            }
            if ($elment["value"] == $selectedInput) {
                $checked = "checked";
            }
            $str .= "<div class=\"row\">
                        <div class=\"col-md-4\">
                            <span>" . $elment['title'] . "</span>
                        </div>
                        <div class=\"col-md-4\">
                            <input type=\"radio\" name=\"$name\" value=\"$value\" $checked>
                        </div>
                    </div>";
        }
        echo $str;
    }
    function sgCreateSelect($options, $name, $selecteOption)
    {
        $selected = '';
        $str      = "";
        $checked  = "";
        if ($name == 'theme' || $name == 'restrictionAction') {
            $popup_style_name = 'popup_theme_name';
            $firstOption      = array_shift($options);
            $i                = 1;
            foreach ($options as $key) {
                $checked = '';
                if ($key == $selecteOption) {
                    $checked = "checked";
                }
                $i++;
                $str .= "<input type='radio' name=\"$name\" value=\"$key\" $checked class='popup_theme_name' sgPoupNumber=" . $i . ">";
            }
            if ($checked == '') {
                $checked = "checked";
            }
            $str = "<input type='radio' name=\"$name\" value=\"" . $firstOption . "\" $checked class='popup_theme_name' sgPoupNumber='1'>" . $str;
            return $str;
        } else {
            @$popup_style_name = ($popup_style_name) ? $popup_style_name : '';
            $str .= "<select name=$name class='selectpicker input-md sg-selectpicker'>"; //selectpicker
            foreach ($options as $key => $option) {
                if ($key == $selecteOption) {
                    $selected = "selected";
                } else {
                    $selected = '';
                }
                $str .= "<option value='" . $key . "' " . $selected . " >$option</potion>";
            }
            $str .= "</select>";
            return $str;
        }
    }
    public function fileUpload($file, $uploadPath, $fileTypes, $name)
    {
        if (isset($file['name']) && $file['name'] != '') {
            try {
                $fileFormat = substr($file['name'], -3);
                $path       = Mage::getBaseDir() . "/" . $uploadPath . "/"; //desitnation directory    
                $fname      = md5($file['name']) . "." . $fileFormat; //file name                       
                $uploader   = new Varien_File_Uploader($name); //load class
                $uploader->setAllowedExtensions($fileTypes); //Allowed extension for file
                $uploader->setAllowCreateFolders(true); //for creating the directory if not exists
                $uploader->setAllowRenameFiles(false); //if true, uploaded file's name will be changed, if file with the same name already exists directory.
                $uploader->setFilesDispersion(false);
                $uploader->save($path, $fname); //save the file on the specified path
            }
            catch (Exception $e) {
                echo 'Error Message: ' . $e->getMessage();
            }
            return $fname;
        }
    }
    public function deleteExistsUploadFile($data, $path, $target)
    {
        $iamgeName = $data[$target];
        unlink($path . "/" . $iamgeName);
    }
    public function getAllPages()
    {
        $allPages = array();
        $pages    = Mage::getModel('cms/page')->getCollection()->getData();
        foreach ($pages as $page) {
            $value = @$page['title'];
            $id    = md5($value . $page['page_id']);
            $allPages[$id] = $value;
        }
        return $allPages;
    }
    public function getAllProducts()
    {
        $collection = Mage::getResourceModel('catalog/product_collection')->addAttributeToSelect('name');
        $result     = array();
        foreach ($collection as $temp) {
            $name        = $temp->getName();
            $id          = md5($name . $temp->getId());
            $result[$id] = $name;
        }
        return $result;
    }
    public function getAllStores()
    {
        $allStores = Mage::app()->getStores();
        $result     = array();
        foreach ($allStores as $_eachStoreId => $val) {
            $_storeCode = Mage::app()->getStore($_eachStoreId)->getCode();
            $_storeName = Mage::app()->getStore($_eachStoreId)->getName();
            $_storeId = Mage::app()->getStore($_eachStoreId)->getId();
            $result[$_storeId] = $_storeName;
        }
        return $result;
    }
    public function getAllCategories()
    {
        $listOfCategories = array();
        $categories = Mage::getModel('catalog/category')->getCollection()->addAttributeToSelect('*')->addIsActiveFilter();
        foreach($categories as $category) {
            $nameCateory = $category->getName();
            $idCategory = md5($category->getId() . $nameCateory);
            $listOfCategories[$idCategory] = $category->getName();
        }
        return $listOfCategories;
    }
    public static function multiSelect($name, $isAllSelected, $size, $data, $selectedData)
    {
        if ($size < count($data)) {
            $size = count($data);
        }
        $select = '<select multiple name="' . $name . '" size="' . $size . '">';
        foreach ($data as $key => $title) {
            $selected     = '';
            $optionValue  = $key;
            $chekedStatus = @in_array($optionValue, $selectedData);
            if (!empty($selectedData)) {
                $isAllSelected = false;
            }
            if ($isAllSelected) {
                $selected = "selected";
            }
            if ($chekedStatus) {
                $selected = "selected";
            }
            $select .= "<option value=$optionValue " . @$selected . ">" . $title . "</option>";
        }
        $select .= "</select>";
        return $select;
    }
}