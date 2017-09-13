<?php
require_once(dirname(__FILE__) . '/SGPopup.php');
class SGExitintentPopup extends SGPopup
{
    public $content;
    public $exitIntentOptions;

    function __construct()
    {
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setExitIntentOptions($options)
    {
        $this->exitIntentOptions = $options;
    }

    public function getExitIntentOptions()
    {
        return $this->exitIntentOptions;
    }

    public static function create($data, $obj = null)
    {
        $obj               = new self();
        $options           = json_decode($data['options'], true);
        $exitIntentOptions = $options['exitIntentOptions'];
        $obj->setContent($data['exitIntent']);
        $obj->setExitIntentOptions($exitIntentOptions);
        return parent::create($data, $obj);
    }

    public function save($data = array())
    {
        $editMode = $this->getId() ? true : false;
        $res      = parent::save($data);
        if ($res === false)
            return false;
        $content    = $this->getContent();
        $options    = $this->getExitIntentOptions();
        $id         = $this->getId();
        $model      = Mage::getModel("popupbuilder/Sgexitintentpopup");
        $fblikeDara = array(
            'id' => $id,
            "content" => $content,
            "options" => $options
        );
        $model->setData($fblikeDara);
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
        $model = Mage::getModel("popupbuilder/Sgexitintentpopup");
        $arr   = $model->load($id)->getData();
        $this->setContent($arr['content']);
        $this->setExitIntentOptions($arr['options']);
    }

    public function getRemoveOptions()
    {
        return array(
            'onScrolling' => 1,
            'showOnlyOnce' => 1
        );
    }

    public function getExitIntentInitScript($id)
    {
        return "

        <script type='text/javascript'>

            jQuery(document).ready(function() {

                sgExitIntentObj = new SGExitIntnetPopup();

                sgExitIntentObj.init($id);

            });

        </script>";
    }
    
    protected function getExtraRenderOptions()
    {
        $content = "";
        $content .= "";
        $content .= $this->improveContent($this->getContent());
        $options = json_decode($this->getExitIntentOptions(), true);
        $type    = $options['exit-intent-type'];
        return array(
            'html' => $content
        );
    }
    public function render()
    {
        return parent::render();
    }
}
