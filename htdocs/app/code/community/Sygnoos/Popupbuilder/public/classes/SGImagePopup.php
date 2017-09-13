<?php
require_once(dirname(__FILE__) . '/SGPopup.php');
class SGImagePopup extends SGPopup
{
    private $url;
    public function setUrl($url)
    {
        $this->url = $url;
    }
    public function getUrl()
    {
        return $this->url;
    }
    public static function create($data, $obj = null)
    {
        $obj = new self();
        $obj->setUrl($data['image']);
        return parent::create($data, $obj);
    }
    public function save($data = array())
    {
        $editMode = $this->getId() ? true : false;
        $res      = parent::save($data);
        if ($res === false)
            return false;
        $id               = $this->getId();
        $functionsHellper = Mage::helper('popupbuilder/SgFunctions');
        $model            = Mage::getModel("popupbuilder/Sgimagepopup");
        $uploadPath       = SG_SKIN_IAMGE;
        $target           = "path";
        // Get All datata from image popup
        $allData          = $model->load($id)->getData();
        if (!empty($allData)) {
            //delete edit image
            //$functionsHellper->deleteExistsUploadFile($allData, $uploadPath, $target);
        }
        $fileTypes   = array(
            'jpg',
            'jpeg',
            'png',
            'gif'
        );
        $image       = $this->getUrl();
        $path        = $functionsHellper->fileUpload($image, $uploadPath, $fileTypes, 'ad_image');
        $sgImageData = array(
            'id' => $id,
            "path" => $path
        );
        $model->setData($sgImageData);
        $query = $model->save();
        if (!$editMode) {
            return array(
                'id' => $id,
                'path' => $path
            );
        }
        return "";
    }
    protected function setCustomOptions($id)
    {
        $model = Mage::getModel("popupbuilder/Sgimagepopup");
        $arr   = $model->load($id)->getData();
        $this->setUrl($arr['path']);
    }
    protected function getExtraRenderOptions()
    {
        return array(
            'image' => $this->getUrl()
        );
    }
    public function render()
    {
        return parent::render();
    }
}