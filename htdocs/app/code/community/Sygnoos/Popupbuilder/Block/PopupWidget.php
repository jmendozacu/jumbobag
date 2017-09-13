<?php
require_once(Mage::getModuleDir('', 'Sygnoos_Popupbuilder').DS.'public'.DS.'boot.php');
class Sygnoos_Popupbuilder_Block_PopupWidget extends Mage_Core_Block_Abstract implements Mage_Widget_Block_Interface
{
	private static $oneLoad = false;
	private $multiplePopups = false;
	private $popupsId = array();

	public function _toHtml($popupId = '')
	{
		$html          = '';
		$this->popupInPageByClass();
		$this->showPopupInAllProducts();

		//get popup id from widget
		$widgetId      = Sygnoos_Popupbuilder_Block_PopupWidget::getData('link_options');
		if(empty($widgetId)) {
			$widgetId = $popupId;
		}

		//get popup id from pages
		if (!empty($widgetId)) {
			$html .= $this->showPopup($widgetId);
			echo $html;
			return;
		}

		$cuurentPageId = Mage::getSingleton('cms/page')->getPageId();
		$pageTitle     = Mage::getSingleton('cms/page')->getTitle();
		$pageId        = md5($pageTitle . $cuurentPageId);

		if (empty($cuurentPageId)) {


			$productModel = Mage::registry('current_product');
			if(empty($productModel)) {
				return $html;
			}

			$productId   = $productModel->getId();

			$product     = $productModel->load($productId); //getting product object for particular product id
			if(empty($product)) {
				return $html;
			}
			$productName = $product->getName();
			$pageId      = md5($productName . $productId);
		}

		$this->isOnloadPopup($pageId);

	}

	public function showPopupInAllProducts()
	{
		$productObj = Mage::registry('current_product');
		$isProduct = isset($productObj);

		if($isProduct) {
			$this->isOnloadPopup('-1');
		}
		return;
	}

	public function popupInPageByClass()
	{
		$popupsID = array();
		$content = '';

		switch($this->getRequest()->getControllerName()) {
			case 'page':
				$pageId = Mage::app()->getRequest()->getParam('page_id', Mage::app()->getRequest()->getParam('id', false));
				$page = Mage::getModel('cms/page')->load($pageId);
				$content = $page->getContent();
				break;
			case 'product':
				$productModel = Mage::registry('current_product');
				$product = $productModel->load($productModel->getId());
				$content .= $product->getData('description');
				$content .= $product->getShortDescription();
				break;
		}

		preg_match_all("/sg-popup-id-+[0-9]+/i", $content, $matchers);
		if(empty($matchers['0'])) {
			return $popupsID;
		}
		foreach ($matchers['0'] as $value) {
			$ids = explode("sg-popup-id-", $value);
			$id = @$ids[1];
			if(!empty($id)) {
				array_push($popupsID, $id);
			}
		}

		$this->renderDringShortcodePopup($popupsID);
	}

	public function renderDringShortcodePopup($popupsID)
	{
		$html = $rendringData = '';

		foreach ($popupsID as $popupId) {

			$obj  = SGPopup::findById($popupId);
			if(empty($obj)) {
				continue;
			}
			$content       = $obj->render();
			$redneringData = "<script type='text/javascript'>" . $content . "</script>";
			$html .= $this->rendringData($obj);
			$html .= $redneringData;
		}
		echo $html;
	}

	public function isOnloadPopup($pageId)
	{
		$html = '';

		$model = Mage::getModel("popupbuilder/Sgpopupinpagespopup");
		$productModel = Mage::registry('current_product');

		// when model does not exist
		if(empty($model)) {
			return $html;
		}
		$data  = $model->getCollection();

		if (!empty($data)) {

			$pageDataModel = $data->addFieldToFilter("pageId", array('eq' => "$pageId"));

			if(empty($pageDataModel)) {
				return $html;
			}

			$getData = $pageDataModel->getData();
			if(count($getData) > 0) {
				$this->multiplePopups = true;
				$popupsId = array();
				foreach($getData as $data) {

					array_push($popupsId, $data['popupId']);
				}
				$this->popupsId = $popupsId;
			}

			/*Check is  this page have popup with catgory */
			if(empty($getData) && !empty($productModel)) {

				$allCategorisId = $productModel->getCategoryIds();
				$data  = $model->getCollection();
				foreach ($allCategorisId as $categoryId) {
					$categoryModel = Mage::getModel('catalog/category')->load($categoryId);
					$categoryName = $categoryModel->getName();
					$categordyIdKey = md5($categoryId . $categoryName);

					$pageDataModels = $this->getpopupIdFromAllPages($categordyIdKey);
					$getData[] = $pageDataModels->getData();
				}


				if(count($getData) > 0) {
					$this->multiplePopups = true;
					$popupsId = array();

					foreach($getData as $datas) {
						foreach ($datas as $data) {
							if(isset($data['popupId'])) {
								if(!in_array($data['popupId'], $popupsId)) {
									array_push($popupsId, $data['popupId']);
								}
							}
						}
					}

					$this->popupsId = $popupsId;
				}
			}



		}

		if (!empty($popupId)) {

			$html .= $this->showPopup($popupId);
		}
		else if($this->multiplePopups) {

			if(is_array($this->popupsId)) {

				foreach ($this->popupsId as $popupId) {
					$html .= $this->showPopup($popupId);
				}
			}
		}

		echo $html;

	}

	public function showPopup($id)
	{
		$html = $rendringData = '';

		$obj  = SGPopup::findById($id);

		if (!empty($obj)) {
			$rendringData = $this->showPopupInPage($id, $obj);
		}
		else {
			return "";
		}

		$html .= $this->rendringData($obj);

		$html .= $rendringData;
		return $html;
	}

	public function showPopupInPage($id, $obj)
	{
		if(SG_POPUP_PLATINUM) {
			if (!SGPopup::showPopupForCounrty($id)) {
				return;
			}
		}
		return $this->redenderScriptMode($id, $obj);
	}

	public function redenderScriptMode($id, $obj)
	{
		$content       = $obj->render();
		$redneringData = "<script type='text/javascript'>" . $content . "</script>";
		if ($obj->getType() == 'exitintent') {
			echo $obj->getExitIntentInitScript($id);
		}
		else if(!$this->multiplePopups) {
			$redneringData .= "<script type='text/javascript'>sgAddEvent(window, 'load',function() {
									var sgPoupFrontendObj = new SGPopup();
									sgPoupFrontendObj.popupOpenById($id)
								});
							</script>";
		}
		return $redneringData;
	}
	public function rendringData($obj)
	{
		$popupType = $obj->getType();

		$skinUrlJs  = $this->getSkinUrl('js/PopupBuilder', array('_area' => 'adminhtml'));
		$skinUrlCss = $this->getSkinUrl('css/PopupBuilder', array('_area' => 'adminhtml'));
		$skinImgUrl = $this->getSkinUrl('images/PopupBuilder/', array('_area' => 'adminhtml'));

		$content = $this->renderAllScripts();

		if($popupType == 'agerestriction') {
			$content .= "<script src='$skinUrlJs/sg_ageRestriction.js'></script>";
			echo $obj->renderData();
		}
		else if($popupType == 'countdown') {
			$content .= "<script src='$skinUrlJs/sg_flipclock.js'></script>";
			$content .= "<script src='$skinUrlJs/sg_countdown.js'></script>";
			echo $obj->renderData();
		}
		else if($popupType == 'social') {
			$content .= "<script src='$skinUrlJs/jssocials.min.js'></script>";
			echo $obj->renderData();
		}
		else if($popupType == 'exitintent') {
			$content .= "<script src='$skinUrlJs/sg_exit_intent.js'></script>";
		}
		else if($popupType == 'subscription') {
			$content .= "<script src='$skinUrlJs/sg_subscription.js'></script>";
			echo $obj->renderData();
		}
		else if($popupType == 'contactform') {
			$content .= "<script src='$skinUrlJs/sg_contactForm.js'></script>";
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

		$content .= "<script src='$skinUrlJs/jquery-1.12.0.min.js'></script>";
		$content .= "<script src='$skinUrlJs/jsg.js'></script>";
		$content .= "<script type='text/javascript'>SG_POPUP_DATA = [];SG_POPUPS_QUEUE = [];SG_APP_POPUP_URL='" . $this->getSkinUrl('css/PopupBuilder', array('_area' => 'adminhtml')) . "';SG_APP_POPUP_IMAGE_URL='" . $this->getSkinUrl('media/PopupBuilder/', array('_area' => 'adminhtml')) . "';</script>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox1.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox2.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox3.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox4.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/sgcolorbox/colorbox5.css'>";
		$content .= "<link rel='stylesheet' type='text/css' href='$skinUrlCss/animate.css'>";

		$content .= "<script src='$skinUrlJs/jquery.sgcolorbox-min.js'></script>";
		$content .= "<script src='$skinUrlJs/sg_popup_frontend.js'></script>";
		$content .= "<script src='$skinUrlJs/sg_popup_pro.js'></script>";
		$content .= "<script src='$skinUrlJs/sg_popup_core.js'></script>";
		$content .= "<script src='$skinUrlJs/jquery_cookie.js'></script>";
		$content .= "<script src='$skinUrlJs/sg_popup_queue.js'></script>";

		if($this->multiplePopups) {
			$popupsId = $this->popupsId;

			$content .= "<script>SG_POPUPS_QUEUE = ".json_encode($popupsId)."; </script>";
		}

		return $content;

	}

	/**
	 * Filter popupId from All Pages
	 *
	 * @since 1.1.1
	 *
	 * @param inrt $popupId
	 *
	 * @return object $filterData
	 *
	 */
	public function getpopupIdFromAllPages($popupId) {

		//$popupId = (int)$popupId;
		$model = Mage::getModel("popupbuilder/Sgpopupinpagespopup");
		$data  = $model->getCollection();
		$filterData = $data->addFieldToFilter("pageId", array('eq' => $popupId));

		return $filterData;
	}
}