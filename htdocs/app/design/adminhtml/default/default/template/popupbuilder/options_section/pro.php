<?php 
$allSelectedPosts = $getData['allSelectedPosts'];
$allSelectedPosts = @implode(',', $allSelectedPosts);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		Advanced options
 	</div>
	<div class="panel-body">
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Show on selected pages:</label>
			</div>
			<div class="col-xs-4">
				<div class="checkbox">
					<input class="js-on-all-pages js-checkbox-acordion-content" data-acordion-conetnt="sg-popup-pages" type="checkbox" name="allPages" <?php echo $getData['sgAllPages'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-popup-pages">
			<div class="col-xs-4">
				<label class="control-label">Pages:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<?php 
						$allPages = $functions->getAllPages();
						if(!empty($allPages)) {
							echo $functions->multiSelect('all-selected-page[]', false, 10, $allPages, $getData['allSelectedPages']);
						}
						else {

							echo "<div class='checkbox'>No Products</div>";
						}
					?>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Show on all products:</label>
			</div>
			<div class="col-xs-4">
				<div class="checkbox">
					<input class="" type="checkbox" name="allProducts" <?php echo $getData['sgAllProducts'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Show on selected products:</label>
			</div>
			<div class="col-xs-4">
				<div class="checkbox">
					<input class="js-on-all-posts js-checkbox-acordion-content" data-acordion-conetnt="sg-popup-products" type="checkbox" name="allPosts" <?php echo $getData['sgAllPosts'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-popup-products sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Products:</label>
			</div>
			<div class="col-xs-8">
				<div class="sg-align-checkbox">
					<div class="sg-popup-stores">
						<h3>Stores</h3>
						<?php 
							$allStores =  $functions->getAllStores();
							$allStores['othderProduct'] = 'Other';
							$selectedStores = $getData['allProductStores'];
							if(!isset($popupId)) {
								$selectedStores = array_keys($allStores);
							}
							$storeProductsUrl = Mage::helper('popupbuilder/GetData')->getPageUrl('showStoresProducts');
							$key = Mage::getSingleton('core/session')->getFormKey();
							
							echo '<input type="hidden" class="js-sg-popup-products-data" data-prouct-stores-url="'.$storeProductsUrl.'" data-prouct-stores-key="'.$key.'" data-popup-id="'.$popupId.'">';
							echo $functions->multiSelect('all-product-stores[]', false, 10, $allStores, $selectedStores);
						?>
						

					</div>
					<div class="sg-popup-products-selectbox">
						<h3>Products</h3>
						<?php

							$test = $collection = Mage::getResourceModel('catalog/product_collection')->addAttributeToSelect('name');
						
							
							$allProducts = $functions->getAllProducts();
						
							if(!empty($allProducts)) {
								echo '<select class="all-selected-posts-selectbox" size="10" multiple></select>';
							//	echo $functions->multiSelect('all-selected-posts[]', false, 10, $allProducts, $getData['allSelectedPosts']);
							}
							else {

								echo "<div class='checkbox'>No Products</div>";
							}
							
						?>
						<input type="hidden" class="sg-selected-posts" name="all-selected-posts" value="<?php echo $allSelectedPosts;?>">
						<img class="sg-products-spinner sg-hide" src="<?php echo SG_SKIN_IAMGE_URL?>/wpAjax.gif" >
					</div>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Show on selected categorie(s) product(s):</label>
			</div>
			<div class="col-xs-4">
				<div class="checkbox">
					<input class="js-on-all-categories js-checkbox-acordion-content" data-acordion-conetnt="sg-popup-categories" type="checkbox" name="allCategories" <?php echo $getData['sgAllCategories'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-popup-categories sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Categories:</label>
			</div>
			<div class="col-xs-8">
				<div class="sg-align-checkbox">
					<?php 
						$listOfCategories = $functions->getAllCategories();
						echo $functions->multiSelect('all-product-categories[]', false, 10, $listOfCategories, $getData['allSelectedCategories']);
					?>
				</div>
			</div>
		</div>

		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Show while scrolling:</label>
			</div>
			<div class="col-xs-4">
				<div class="checkbox">
					<input id="js-scrolling-event-inp" class="js-checkbox-acordion-content" data-acordion-conetnt="sg-popup-scrolling" type="checkbox" name="onScrolling" <?php echo $getData['sgOnScrolling'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-popup-scrolling sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Show popup after scrolling</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control input-md" type="text" name="beforeScrolingPrsent" value="<?php echo $getData['beforeScrolingPrsent'];?>">
				</div>
			</div>
			<div class="col-md-1">
				<span>%</span>
			</div>
		</div>

		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Hide on mobile devices:</label>
			</div>
			<div class="col-xs-4">
				<div class="checkbox">
					<input type="checkbox" name="forMobile" <?php echo $getData['sgForMobile'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Show only on mobile devices:</label>
			</div>
			<div class="col-xs-4">
				<div class="checkbox">
					<input type="checkbox" name="openMobile" <?php echo $getData['sgOpenOnMobile'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Show popup only once:</label>
			</div>
			<div class="col-xs-4">
				<div class="checkbox">
					<input class="js-checkbox-acordion-content" data-acordion-conetnt="sg-popup-only-once"  id="js-popup-only-once" type="checkbox" name="repeatPopup" <?php echo $getData['sgRepeatPopup'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-popup-only-once">
			<div class="col-xs-4">
				<label class="control-label">Expire time</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control input-md" type="number" min="1" name="onceExpiresTime" value="<?php echo $getData['sgOnceExpiresTime'];?>">
				</div>
			</div>
			<div class="col-md-1">
				<span>Days</span>
			</div>
		</div>

		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Disable popup closing:</label>
			</div>
			<div class="col-xs-4">
				<div class="checkbox">
					<input type="checkbox" name="disablePopup" <?php echo $getData['sgDisablePopup'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Disable popup overlay:</label>
			</div>
			<div class="col-xs-4">
				<div class="checkbox">
					<input type="checkbox" name="disablePopupOverlay" <?php echo $getData['sgDisablePopupOverlay'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Auto close popup:</label>
			</div>
			<div class="col-xs-4">
				<div class="checkbox">
					<input id="js-auto-close" class="js-checkbox-acordion-content" data-acordion-conetnt="sg-popup-auto-close" type="checkbox" name="autoClosePopup" <?php echo $getData['sgAutoClosePopup'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-popup-auto-close sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">After:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="popupTimer improveOptionsstyle" type="text" name="popupClosingTimer" value="<?php echo $getData['sgPopupClosingTimer'];?>">
				</div>
			</div>
		</div>
		<?php if(SG_POPUP_PLATINUM): ?>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label">Filter popup for selected countries:</label>
			</div>
			<div class="col-xs-4">
				<div class="checkbox">
					<input id="js-countris" class="js-checkbox-acordion-content" data-acordion-conetnt="sg-popup-country" type="checkbox" name="countryStatus" <?php echo $getData['sgCountryStatus'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-popup-country sg-static-margin-bottom">
			<div class="col-xs-4">
				
			</div>
			<div class="col-md-8">
				<div class="sg-align-checkbox">
					<?php echo $functions->sgCreateRadioElements($defaults['countriesRadio'], $getData['sgAllowCountries'], false);?>
					<div class="row">
						<div class="col-xs-4">
							<?php  echo $defaults['countris'];?>
						</div>
						<div class="col-xs-4">
							<input type="button" value="Add" class="btn btn-primary addCountry">
						</div>
					</div>
					
					
					<input type="text" name="countryName" id="countryName" data-role="tagsinput" id="countryName" value="<?php echo $getData['sgCountryName']?>">
					<input type="hidden" name="countryIso"  id="countryIso" value="<?php echo $getData['sgCountryIso']?>">
				</div>
			</div>
		</div>
		<?php endif;?>
	</div>
</div>