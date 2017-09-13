<?php
require_once(dirname(__FILE__) . '/SGPopup.php');
class SGAgerestrictionPopup extends SGPopup
{
    private $content;
    private $yesBuuton;
    private $noBuuton;
    private $restrictionUrl;

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setYesButton($label)
    {
        $this->yesBuuton = $label;
    }

    public function getYesButton()
    {
        return $this->yesBuuton;
    }

    public function setNoButton($label)
    {
        $this->noBuuton = $label;
    }

    public function getNoButton()
    {
        return $this->noBuuton;
    }

    public function setRestrictionUrl($restrictionUrl)
    {
        $this->restrictionUrl = $restrictionUrl;
    }

    public function getRestrictionUrl()
    {
        return $this->restrictionUrl;
    }

    public static function create($data, $obj = null)
    {
        $options          = json_decode($data['options']);
        $yesButtonLabel   = $options->yesButtonLabel;
        $noButtonLabel    = $options->noButtonLabel;
        $sgRestrictionUrl = $options->restrictionUrl;
        $sgRestriction    = $data['ageRestriction'];
        $obj              = new self();
        $obj->setYesButton($yesButtonLabel);
        $obj->setNoButton($noButtonLabel);
        $obj->setRestrictionUrl($sgRestrictionUrl);
        $obj->setContent($sgRestriction);
        return parent::create($data, $obj);
    }

    public function save($data = array())
    {
        $editMode = $this->getId() ? true : false;
        $res      = parent::save($data);
        if ($res === false)
            return false;
        $id                = $this->getId();
        $sgAgerestriction  = stripslashes($this->getContent());
        $sgYesBuuton       = $this->getYesButton();
        $sgNoBuuton        = $this->getNoButton();
        $sgRestrictionUrl  = $this->getRestrictionUrl();
        $model             = Mage::getModel("popupbuilder/Sgagerestrictionpopup");
        $agrestrictionData = array(
            'id' => $id,
            "content" => $sgAgerestriction,
            'yesButton' => $sgYesBuuton,
            'noButton' => $sgNoBuuton,
            'url' => $sgRestrictionUrl
        );
        $model->setData($agrestrictionData);
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
        $model = Mage::getModel("popupbuilder/Sgagerestrictionpopup");
        $arr   = $model->load($id)->getData();
        $this->setContent($arr['content']);
        $this->setYesButton($arr['yesButton']);
        $this->setNoButton($arr['noButton']);
        $this->setRestrictionUrl($arr['url']);
    }

    public function renderData()
    {
        $options = $this->getOptions();
        $optionsArray = json_decode($options, true);
        $restrictionAction = @$optionsArray['restrictionAction'];
        $restrictionUrl    = $optionsArray['restrictionUrl'];
        $id                = $this->getId();
        $content .= "<script type=\"text/javascript\">
                    function sgInitPopupVariables() {
                        jQuery('#sgcolorbox').on('sgColorboxOnCompleate',function(e) {

                            if(arguments[1] == 'on') { /* push to bottom param */

                                jQuery('.buttons-wrapper').css({'position': 'absolute','bottom': '2px','left': '0','right': '0'});

                            }

                            });

                        jQuery('#sgYesButton').on('click',function() {

                            jQuery.sgcolorbox.close();

                        });

                        jQuery('#sgNoButton').bind('click',function() {

                            if('" . $restrictionUrl . "' == '' ) {

                                jQuery.sgcolorbox.close();

                            }

                            else {

                                window.location = '" . $restrictionUrl . "';

                            }

                        });

                        var objRestriction = new SGAgeRestriction();

                        objRestriction.init(" . $this->getId() . ");
                    }
                    </script>";
        return $content;

    }

    protected function getExtraRenderOptions()
    {
        $options           = $this->getOptions();
        $optionsArray      = json_decode($options, true);
        $restrictionAction = @$optionsArray['restrictionAction'];
        $restrictionUrl    = $optionsArray['restrictionUrl'];
        $content           = $this->getContent();
        $content           = $this->improveContent($content);
        $id                = $this->getId();
        $content .= "<div class=\"buttons-wrapper\" ><button id='sgYesButton' type='button' > " . $this->getYesButton() . "</button><button id='sgNoButton' type='button' style='margin-left: 5px;' > " . $this->getNoButton() . "</button></div>";
        $content .= "<style type=\"text/css\">

            .buttons-wrapper {

                text-align: center;

            }

        </style>";
        
        return array(
            'html' => $content,
            'contentClick' => '',
            'overlayClose' => '',
            'escKey' => '',
            'closeButton' => ''
        );
    }
    public function render()
    {
        return parent::render();
    }
}
