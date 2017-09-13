<?php
require_once(dirname(__FILE__) . '/SGPopup.php');
class SGSubscriptionPopup extends SGPopup
{
    public $content;
    public $subscriptionOptions;
    public $title;

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

    public function setSubscriptionOptions($options)
    {
        $this->subscriptionOptions = $options;
    }

    public function getSubscriptionOptions()
    {
        return $this->subscriptionOptions;
    }

    public function setSubscriptionTitle($title)
    {
        $this->title = $title;
    }

    public function getSubscriptionTitle()
    {
        return $this->title;
    }

    public static function create($data, $obj = null)
    {
        $obj                 = new self();
        $title               = $data['title'];
        $options             = json_decode($data['options'], true);
        $subscriptionOptions = $options['subscriptionOptions'];
        unset($data['subscriptionOptions']);
        $obj->setContent($data['subscription']);
        $obj->setSubscriptionOptions($subscriptionOptions);
        $obj->setSubscriptionTitle($title);
        return parent::create($data, $obj);
    }

    public function save($data = array())
    {
        $editMode = $this->getId() ? true : false;
        $res      = parent::save($data);
        if ($res === false)
            return false;
        $content    = $this->getContent();
        $options    = $this->getSubscriptionOptions();
        $id         = $this->getId();
        $model      = Mage::getModel("popupbuilder/Sgsubscriptionpopup");
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
        $model = Mage::getModel("popupbuilder/Sgsubscriptionpopup");
        $arr   = $model->load($id)->getData();
        $this->setContent($arr['content']);
        $this->setSubscriptionOptions($arr['options']);
    }

    public function getRemoveOptions()
    {
        return array(
            'onScrolling' => 1
        );
    }

    public function renderData()
    {
        $content = '';
        $options                = json_decode($this->getSubscriptionOptions(), true);
        $controllerKey          = Mage::getSingleton('adminhtml/url')->getSecretKey();
        $baseUrl                = Mage::getUrl("popupbuilder/adminhtml_front/subsAjax");
        $fullUrl                = $baseUrl . "key/" . $_SESSION["core"]['visitor_data']['session_id'] . "/";
        $key                    = Mage::getSingleton('core/session')->getFormKey(); //MsD3cN5jXogoiYQ3
        $title                  = $this->getSubscriptionTitle();
        $textInputsWidth        = $options['subs-text-width'];
        $emailPlaceholder       = $options['subscription-email'];
        $sgSubsFirstName        = $options['subs-first-name'];
        $sgSubsLastName         = $options['subs-last-name'];
        $textInputsBgColor      = $options['subs-text-input-bgcolor'];
        $submitButtonBgColor    = $options['subs-button-bgcolor'];
        $subsButtonColor        = $options['subs-button-color'];
        $sgSubsBtnTitle         = $options['subs-btn-title'];
        $sgSubsTextBordercolor  = $options['subs-text-bordercolor'];
        $sgSubsInputsColor      = $options['subs-inputs-color'];
        $sgSubsPlaceholderColor = $options['subs-placeholder-color'];
        $sgSubsTextHeight       = $options['subs-text-height'];
        $sgSubsBtnWidth         = $options['subs-btn-width'];
        $sgSubsBtnHeight        = $options['subs-btn-height'];
        $sgSubsFirstNameStatus  = $options['subs-first-name-status'];
        $sgSubsLastNameStatus   = $options['subs-last-name-status'];
        $sgSubsValidateMessage  = $options['subs-validation-message'];
        $sgSubsEmailValidate    = $options['subs-email-validate'];
        $sgSubsBtnProgressTitle = $options['subs-btn-progress-title'];
        $sgSubsSuccessMessage   = $options['subs-success-message'];
        $sgSubstextBorderWidth  = $options['subs-text-border-width'];

        $content .= "<script type=\"text/javascript\">
   
        function sgInitPopupVariables() {
            sgSubscriptionObj = new SgSubscription();
            sgSubscriptionObj.setTextInputWidth('$textInputsWidth');
            sgSubscriptionObj.setBtnWidth('$sgSubsBtnWidth');
            sgSubscriptionObj.setBtnHeight('$sgSubsBtnHeight');
            sgSubscriptionObj.setTextInputsHeight('$sgSubsTextHeight');
            sgSubscriptionObj.setupBackgroundColor('.js-subs-text-inputs', '$textInputsBgColor');
            sgSubscriptionObj.setupBackgroundColor('.js-subs-submit-btn', '$submitButtonBgColor');
            sgSubscriptionObj.setupBorderColor('.js-subs-text-inputs', '$sgSubsTextBordercolor');
            sgSubscriptionObj.setupButtonColor('.js-subs-text-inputs', '$sgSubsInputsColor');
            sgSubscriptionObj.setupButtonColor('.js-subs-submit-btn', '$subsButtonColor');
            sgSubscriptionObj.setupButtonText('.js-subs-submit-btn', '$sgSubsBtnTitle');
            sgSubscriptionObj.setInProgresTitle('$sgSubsBtnProgressTitle');
            sgSubscriptionObj.setupBorderWidth('js-subs-text-inputs', '$sgSubstextBorderWidth');
            
                sgSubscriptionObj.buildStyle();
           
            sgSupcriptionOptions = {
                'requieredField': '" . $sgSubsValidateMessage . "',
                'emailValidate': '" . $sgSubsEmailValidate . "'
            };
        }
        </script>";

        $content .= "
        <style type=\"text/css\">
            .sg-subs-inputs-wrapper {
                text-align: center;
            }
            .js-subs-submit-btn,
            .js-subs-text-inputs {
                display: block !important;
                margin: 0 auto;
                padding: 5px !importan;
                box-sizing: border-box;
                font-size: 14px !important;
                border-radius: none !important;
                 box-shadow: none !important;
            }
            .js-subs-submit-btn {
                border:0px !important;
                margin-bottom: 2px;
            }
            .js-subs-text-inputs {
                margin-bottom: 8px;
            }
            .sg-js-hide {
                display: none;
            }
            .sg-subs-success {
                border: 1px solid black;
                color: black;
                background-color: #F0EFEF;
                padding: 5px;
            }

            .js-requierd-style,
            .js-validate-email {
                margin-bottom: 7px !important;
                'font-size': '40px',
                'color': 'red !important'
            }
            div.js-validate-email {
                color: 'red !important'
            }
            .js-requierd-style {
                margin: 0px auto;
                font-size: 12px;
                color: red;
                display: block;
            }
            .js-subs-text-inputs::-webkit-input-placeholder {color:" . $sgSubsPlaceholderColor . ";}
            .js-subs-text-inputs::-moz-placeholder {color:" . $sgSubsPlaceholderColor . ";}
            .js-subs-text-inputs:-ms-input-placeholder {color:" . $sgSubsPlaceholderColor . ";} /* ie */
            .js-subs-text-inputs:-moz-placeholder {color:" . $sgSubsPlaceholderColor . ";}
        </style>";
        return $content;
    }

    protected function getExtraRenderOptions()
    {
        $options                = json_decode($this->getSubscriptionOptions(), true);
        $controllerKey          = Mage::getSingleton('adminhtml/url')->getSecretKey();
        $baseUrl                = Mage::getUrl("popupbuilder/adminhtml_front/subsAjax");
        $fullUrl                = $baseUrl . "key/" . $_SESSION["core"]['visitor_data']['session_id'] . "/";
        $key                    = Mage::getSingleton('core/session')->getFormKey(); //MsD3cN5jXogoiYQ3
        $title                  = $this->getSubscriptionTitle();
        $textInputsWidth        = $options['subs-text-width'];
        $emailPlaceholder       = $options['subscription-email'];
        $sgSubsFirstName        = $options['subs-first-name'];
        $sgSubsLastName         = $options['subs-last-name'];
        $textInputsBgColor      = $options['subs-text-input-bgcolor'];
        $submitButtonBgColor    = $options['subs-button-bgcolor'];
        $subsButtonColor        = $options['subs-button-color'];
        $sgSubsBtnTitle         = $options['subs-btn-title'];
        $sgSubsTextBordercolor  = $options['subs-text-bordercolor'];
        $sgSubsInputsColor      = $options['subs-inputs-color'];
        $sgSubsPlaceholderColor = $options['subs-placeholder-color'];
        $sgSubsTextHeight       = $options['subs-text-height'];
        $sgSubsBtnWidth         = $options['subs-btn-width'];
        $sgSubsBtnHeight        = $options['subs-btn-height'];
        $sgSubsFirstNameStatus  = $options['subs-first-name-status'];
        $sgSubsLastNameStatus   = $options['subs-last-name-status'];
        $sgSubsValidateMessage  = $options['subs-validation-message'];
        $sgSubsEmailValidate    = $options['subs-email-validate'];
        $sgSubsBtnProgressTitle = $options['subs-btn-progress-title'];
        $sgSubsSuccessMessage   = $options['subs-success-message'];
        $sgSubstextBorderWidth  = $options['subs-text-border-width'];
        $subsciption            = "<form id='sg-subscribers-data'><div class=\"sg-subs-inputs-wrapper\">";
        $subsciption .= "<input type=\"text\" name='subs-email-name' class=\"js-subs-text-inputs js-subs-email-name\" placeholder=\"$emailPlaceholder\">";
        $subsciption .= "<div class=\"sg-js-hide js-validate-email \"><p style='color:red;'>" . $sgSubsEmailValidate . "</p></div>";
        if ($sgSubsFirstNameStatus) {
            $subsciption .= "<input type=\"text\" name='subs-first-name' class=\"js-subs-first-name js-subs-text-inputs\" placeholder=\"$sgSubsFirstName\">";
        }
        if ($sgSubsLastNameStatus) {
            $subsciption .= "<input type=\"text\" name='subs-last-name' class=\"js-subs-last-name js-subs-text-inputs\" placeholder=\"$sgSubsLastName\">";
        }
        $subsciption .= "<input type=\"hidden\" name=\"subs-popup-title\" value=\"$title\">";
        $subsciption .= '<input type="submit" value="Submit" class="js-subs-submit-btn">';
        $subsciption .= "<input type=\"hidden\" name=\"form_key\" class=\"form-key\" value=\"$key\"  data-upload-url=\"$fullUrl\" >";
        $subsciption .= "</div></form>";
        $subsciption .= "<div class='sg-js-hide sg-subs-success'>" . $sgSubsSuccessMessage . "</div>";
        $content = "";
        $content .= $this->improveContent($this->getContent());
        $content .= $subsciption;
    
        return array(
            'html' => $content
        );
    }
    public function render()
    {
        return parent::render();
    }
}