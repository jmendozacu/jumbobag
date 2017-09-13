<?php



class Sygnoos_Popupbuilder_Model_Options {

/**

  * Provide available options as a value/label array

  *

  * @return array

  */

	public function toOptionArray() {

		$data = array();

		$model = Mage::getModel("popupbuilder/Sgpopup");

		$collection = $model->getCollection();

		$collection->setOrder("id","desc");

		$allData = $collection->getData();



		foreach ($allData as $popup) {

			$data[] = array(

				'value' => $popup['id'], 

				'label' => $popup['title']."-".$popup['type']

			);

		}

		return $data;

	}

}