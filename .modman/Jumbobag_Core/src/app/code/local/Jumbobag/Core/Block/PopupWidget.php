<?php
class Jumbobag_Core_Block_PopupWidget extends Sygnoos_Popupbuilder_Block_PopupWidget
{
	protected static $oneLoad = false;
	protected $multiplePopups = false;
	protected $popupsId = array();

	public function rendringData($obj)
	{
		$popupType = $obj->getType();

		$skinUrlJs  = $this->getSkinUrl('js/PopupBuilder', array('_area' => 'adminhtml'));
		$skinUrlCss = $this->getSkinUrl('css/PopupBuilder', array('_area' => 'adminhtml'));
		$skinImgUrl = $this->getSkinUrl('images/PopupBuilder/', array('_area' => 'adminhtml'));

		$content = $this->renderAllScripts();

		if($popupType == 'agerestriction') {
			$content .= "<script src='$skinUrlJs/sg_ageRestriction.js' defer></script>";
			echo $obj->renderData();
		}
		else if($popupType == 'countdown') {
			$content .= "<script src='$skinUrlJs/sg_flipclock.js' defer></script>";
			$content .= "<script src='$skinUrlJs/sg_countdown.js' defer></script>";
			echo $obj->renderData();
		}
		else if($popupType == 'social') {
			$content .= "<script src='$skinUrlJs/jssocials.min.js' defer></script>";
			echo $obj->renderData();
		}
		else if($popupType == 'exitintent') {
			$content .= "<script src='$skinUrlJs/sg_exit_intent.js' defer></script>";
		}
		else if($popupType == 'subscription') {
			$content .= "<script src='$skinUrlJs/sg_subscription.js' defer></script>";
			echo $obj->renderData();
		}
		else if($popupType == 'contactform') {
			$content .= "<script src='$skinUrlJs/sg_contactForm.js' defer></script>";
			echo $obj->renderData();
		}
		//$content .= "<script src='$skinUrlJs/jquery-migrate-1.3.0.js'></script>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox1.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox2.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox3.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox4.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox5.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/animate.css'>";
		return $content;
	}

	public function renderAllScripts()
	{

		$content = '';
		if (self::$oneLoad) {

			return "";

		}

		self::$oneLoad = true;
		$skinUrlJs  = $this->getSkinUrl('js/PopupBuilder', array('_area' => 'adminhtml'));
		$skinUrlCss = $this->getSkinUrl('css/PopupBuilder', array('_area' => 'adminhtml'));
		$skinImgUrl = $this->getSkinUrl('images/PopupBuilder/', array('_area' => 'adminhtml'));

		$content .= "<script src='$skinUrlJs/jquery-1.12.0.min.js' defer></script>";
		$content .= "<script src='$skinUrlJs/jsg.js' defer></script>";
		$content .= "<script type='text/javascript'>SG_POPUP_DATA = [];SG_POPUPS_QUEUE = [];SG_APP_POPUP_URL='" . $this->getSkinUrl('css/PopupBuilder', array('_area' => 'adminhtml')) . "';SG_APP_POPUP_IMAGE_URL='" . $this->getSkinUrl('media/PopupBuilder/', array('_area' => 'adminhtml')) . "';</script>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox1.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox2.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox3.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox4.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox5.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/animate.css'>";

		$content .= "<script src='$skinUrlJs/jquery.sgcolorbox-min.js' defer></script>";
		$content .= "<script src='$skinUrlJs/sg_popup_frontend.js' defer></script>";
		$content .= "<script src='$skinUrlJs/sg_popup_pro.js' defer></script>";
		$content .= "<script src='$skinUrlJs/sg_popup_core.js' defer></script>";
		$content .= "<script src='$skinUrlJs/jquery_cookie.js' defer></script>";
		$content .= "<script src='$skinUrlJs/sg_popup_queue.js' defer></script>";

		if($this->multiplePopups) {
			$popupsId = $this->popupsId;

			$content .= "<script>SG_POPUPS_QUEUE = ".json_encode($popupsId)."; </script>";
		}

		return $content;

	}

}
