<?php

class Jumbobag_Customer_Block_Account_Navigation extends Mage_Customer_Block_Account_Navigation {

	public function renameLink($name, $label)
    {
    	$this->_links[$name]->setData("label", $label);
    }

   	public function removeLink($name)
    {
    	unset($this->_links[$name]);
    }
}