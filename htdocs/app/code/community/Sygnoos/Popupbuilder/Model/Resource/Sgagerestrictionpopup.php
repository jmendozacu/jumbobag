<?php

class Sygnoos_Popupbuilder_Model_Resource_Sgagerestrictionpopup extends Mage_Core_Model_Resource_Db_Abstract

{

	protected function _construct()

	{

		$this->_init('popupbuilder/sgagerestrictionpopup', 'id');

		$this->_isPkAutoIncrement = false;

	}

}