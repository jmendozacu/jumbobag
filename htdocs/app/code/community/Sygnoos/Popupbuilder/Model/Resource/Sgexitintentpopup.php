<?php

class Sygnoos_Popupbuilder_Model_Resource_Sgexitintentpopup extends Mage_Core_Model_Resource_Db_Abstract

{

	public function _construct()

	{

		$this->_init('popupbuilder/sgexitintentpopup', 'id');

		$this->_isPkAutoIncrement = false;

	}   

}