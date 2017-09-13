<?php
require_once(dirname(__FILE__) . '/SGPopup.php');
class SGVideoPopup extends SGPopup
{
    private $url;
    private $realUrl;
    private $videoOptions;
    public function setUrl($url)
    {
        $this->url = $url;
    }
    public function getUrl()
    {
        return $this->url;
    }
    public function setRealUrl($url)
    {
        $this->realUrl = $url;
    }
    public function getRealUrl()
    {
        return $this->realUrl;
    }
    public function setVideoOptions($options)
    {
        $this->videoOptions = $options;
    }
    public function getVideoOptions()
    {
        return $this->videoOptions;
    }
    public function integrateVideo($data)
    {
        $videoUrl          = '';
        $options           = json_decode($this->getVideoOptions(), true);
        $sgDefaultAutoplay = '';
        $parsed            = parse_url($data);
        $videoHost         = @$parsed['host'];
        parse_str(@$parsed['query'], $output);
        $videoParam = @$output['v'];
        $array1     = explode('?', @$output['v']);
        $isAutoplay = in_array("autoplay=1", $array1);
        if (!$isAutoplay && $options['video-autoplay'] == 'on') {
            $sgDefaultAutoplay = "?autoplay=1";
        }
        if ($videoHost == "www.youtube.com" || $videoHost == 'youtube.com' || $videoHost == 'www.youtube-nocookie.com' || $videoHost == 'youtu.be') {
            if ($videoParam) {
                $videoUrl = 'https://www.youtube.com/embed/' . $videoParam;
            } else {
                $videoUrlArray = explode("/", $data);
                $videoUrl      = 'https://www.youtube.com/embed/' . $videoUrlArray[count($videoUrlArray) - 1];
            }
        } else if ($videoHost == 'vimeo.com' || $videoHost == 'player.vimeo.com') {
            $videoUrlArray = explode("/", $data);
            $videoUrl      = 'https://player.vimeo.com/video/' . $videoUrlArray[count($videoUrlArray) - 1];
        } else if ($videoHost == 'screen.yahoo.com') {
            $videoUrlArray = explode("/", $data);
            $sgYahooId     = $videoUrlArray[count($videoUrlArray) - 1];
            $sgYahooRegExp = '/\?format=embed$/';
            preg_match($sgYahooRegExp, $sgYahooId, $matches);
            $videoUrl = 'https://screen.yahoo.com/' . $sgYahooId;
            if ($matches) {
                $videoUrl = 'https://screen.yahoo.com/' . $sgYahooId;
            } else {
                $videoUrl = 'https://screen.yahoo.com/' . $sgYahooId . "?format=embed";
            }
        }
        preg_match("/www.dailymotion.com/", $data, $getdaliyHost);
        if ($videoHost == 'www.dailymotion.com') {
            $videoUrlArray        = explode("/", $data);
            $sgDailymotionId      = $videoUrlArray[count($videoUrlArray) - 1];
            $idPosition           = strpos($sgDailymotionId, '_');
            $sgDailymotionEmbedId = substr($sgDailymotionId, 0, $idPosition);
            $videoUrl             = '//www.dailymotion.com/embed/video/' . $sgDailymotionEmbedId;
        } else if (@$getdaliyHost[0] == "www.dailymotion.com") {
            $sleshPos = strpos($data, "/");
            if ($sleshPos == 0) {
                $videoUrl = $data;
            } else {
                $videoUrl = '//' . $data;
            }
        }
        return $videoUrl . $sgDefaultAutoplay;
    }
    public static function create($data, $obj = null)
    {
        $obj          = new self();
        $options      = json_decode($data['options'], true);
        $videoOptions = $options['videoOptions'];
        $obj->setRealUrl($data['video']);
        $obj->setVideoOptions($videoOptions);
        $videoUrl = $obj->integrateVideo($data['video']);
        $obj->setUrl($videoUrl);
        return parent::create($data, $obj);
    }
    public function save($data = array())
    {
        $editMode = $this->getId() ? true : false;
        $res      = parent::save($data);
        if ($res === false) {
            return false;
        }
        $id      = $this->getId();
        $url     = $this->getUrl();
        $realUrl = $this->getRealUrl();
        if ($editMode) {
            $url = $this->integrateVideo($this->getUrl());
        }
        $videoOptions = $this->getVideoOptions();
        $model        = Mage::getModel("popupbuilder/Sgvideopopup");
        $videoData    = array(
            'id' => $id,
            "url" => $url,
            "real_url" => $realUrl,
            "options" => $videoOptions
        );
        $model->setData($videoData);
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
        $model = Mage::getModel("popupbuilder/Sgvideopopup");
        $arr   = $model->load($id)->getData();
        $this->setUrl($arr['url']);
        $this->setRealUrl($arr['real_url']);
        $this->setVideoOptions($arr['options']);
    }
    protected function getExtraRenderOptions()
    {
        $vidoOtions   = $this->getVideoOptions();
        $videoUrl     = $this->getUrl();
        $videoOptions = json_decode($vidoOtions, true);
        return array(
            'video' => $videoUrl
        );
    }
    public function render()
    {
        return parent::render();
    }
}