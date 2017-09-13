<?php

class Sygnoos_Popupbuilder_Model_Resource_Sgcountdownpopup extends Mage_Core_Model_Resource_Db_Abstract

{

    protected function _construct()

    {

        $this->_init('popupbuilder/sgcountdownpopup', 'id');

        $this->_isPkAutoIncrement = false;

    }

}