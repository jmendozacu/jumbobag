<?php
class Jumbobag_Customer_Block_Widget_Name extends Mage_Customer_Block_Widget_Name
{
    public function __construct() {
        parent::__construct();
        $this->setTemplate('customer/widget/formName.phtml');
    }
}
