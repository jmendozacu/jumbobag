<?php
require_once(dirname(__FILE__) . '/SGPopup.php');
class SGIframePopup extends SGPopup
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
        $obj->setUrl($data['iframe']);
        return parent::create($data, $obj);
    }
    public function save($data = array())
    {
        $editMode = $this->getId() ? true : false;
        $res      = parent::save($data);
        if ($res === false)
            return false;
        $id         = $this->getId();
        $url        = $this->getUrl();
        $model      = Mage::getModel("popupbuilder/Sgiframepopup");
        $iframeData = array(
            'id' => $id,
            "url" => $url
        );
        $model->setData($iframeData);
        $query = $model->save();
        if (!$editMode) {
            return array(
                'id' => $id
            );
        }
        return $model->getId();
    }
    protected function setCustomOptions($id)
    {
        $model = Mage::getModel("popupbuilder/Sgiframepopup");
        $arr   = $model->load($id)->getData();
        $this->setUrl($arr['url']);
    }
    protected function getExtraRenderOptions()
    {
        return array(
            'iframe' => $this->getUrl()
        );
    }
    public function render()
    {
        return parent::render();
    }
}