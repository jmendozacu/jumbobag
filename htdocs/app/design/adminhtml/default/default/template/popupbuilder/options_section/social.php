<?php
	define('SG_SOCIAL_LABEL_MAX_SIZE', 15);

	$radioElements = array(
		array(
			'name'=>'shareUrlType',
			'value'=>'activeUrl',
			'additionalHtml'=>''.'<span>'.'Use active URL'.'</span></span></div><div class="col-xs-4">
							<span class="span-width-static"></span><span class="dashicons dashicons-info scrollingImg sameImageStyle sg-active-url"></span></div>',
			'newline' => true
		),
		array(
			'name'=>'shareUrlType',
			'value'=>'shareUrl',
			'additionalHtml'=>''.'<span class="sg-socila-share">'.'Share url'.'</span></span></div><div class="col-xs-4">'.'<div class="sg-align-checkbox"> <input class="form-control input-md sg-active-url" type="text" name="sgShareUrl" value="'.@$getData['sgShareUrl'].'"></div></div>',
			'newline' => true
		)
	);
?>

<div class="panel panel-default">
	<div class="panel-heading">
	<?php 
		$popupTitles = $defaults['popupTitles'];
		echo $popupTitles[$popupType]." options";
	?>
 	</div>
	<div class="panel-body">
		<div class="sg-align-checkbox">
		<?php
			if (empty($getData['shareUrlType'])) {
				$getData['shareUrlType'] = 'activeUrl';
			}
			echo $functions->sgCreateRadioElements($radioElements,$getData['shareUrlType'], true);
		?>
		</div>
		<br>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput"><b>Configuration of the buttons</b></label>
			</div>
			<div class="col-xs-4">
				
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Theme:</label>
			</div>
			<div class="col-xs-4">

				<?php if(@$sgSocialTheme == ''): ?>
					<?php $sgSocialTheme = 'classic'; ?>
				<?php endif; ?>
				<div class="sg-align-checkbox">
					<?php echo $functions->sgCreateSelect($defaults['sgTheme'],'sgSocialTheme',$getData['sgSocialTheme']);?>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Font size:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<?php echo $functions->sgCreateSelect($defaults['sgThemeSize'],'sgSocialButtonsSize',$getData['sgSocialButtonsSize']);?>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Show labels:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="checkbox" name="sgSocialLabel"  class="socialTrigger" <?php echo $getData['sgSocialLabel'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Show share count:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<?php echo $functions->sgCreateSelect($defaults['sgSocialCount'],'sgSocialShareCount',@$options['sgSocialShareCount']);?>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Use round buttons:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="checkbox" class="socialTrigger" name="sgRoundButton" <?php echo $getData['sgRoundButtons'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Push to bottom:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="checkbox" class="pushToBottom" name="pushToBottom" <?php echo $getData['sgPushToBottom'];?>>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-md-12">
				<div id="share-btns-container"></div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput"><b>Share Buttons</b></label>
			</div>
			<div class="col-xs-4">
				
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput"><b>E-mail</b></label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input id="js-email-checkbox" data-social-conetnt="js-email-content" class="js-social-btn-status js-checkbox-acordion" data-social-button="email" type="checkbox" name="sgEmailStatus" <?php echo $getData['sgEmailStatus'];?>>
				</div>
			</div>
		</div>
		<div class="row js-email-content sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Label:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input  class="form-control js-social-btn-text" data-social-button="email" type="text" name="sgMailLable" placeholder="E-mail" maxlength=">" value="<?php echo $getData['sgMailLable']?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput"><b>Facebook</b></label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input id="js-fb-checkbox" data-social-conetnt="js-fb-content" class="js-social-btn-status js-checkbox-acordion" data-social-button="facebook" type="checkbox" name="sgFbStatus" <?php echo $getData['sgFbStatus'];?>>
				</div>
			</div>
		</div>
		<div class="row js-fb-content sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Label:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control js-social-btn-text" data-social-button="facebook" type="text" name="fbShareLabel"  placeholder="Like" maxlength="" value="<?php echo $getData['fbShareLabel']?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput"><b>LinkedIn</b></label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="checkbox" id="js-linkedin-checkbox" data-social-conetnt="js-linkedin-content" class="js-social-btn-status js-checkbox-acordion" data-social-button="linkedin" name="sgLinkedinStatus" <?php echo $getData['sgLinkedinStatus'];?>>
				</div>
			</div>
		</div>
		<div class="row js-linkedin-content sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Label:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input  class="form-control js-social-btn-text" data-social-button="linkedin" type="text" name="lindkinLabel" placeholder="Share" maxlength="" value="<?php echo $getData['lindkinLabel']?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput"><b>Google+</b></label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="checkbox" id="js-google-checkbox"  data-social-conetnt="js-google-content" class="js-social-btn-status js-checkbox-acordion" data-social-button="googleplus" name="sgGoogleStatus" <?php echo $getData['sgGoogleStatus'];?>>
				</div>
			</div>
		</div>
		<div class="row js-google-content sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Label:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control js-social-btn-text" data-social-button="googleplus" type="text" name="googLelabel" placeholder="+1" maxlength="" value="<?php echo $getData['googLelabel']?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput"><b>Twitter</b></label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input id="js-twitter-checkbox" type="checkbox" data-social-conetnt="js-twitter-content" class="js-social-btn-status js-checkbox-acordion" data-social-button="twitter" name="sgTwitterStatus" <?php echo $getData['sgTwitterStatus'];?>>
				</div>
			</div>
		</div>
		<div class="row js-twitter-content sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Label:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input  class="form-control js-social-btn-text" data-social-button="twitter" type="text" name="twitterLabel" placeholder="Tweet" maxlength="" value="<?php echo $getData['twitterLabel']?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput"><b>Pinterest</b></label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="checkbox" id="js-pinterest-checkbox" data-social-conetnt="js-pinterest-content" class="js-social-btn-status" data-social-button="pinterest" name="sgPinterestStatus" <?php echo $getData['sgPinterestStatus'];?>>
				</div>
			</div>
		</div>
		<div class="row js-pinterest-content sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Label:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input  class="form-control js-social-btn-text" data-social-button="pinterest" type="text" name="pinterestLabel" placeholder="Pin it" maxlength="" value="<?php echo $getData['pinterestLabel']?>">
				</div>
			</div>
		</div>
	</div>
</div>
<link type="text/css" rel="stylesheet" href="<?php echo SG_SKIN_ADMIN_CSS_URL;?>jssocial/jssocials.css">
<link type="text/css" rel="stylesheet" href="<?php echo SG_SKIN_ADMIN_CSS_URL;?>jssocial/font-awesome.min.css">
<link type="text/css" rel="stylesheet" id="jssocials_theme_tm-css" href="<?php echo SG_SKIN_ADMIN_CSS_URL;?>jssocial/jssocials-theme-classic.css">
<script type="text/javascript" src="<?php echo SG_SKIN_ADMIN_JS_URL;?>sg_social_backend.js"></script>
<script type="text/javascript" src="<?php echo SG_SKIN_ADMIN_JS_URL;?>jssocials.min.js"></script>