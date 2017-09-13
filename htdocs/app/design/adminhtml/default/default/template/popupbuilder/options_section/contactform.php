<?php
$uploadUrl= Mage::helper('popupbuilder/GetData')->getPageUrl('subsAjax');
$user = Mage::getSingleton('admin/session'); 
$userEmail = $user->getUser()->getEmail();
$contactEmail = ((!$getData['sgContactResiveMail']) ? $userEmail: $getData['sgContactResiveMail']);

?>
<div class="panel panel-default">
	<div class="panel-heading">
	<?php 
		$popupTitles = $defaults['popupTitles'];
		echo @$popupTitles[$popupType]." options";
	?>
 	</div>
	<div class="panel-body">
		
		<div class="row">
			<div class="col-xs-4">
				
			</div>
			<div class="col-md-8">
				<div class="sg-align-checkbox">
				<h1 >Live Preview</h1>
				<input type="text" class="js-contact-text-inputs js-contact-name" value="" placeholder="<?php echo @$getData['sgContactNameLabel'];?>">
				<input type="text" class="js-contact-text-inputs js-contact-subject" value="" placeholder="<?php echo @$getData['sgContactSubjectLabel'];?>">
				<input type="text" class="js-contact-text-inputs js-contact-email" value="" placeholder="<?php echo @$getData['sgContactEmailLabel'];?>">
				<textarea placeholder="<?php echo @$getData['sgContactMessageLabel']?>" class="js-contact-message js-contact-text-area"></textarea><br>
				<input type="button" value="Submit" class="js-contact-submit-btn">
				</div>
			</div>
		</div>
		<div class="row sg-firstdiv-margin">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Receive mail</label>
			</div>
			<div class="col-xs-4">	
				<div class="sg-align-checkbox">
					<input class="form-control input-md sg-contact-fileds" type="text" name="contact-resive-email" value="<?php echo $contactEmail;?>"/><br>
				</div>
			</div>
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
				<label class="control-label" for="textinput">Name</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control input-md sg-contact-fileds" data-contact-rel="js-contact-name" type="text" name="contact-name" value="<?php echo $getData['sgContactNameLabel'];?>"/><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Subject:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control input-md sg-contact-fileds" data-contact-rel="js-contact-subject" type="text" name="contact-subject" value="<?php echo $getData['sgContactSubjectLabel'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Email:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control input-md sg-contact-fileds" data-contact-rel="js-contact-email" name="contact-email" value="<?php echo $getData['sgContactEmailLabel'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Message:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control input-md sg-contact-fileds" data-contact-rel="js-contact-message" type="text" name="contact-message" value="<?php echo $getData['sgContactMessageLabel'];?>"/><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Required field message:</span>
			</div>
			<div class="col-xs-4">	
				<div class="sg-align-checkbox">
					<input class="form-control input-md" type="text" name="contact-validation-message" value="<?php echo $getData['sgContactValidationMessage'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Send error message</span>
			</div>
			<div class="col-xs-4">	
				<div class="sg-align-checkbox">
					<input class="form-control input-md" type="text" name="contact-fail-message" value="<?php echo $getData['sgContactFailMessage'];?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Invalid email field message:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control input-md" type="text" name="contact-validate-email" value="<?php echo $getData['sgContactValidateEmail'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Success message:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control input-md" type="text" name="contact-success-message" value="<?php echo $getData['sgContactSuccessMessage'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Input styles</label>
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
					<input type="text" class="form-control input-md" name="contact-inputs-width" data-contact-rel="js-contact-text-inputs" value="<?php echo $getData['sgContactInputsWidth'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Height:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control input-md" name="contact-inputs-height" data-contact-rel="js-contact-text-inputs" value="<?php echo $getData['sgContactInputsHeight'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Border width:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">	
					<input type="text" class="form-control input-md" name="contact-inputs-border-width" data-contact-rel="js-contact-text-inputs" value="<?php echo $getData['sgContactInputsBorderWidth'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Background color:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" data-contact-rel="js-contact-text-inputs" data-contact-area-rel="js-contact-text-area" id="sgOverlayColor" name="contact-text-input-bgcolor" class="sg-color-picker sg-color sg-contact-btn-color" value="<?php echo $getData['sgContactTextInputBgcolor'];?>"></div><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Border color:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" data-contact-rel="js-contact-text-area" data-contact-area-rel="js-contact-text-area" id="sgOverlayColor" name="contact-text-bordercolor" class="sg-color-picker sg-color sg-contact-btn-border-color" value="<?php echo $getData['sgContactTextBordercolor'];?>"></div><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Text color:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" data-contact-rel="js-contact-text-inputs" data-contact-area-rel="js-contact-text-area" id="sgOverlayColor" name="contact-inputs-color" class="sg-color-picker sg-color sg-contact-btn-text-color" value="<?php echo $getData['sgContactInputsColor'];?>"></div><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Text color:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" data-contact-rel="js-contact-text-inputs" data-contact-area-rel="js-contact-text-area" id="sgOverlayColor" name="contact-inputs-color" class="sg-color-picker sg-color sg-contact-btn-text-color" value="<?php echo $getData['sgContactInputsColor'];?>"></div><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Placeholder color:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">	
					<div class="sg-color-picker-wrapper"><input type="text" data-contact-rel="js-contact-text-inputs" data-contact-area-rel="js-contact-text-area" id="sgOverlayColor" name="contact-placeholder-color" class="sg-color-picker sg-color sg-contact-placeholder-color" value="<?php echo $getData['sgContactPlaceholderColor'];?>"></div><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Textarea style</label>
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
					<input type="text" class="form-control input-md" name="contact-area-width" data-contact-rel="js-contact-text-area" value="<?php echo $getData['sgContactAreaWidth'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Height:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control input-md" name="contact-area-height" data-contact-rel="js-contact-text-area" value="<?php echo $getData['sgContactAreaHeight'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Reszie:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<?php echo $functions->sgCreateSelect($defaults['sgTextAreaResizeOptions'],'sg-contact-resize',@$getData['sgContactResize'])?><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Sumbmit button style</label>
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
					<input type="text" class="form-control input-md" data-contact-rel="js-contact-submit-btn" name="contact-btn-width" value="<?php echo $getData['sgContactBtnWidth'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Height:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control input-md" data-contact-rel="js-contact-submit-btn" name="contact-btn-height" value="<?php echo $getData['sgContactBtnHeight'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom ">
			<div class="col-xs-4">
				<span>Title:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control input-md" data-contact-rel="js-contact-submit-btn" name="contact-btn-title" value="<?php echo $getData['sgContactBtnTitle'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Title (in progress):</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control input-md" data-contact-rel="js-contact-submit-btn" name="contact-btn-progress-title" value="<?php echo $getData['sgContactBtnProgressTitle'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Background color:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" data-contact-rel="js-contact-submit-btn" id="sgOverlayColor" name="contact-button-bgcolor" class="sg-color-picker sg-color sg-contact-btn-color" value="<?php echo $getData['sgContactButtonBgcolor'];?>"></div><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<span>Text color:</span>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" data-contact-rel="js-contact-submit-btn" id="sgOverlayColor" name="contact-button-color" class="sg-color-picker sg-color sg-contact-btn-text-color" value="<?php echo $getData['sgContactButtonColor'];?>"></div><br>
				</div>
			</div>
		</div>

	</div>
</div>
<script type="text/javascript" src="<?php echo SG_SKIN_ADMIN_JS_URL;?>sg_contactForm.js"></script>
<?php 						
	echo "<script type='text/javascript'>
			contactFrontend = {
				'inputsWidth': '".$getData['sgContactInputsWidth']."',
				'buttnsWidth': '".$getData['sgContactBtnWidth']."',
				'inputsHeight': '".$getData['sgContactInputsHeight']."',
				'buttonHeight': '".$getData['sgContactBtnHeight']."',
				'procesingTitle': '55px',
				'placeholderColor': '".$getData['sgContactPlaceholderColor']."',
				'btnTextColor': '".$getData['sgContactButtonColor']."',
				'btnBackgroundColor': '".$getData['sgContactButtonBgcolor']."',
				'inputsBackgroundColor': '".$getData['sgContactTextInputBgcolor']."',
				'inputsColor': '".$getData['sgContactInputsColor']."',
				'contactInputsBorderWidth': '".$getData['sgContactInputsBorderWidth']."',
				'ajaxurl': '',
				'contactAreaWidth': '".$getData['sgContactAreaWidth']."',
				'contactAreaHeight': '".$getData['sgContactAreaHeight']."',
				'contactResize': '".@$getData['sgContactResize']."',
				'inputsBorderColor': '".$getData['sgContactTextInputBgcolor']."',
				'validateMessage': '".$getData['sgContactValidationMessage']."'
			}
	</script>";
	echo "<script type=\"text/javascript\">
		jQuery(document).ready(function() {
			sgContactObj = new SgContactForm();
			sgContactObj.livePreview();
			sgContactObj.buildStyle();
		});

	</script>";
?>