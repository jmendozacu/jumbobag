<?php
require_once(dirname(__FILE__) . '/SGPopup.php');
class SGHtmlPopup extends SGPopup
{
    public $content;
    public function setContent($content)
    {
        $this->content = $content;
    }
    public function getContent()
    {
        return $this->content;
    }
    public static function create($data, $obj = null)
    {
        $obj = new self();
        $obj->setContent($data['html']);
        return parent::create($data, $obj);
    }
    public function save($data = array())
    {
        $editMode = $this->getId() ? true : false;
        $res      = parent::save($data);
        if ($res === false)
            return false;
        $sgHtmlPopup = $this->getContent();
        $id          = $this->getId();
        $model       = Mage::getModel("popupbuilder/Sghtmlpopup");
        $htmlData    = array(
            'id' => $id,
            "content" => $sgHtmlPopup
        );
        $model->setData($htmlData);
        $query = $model->save();
        if (!$editMode) {
            return array(
                'id' => $id
            );
        }
        return "";
    }
    protected function setCustomOptions($id)
    {
        $model = Mage::getModel("popupbuilder/Sghtmlpopup");
        $arr   = $model->load($id)->getData();
        $this->setContent($arr['content']);
    }
    protected function getExtraRenderOptions()
    {
        $content = $this->getContent();
        $content = $this->improveContent($content);
        return array(
            'html' => $content
        );
    }
    public function render()
    {
        return parent::render();
    }
}