<?php
require_once(dirname(__FILE__) . '/SGPopup.php');
class SGContactformPopup extends SGPopup
{
    public $content;
    public $params;

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

    public function steParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public static function create($data, $obj = null)
    {
        $obj     = new self();
        $options = json_decode($data['options'], true);
        $obj->steParams($options['contactFormOptions']);
        unset($data['contactFormOptions']);
        $obj->setContent($data['contactForm']);
        return parent::create($data, $obj);
    }

    public function save($data = array())
    {
        $editMode = $this->getId() ? true : false;
        $res      = parent::save($data);
        if ($res === false)
            return false;
        $content     = $this->getContent();
        $params      = $this->getParams();
        $id          = $this->getId();
        $model       = Mage::getModel("popupbuilder/Sgcontactformpopup");
        $contactData = array(
            'id' => $id,
            "content" => $content,
            "options" => $params
        );
        $model->setData($contactData);
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
        $model = Mage::getModel("popupbuilder/Sgcontactformpopup");
        $arr   = $model->load($id)->getData();
        $this->setContent($arr['content']);
        $this->steParams($arr['options']);
    }

    public function getRemoveOptions()
    {
        return array();
    }

    public function renderData()
    {
        $options                    = json_decode($this->getParams(), true);
        $controllerKey              = Mage::getSingleton('adminhtml/url')->getSecretKey();
        $baseUrl                    = Mage::getUrl("popupbuilder/adminhtml_front/contactFormAjax");
        $fullUrl                    = $baseUrl . "key/" . $_SESSION["core"]['visitor_data']['session_id'] . "/";
        $key                        = Mage::getSingleton('core/session')->getFormKey(); //MsD3cN5jXogoiYQ3
        $namePlaceholder            = $options['contact-name'];
        $subjectPlaceholder         = $options['contact-subject'];
        $emailPlaceholder           = $options['contact-email'];
        $messagePlaceholder         = $options['contact-message'];
        $sgContactInputsWidth       = $options['contact-inputs-width'];
        $sgContactBtnWidth          = $options['contact-btn-width'];
        $sgContactInputsHeight      = $options['contact-inputs-height'];
        $sgContactBtnHeight         = $options['contact-btn-height'];
        $sgContactBtnProgressTitle  = $options['contact-btn-progress-title'];
        $sgContactPlaceholderColor  = $options['contact-placeholder-color'];
        $sgContactButtonColor       = $options['contact-button-color'];
        $sgContactButtonBgcolor     = $options['contact-button-bgcolor'];
        $sgContactTextInputBgcolor  = $options['contact-text-input-bgcolor'];
        $sgContactInputsBorderWidth = $options['contact-inputs-border-width'];
        $sgContactInputsColor       = $options['contact-inputs-color'];
        $sgContactTextBordercolor   = $options['contact-text-bordercolor'];
        $sgContactValidationMessage = $options['contact-validation-message'];
        $sgContactAreaWidth         = $options['contact-area-width'];
        $sgContactAreaHeight        = $options['contact-area-height'];
        $sgContactResize            = $options['sg-contact-resize'];
        $sgSubsValidateMessage      = $options['contact-validation-message'];
        $contactSuccessMessage      = $options['contact-success-message'];
        $sgContactValidateEmail     = $options['contact-validate-email'];
        $sgContactEmail             = $options['contact-resive-email'];

        $content .= "

            <script type=\"text/javascript\">

                contactFrontend = {

                    'inputsWidth': '" . $sgContactInputsWidth . "',

                    'buttnsWidth': '" . $sgContactBtnWidth . "',

                    'inputsHeight': '" . $sgContactInputsHeight . "',

                    'buttonHeight': '" . $sgContactBtnHeight . "',

                    'procesingTitle': '" . $sgContactBtnProgressTitle . "',

                    'placeholderColor': '" . $sgContactPlaceholderColor . "',

                    'btnTextColor': '" . $sgContactButtonColor . "',

                    'btnBackgroundColor': '" . $sgContactButtonBgcolor . "',

                    'inputsBackgroundColor': '" . $sgContactTextInputBgcolor . "',

                    'inputsColor': '" . $sgContactInputsColor . "',

                    'contactInputsBorderWidth': '" . $sgContactInputsBorderWidth . "',

                    'ajaxurl': '',

                    'contactAreaWidth': '" . $sgContactAreaWidth . "',

                    'contactAreaHeight': '" . $sgContactAreaHeight . "',

                    'contactResize': '" . $sgContactResize . "',

                    'inputsBorderColor': '" . $sgContactTextBordercolor . "',

                    'validateMessage': '" . $sgContactValidationMessage . "'

                }

            </script>

        ";

        $content .= '<script type="text/javascript">

        function sgInitPopupVariables() {

            contactObj = new SgContactForm();

            contactObj.buildStyle();

        }

        </script>';
        return $content;
    }

    protected function getExtraRenderOptions()
    {
        $content = "<div class='contact-content-wrapper'>";
        $content .= $this->improveContent($this->getContent());
        $popupId = $this->getId();
        $content .= "</div>";
        $options                    = json_decode($this->getParams(), true);
        $controllerKey              = Mage::getSingleton('adminhtml/url')->getSecretKey();
        $baseUrl                    = Mage::getUrl("popupbuilder/adminhtml_front/contactFormAjax");
        $fullUrl                    = $baseUrl . "key/" . $_SESSION["core"]['visitor_data']['session_id'] . "/";
        $key                        = Mage::getSingleton('core/session')->getFormKey(); //MsD3cN5jXogoiYQ3
        $namePlaceholder            = $options['contact-name'];
        $subjectPlaceholder         = $options['contact-subject'];
        $emailPlaceholder           = $options['contact-email'];
        $messagePlaceholder         = $options['contact-message'];
        $sgContactInputsWidth       = $options['contact-inputs-width'];
        $sgContactBtnWidth          = $options['contact-btn-width'];
        $sgContactInputsHeight      = $options['contact-inputs-height'];
        $sgContactBtnHeight         = $options['contact-btn-height'];
        $sgContactBtnProgressTitle  = $options['contact-btn-progress-title'];
        $sgContactPlaceholderColor  = $options['contact-placeholder-color'];
        $sgContactButtonColor       = $options['contact-button-color'];
        $sgContactButtonBgcolor     = $options['contact-button-bgcolor'];
        $sgContactTextInputBgcolor  = $options['contact-text-input-bgcolor'];
        $sgContactInputsBorderWidth = $options['contact-inputs-border-width'];
        $sgContactInputsColor       = $options['contact-inputs-color'];
        $sgContactTextBordercolor   = $options['contact-text-bordercolor'];
        $sgContactValidationMessage = $options['contact-validation-message'];
        $sgContactAreaWidth         = $options['contact-area-width'];
        $sgContactAreaHeight        = $options['contact-area-height'];
        $sgContactResize            = $options['sg-contact-resize'];
        $sgSubsValidateMessage      = $options['contact-validation-message'];
        $contactSuccessMessage      = $options['contact-success-message'];
        $sgContactValidateEmail     = $options['contact-validate-email'];
        $sgContactEmail             = $options['contact-resive-email'];
        $contactFailMessage         = $options['contact-fail-message'];
        

        $content .= '

        <div id="sg-contact-faild" class="sg-js-hide">'.$contactFailMessage.'</div>

        <form id="sg-contact-data"><div class="sg-contact-inputs-wrapper">

                    <input type="text" name="contact-name" class="sg-contact-required js-contact-text-inputs js-contact-name" value="" placeholder="' . $namePlaceholder . '">

                    <input type="text" name="contact-subject" class=" sg-contact-required js-contact-text-inputs js-contact-subject" value="" placeholder="' . $subjectPlaceholder . '">

                    <input type="text" name="contact-email" class="sg-contact-required js-contact-text-inputs js-contact-email" value="" placeholder="' . $emailPlaceholder . '">

                    <div class="sg-js-hide js-validate-email">' . $sgContactValidateEmail . '</div>

                    <textarea name="content-message" placeholder="' . $messagePlaceholder . '" class="sg-contact-required js-contact-message js-contact-text-area"></textarea>

                    <input type="hidden" name="form_key" class="form-key" value="' . $key . '"  data-upload-url="' . $fullUrl . '" >

                    <input type="hidden" name="popup-id" value="' . $popupId . '">

                    <input type="submit" value="Submit" class="js-contact-submit-btn">
        </div></form>

        <div id="sg-contact-success" class="sg-js-hide">' . $contactSuccessMessage . '</div>';

        
        $content .= "<style>

            .sg-contact-inputs-wrapper {

                text-align: center;

            }

            .js-contact-submit-btn,

            .js-contact-text-inputs {

                padding: 5px !important;

                box-sizing: border-box;

                font-size: 14px !important;

                border-radius: none !important;

                 box-shadow: none !important;

            }

            .js-subs-submit-btn {

                border:0px !important;

                margin-bottom: 2px;

            }

            .js-contact-text-inputs {
                margin-bottom: 8px;
            }
            .sg-js-hide {
                display: none;
            }
            #sg-contact-success {
                border: 1px solid black;
                color: black;
                background-color: #F0EFEF;
                padding: 5px;
            }
            #sg-contact-faild {
                border: 1px solid black;
                color: red;
                text-align: center;
                background-color: #F0EFEF;
                padding: 5px;
                width: $sgContactInputsWidth;
                margin: 5px auto;
            }
            .js-contact-text-area {
                padding: 0px !important;
                text-indent: 3px;
            }

            .contact-content-wrapper {
                margin: 0;
                height: auto;
                display: table;
            }
            .js-validate-email,
            .js-requierd-style {
                'margin': '0px auto 5px auto',
                'font-size': '12px',
                'color': 'red'
            }
            .sg-contact-required {
                display: block;
                margin: 3px auto 3px auto;
            }
            .js-contact-submit-btn {
                margin-bottom: 8px;
                line-height: 0px !important;
            }
            .js-requierd-style {
                margin: 0px auto 5px auto;
                font-size: 12px;
                color: red;
                display: block;
            }
            .js-contact-text-inputs::-webkit-input-placeholder {color:" . $sgContactPlaceholderColor . ";}
            .js-contact-text-inputs::-moz-placeholder {color:" . $sgContactPlaceholderColor . ";}
            .js-contact-text-inputs:-ms-input-placeholder {color:" . $sgContactPlaceholderColor . ";} /* ie */
            .js-contact-text-inputs:-moz-placeholder {color:" . $sgContactPlaceholderColor . ";}
        </style>";
        return array(
            'html' => $content
        );
    }
    public function render()
    {
        return parent::render();
    }
}