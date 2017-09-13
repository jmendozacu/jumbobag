<?php

 

class Sygnoos_Popupbuilder_Model_Observer {

/**

  * Provide available options as a value/label array

  *

  * @return array

  */

	public function showPopupOnPage() {

		var_dump(Mage::getSingleton('cms/page')->getTitle());

		if(Mage::registry('current_product')) {

			echo "jjjj";

		}

		else {

			echo "poili";

		}

	}

}