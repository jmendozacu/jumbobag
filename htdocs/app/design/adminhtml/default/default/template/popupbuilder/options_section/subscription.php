<div class="panel panel-default">
	<div class="panel-heading">
	<?php 
		$popupTitles = $defaults['popupTitles'];
		echo $popupTitles[$popupType]." options";
	?>
 	</div>
	<div class="panel-body">
		<div class="sg-text-align">
			<h1 >Live Preview</h1>
			<input type="text" class="js-subs-text-inputs js-subs-email-name" value="" placeholder="<?php echo @$getData['sgSubscriptionEmail'];?>">
			<input type="text" class="js-subs-text-inputs js-subs-first-name" value="" placeholder="<?php echo @$getData['sgSubsFirstName'];?>">
			<input type="text" class="js-subs-text-inputs js-subs-last-name" value="" placeholder="<?php echo @$getData['sgSubsLastName']; ?>">
			<input type="button" value="Submit" class="js-subs-submit-btn"><br>
			<hr>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">General options</label>
			</div>
			<div class="col-xs-4">
				
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Email:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control sg-subs-fileds" data-subs-rel="js-subs-email-name" name="subscription-email" value="<?php echo $getData['sgSubscriptionEmail'];?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput"><b>First name</b></label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="js-checkbox-acordion" data-subs-conetnt="js-first-content" data-subs-rel="js-subs-first-name" type="checkbox" name="subs-first-name-status" <?php echo $getData['sgSubsFirstNameStatus'];?>>
				</div>
			</div>
		</div>
		<div class="row js-first-content sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Your first Name:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control sg-subs-fileds" data-subs-rel="js-subs-first-name" type="text" name="subs-first-name" value="<?php echo $getData['sgSubsFirstName'];?>"/>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput"><b>Last name</b></label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="js-checkbox-acordion" type="checkbox" data-subs-conetnt="js-second-content" data-subs-rel="js-subs-last-name" name="subs-last-name-status" <?php echo $getData['sgSubsLastNameStatus'];?>>
				</div>
			</div>
		</div>
		<div class="row js-second-content sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Your last name:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control sg-subs-fileds" data-subs-rel="js-subs-last-name" type="text" name="subs-last-name" value="<?php echo $getData['sgSubsLastName'];?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Required field message:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control" type="text" name="subs-validation-message" value="<?php echo $getData['sgSubsValidateMessage'];?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Success message:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control" type="text" name="subs-success-message" value="<?php echo $getData['sgSuccessMessage'];?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Invalid email field message:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control" type="text" name="subs-email-validate" value="<?php echo $getData['sgSubsEmailValidate'];?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput"><b>Input styles</b></label>
			</div>
			<div class="col-xs-4">
		
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Width:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control" name="subs-text-width" value="<?php echo $getData['sgSubsTextWidth'];?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Height:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control" name="subs-text-height" value="<?php echo $getData['sgSubsTextHeight'];?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Border width:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control" name="subs-text-border-width" data-subs-rel="js-subs-text-inputs" value="<?php echo $getData['sgSubsTextBorderWidth'];?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Background color:<span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" data-subs-rel="js-subs-text-inputs" id="sgOverlayColor" name="subs-text-input-bgcolor" class="sg-color-picker sg-color sg-subs-btn-color" value="<?php echo $getData['sgSubsTextInputBgcolor'];?>"></div>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Border color:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" data-subs-rel="js-subs-text-inputs" id="sgOverlayColor" name="subs-text-bordercolor" class="sg-color-picker sg-color sg-subs-btn-border-color" value="<?php echo $getData['sgSubsTextBordercolor'];?>"></div>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Text color:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" data-subs-rel="js-subs-text-inputs" id="sgOverlayColor" name="subs-inputs-color" class="sg-color-picker sg-color sg-subs-btn-border-color" value="<?php echo $getData['sgSubsInputsColor'];?>"></div>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Placeholder color:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" data-subs-rel="js-subs-text-inputs" id="sgOverlayColor" name="subs-placeholder-color" class="sg-color-picker sg-color sg-subs-placeholder-color" value="<?php echo $getData['sgSubsPlaceholderColor'];?>"></div>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Sumbmit button styles:</label>
			</div>
			<div class="col-xs-4">
				
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Width:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control" data-subs-rel="js-subs-submit-btn" name="subs-btn-width" value="<?php echo $getData['sgSubsBtnWidth'];?>" >
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Height:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control" name="subs-btn-height" value="<?php echo $getData['sgSubsBtnHeight'];?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Title:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control" data-subs-rel="js-subs-submit-btn" name="subs-btn-title" value="<?php echo $getData['sgSubsBtnTitle'];?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Title (in progress):</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control" data-subs-rel="js-subs-submit-btn" name="subs-btn-progress-title" value="<?php echo $getData['sgSubsBtnProgressTitle'];?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Background color:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" data-subs-rel="js-subs-submit-btn" id="sgOverlayColor" name="subs-button-bgcolor" class="sg-color-picker sg-color sg-subs-btn-color" value="<?php echo $getData['sgSubsButtonBgcolor'];?>"></div>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Text color:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" data-subs-rel="js-subs-submit-btn" id="sgOverlayColor" name="subs-button-bgcolor" class="sg-color-picker sg-color sg-subs-btn-color" value="<?php echo $getData['sgSubsButtonBgcolor'];?>"></div>
				</div>
			</div>
		</div>

	</div>
</div>

<script type="text/javascript" src="<?php echo SG_SKIN_ADMIN_JS_URL;?>sg_subscription.js"></script>
<?php
echo "<script>
		jQuery(document).ready(function() {
			sgSubscriptionObj = new SgSubscription();
			sgSubscriptionObj.setTextInputWidth('".$getData['sgSubsTextWidth']."');
			sgSubscriptionObj.setBtnWidth('".$getData['sgSubsBtnWidth']."');
			sgSubscriptionObj.setTextInputsHeight('".$getData['sgSubsTextHeight']."');
			sgSubscriptionObj.setBtnHeight('".$getData['sgSubsBtnHeight']."');
			sgSubscriptionObj.setupBackgroundColor('.js-subs-text-inputs', '".$getData['sgSubsTextInputBgcolor']."');
			sgSubscriptionObj.setupBackgroundColor('.js-subs-submit-btn', '".$getData['sgSubsButtonBgcolor']."');
			sgSubscriptionObj.setupBorderColor('.js-subs-text-inputs', '".$getData['sgSubsTextBordercolor']."');
			sgSubscriptionObj.setupButtonColor('.js-subs-submit-btn', '".$getData['sgSubsButtonColor']."');
			sgSubscriptionObj.setupButtonColor('.js-subs-text-inputs', '".$getData['sgSubsInputsColor']."');
			sgSubscriptionObj.setupButtonText('.js-subs-submit-btn', '".$getData['sgSubsBtnTitle']."');
			sgSubscriptionObj.setupPlaceholderColor('js-subs-text-inputs', '".$getData['sgSubsPlaceholderColor']."');
			sgSubscriptionObj.setupBorderWidth('js-subs-text-inputs', '".$getData['sgSubsTextBorderWidth']."');
			sgSubscriptionObj.buildStyle();
			sgSubscriptionObj.livePreview();
		});
	</script>";
	echo "<style>
	.js-subs-submit-btn,
	.js-subs-text-inputs {
		padding: 5px !important;
		box-sizing: border-box;
	}
	</style>";