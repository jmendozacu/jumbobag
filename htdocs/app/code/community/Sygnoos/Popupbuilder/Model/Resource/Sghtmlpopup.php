<?php

class Sygnoos_Popupbuilder_Model_Resource_Sghtmlpopup extends Mage_Core_Model_Resource_Db_Abstract

{

    protected function _construct()

    {

        $this->_init('popupbuilder/sghtmlpopup', 'id');

        $this->_isPkAutoIncrement = false;

    }

}