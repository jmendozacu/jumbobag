<?php

abstract class SGPopup {
	protected $id;
	protected $type;
	protected $title;
	protected $width;
	protected $height;
	protected $delay;
	protected $effectDuration;
	protected $effect;
	protected $initialWidth;
	protected $initialHeight;
	protected $options;
	public static $registeredScripts = false;

	public function setType($type){
		$this->type = $type;
	}
	public function getType() {
		return $this->type;
	}
	public function setTitle($title){
		$this->title = $title;
	}
	public function getTitle() {
		return $this->title;
	}
	public function setId($id){
		$this->id = $id;
	}
	public function getId() {
		return $this->id;
	}
	public function setWidth($width){
		$this->width = $width;
	}
	public function getWidth() {
		return $this->width;
	}
	public function setHeight($height){
		$this->height = $height;
	}
	public function getHeight() {
		return $this->height;
	}
	public function setDelay($delay){
		$this->delay = $delay;
	}
	public function getDelay() {
		return $this->delay;
	}
	public function setEffectDuration($effectDuration){
		$this->effectDuration = $effectDuration;
	}
	public function getEffectDuration() {
		return $this->effectDuration;
	}
	public function setEffect($effect){
		$this->effect = $effect;
	}
	public function getEffect() {
		return $this->effect;
	}
	public function setInitialWidth($initialWidth){
		$this->initialWidth = $initialWidth;
	}
	public function getInitialWidth() {
		return $this->initialWidth;
	}
	public function setInitialHeight($initialHeight){
		$this->initialHeight = $initialHeight;
	}
	public function getInitialHeight() {
		return $this->initialHeight;
	}
	public function setOptions($options) {
		$this->options = $options;
	}
	public function getOptions() {
		return $this->options;
	}
	public static function findById($id) {

		$model = Mage::getModel("popupbuilder/Sgpopup");
		$arr = $model->load($id)->getData();

		if(!$arr) return false;
		return self::popupObjectFromArray($arr);

	}

	abstract protected function setCustomOptions($id);

	abstract protected function getExtraRenderOptions();

	private static function popupObjectFromArray($arr, $obj = null) {

		$jsonData = json_decode($arr['options'], true);

		$type = sgSafeStr($arr['type']);

		if ($obj===null) {
			$className = "SG".ucfirst(strtolower($type)).'Popup';
			require_once(dirname(__FILE__).'/'.$className.'.php');
			$obj = new $className();
		}

		$obj->setType(sgSafeStr($type));
		$obj->setTitle(sgSafeStr($arr['title']));
		if (@$arr['id']) $obj->setId($arr['id']);
		$obj->setWidth(sgSafeStr(@$jsonData['width']));
		$obj->setHeight(sgSafeStr(@$jsonData['height']));
		$obj->setDelay(sgSafeStr(@$jsonData['delay']));
		$obj->setEffectDuration(sgSafeStr(@$jsonData['duration']));
		$obj->setEffect(sgSafeStr($jsonData['effect']));
		$obj->setInitialWidth(sgSafeStr(@$jsonData['initialWidth']));
		$obj->setInitialHeight(sgSafeStr(@$jsonData['initialHeight']));
		$obj->setOptions(sgSafeStr($arr['options']));
		
		if (@$arr['id']) $obj->setCustomOptions($arr['id']);

		return $obj;
	}

	public static function create($data, $obj) {
		self::popupObjectFromArray($data, $obj);
		return $obj->save();
	}

	public function save($data = array()) {

		$id = $this->getId();
		$type = $this->getType();
		$title = $this->getTitle();
		$options = $this->getOptions();
		$model = Mage::getModel("popupbuilder/Sgpopup");
	
		if($id  == '') {
			$data = $sgPopupData = array("type"=>$type,"title"=>$title,"options"=>$options);
		}
		else {
			$data = $sgPopupData = array("id"=>$id,"type"=>$type,"title"=>$title,"options"=>$options);
		}
		$model->setData($sgPopupData);
		$res = $model->save();

		if ($res) {
			$id = $model->getId();
			$this->setId($id);
		}
		return $res;
	}

	public static function findAll($orderBy = null, $limit = null, $offset = null) {

		global $wpdb;

		$query = "SELECT * FROM ". $wpdb->prefix ."sg_popup";

		if ($orderBy) {
			$query .= " ORDER BY ".$orderBy;
		}

		if ($limit) {
			$query .= " LIMIT ".intval($offset).','.intval($limit);
		}

		//$st = $wpdb->prepare($query, array());
		$popups = $wpdb->get_results($query, ARRAY_A);

		$arr = array();
		foreach ($popups as $popup) {
			$arr[] = self::popupObjectFromArray($popup);
		}

		return $arr;
	}

	public static function delete($id) {
			$pop = self::findById($id);
			$type =  $pop->getType();
			$table = 'sg_'.$type.'_popup';

			global $wpdb;
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM ". $wpdb->prefix ."$table WHERE id = %d"
					,$id
				)
			);
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM ". $wpdb->prefix ."sg_popup WHERE id = %d"
					,$id
				)
			);

			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM ". $wpdb->prefix ."postmeta WHERE meta_value = %d and meta_key = 'wp_sg_popup'"
					,$id
				)
			);
	}

	public static function setPopupForPost($post_id, $popupId) {
		update_post_meta($post_id, 'wp_sg_popup' , $popupId);
	}

	public function getRemoveOptions() {
		return array();
	}

	public function improveContent($content) {
		
		return Mage::helper('cms')->getBlockTemplateProcessor()->filter($content);
	}

	public function render() {
		
		$parentOption = array('id'=>$this->getId(),'title'=>$this->getTitle(),'type'=>$this->getType(),'effect'=>$this->getEffect(),'width',$this->getWidth(),'height'=>$this->getHeight(),'delay'=>$this->getDelay(),'duration'=>$this->getEffectDuration(),'initialWidth',$this->getInitialWidth(),'initialHeight'=>$this->getInitialHeight());
		$getexrArray = $this->getExtraRenderOptions();
	
		$options = json_decode($this->getOptions(),true);
		if(empty($options)) $options = array();
		$sgPopupVars = 'SG_POPUP_DATA['.$this->getId().'] ='.json_encode(array_merge($parentOption, $options, $getexrArray)).';';
		//$sgPopupVars = 'SG_POPUP_DATA['.$this->getId().'] ='.json_encode(array_merge($parentOption, $options)).';';

		return $sgPopupVars;
	}

	public static function getTotalRowCount() {
		global $wpdb;
		$res =  $wpdb->get_var( "SELECT COUNT(id) FROM ". $wpdb->prefix ."sg_popup" );
		return $res;
	}

	public static function getPagePopupId($page,$popup) {
		global $wpdb;
		$sql = $wpdb->prepare("SELECT meta_value FROM ". $wpdb->prefix ."postmeta WHERE post_id = %d AND meta_key = %s",$page,$popup);
		$row = $wpdb->get_row($sql);
		$id = 0;
		if($row) {
			$id =  (int)@$row->meta_value;
		}
		return $id;
	}

	public static function showPopupForCounrty($popupId) {

 		$obj = SGPopup::findById($popupId);

 		if(!$obj) {
 			return true;
 		}

 		$isInArray = true;
 		$options = json_decode($obj->getOptions(), true);
 
 		$countryStatus = $options['countryStatus'];
 		$countryIso = $options['countryIso'];
 		$allowCountries = $options['allowCountries'];
 		$countryIsoArray = explode(',', $countryIso);

 		if($countryStatus) {

 			$functions = Mage::helper('popupbuilder/SgFunctions');
			$ip = $functions::getUserIpAdress();

			$counrty = $functions::getCountryName($ip);
 			
 			if($allowCountries == 'allow') {
				$isInArray = in_array($contryIsoName, $countryIsoArray);
				$isInArray = in_array($counrty, $countryIsoArray);
 			}

 			if($allowCountries == 'disallow') {
				$isInArray = !in_array($contryIsoName, $countryIsoArray);
				$isInArray = !in_array($counrty, $countryIsoArray);
 			}
 		}
 		return $isInArray;
 	}

	public static function addPopupForAllPages($id = '', $selectedData = '') {

		$values = array();
		$insertPreapre = array();
		$model = Mage::getModel("popupbuilder/Sgpopupinpagespopup");
		if(empty($selectedData)) {
			return;
		}
		foreach ($selectedData as $value) {
			//self::deleteAllPagesPopup($value);
		}

		//self::deleteAllPagesPopup($selectedData);

		foreach ($selectedData as $value) {
			$inPagesData = array("popupId"=>$id,"pageId"=>$value);
			$model->setData($inPagesData);
			$model->save();
		}
	}

	public static function removePopupFromPages($popupId) {

		$model = Mage::getModel("popupbuilder/Sgpopupinpagespopup");
		$data = $model->getCollection();
		$allSelected = $data->addFieldToFilter("popupId",  array('eq' => "$popupId"))->getData();
		foreach ($allSelected as $value) {
			$model->setData($value);
			$model->delete();
		}
	}

	public static function deleteAllPagesPopup($value) {
		
		$model = Mage::getModel("popupbuilder/Sgpopupinpagespopup");
		$data = $model->getCollection();
		$selected = $data->addFieldToFilter("pageId",  array('eq' => "$value"))->getData();
		@$model->setData($selected[0]);
		$model->delete();
		
	}

	public static function findInAllSelectedPages($pageId) {
		global $wpdb;

		$st = $wpdb->prepare("SELECT * FROM ". $wpdb->prefix ."sg_popup_in_pages WHERE pageId = %d", $pageId);
		$arr = $wpdb->get_row($st, ARRAY_A);
		if(!$arr) return false;
		return $arr['popupId'];
	}

}

function sgSafeStr ($param) {
	return ($param===null?'':$param);
}
