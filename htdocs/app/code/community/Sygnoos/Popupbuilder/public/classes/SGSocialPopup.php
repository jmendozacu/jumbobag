<?php
require_once(dirname(__FILE__) . '/SGPopup.php');
class SGSocialPopup extends SGPopup
{
    public $socialContent;
    public $buttons;
    public $socialOptions;

    function __construct()
    {
    }

    public function renderScripts($themeType = 'classic')
    {
    }

    public function setSocialContent($socialContent)
    {
        $this->socialContent = $socialContent;
    }

    public function getSocialContent()
    {
        return $this->socialContent;
    }

    public function setButtons($buttons)
    {
        $this->buttons = $buttons;
    }

    public function getButtons()
    {
        return $this->buttons;
    }

    public function setSocialOptions($socialOptions)
    {
        $this->socialOptions = $socialOptions;
    }

    public function getSocialOptions()
    {
        return $this->socialOptions;
    }

    public static function create($data, $obj = null)
    {
        $obj           = new self();
        $options       = json_decode($data['options'], true);
        $socialContent = $data['social'];
        $socialButtons = $options['socialButtons'];
        $socialOptions = $options['socialOptions'];
        $obj->setSocialContent($socialContent);
        $obj->setButtons($socialButtons);
        $obj->setSocialOptions($socialOptions);
        return parent::create($data, $obj);
    }

    public function save($data = array())
    {
        $editMode = $this->getId() ? true : false;
        $res      = parent::save($data);
        if ($res === false)
            return false;
        $socialContent = $this->getSocialContent();
        $buttons       = $this->getButtons();
        $socialOptions = $this->getSocialOptions();
        $id            = $this->getId();
        $model         = Mage::getModel("popupbuilder/Sgsocialpopup");
        $fblikeDara    = array(
            'id' => $id,
            "socialContent" => $socialContent,
            "buttons" => $buttons,
            'socialOptions' => $socialOptions
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
        $model = Mage::getModel("popupbuilder/Sgsocialpopup");
        $arr   = $model->load($id)->getData();
        $this->setSocialContent($arr['socialContent']);
        $this->setButtons($arr['buttons']);
        $this->setSocialOptions($arr['socialOptions']);
    }

    public function renderData()
    {
        $plain         = 'classic';
        $socialButtons = json_decode($this->getButtons(), true);
        $socialOptions = json_decode($this->getSocialOptions(), true);
        $plain         = $socialOptions['sgSocialTheme'];

        $fbShareLabel        = $socialOptions['fbShareLabel'];
        $sgTwitte            = @$socialOptions['sgTwitte'];
        $sgMailSubject       = $socialOptions['sgMailSubject'];
        $sgMailLable         = $socialOptions['sgMailLable'];
        $sgGoogLelabel       = $socialOptions['googLelabel'];
        $sgLindkinLabel      = $socialOptions['lindkinLabel'];
        $sgSocialButtonsSize = $socialOptions['sgSocialButtonsSize'];
        $sgSocialShareCount  = $socialOptions['sgSocialShareCount'];
        $sgPinterestLabel    = $socialOptions['pinterestLabel'];
        $sgSocialLabel       = $socialOptions['sgSocialLabel'];
        $sgShareUrl          = $socialOptions['sgShareUrl'];
        $shareUrlType        = $socialOptions['shareUrlType'];
        $sgTwitte            = @$socialOptions['sgTwitte'];
        $sgRoundButton       = $socialOptions['sgRoundButton'];
        $fbStatus            = $socialButtons['sgFbStatus'];
        $sgEmailStatus       = $socialButtons['sgEmailStatus'];
        $sgTwitterStatus     = $socialButtons['sgTwitterStatus'];
        $sgLinkedinStatus    = $socialButtons['sgLinkedinStatus'];
        $sgGoogleStatus      = $socialButtons['sgGoogleStatus'];
        $sgPinterestStatus   = $socialButtons['sgPinterestStatus'];

        if ($sgSocialShareCount === true) {
            $sgSocialShareCount = true;
        }
        if ($sgSocialShareCount === false) {
            $sgSocialShareCount = false;
        }
        if ($sgSocialShareCount === 'inside') {
            $sgSocialShareCount = 'inside';
        }
        if ($shareUrlType == 'activeUrl') {
            $sgShareUrl = '';
        }
        $socialContent .= "<style type=\"text/css\">";
        if ($sgRoundButton == 'on') {
            $socialContent .= ".jssocials-share-link { border-radius: 50%; }";
        }
        $socialContent .= "#share-btns-container {text-align: center}";
        $socialContent .= "</style>";
        $socialContent .= "<script type=\"text/javascript\">
            function sgInitPopupVariables() {
                if(arguments[1] == 'on') { /* push to bottom param */
                    jQuery('#share-btns-container').css({'position': 'absolute','bottom': '2px','left': '0','right': '0'});
                }
                jQuery('#share-btns-container').jsSocials(sgSocialOptions);
            }
                var socialArray = [];
                if ('$sgEmailStatus' == 'on') {
                    if('$sgMailLable' == '') {
                        socialArray.push('email');
                    }
                    else {
                        socialArray.push({share:'email', label: '$sgMailLable'});
                    }
                }
                if ('$sgTwitterStatus' == 'on') {
                    socialArray.push('twitter');
                }
                if ('$fbStatus' == 'on') {
                    if('$fbShareLabel' == '') {
                        socialArray.push('facebook');
                    }
                    else {
                        socialArray.push({share:'facebook', label: '$fbShareLabel'});
                    }
                }
                if ('$sgGoogleStatus' == 'on') {
                    if('$sgGoogLelabel' == '') {
                        socialArray.push('googleplus');
                    }
                    else {
                        socialArray.push({share:'googleplus', label: '$sgGoogleStatus'});
                    }
                }
                if ('$sgLinkedinStatus' == 'on') {
                    if('$sgLindkinLabel' == '') {
                        socialArray.push('linkedin');
                    }
                    else {
                        socialArray.push({share:'linkedin', label: '$sgLindkinLabel'});
                    }
                }
                if ('$sgPinterestStatus' == 'on') {
                    if('$sgPinterestLabel' == '')  {
                        socialArray.push('pinterest');
                    }
                    else {
                        socialArray.push({share:'pinterest', label: '$sgPinterestLabel'});
                    }
                }
                if ('$sgSocialLabel' == 'on') {
                    sgSocialLabel = true;
                }
                else {
                    sgSocialLabel = false;
                }
                var sgShareUrl = '$sgShareUrl';
                var sgSocialOptions = {
                    countUrl: sgShareUrl,
                    shares: socialArray,
                    showCount : '$sgSocialShareCount' == 'false' ? false: '$sgSocialShareCount',
                    showLabel : sgSocialLabel
                }
                if(sgShareUrl !== '') {

                    sgSocialOptions.url = sgShareUrl;
                }
                
            </script>";
            return $socialContent;
    }
    protected function getExtraRenderOptions()
    {
        $plain         = 'classic';
        $socialButtons = json_decode($this->getButtons(), true);
        $socialOptions = json_decode($this->getSocialOptions(), true);
        $plain         = $socialOptions['sgSocialTheme'];
        //$this->renderScripts($plain);
        $socialContent = "

        <link type='text/css' rel='stylesheet' href='" . SG_SKIN_ADMIN_CSS_URL . "jssocial/font-awesome.min.css'>

        <link type='text/css' rel='stylesheet' href='" . SG_SKIN_ADMIN_CSS_URL . "jssocial/jssocials.css'>

        <link type='text/css' rel='stylesheet' href='" . SG_SKIN_ADMIN_CSS_URL . "jssocial/jssocials-theme-classic.css'>";
        $socialContent .= $this->improveContent($this->getSocialContent());
        $fbShareLabel        = $socialOptions['fbShareLabel'];
        $sgTwitte            = @$socialOptions['sgTwitte'];
        $sgMailSubject       = $socialOptions['sgMailSubject'];
        $sgMailLable         = $socialOptions['sgMailLable'];
        $sgGoogLelabel       = $socialOptions['googLelabel'];
        $sgLindkinLabel      = $socialOptions['lindkinLabel'];
        $sgSocialButtonsSize = $socialOptions['sgSocialButtonsSize'];
        $sgSocialShareCount  = $socialOptions['sgSocialShareCount'];
        $sgPinterestLabel    = $socialOptions['pinterestLabel'];
        $sgSocialLabel       = $socialOptions['sgSocialLabel'];
        $sgShareUrl          = $socialOptions['sgShareUrl'];
        $shareUrlType        = $socialOptions['shareUrlType'];
        $sgTwitte            = @$socialOptions['sgTwitte'];
        $sgRoundButton       = $socialOptions['sgRoundButton'];
        $fbStatus            = $socialButtons['sgFbStatus'];
        $sgEmailStatus       = $socialButtons['sgEmailStatus'];
        $sgTwitterStatus     = $socialButtons['sgTwitterStatus'];
        $sgLinkedinStatus    = $socialButtons['sgLinkedinStatus'];
        $sgGoogleStatus      = $socialButtons['sgGoogleStatus'];
        $sgPinterestStatus   = $socialButtons['sgPinterestStatus'];
        $socialContent .= '<div id="share-btns-container"  style="font-size: ' . $sgSocialButtonsSize . 'px"></div>';
        
        return array(
            'html' => $socialContent
        );
    }

    public function render()
    {
        return parent::render();
    }
}