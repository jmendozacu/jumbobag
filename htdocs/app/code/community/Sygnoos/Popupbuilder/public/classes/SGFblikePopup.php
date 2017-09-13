<?php
require_once(dirname(__FILE__) . '/SGPopup.php');
class SGFblikePopup extends SGPopup
{
    public $content;
    public $fblikeOptions;
    public function setContent($content)
    {
        $this->content = $content;
    }
    public function getContent()
    {
        return $this->content;
    }
    public function setFblikeOptions($options)
    {
        $this->fblikeOptions = $options;
    }
    public function getFblikeOptions()
    {
        return $this->fblikeOptions;
    }
    public static function create($data, $obj = null)
    {
        $obj           = new self();
        $options       = json_decode($data['options'], true);
        $fblikeOptions = $options['fblikeOptions'];
        $obj->setFblikeOptions($fblikeOptions);
        $obj->setContent($data['fblike']);
        return parent::create($data, $obj);
    }
    public function save($data = array())
    {
        $editMode = $this->getId() ? true : false;
        $res      = parent::save($data);
        if ($res === false)
            return false;
        $sgFblikeContent = $this->getContent();
        $fblikeOptions   = $this->getFblikeOptions();
        $id              = $this->getId();
        $model           = Mage::getModel("popupbuilder/Sgfblikepopup");
        $fblikeDara      = array(
            'id' => $id,
            "content" => $sgFblikeContent,
            "options" => $fblikeOptions
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
        $model = Mage::getModel("popupbuilder/Sgfblikepopup");
        $arr   = $model->load($id)->getData();
        $this->setContent($arr['content']);
        $this->setFblikeOptions($arr['options']);
    }
    protected function getExtraRenderOptions()
    {
        $options = json_decode($this->getFblikeOptions(), true);
        $url     = $options['fblike-like-url'];
        $layout  = $options['fblike-layout'];
        $content = $this->getContent();
        $content = $this->improveContent($content);
        $content .= "<div id=\"sg-facebook-like\"><script type=\"text/javascript\">
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = \"//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5\";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>";
        $content .= '<div class = "sg-fb-buttons-wrapper"><div class="fb-like" data-href="' . $url . '" data-layout="' . $layout . '" data-action="like" data-show-faces="true" data-share="true"></div></div></div>';
        $content .= '<style>
            .sg-fb-buttons-wrapper{
                text-align: center;
                min-height: 25px;
            }
            #sgcboxLoadedContent iframe {
                max-width: none !important;
            }
        </style>';
        return array(
            'html' => $content
        );
    }
    public function render()
    {
        return parent::render();
    }
}