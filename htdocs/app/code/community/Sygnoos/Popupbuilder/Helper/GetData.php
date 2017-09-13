<?php
class Sygnoos_Popupbuilder_Helper_GetData extends Mage_Core_Helper_Abstract
{
    public function getPageUrl($page)
    {
        $url = Mage::helper("adminhtml")->getUrl("adminhtml/popupbuilder/" . $page);
        return $url;
    }
    public function getWysiwygData()
    {
        $options       = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $storeMediaUrl = '';
        $options->addData(array(
            'add_variables' => true,
            'plugins' => array(),
            'widget_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/widget/index'),
            'directives_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive'),
            'directives_url_quoted' => preg_quote(Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg/directive')),
            'files_browser_window_url' => Mage::getSingleton('adminhtml/url')->getUrl('adminhtml/cms_wysiwyg_images/index'),
            'store_media_url' => $storeMediaUrl
        ));
        echo "<script>

            sgTinymceOptions = {
                'directives_url': '" . $options['directives_url'] . "',
                'popup_css': '" . $options['popup_css'] . "',
                'content_css': '" . $options['content_css'] . "',
                'files_browser_window_url': '" . $options['files_browser_window_url'] . "',
                'widget_plugin_src': '" . $options['widget_plugin_src'] . "',
                'widget_images_url': '" . $options['widget_images_url'] . "',
                'widget_window_url': '" . $options['widget_window_url'] . "',
                'directives_url_quoted': '" . $options['directives_url_quoted'] . "',
                'plugins_src': '" . @$options['plugins'][0]['src'] . "',
                'plugins_options_url': '" . @$options['plugins'][0]['options']["url"] . "',
                'plugins_options_onlick_subject': '" . @addslashes($options['plugins'][0]['options']["onclick"]['subject']) . "'
            }
        </script>";
    }
    public function getPopupData($id)
    {
        $popupData                                = array();
        $model                                    = Mage::getModel("popupbuilder/Sgpopup")->load($id);
        $params                                   = $model->getData();
        $popupType                                = @$params['type'];
        $modelname                                = "Sg" . $popupType . "popup";
        $popupTypeData                            = Mage::getModel("popupbuilder/$modelname")->load($id)->getData();
        $defaults                                 = Mage::helper('popupbuilder/Defaults')->dataArray();
        $popupProDefaultValues                    = @$defaults['defaluts'];
        $jsonData                                 = @json_decode($params['options'], true);
        $sgEscKey                                 = @$jsonData['escKey'];
        $sgPopoupTitle                            = @$params['title'];
        $sgOnceExpiresTime                        = @$jsonData['onceExpiresTime'];
        $sgScrolling                              = @$jsonData['scrolling'];
        $sgCloseButton                            = @$jsonData['closeButton'];
        $sgReposition                             = @$jsonData['reposition'];
        $sgOverlayClose                           = @$jsonData['overlayClose'];
        $sgOverlayColor                           = @$jsonData['sgOverlayColor'];
	    $sgBackgroundColor                        = @$jsonData['sgBackgroundColor'];
        $sgContentClick                           = @$jsonData['contentClick'];
        $sgOpacity                                = @$jsonData['opacity'];
        $sgPopupFixed                             = @$jsonData['popupFixed'];
        $sgFixedPostion                           = @$jsonData['fixedPostion'];
        $sgOnScrolling                            = @$jsonData['onScrolling'];
        $beforeScrolingPrsent                     = @$jsonData['beforeScrolingPrsent'];
        $duration                                 = @$jsonData['duration'];
        $delay                                    = @$jsonData['delay'];
        $effect                                   = @$jsonData['effect'];
        $sgInitialWidth                           = @$jsonData['initialWidth'];
        $sgInitialHeight                          = @$jsonData['initialHeight'];
        $sgWidth                                  = @$jsonData['width'];
        $sgHeight                                 = @$jsonData['height'];
        $sgMaxWidth                               = @$jsonData['maxWidth'];
        $sgMaxHeight                              = @$jsonData['maxHeight'];
        $sgForMobile                              = @$jsonData['forMobile'];
        $sgThemeCloseText                         = @$jsonData['theme-close-text'];
        $sgOpenOnMobile                           = @$jsonData['openMobile'];
        $sgAllPages                               = @$jsonData['allPages'];
        $sgRepeatPopup                            = @$jsonData['repeatPopup'];
        $sgDisablePopup                           = @$jsonData['disablePopup'];
        $sgDisablePopupOverlay                    = @$jsonData['disablePopupOverlay'];
        $sgPopupClosingTimer                      = @$jsonData['popupClosingTimer'];
        $sgAutoClosePopup                         = @$jsonData['autoClosePopup'];
        $sgCountryStatus                          = $jsonData['countryStatus'];
        $sgAllPagesStatus                         = $jsonData['showAllPages'];
        $allSelectedPages                         = @$jsonData['allSelectedPages'];
        $sfAllPostStatus                          = $jsonData['showAllPosts'];
        $sgAllProducts                          = @$jsonData['allProducts'];
        $sgAllCategoriesStatus                    = @$jsonData['showAllCategories'];
        $allSelectedPosts                         = @$jsonData['allSelectedPosts'];
        $allSelectedCategories                    = @$jsonData['allSelectedCategories'];
        $allProductStores                         = @$jsonData['allProductStores'];
        $sgAllowCountries                         = @$jsonData['allowCountries'];
        $sgCountryName                            = @$jsonData['countryName'];
        $sgCountryIso                             = @$jsonData['countryIso'];
        $sgColorboxTheme                          = @$jsonData['theme'];
        $sgOverlayCustomClasss                    = @$jsonData['sgOverlayCustomClasss'];
        $sgContentCustomClasss                    = @$jsonData['sgContentCustomClasss'];
        $ageRestriction                           = @$jsonData['ageRestrictionOptions'];
        $sgSocialOptions                          = @$popupTypeData['buttons'];
        $sgSocialButtons                          = @$popupTypeData['socialOptions'];
        $exitIntentOptions                        = @$popupTypeData['options'];
        $subScriptionOption                       = @$popupTypeData['options'];
        $contactForm                              = @$popupTypeData['options'];
        $videoOptions                             = @$popupTypeData['options'];
        $countdowunOptions                        = @$popupTypeData['options'];
        $ageRestrictionOptions                    = json_decode($ageRestriction, true);
        $sgSocialOptions                          = json_decode(@$sgSocialOptions, true);
        $sgSocialButtons                          = json_decode(@$sgSocialButtons, true);
        $exitIntentOptions                        = json_decode($exitIntentOptions, true);
        $subScriptionOption                       = json_decode($subScriptionOption, true);
        $contactFormOptions                       = json_decode($contactForm, true);
        $videoOptions                             = json_decode($videoOptions, true);
        $countdowunOptions                        = json_decode($countdowunOptions, true);
        $sgCountdownNumbersTextColor              = @$countdowunOptions['countdownNumbersTextColor'];
        $sgCountdownNumbersBgColor                = @$countdowunOptions['countdownNumbersBgColor'];
        $sgCountdownPosition                      = @$countdowunOptions['countdown-position'];
        $sgCountdownLang                          = @$countdowunOptions['counts-language'];
        $sgSelectedTimeZone                       = @$countdowunOptions['sg-time-zone'];
        $sgDueDate                                = @$countdowunOptions['sg-due-date'];
        $sgGetCountdownType                       = @$countdowunOptions['sg-countdown-type'];
        $sgVideoAutopaly                          = @$videoOptions['video-autoplay'];
        $sgContactNameLabel                       = @$contactFormOptions['contact-name'];
        $sgContactSubjectLabel                    = @$contactFormOptions['contact-subject'];
        $sgContactEmailLabel                      = @$contactFormOptions['contact-email'];
        $sgContactMessageLabel                    = @$contactFormOptions['contact-message'];
        $sgContactValidationMessage               = @$contactFormOptions['contact-validation-message'];
        $sgContactSuccessMessage                  = @$contactFormOptions['contact-success-message'];
        $sgContactInputsWidth                     = @$contactFormOptions['contact-inputs-width'];
        $sgContactInputsHeight                    = @$contactFormOptions['contact-inputs-height'];
        $sgContactInputsBorderWidth               = @$contactFormOptions['contact-inputs-border-width'];
        $sgContactTextInputBgcolor                = @$contactFormOptions['contact-text-input-bgcolor'];
        $sgContactTextBordercolor                 = @$contactFormOptions['contact-text-bordercolor'];
        $sgContactInputsColor                     = @$contactFormOptions['contact-inputs-color'];
        $sgContactPlaceholderColor                = @$contactFormOptions['contact-placeholder-color'];
        $sgContactBtnWidth                        = @$contactFormOptions['contact-btn-width'];
        $sgContactBtnHeight                       = @$contactFormOptions['contact-btn-height'];
        $sgContactBtnTitle                        = @$contactFormOptions['contact-btn-title'];
        $sgContactBtnProgressTitle                = @$contactFormOptions['contact-btn-progress-title'];
        $sgContactButtonBgcolor                   = @$contactFormOptions['contact-button-bgcolor'];
        $sgContactButtonColor                     = @$contactFormOptions['contact-button-color'];
        $sgContactAreaWidth                       = @$contactFormOptions['contact-area-width'];
        $sgContactAreaHeight                      = @$contactFormOptions['contact-area-height'];
        $sgContactResize                          = @$contactFormOptions['sg-contact-resize'];
        $sgContactValidateEmail                   = @$contactFormOptions['contact-validate-email'];
        $sgContactResiveMail                      = @$contactFormOptions['contact-resive-email'];
        $sgContactFailMessage                     = @$contactFormOptions['contact-fail-message'];
        $sgSubsFirstNameStatus                    = @$subScriptionOption['subs-first-name-status'];
        $sgSubsLastNameStatus                     = @$subScriptionOption['subs-last-name-status'];
        $sgSubsTextWidth                          = @$subScriptionOption['subs-text-width'];
        $sgSubsBtnWidth                           = @$subScriptionOption['subs-btn-width'];
        $sgSubsTextInputBgcolor                   = @$subScriptionOption['subs-text-input-bgcolor'];
        $sgSubsButtonBgcolor                      = @$subScriptionOption['subs-button-bgcolor'];
        $sgSubsTextBordercolor                    = @$subScriptionOption['subs-text-bordercolor'];
        $sgSubscriptionEmail                      = @$subScriptionOption['subscription-email'];
        $sgSubsFirstName                          = @$subScriptionOption['subs-first-name'];
        $sgSubsLastName                           = @$subScriptionOption['subs-last-name'];
        $sgSubsButtonColor                        = @$subScriptionOption['subs-button-color'];
        $sgSubsInputsColor                        = @$subScriptionOption['subs-inputs-color'];
        $sgSubsBtnTitle                           = @$subScriptionOption['subs-btn-title'];
        $sgSubsPlaceholderColor                   = @$subScriptionOption['subs-placeholder-color'];
        $sgSubsTextHeight                         = @$subScriptionOption['subs-text-height'];
        $sgSubsBtnHeight                          = @$subScriptionOption['subs-btn-height'];
        $sgSuccessMessage                         = @$subScriptionOption['subs-success-message'];
        $sgSubsEmailValidate                      = @$subScriptionOption['subs-email-validate'];
        $sgSubsValidateMessage                    = @$subScriptionOption['subs-validation-message'];
        $sgSubsBtnProgressTitle                   = @$subScriptionOption['sgSubsBtnProgressTitle'];
        $sgSubsTextBorderWidth                    = @$subScriptionOption['"subs-text-border-width'];
        $sgExitIntentTpype                        = @$exitIntentOptions['exit-intent-type'];
        $sgExitIntntExpire                        = @$exitIntentOptions['exit-intent-expire-time'];
        $sgExitIntentAlert                        = @$exitIntentOptions['exit-intent-alert'];
        $sgShareUrl                               = @$sgSocialButtons['sgShareUrl'];
        $shareUrlType                             = @$this->sgSafeStr($sgSocialButtons['shareUrlType']);
        $fbShareLabel                             = @$this->sgSafeStr($sgSocialButtons['fbShareLabel']);
        $lindkinLabel                             = @$this->sgSafeStr($sgSocialButtons['lindkinLabel']);
        $googLelabel                              = @$this->sgSafeStr($sgSocialButtons['googLelabel']);
        $twitterLabel                             = @$this->sgSafeStr($sgSocialButtons['twitterLabel']);
        $pinterestLabel                           = @$this->sgSafeStr($sgSocialButtons['pinterestLabel']);
        $sgMailSubject                            = @$this->sgSafeStr($sgSocialButtons['sgMailSubject']);
        $sgMailLable                              = @$this->sgSafeStr($sgSocialButtons['sgMailLable']);
        $sgTwitterStatus                          = @$this->sgSetChecked($sgSocialOptions['sgTwitterStatus']);
        $sgFbStatus                               = @$this->sgSetChecked($sgSocialOptions['sgFbStatus']);
        $sgEmailStatus                            = @$this->sgSetChecked($sgSocialOptions['sgEmailStatus']);
        $sgLinkedinStatus                         = @$this->sgSetChecked($sgSocialOptions['sgLinkedinStatus']);
        $sgGoogleStatus                           = @$this->sgSetChecked($sgSocialOptions['sgGoogleStatus']);
        $sgPinterestStatus                        = @$this->sgSetChecked($sgSocialOptions['sgPinterestStatus']);
        $sgSocialTheme                            = @$this->sgSafeStr($sgSocialButtons['sgSocialTheme']);
        $sgSocialButtonsSize                      = @$this->sgSafeStr($sgSocialButtons['sgSocialButtonsSize']);
        $sgSocialLabel                            = @$this->sgSetChecked($sgSocialButtons['sgSocialLabel']);
        $sgSocialShareCount                       = @$this->sgSetChecked($sgSocialButtons['sgSocialShareCount']);
        $sgRoundButton                            = @$this->sgSetChecked($sgSocialButtons['sgRoundButton']);
        $sgPushToBottom                           = @$this->sgSetChecked($sgSocialOptions['pushToBottom']);
        $defaults                                 = Mage::helper('popupbuilder/Defaults');
        // Getting defaults 2 array 
        $dataArray                                = $defaults->dataArray();
        $sgPopup                                  = $dataArray['popupValues'];
        $width                                    = $sgPopup['width'];
        $height                                   = $sgPopup['height'];
        $opacityValue                             = $sgPopup['opacity'];
        $top                                      = $sgPopup['top'];
        $right                                    = $sgPopup['right'];
        $bottom                                   = $sgPopup['bottom'];
        $left                                     = $sgPopup['left'];
        $initialWidth                             = $sgPopup['initialWidth'];
        $initialHeight                            = $sgPopup['initialHeight'];
        $maxWidth                                 = $sgPopup['maxWidth'];
        $maxHeight                                = $sgPopup['maxHeight'];
        $deafultFixed                             = $sgPopup['fixed'];
        $defaultDuration                          = $sgPopup['duration'];
        $defaultDelay                             = $sgPopup['delay'];
        $themeCloseText                           = $sgPopup['theme-close-text'];
        $escKey                                   = $this->sgBoolToChecked($sgPopup['escKey']);
        $closeButton                              = $this->sgBoolToChecked($sgPopup['closeButton']);
        $scrolling                                = $this->sgBoolToChecked($sgPopup['scrolling']);
        $reposition                               = $this->sgBoolToChecked($sgPopup['reposition']);
        $overlayClose                             = $this->sgBoolToChecked($sgPopup['overlayClose']);
        $contentClick                             = $this->sgBoolToChecked($sgPopup['contentClick']);
        $closeType                                = $this->sgBoolToChecked($popupProDefaultValues['closeType']);
        $onScrolling                              = $this->sgBoolToChecked($popupProDefaultValues['onScrolling']);
        $forMobile                                = $this->sgBoolToChecked($popupProDefaultValues['forMobile']);
        $openMobile                               = $this->sgBoolToChecked($popupProDefaultValues['openMobile']);
        $repetPopup                               = $this->sgBoolToChecked($popupProDefaultValues['repetPopup']);
        $disablePopup                             = $this->sgBoolToChecked($popupProDefaultValues['disablePopup']);
        $disablePopupOverlay                      = $this->sgBoolToChecked($popupProDefaultValues['disablePopupOverlay']);
        $autoClosePopup                           = $this->sgBoolToChecked($popupProDefaultValues['autoClosePopup']);
        $fbStatus                                 = $this->sgBoolToChecked($popupProDefaultValues['fbStatus']);
        $twitterStatus                            = $this->sgBoolToChecked($popupProDefaultValues['twitterStatus']);
        $emailStatus                              = $this->sgBoolToChecked($popupProDefaultValues['emailStatus']);
        $linkedinStatus                           = $this->sgBoolToChecked($popupProDefaultValues['linkedinStatus']);
        $googleStatus                             = $this->sgBoolToChecked($popupProDefaultValues['googleStatus']);
        $pinterestStatus                          = $this->sgBoolToChecked($popupProDefaultValues['pinterestStatus']);
        $socialLabel                              = $this->sgBoolToChecked($popupProDefaultValues['sgSocialLabel']);
        $roundButtons                             = $this->sgBoolToChecked($popupProDefaultValues['roundButtons']);
        $shareUrl                                 = $popupProDefaultValues['sgShareUrl'];
        $pushToBottom                             = $this->sgBoolToChecked($popupProDefaultValues['pushToBottom']);
        $allPages                                 = $this->sgBoolToChecked($popupProDefaultValues['allPages']);
        $allPosts                                 = $this->sgBoolToChecked($popupProDefaultValues['allPosts']);
        $allProducts                                 = $this->sgBoolToChecked($popupProDefaultValues['allProducts']);
        $allCategories                                 = $this->sgBoolToChecked($popupProDefaultValues['allCategories']);
        $onceExpiresTime                          = $popupProDefaultValues['onceExpiresTime'];
        $countryStatus                            = $this->sgBoolToChecked($popupProDefaultValues['countryStatus']);
        $allowCountries                           = $popupProDefaultValues['allowCountries'];
        $countdownNumbersTextColor                = $popupProDefaultValues['countdownNumbersTextColor'];
        $countdownNumbersBgColor                  = $popupProDefaultValues['countdownNumbersBgColor'];
        $countdownLang                            = $popupProDefaultValues['countDownLang'];
        $countdownPosition                        = $popupProDefaultValues['countdown-position'];
        $videoAutoplay                            = $popupProDefaultValues['video-autoplay'];
        $timeZone                                 = $popupProDefaultValues['time-zone'];
        $dueDate                                  = $popupProDefaultValues['due-date'];
        $dueDate                                  = @$popupProDefaultValues['overlayCustomClasss'];
        $exitIntentType                           = $popupProDefaultValues['exit-intent-type'];
        $exitIntentExpireTime                     = $popupProDefaultValues['exit-intent-expire-time'];
        $subsFirstNameStatus                      = $this->sgBoolToChecked($popupProDefaultValues['subs-first-name-status']);
        $subsLastNameStatus                       = $this->sgBoolToChecked($popupProDefaultValues['subs-last-name-status']);
        $subscriptionEmail                        = $popupProDefaultValues['subscription-email'];
        $subsFirstName                            = $popupProDefaultValues['subs-first-name'];
        $subsLastName                             = $popupProDefaultValues['subs-last-name'];
        $subsButtonBgcolor                        = $popupProDefaultValues['subs-button-bgcolor'];
        $subsButtonColor                          = $popupProDefaultValues['subs-button-color'];
        $subsInputsColor                          = $popupProDefaultValues['subs-inputs-color'];
        $subsBtnTitle                             = $popupProDefaultValues['subs-btn-title'];
        $subsPlaceholderColor                     = $popupProDefaultValues['subs-placeholder-color'];
        $subsTextHeight                           = $popupProDefaultValues['subs-text-height'];
        $subsBtnHeight                            = $popupProDefaultValues['subs-btn-height'];
        $subsSuccessMessage                       = $popupProDefaultValues['subs-success-message'];
        $subsValidationMessage                    = $popupProDefaultValues['subs-validation-message'];
        $subsTextWidth                            = $popupProDefaultValues['subs-text-width'];
        $subsBtnWidth                             = $popupProDefaultValues['subs-btn-width'];
        $subsBtnProgressTitle                     = $popupProDefaultValues['subs-btn-progress-title'];
        $subsTextBorderWidth                      = $popupProDefaultValues['subs-text-border-width'];
        $subsTextBordercolor                      = $popupProDefaultValues['subs-text-bordercolor'];
        $subsTextInputBgcolor                     = $popupProDefaultValues['subs-text-input-bgcolor'];
        $contactName                              = $popupProDefaultValues['contact-name'];
        $contactEmail                             = $popupProDefaultValues['contact-email'];
        $contactMessage                           = $popupProDefaultValues['contact-message'];
        $contactSubject                           = $popupProDefaultValues['contact-subject'];
        $contactSuccessMessage                    = $popupProDefaultValues['contact-success-message'];
        $contactBtnTitle                          = $popupProDefaultValues['contact-btn-title'];
        $contactValidateEmail                     = $popupProDefaultValues['contact-validate-email'];
        $overlayCustomClasss                      = $popupProDefaultValues['overlay-custom-classs'];
        $contentCustomClasss                      = $popupProDefaultValues['content-custom-classs'];
        $contactFailMessage                       = $popupProDefaultValues['contact-fail-message'];
        $popupData['sgCloseButton']               = @$this->sgSetChecked($sgCloseButton, $closeButton);
        $popupData['sgEscKey']                    = @$this->sgSetChecked($sgEscKey, $escKey);
        $popupData['sgContentClick']              = @$this->sgSetChecked($sgContentClick, $contentClick);
        $popupData['sgOverlayClose']              = @$this->sgSetChecked($sgOverlayClose, $overlayClose);
        $popupData['sgReposition']                = @$this->sgSetChecked($sgReposition, $reposition);
        $popupData['sgScrolling']                 = @$this->sgSetChecked($sgScrolling, $scrolling);
        $popupData['sgCloseType']                 = @$this->sgSetChecked($sgCloseType, $closeType);
        $popupData['sgVideoAutopaly']             = @$this->sgSetChecked($sgVideoAutopaly, $videoAutoplay);
        $popupData['sgOnScrolling']               = @$this->sgSetChecked($sgOnScrolling, $onScrolling);
        $popupData['sgForMobile']                 = @$this->sgSetChecked($sgForMobile, $forMobile);
        $popupData['sgOpenOnMobile']              = @$this->sgSetChecked($sgOpenOnMobile, $openMobile);
        $popupData['sgRepeatPopup']               = @$this->sgSetChecked($sgRepeatPopup, $repetPopup);
        $popupData['sgDisablePopup']              = @$this->sgSetChecked($sgDisablePopup, $disablePopup);
        $popupData['sgDisablePopupOverlay']       = @$this->sgSetChecked($sgDisablePopupOverlay, $disablePopupOverlay);
        $popupData['sgAutoClosePopup']            = @$this->sgSetChecked($sgAutoClosePopup, $autoClosePopup);
        $popupData['sgFbStatus']                  = @$this->sgSetChecked($sgFbStatus, $fbStatus);
        $popupData['sgTwitterStatus']             = @$this->sgSetChecked($sgTwitterStatus, $twitterStatus);
        $popupData['sgEmailStatus']               = @$this->sgSetChecked($sgEmailStatus, $emailStatus);
        $popupData['sgLinkedinStatus']            = @$this->sgSetChecked($sgLinkedinStatus, $linkedinStatus);
        $popupData['sgGoogleStatus']              = @$this->sgSetChecked($sgGoogleStatus, $googleStatus);
        $popupData['sgPinterestStatus']           = @$this->sgSetChecked($sgPinterestStatus, $pinterestStatus);
        $popupData['sgRoundButtons']              = @$this->sgSetChecked($sgRoundButton, $roundButtons);
        $popupData['sgSocialLabel']               = @$this->sgSetChecked($sgSocialLabel, $socialLabel);
        $popupData['sgPopupFixed']                = @$this->sgSetChecked($sgPopupFixed, $deafultFixed);
        $popupData['sgPushToBottom']              = @$this->sgSetChecked($sgPushToBottom, $pushToBottom);
        $popupData['sgAllPages']                  = @$this->sgSetChecked($sgAllPagesStatus, $allPages);
        $popupData['sgAllPosts']                  = @$this->sgSetChecked($sfAllPostStatus, $allPosts);
        $popupData['sgAllProducts']                  = @$this->sgSetChecked($sgAllProducts, $allProducts);
        $popupData['sgAllCategories']                  = @$this->sgSetChecked($sgAllCategoriesStatus, $allCategories);
        $popupData['sgCountdownPosition']         = @$this->sgSetChecked($sgCountdownPosition, $countdownPosition);
        $popupData['sgVideoAutoplay']             = @$this->sgSetChecked($sgVideoAutoplay, $videoAutoplay);
        $popupData['sgSubsLastNameStatus']        = @$this->sgSetChecked($sgSubsLastNameStatus, $subsLastNameStatus);
        $popupData['sgSubsFirstNameStatus']       = @$this->sgSetChecked($sgSubsFirstNameStatus, $subsFirstNameStatus);
        $popupData['sgCountryStatus']             = @$this->sgSetChecked($sgCountryStatus, $countryStatus);
        $popupData['sgOpacity']                   = @$this->sgGetValue($sgOpacity, $opacityValue);
        $popupData['effect']                      = @$this->sgGetValue($effect, '');
        $popupData['sgWidth']                     = @$this->sgGetValue($sgWidth, $width);
        $popupData['sgHeight']                    = @$this->sgGetValue($sgHeight, $height);
        $popupData['sgInitialWidth']              = @$this->sgGetValue($sgInitialWidth, $initialWidth);
        $popupData['sgInitialHeight']             = @$this->sgGetValue($sgInitialHeight, $initialHeight);
        $popupData['sgMaxWidth']                  = @$this->sgGetValue($sgMaxWidth, $maxWidth);
        $popupData['sgMaxHeight']                 = @$this->sgGetValue($sgMaxHeight, $maxHeight);
        $popupData['duration']                    = @$this->sgGetValue($duration, $defaultDuration);
        $popupData['sgOnceExpiresTime']           = @$this->sgGetValue($sgOnceExpiresTime, $onceExpiresTime);
        $popupData['delay']                       = @$this->sgGetValue($delay, $defaultDelay);
        $popupData['sgThemeCloseText']            = @$this->sgGetValue($sgThemeCloseText, $themeCloseText);
        $popupData['sgPopupDataIframe']           = @$this->sgGetValue($sgPopupDataIframe, 'http://');
        $popupData['sgShareUrl']                  = @$this->sgGetValue($sgShareUrl, $shareUrl);
        $popupData['sgPopupDataHtml']             = @$this->sgGetValue($sgPopupDataHtml, '');
        $popupData['sgPopupDataImage']            = @$this->sgGetValue($sgPopupDataImage, '');
        $popupData['sgAllowCountries']            = @$this->sgGetValue($sgAllowCountries, $allowCountries);
        $popupData['sgCountdownNumbersTextColor'] = @$this->sgGetValue($sgCountdownNumbersTextColor, $countdownNumbersTextColor);
        $popupData['sgCountdownNumbersBgColor']   = @$this->sgGetValue($sgCountdownNumbersBgColor, $countdownNumbersBgColor);
        $popupData['sgCountdownLang']             = @$this->sgGetValue($sgCountdownLang, $countdownLang);
        $popupData['sgSelectedTimeZone']          = @$this->sgGetValue($sgSelectedTimeZone, $timeZone);
        $popupData['sgDueDate']                   = @$this->sgGetValue($sgDueDate, $dueDate);
        $popupData['sgExitIntentTpype']           = @$this->sgGetValue($sgExitIntentTpype, $exitIntentType);
        $popupData['sgExitIntntExpire']           = @$this->sgGetValue($sgExitIntntExpire, $exitIntentExpireTime);
        $popupData['sgSubsTextWidth']             = @$this->sgGetValue($sgSubsTextWidth, $subsTextWidth);
        $popupData['sgSubsBtnWidth']              = @$this->sgGetValue($sgSubsBtnWidth, $subsBtnWidth);
        $popupData['sgSubsTextInputBgcolor']      = @$this->sgGetValue($sgSubsTextInputBgcolor, $subsTextInputBgcolor);
        $popupData['sgSubsButtonBgcolor']         = @$this->sgGetValue($sgSubsButtonBgcolor, $subsButtonBgcolor);
        $popupData['sgSubsTextBordercolor']       = @$this->sgGetValue($sgSubsTextBordercolor, $subsTextBordercolor);
        $popupData['sgSubscriptionEmail']         = @$this->sgGetValue($sgSubscriptionEmail, $subscriptionEmail);
        $popupData['sgSubsFirstName']             = @$this->sgGetValue($sgSubsFirstName, $subsFirstName);
        $popupData['sgSubsLastName']              = @$this->sgGetValue($sgSubsLastName, $subsLastName);
        $popupData['sgSubsButtonColor']           = @$this->sgGetValue($sgSubsButtonColor, $subsButtonColor);
        $popupData['sgSubsInputsColor']           = @$this->sgGetValue($sgSubsInputsColor, $subsInputsColor);
        $popupData['sgSubsBtnTitle']              = @$this->sgGetValue($sgSubsBtnTitle, $subsBtnTitle);
        $popupData['sgSubsPlaceholderColor']      = @$this->sgGetValue($sgSubsPlaceholderColor, $subsPlaceholderColor);
        $popupData['sgSubsTextHeight']            = @$this->sgGetValue($sgSubsTextHeight, $subsTextHeight);
        $popupData['sgSubsBtnHeight']             = @$this->sgGetValue($sgSubsBtnHeight, $subsBtnHeight);
        $popupData['sgSuccessMessage']            = @$this->sgGetValue($sgSuccessMessage, $subsSuccessMessage);
        $popupData['sgSubsEmailValidate']         = @$this->sgGetValue($sgSubsEmailValidate, $contactValidateEmail);
        $popupData['sgSubsValidateMessage']       = @$this->sgGetValue($sgSubsValidateMessage, $subsValidationMessage);
        $popupData['sgSubsBtnProgressTitle']      = @$this->sgGetValue($sgSubsBtnProgressTitle, $subsBtnProgressTitle);
        $popupData['sgSubsTextBorderWidth']       = @$this->sgGetValue($sgSubsTextBorderWidth, $subsTextBorderWidth);
        $popupData['sgContactNameLabel']          = @$this->sgGetValue($sgContactNameLabel, $contactName);
        $popupData['sgContactSubjectLabel']       = @$this->sgGetValue($sgContactSubjectLabel, $contactSubject);
        $popupData['sgContactEmailLabel']         = @$this->sgGetValue($sgContactEmailLabel, $contactEmail);
        $popupData['sgContactMessageLabel']       = @$this->sgGetValue($sgContactMessageLabel, $contactMessage);
        $popupData['sgContactValidationMessage']  = @$this->sgGetValue($sgContactValidationMessage, $subsValidationMessage);
        $popupData['sgContactSuccessMessage']     = @$this->sgGetValue($sgContactSuccessMessage, $contactSuccessMessage);
        $popupData['sgContactInputsWidth']        = @$this->sgGetValue($sgContactInputsWidth, $subsTextWidth);
        $popupData['sgContactInputsHeight']       = @$this->sgGetValue($sgContactInputsHeight, $subsTextHeight);
        $popupData['sgContactInputsBorderWidth']  = @$this->sgGetValue($sgContactInputsBorderWidth, $subsTextBorderWidth);
        $popupData['sgContactTextInputBgcolor']   = @$this->sgGetValue($sgContactTextInputBgcolor, $subsTextInputBgcolor);
        $popupData['sgContactTextBordercolor']    = @$this->sgGetValue($sgContactTextBordercolor, $subsTextBordercolor);
        $popupData['sgContactInputsColor']        = @$this->sgGetValue($sgContactInputsColor, $subsInputsColor);
        $popupData['sgContactPlaceholderColor']   = @$this->sgGetValue($sgContactPlaceholderColor, $subsPlaceholderColor);
        $popupData['sgContactBtnWidth']           = @$this->sgGetValue($sgContactBtnWidth, $subsBtnWidth);
        $popupData['sgContactBtnHeight']          = @$this->sgGetValue($sgContactBtnHeight, $subsBtnHeight);
        $popupData['sgContactBtnTitle']           = @$this->sgGetValue($sgContactBtnTitle, $contactBtnTitle);
        $popupData['sgContactBtnProgressTitle']   = @$this->sgGetValue($sgContactBtnProgressTitle, $subsBtnProgressTitle);
        $popupData['sgContactButtonBgcolor']      = @$this->sgGetValue($sgContactButtonBgcolor, $subsButtonBgcolor);
        $popupData['sgContactButtonColor']        = @$this->sgGetValue($sgContactButtonColor, $subsButtonColor);
        $popupData['sgContactAreaWidth']          = @$this->sgGetValue($sgContactAreaWidth, $subsTextWidth);
        $popupData['sgContactAreaHeight']         = @$this->sgGetValue($sgContactAreaHeight, '');
        $popupData['sgContactValidateEmail']      = @$this->sgGetValue($sgContactValidateEmail, $contactValidateEmail);
        $popupData['sgContactFailMessage']        = @$this->sgGetValue($sgContactFailMessage, $contactFailMessage);
        $popupData['sgOverlayCustomClasss']       = @$this->sgGetValue($sgOverlayCustomClasss, $overlayCustomClasss);
        $popupData['sgContentCustomClasss']       = @$this->sgGetValue($sgContentCustomClasss, $contentCustomClasss);
        $popupData['popupTypeData']               = $popupTypeData;
        $popupData['sgFixedPostion']              = $sgFixedPostion;
        $popupData['popuptitle']                  = $sgPopoupTitle;
        $popupData['allSelectedPages']            = $allSelectedPages;
        $popupData['allSelectedPosts']            = $allSelectedPosts;
        $popupData['allSelectedCategories']            = $allSelectedCategories;
        $popupData['allProductStores']            = $allProductStores;
        $popupData['sgOverlayColor']              = $sgOverlayColor;
        $popupData['sgBackgroundColor']           = $sgBackgroundColor;
        $popupData['sgColorboxTheme']             = $sgColorboxTheme;
        $popupData['beforeScrolingPrsent']        = $beforeScrolingPrsent;
        $popupData['sgCountryName']               = $sgCountryName;
        $popupData['sgCountryIso']                = $sgCountryIso;
        $popupData['sgPopupClosingTimer']         = $sgPopupClosingTimer;
        $popupData['yesButtonBackgroundColor']    = $this->sgGetValue($ageRestrictionOptions['yesButtonBackgroundColor'], $popupProDefaultValues['restrcition-yes-background-color']);
        $popupData['noButtonBackgroundColor']     = $this->sgGetValue($ageRestrictionOptions['noButtonBackgroundColor'], $popupProDefaultValues['restriction-no-background-color']);
        $popupData['yesButtonTextColor']          = $this->sgGetValue($ageRestrictionOptions['yesButtonTextColor'], $popupProDefaultValues['restrcition-yes-text-color']);
        $popupData['noButtonTextColor']           = $this->sgGetValue($ageRestrictionOptions['noButtonTextColor'], $popupProDefaultValues['restrcition-no-text-color']);
        $popupData['yesButtonRadius']             = $this->sgGetValue($ageRestrictionOptions['yesButtonRadius'], $popupProDefaultValues['restrcition-yes-border-radius']);
        $popupData['noButtonRadius']              = $this->sgGetValue($ageRestrictionOptions['noButtonRadius'], $popupProDefaultValues['restrcition-no-border-radius']);
        $popupData['pushToBottom']                = $this->sgBoolToChecked($ageRestrictionOptions['pushToBottom']);
        $popupData['shareUrlType']                = $shareUrlType;
        $popupData['fbShareLabel']                = $fbShareLabel;
        $popupData['lindkinLabel']                = $lindkinLabel;
        $popupData['googLelabel']                 = $googLelabel;
        $popupData['twitterLabel']                = $twitterLabel;
        $popupData['pinterestLabel']              = $pinterestLabel;
        $popupData['sgMailSubject']               = $sgMailSubject;
        $popupData['sgMailLable']                 = $sgMailLable;
        $popupData['sgSocialTheme']               = $sgSocialTheme;
        $popupData['sgSocialButtonsSize']         = $sgSocialButtonsSize;
        $popupData['sgExitIntentAlert']           = $sgExitIntentAlert;
        $popupData['sgGetCountdownType']          = $sgGetCountdownType;
        $popupData['sgContactResiveMail']         = $sgContactResiveMail;
        return $popupData;
    }
    public function sgBoolToChecked($var)
    {
        return ($var ? 'checked' : '');
    }
    public function sgGetValue($getedVal, $defValue)
    {
        if (!isset($getedVal)) {
            return htmlspecialchars($defValue);
        } else {
            return htmlspecialchars($getedVal);
        }
    }
    public function sgSetChecked($optionsParam, $defaultOption)
    {
        if (isset($optionsParam)) {
            if ($optionsParam == '') {
                return '';
            } else {
                return 'checked';
            }
        } else {
            return $defaultOption;
        }
    }
    public function sgSafeStr($param)
    {
        return ($param === null ? '' : $param);
    }
}