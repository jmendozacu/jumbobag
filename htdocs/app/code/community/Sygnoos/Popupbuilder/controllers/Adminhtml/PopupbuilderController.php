<?php
require_once(Mage::getModuleDir('', 'Sygnoos_Popupbuilder').DS.'public'.DS.'boot.php');
class Sygnoos_Popupbuilder_Adminhtml_PopupbuilderController extends Mage_Adminhtml_Controller_Action
{
	public function renderLayoutAction()
	{
		$this->loadLayout();
		$this->renderLayout();
	}
	public function popupsAction()
	{
		$this->renderLayoutAction();
	}
	public function addNewAction()
	{
		$this->renderLayoutAction();
	}
	public function editAction()
	{
		$this->loadLayout();
		$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
		$this->renderLayout();
	}
	public function subscribersAction()
	{
		$this->loadLayout();
		$this->renderLayout();
	}
	public function saveAction()
	{
		global $sgPopupPostData;
		$extraData       = array();
		$sgPopupPostData = $_POST;
		// get assoc array for save sg_popup table
		$sgPopupData     = $this->sgPopup();
		die();
	}
	public function sgSanitize($optionsKey)
	{
		global $sgPopupPostData;
		if (isset($sgPopupPostData[$optionsKey])) {
			return $sgPopupPostData[$optionsKey]; //if you need striptag use strip_tags
		} else {
			return "";
		}
	}
	public function sgPopup()
	{
		$type             = $this->sgSanitize('type');
		$title            = $this->sgSanitize('title');
		$editId           = $this->sgSanitize('hidden_popup_number');
		$image            = @$_FILES['ad_image'];
		$showAllPages     = $this->sgSanitize('allPages');
		$allPosts         = $this->sgSanitize('allPosts');
		$allProducts         = $this->sgSanitize('allProducts');
		$allCategories    = $this->sgSanitize('allCategories');
		$allSelectedPages = $this->sgSanitize('all-selected-page');
		$allSelectedPosts = $this->sgSanitize('all-selected-posts');
		$allProductStores = $this->sgSanitize('all-product-stores');
		$allSelectedCategories = $this->sgSanitize('all-product-categories');
		$html             = stripslashes($this->sgSanitize('sg_popup_html'));
		$fblike           = $this->sgSanitize('sg-popup-fblike');
		$iframeUrl        = stripslashes($this->sgSanitize('iframe'));
		$video            = $this->sgSanitize('video');
		$ageRestriction   = stripslashes($this->sgSanitize('sg_ageRestriction'));
		$countdown        = stripslashes($this->sgSanitize('sg_countdown'));
		$social           = stripslashes($this->sgSanitize('sg_social'));
		$exitIntent       = stripslashes($this->sgSanitize('sg-exit-intent'));
		$subscription     = stripslashes($this->sgSanitize('sg_subscription'));
		$contactForm      = stripslashes($this->sgSanitize('sg_contactForm'));
		$popupName        = "SG" . ucfirst(strtolower($type));
		
		if($allSelectedPosts != '') {
			$allSelectedPosts = explode(",", $allSelectedPosts);
		}

		$popupClassName   = $popupName . "Popup";
		require_once(dirname(__FILE__) . '/../../public/classes/' . $popupClassName . ".php");
		$fblikeOptions         = array(
			'fblike-like-url' => $this->sgSanitize('fblike-like-url'),
			'fblike-layout' => $this->sgSanitize('fblike-layout')
		);
		$videoOptions          = array(
			'video-autoplay' => $this->sgSanitize('video-autoplay')
		);
		$ageRestrictionOptions = array(
			'yesButtonLabel' => $this->sgSanitize('yesButtonLabel'),
			'noButtonLabel' => $this->sgSanitize('noButtonLabel'),
			'restrictionUrl' => $this->sgSanitize('restrictionUrl'),
			'yesButtonBackgroundColor' => $this->sgSanitize('yesButtonBackgroundColor'),
			'noButtonBackgroundColor' => $this->sgSanitize('noButtonBackgroundColor'),
			'yesButtonTextColor' => $this->sgSanitize('yesButtonTextColor'),
			'noButtonTextColor' => $this->sgSanitize('noButtonTextColor'),
			'yesButtonRadius' => (int) $this->sgSanitize('yesButtonRadius'),
			'noButtonRadius' => (int) $this->sgSanitize('noButtonRadius'),
			'pushToBottom' => $this->sgSanitize('pushToBottom'),
			'onceExpiresTime' => $this->sgSanitize('onceExpiresTime'),
			'sgOverlayCustomClasss' => $this->sgSanitize('sgOverlayCustomClasss'),
			'sgContentCustomClasss' => $this->sgSanitize('sgContentCustomClasss'),
			'theme-close-text' => $this->sgSanitize('theme-close-text')
		);
		$countdownOptions      = array(
			'pushToBottom' => $this->sgSanitize('pushToBottom'),
			'countdownNumbersBgColor' => $this->sgSanitize('countdownNumbersBgColor'),
			'countdownNumbersTextColor' => $this->sgSanitize('countdownNumbersTextColor'),
			'sg-due-date' => $this->sgSanitize('sg-due-date'),
			'countdown-position' => $this->sgSanitize('countdown-position'),
			'counts-language' => $this->sgSanitize('counts-language'),
			'sg-time-zone' => $this->sgSanitize('sg-time-zone'),
			'sg-countdown-type' => $this->sgSanitize('sg-countdown-type')
		);
		$socialOptions         = array(
			'sgSocialTheme' => $this->sgSanitize('sgSocialTheme'),
			'sgSocialButtonsSize' => $this->sgSanitize('sgSocialButtonsSize'),
			'sgSocialLabel' => $this->sgSanitize('sgSocialLabel'),
			'sgSocialShareCount' => $this->sgSanitize('sgSocialShareCount'),
			'sgRoundButton' => $this->sgSanitize('sgRoundButton'),
			'fbShareLabel' => $this->sgSanitize('fbShareLabel'),
			'lindkinLabel' => $this->sgSanitize('lindkinLabel'),
			'sgShareUrl' => $this->sgSanitize('sgShareUrl'),
			'shareUrlType' => $this->sgSanitize('shareUrlType'),
			'googLelabel' => $this->sgSanitize('googLelabel'),
			'twitterLabel' => $this->sgSanitize('twitterLabel'),
			'pinterestLabel' => $this->sgSanitize('pinterestLabel'),
			'sgMailSubject' => $this->sgSanitize('sgMailSubject'),
			'sgMailLable' => $this->sgSanitize('sgMailLable')
		);
		$socialButtons         = array(
			'sgTwitterStatus' => $this->sgSanitize('sgTwitterStatus'),
			'sgFbStatus' => $this->sgSanitize('sgFbStatus'),
			'sgEmailStatus' => $this->sgSanitize('sgEmailStatus'),
			'sgLinkedinStatus' => $this->sgSanitize('sgLinkedinStatus'),
			'sgGoogleStatus' => $this->sgSanitize('sgGoogleStatus'),
			'sgPinterestStatus' => $this->sgSanitize('sgPinterestStatus'),
			'pushToBottom' => $this->sgSanitize('pushToBottom')
		);
		$exitIntentOptions     = array(
			'exit-intent-type' => $this->sgSanitize('exit-intent-type'),
			'exit-intent-expire-time' => $this->sgSanitize('exit-intent-expire-time'),
			'exit-intent-alert' => $this->sgSanitize('exit-intent-alert')
		);
		$subscriptionOptions   = array(
			'subs-first-name-status' => $this->sgSanitize('subs-first-name-status'),
			'subs-last-name-status' => $this->sgSanitize('subs-last-name-status'),
			'subscription-email' => $this->sgSanitize('subscription-email'),
			'subs-first-name' => $this->sgSanitize('subs-first-name'),
			'subs-last-name' => $this->sgSanitize('subs-last-name'),
			'subs-text-width' => $this->sgSanitize('subs-text-width'),
			'subs-button-bgcolor' => $this->sgSanitize('subs-button-bgcolor'),
			'subs-btn-width' => $this->sgSanitize('subs-btn-width'),
			'subs-btn-title' => $this->sgSanitize('subs-btn-title'),
			'subs-text-input-bgcolor' => $this->sgSanitize('subs-text-input-bgcolor'),
			'subs-text-bordercolor' => $this->sgSanitize('subs-text-bordercolor'),
			'subs-button-color' => $this->sgSanitize('subs-button-color'),
			'subs-inputs-color' => $this->sgSanitize('subs-inputs-color'),
			'subs-btn-height' => $this->sgSanitize('subs-btn-height'),
			'subs-text-height' => $this->sgSanitize('subs-text-height'),
			'subs-placeholder-color' => $this->sgSanitize('subs-placeholder-color'),
			'subs-validation-message' => $this->sgSanitize('subs-validation-message'),
			'subs-success-message' => $this->sgSanitize('subs-success-message'),
			'subs-email-validate' => $this->sgSanitize('subs-email-validate'),
			'subs-btn-progress-title' => $this->sgSanitize('subs-btn-progress-title'),
			'subs-text-border-width' => $this->sgSanitize('subs-text-border-width')
		);
		$contactFormOptions    = array(
			'contact-name' => $this->sgSanitize('contact-name'),
			'contact-subject' => $this->sgSanitize('contact-subject'),
			'contact-email' => $this->sgSanitize('contact-email'),
			'contact-message' => $this->sgSanitize('contact-message'),
			'contact-validation-message' => $this->sgSanitize('contact-validation-message'),
			'contact-success-message' => $this->sgSanitize('contact-success-message'),
			'contact-inputs-width' => $this->sgSanitize('contact-inputs-width'),
			'contact-inputs-height' => $this->sgSanitize('contact-inputs-height'),
			'contact-inputs-border-width' => $this->sgSanitize('contact-inputs-border-width'),
			'contact-text-input-bgcolor' => $this->sgSanitize('contact-text-input-bgcolor'),
			'contact-text-bordercolor' => $this->sgSanitize('contact-text-bordercolor'),
			'contact-inputs-color' => $this->sgSanitize('contact-inputs-color'),
			'contact-placeholder-color' => $this->sgSanitize('contact-placeholder-color'),
			'contact-btn-width' => $this->sgSanitize('contact-btn-width'),
			'contact-btn-height' => $this->sgSanitize('contact-btn-height'),
			'contact-btn-title' => $this->sgSanitize('contact-btn-title'),
			'contact-btn-progress-title' => $this->sgSanitize('contact-btn-progress-title'),
			'contact-button-bgcolor' => $this->sgSanitize('contact-button-bgcolor'),
			'contact-button-color' => $this->sgSanitize('contact-button-color'),
			'contact-area-width' => $this->sgSanitize('contact-area-width'),
			'contact-area-height' => $this->sgSanitize('contact-area-height'),
			'sg-contact-resize' => $this->sgSanitize('sg-contact-resize'),
			'contact-validate-email' => $this->sgSanitize('contact-validate-email'),
			'contact-resive-email' => $this->sgSanitize('contact-resive-email'),
			'contact-fail-message' => $this->sgSanitize('contact-fail-message')
		);
		$options               = array(
			'width' => $this->sgSanitize('width'),
			'height' => $this->sgSanitize('height'),
			'delay' => (int) $this->sgSanitize('delay'),
			'duration' => (int) $this->sgSanitize('duration'),
			'effect' => $this->sgSanitize('effect'),
			'escKey' => $this->sgSanitize('escKey'),
			'scrolling' => $this->sgSanitize('scrolling'),
			'reposition' => $this->sgSanitize('reposition'),
			'overlayClose' => $this->sgSanitize('overlayClose'),
			'contentClick' => $this->sgSanitize('contentClick'),
			'opacity' => $this->sgSanitize('opacity'),
			'sgOverlayColor' => $this->sgSanitize('sgOverlayColor'),
			'sgBackgroundColor' => $this->sgSanitize('sgBackgroundColor'),
			'popupFixed' => $this->sgSanitize('popupFixed'),
			'fixedPostion' => $this->sgSanitize('fixedPostion'),
			'maxWidth' => $this->sgSanitize('maxWidth'),
			'maxHeight' => $this->sgSanitize('maxHeight'),
			'initialWidth' => $this->sgSanitize('initialWidth'),
			'initialHeight' => $this->sgSanitize('initialHeight'),
			'closeButton' => $this->sgSanitize('closeButton'),
			'theme' => $this->sgSanitize('theme'),
			'theme-close-text' => $this->sgSanitize('theme-close-text'),
			'onScrolling' => $this->sgSanitize('onScrolling'),
			'beforeScrolingPrsent' => (int) $this->sgSanitize('beforeScrolingPrsent'),
			'forMobile' => $this->sgSanitize('forMobile'),
			'openMobile' => $this->sgSanitize('openMobile'), // open only for mobile
			'repeatPopup' => $this->sgSanitize('repeatPopup'),
			'autoClosePopup' => $this->sgSanitize('autoClosePopup'),
			'countryStatus' => $this->sgSanitize('countryStatus'),
			'allProducts' => $allProducts,
			'showAllPages' => $showAllPages,
			'allSelectedPages' => $allSelectedPages,
			'showAllPosts' => $allPosts,
			'showAllCategories' => $allCategories,
			'allSelectedPosts' => $allSelectedPosts,
			'allSelectedCategories' => $allSelectedCategories,
			'allProductStores' => $allProductStores,
			'allowCountries' => $this->sgSanitize('allowCountries'),
			'countryName' => $this->sgSanitize('countryName'),
			'countryIso' => $this->sgSanitize('countryIso'),
			'disablePopup' => $this->sgSanitize('disablePopup'),
			'disablePopupOverlay' => $this->sgSanitize('disablePopupOverlay'),
			'popupClosingTimer' => $this->sgSanitize('popupClosingTimer'),
			'yesButtonLabel' => $this->sgSanitize('yesButtonLabel'),
			'noButtonLabel' => $this->sgSanitize('noButtonLabel'),
			'restrictionUrl' => $this->sgSanitize('restrictionUrl'),
			'yesButtonBackgroundColor' => $this->sgSanitize('yesButtonBackgroundColor'),
			'noButtonBackgroundColor' => $this->sgSanitize('noButtonBackgroundColor'),
			'yesButtonTextColor' => $this->sgSanitize('yesButtonTextColor'),
			'noButtonTextColor' => $this->sgSanitize('noButtonTextColor'),
			'yesButtonRadius' => (int) $this->sgSanitize('yesButtonRadius'),
			'noButtonRadius' => (int) $this->sgSanitize('noButtonRadius'),
			'pushToBottom' => $this->sgSanitize('pushToBottom'),
			'onceExpiresTime' => $this->sgSanitize('onceExpiresTime'),
			'sgOverlayCustomClasss' => $this->sgSanitize('sgOverlayCustomClasss'),
			'sgContentCustomClasss' => $this->sgSanitize('sgContentCustomClasss'),
			'fblikeOptions' => json_encode($fblikeOptions),
			'videoOptions' => json_encode($videoOptions),
			'ageRestrictionOptions' => json_encode($ageRestrictionOptions),
			'countdownOptions' => json_encode($countdownOptions),
			'socialButtons' => json_encode($socialButtons),
			'socialOptions' => json_encode($socialOptions),
			'exitIntentOptions' => json_encode($exitIntentOptions),
			'subscriptionOptions' => json_encode($subscriptionOptions),
			'contactFormOptions' => json_encode($contactFormOptions)
		);
		if ($editId == '') {
			$sgPopupData                   = array(
				"type" => $type,
				"title" => $title,
				"options" => json_encode($options)
			);
			$sgPopupData['image']          = $image;
			$sgPopupData['html']           = $html;
			$sgPopupData['fblike']         = $fblike;
			$sgPopupData['iframe']         = $iframeUrl;
			$sgPopupData['video']          = $video;
			$sgPopupData['ageRestriction'] = $ageRestriction;
			$sgPopupData['countdown']      = $countdown;
			$sgPopupData['social']         = $social;
			$sgPopupData['exitIntent']     = $exitIntent;
			$sgPopupData['subscription']   = $subscription;
			$sgPopupData['contactForm']    = $contactForm;
			$res                           = call_user_func(array(
				$popupClassName,
				'create'
			), $sgPopupData);
			if (!empty($showAllPages)) {
				$this->setPopupForAllPages($res['id'], $allSelectedPages);
			}
			if (!empty($allPosts)) {
				$this->setPopupForAllPages($res['id'], $allSelectedPosts);
			}
			if(!empty($allCategories)) {
				$this->setPopupForAllPages($res['id'], $allSelectedCategories);
			}
			if(!empty($allProducts)) {
				$productArray = array('-1');
				$this->setPopupForAllPages($res['id'], $productArray);
			}
			$sgPopupData['id'] = $res['id'];
			if (isset($res['path'])) {
				/* Check this condition for Image uploading */
				$sgPopupData['imagePath'] = $res['path'];
			}
		} else {
			$popup = SGPopup::findById($editId);
			$popup->setTitle($title);
			$popup->setId($editId);
			$popup->setType($type);
			$popup->setOptions(json_encode($options));
			switch ($popupName) {
				case 'SGImage':
					$popup->setUrl($image);
					break;
				case 'SGIframe':
					$popup->setUrl($iframeUrl);
					break;
				case 'SGVideo':
					$popup->setUrl($video);
					$popup->setRealUrl($video);
					$popup->setVideoOptions(json_encode($videoOptions));
					break;
				case 'SGHtml':
					$popup->setContent($html);
					break;
				case 'SGFblike':
					$popup->setContent($fblike);
					$popup->setFblikeOptions(json_encode($fblikeOptions));
					break;
				case 'SGShortcode':
					$popup->setShortcode($shortCode);
					break;
				case 'SGAgerestriction':
					$popup->setContent($ageRestriction);
					$popup->setYesButton($options['yesButtonLabel']);
					$popup->setNoButton($options['noButtonLabel']);
					$popup->setRestrictionUrl($options['restrictionUrl']);
					break;
				case 'SGCountdown':
					$popup->setCountdownContent($countdown);
					$popup->setCountdownOptions(json_encode($countdownOptions));
					break;
				case 'SGSocial':
					$popup->setSocialContent($social);
					$popup->setButtons(json_encode($socialButtons));
					$popup->setSocialOptions(json_encode($socialOptions));
					break;
				case 'SGExitintent':
					$popup->setContent($exitIntent);
					$popup->setExitIntentOptions(json_encode($exitIntentOptions));
					break;
				case 'SGSubscription':
					$popup->setContent($subscription);
					$popup->setSubscriptionOptions(json_encode($subscriptionOptions));
					break;
				case 'SGContactform':
					$popup->setContent($contactForm);
					$popup->steParams(json_encode($contactFormOptions));
					break;
			}
			$res                 = $popup->save();
			$sgPopupData['id']   = $editId;
			$sgPopupData['type'] = $type;
			SGPopup::removePopupFromPages($editId);
			if (!empty($showAllPages)) {
				$this->setPopupForAllPages($editId, $allSelectedPages);
			}
			if (!empty($allPosts)) {
				$this->setPopupForAllPages($editId, $allSelectedPosts);
			}
			if(!empty($allCategories)) {
				$this->setPopupForAllPages($editId, $allSelectedCategories);
			}
			if(!empty($allProducts)) {
				$productArray = array('-1');
				$this->setPopupForAllPages($editId, $productArray);
			}
		}
		$saved       = 1;
		$editPageurl = Mage::helper('popupbuilder/GetData')->getPageUrl('edit/id/');
		$editPageurl .= "id/" . $sgPopupData['id'] . "/type/" . $sgPopupData['type'] . "/saved/" . $saved;
		header("Location:" . $editPageurl);
	}
	public function setPopupForAllPages($id, $data)
	{
		SGPopup::addPopupForAllPages($id, $data);
	}
	public function sgSaveImagePopup($id)
	{
		$functionsHellper = Mage::helper('popupbuilder/SgFunctions');
		$model            = Mage::getModel("popupbuilder/SgImagePopup");
		$uploadPath       = SG_SKIN_IAMGE;
		$target           = "path";
		// Get All datata from image popup
		$allData          = $model->load($id)->getData();
		if (!empty($allData)) {
		}
		$fileTypes   = array(
			'jpg',
			'jpeg',
			'png',
			'gif'
		);
		$path        = $functionsHellper->fileUpload($_FILES['ad_image'], $uploadPath, $fileTypes, 'ad_image');
		$sgImageData = array(
			'id' => $id,
			"path" => $path
		);
		$model->setData($sgImageData);
		$query = $model->save();
		return $path;
	}
	public function sgSaveHtmlPopup($id)
	{
		$model    = Mage::getModel("popupbuilder/SgHtmlPopup");
		$html     = $this->sgSanitize('sg-popup-html-val');
		$htmlData = array(
			'id' => $id,
			'content' => addslashes(stripslashes($html))
		);
		$model->setData($htmlData);
		$query = $model->save();
	}
	public function getSubscribersAction()
	{
		$model          = Mage::getModel("popupbuilder/Subscribers");
		$resource       = $model->getCollection('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$query          = "SHOW COLUMNS FROM sg_subscribers";
		$rows           = $readConnection->fetchAll($query);
		foreach ($rows as $value) {
			$content .= $value['Field'] . ",";
		}
		$content .= "\n";
		$subscribers = $model->getCollection()->getData();
		foreach ($subscribers as $values) {
			foreach ($values as $value) {
				$content .= $value . ',';
			}
			$content .= "\n";
		}
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false);
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"subscribersList.csv\";");
		header("Content-Transfer-Encoding: binary");
		echo $content;
	}
	public function deletePopupAction()
	{
		$model = Mage::getModel("popupbuilder/" . $_POST['modelName']);
		$id    = $_POST['popupId'];
		$model->setId($id)->delete();
		die();
	}
	public function showStoresProductsAction() {

		$porudtsInStores = $_POST['storeIds'];
		$popupId = $_POST['popupId'];
	
		$model = Mage::getModel("popupbuilder/Sgpopup")->load($popupId);
        $params = $model->getData();
        $selectedValues = array();

       	if(!empty($params)) {
       		$options = json_decode($params['options'], true);
			$selectedValues = $options['allSelectedPosts'];
       	}

		$porudtsInStores = explode(',',$porudtsInStores);

//		$collection = Mage::getResourceModel('catalog/product_collection')->addAttributeToSelect('name');
                $collection = Mage::getResourceModel('catalog/product_collection')
                                   ->addAttributeToSelect('name')
                                   ->addAttributeToFilter('type_id', 'configurable')
                                   ->addFieldToFilter('visibility', Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);


        $result     = '';

        foreach ($collection as $temp) {
        	
        	$selected = '';
            $name        = $temp->getName();
            $whichIsConnectedTo  = $temp->getWebsiteIds();
          
            $match = array_intersect($porudtsInStores, $whichIsConnectedTo);
            
            if(in_array('othderProduct', $porudtsInStores) && empty($whichIsConnectedTo)) {

            	$id = md5($name . $temp->getId());
            	if(!empty($selectedValues)) {
            		if(in_array($id, $selectedValues)) {
          				$selected = 'selected';
          			}
            	}
          		
            	//$result[$id] = $name;
            	$result .= "<option value='$id' $selected>$name</option>";
            }
            
          	if(!empty($match)) {

          		$id = md5($name . $temp->getId());
          		if(!empty($selectedValues)) {
          			if(in_array($id, $selectedValues)) {
          				$selected = 'selected';
          			}
          		}

            //	$result[$id] = $name;
            	$result .= "<option value='$id' $selected>$name</option>";
          	}
        }
     	echo $result;
     	die();
	}
}
