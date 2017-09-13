<?php

class Sygnoos_Popupbuilder_Model_Resource_Sgsubscriptionpopup extends Mage_Core_Model_Resource_Db_Abstract

{

	public function _construct()

	{

		$this->_init('popupbuilder/sgsubscriptionpopup', 'id');

		$this->_isPkAutoIncrement = false;

	}   

}