<?php
require_once(dirname(__FILE__) . '/SGPopup.php');
class SGCountdownPopup extends SGPopup
{
    public $countdownContent;
    public $countdownOptions;
    public function setCountdownContent($countdownContent)
    {
        $this->countdownContent = $countdownContent;
    }
    public function getCountdownContent()
    {
        return $this->countdownContent;
    }
    public function setCountdownOptions($options)
    {
        $this->countdownOptions = $options;
    }
    public function getCountdownOptions()
    {
        return $this->countdownOptions;
    }
    public static function create($data, $obj = null)
    {
        $obj              = new self();
        $options          = json_decode($data['options'], true);
        $countdownOptions = $options['countdownOptions'];
        $countdownContent = $data['countdown'];
        $obj->setCountdownContent($countdownContent);
        $obj->setCountdownOptions($countdownOptions);
        return parent::create($data, $obj);
    }
    public function save($data = array())
    {
        $editMode = $this->getId() ? true : false;
        $res      = parent::save($data);
        if ($res === false)
            return false;
        $countdownContent = $this->getCountdownContent();
        $countdownOptions = $this->getCountdownOptions();
        $id               = $this->getId();
        $model            = Mage::getModel("popupbuilder/Sgcountdownpopup");
        $fblikeDara       = array(
            'id' => $id,
            "content" => $countdownContent,
            "options" => $countdownOptions
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
        $model = Mage::getModel("popupbuilder/Sgcountdownpopup");
        $arr   = $model->load($id)->getData();
        $this->setCountdownContent($arr['content']);
        $this->setCountdownOptions($arr['options']);
    }
    public static function dateToSeconds($sgDueDate, $sgTimeZone)
    {
        date_default_timezone_set($sgTimeZone);
        $sgDueDateTime = $sgDueDate;
        $timeNow       = time();
        $seconds       = strtotime($sgDueDateTime) - $timeNow;
        if ($seconds < 0) {
            $seconds = 0;
        }
        return $seconds;
    }
    public static function renderScript($seconds, $sgContType, $content, $sgContLanguage)
    {
        return "<script type=\"text/javascript\">
        function sgInitPopupVariables() {
                var sgCountdown = new SGCountdown();

                sgCountdown.setInterval($seconds);

                sgCountdown.setCountdownType($sgContType);

                sgCountdown.setLanguage('$sgContLanguage');

                sgCountdown.render();

                sgCountdown.init();
        }
           
        </script>";
    }
    public static function renderStyle($countdownNumbersBgColor, $countdownNumbersTextColor)
    {
        echo "<style type='text/css'>

            .flip-clock-wrapper ul li a div div.inn {

                background-color: $countdownNumbersBgColor !important;

                color: $countdownNumbersTextColor !important;

            }

            .flip-clock-wrapper ul li a div.up {

                top: 0 !imporant;

            }

            .sg-countdown-wrapper {

                width: 500px !important;

                height: 130px !important;

                margin: 0 auto !important;

            }

            .sg-counts-content {

                disply: inline-block !important;

            }

            .sg-counts-content > ul.flip {

                width: 40px !important;

            }

        </style>";
    }

    public function renderData() { 
        $countdownContent = '';

        $countdownOptions    = json_decode($this->getCountdownOptions(), true);
        $sgCountdownPosition = $countdownOptions['pushToBottom'];
        $sgCountdownType     = $countdownOptions['sg-countdown-type'];

        $countdownContent .= "

        <script type='text/javascript'>
            jQuery('#sgcolorbox').on('sgColorboxOnCompleate',function(e) {
                if($sgCountdownType == 2) {

                    jQuery('.sg-counts-content').css({width: '340px',margin: '0 auto'});

                }

                else {

                    jQuery('.sg-counts-content').css({width: '461px',margin: '0 auto'});

                }

                jQuery('.sg-countdown-wrapper').css({'text-align': 'center','margin-top': '22px'});

                if(arguments[1] == '') { /* push to bottom param */

                    jQuery('.sg-countdown-wrapper').css({'text-align': 'center','position': 'absolute','bottom': '2px','left': '0','right': '0'});

                }

            });

        </script>

        <style>

            .flip-clock-wrapper ul li a div.up {

                top:0 !important;

            }

        </style>";
        $content                   = $this->getCountdownContent();
        $pushToBottom              = $countdownOptions['pushToBottom'];
        $countdownNumbersTextColor = $countdownOptions['countdownNumbersTextColor'];
        $countdownNumbersBgColor   = $countdownOptions['countdownNumbersBgColor'];
        $sgContType                = $countdownOptions['sg-countdown-type'];
        $sgContLanguage            = $countdownOptions['counts-language'];
        $closeType                 = @$countdownOptions['closeType'];
        $sgDueDate                 = $countdownOptions['sg-due-date'];
        $sgTimeZone                = $countdownOptions['sg-time-zone'];
        $seconds                   = SGCountdownPopup::dateToSeconds($sgDueDate, $sgTimeZone);
        $countdownContent .= SGCountdownPopup::renderStyle($countdownNumbersBgColor, $countdownNumbersTextColor);
        $countdownContent .= SGCountdownPopup::renderScript($seconds, $sgContType, $content, $sgContLanguage);

        return $countdownContent;
    }

    protected function getExtraRenderOptions()
    {
        $countdownContent = "";
        $countdownContent .= "
        <link rel='stylesheet' type='text/css' href='" . SG_SKIN_ADMIN_CSS_URL . "/sg_flipclock.css'>";
        $countdownOptions    = json_decode($this->getCountdownOptions(), true);
         $sgCountdownPosition = $countdownOptions['pushToBottom'];
        $sgCountdownType     = $countdownOptions['sg-countdown-type'];
        $sgFlipClockContent  = '<div class="sg-countdown-wrapper" id="sg-clear-coundown">

                                    <div class="sg-counts-content"></div>

                                </div>';
        $sgCountdownPosition = $countdownOptions['pushToBottom'];
        $sgCountdownType     = $countdownOptions['sg-countdown-type'];
        if ($sgCountdownPosition == 'on') {
            $countdownContent .= $sgFlipClockContent;
        }
        $countdownContent .= $this->improveContent($this->getCountdownContent());
        if ($sgCountdownPosition == '') {
            $countdownContent .= $sgFlipClockContent;
        }
        return array(
            'html' => $countdownContent
        );
    }
    public function render()
    {
        return parent::render();
    }
}
