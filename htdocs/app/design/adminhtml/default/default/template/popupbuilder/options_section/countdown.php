<?php
	require_once(Mage::getModuleDir("community", SG_POPUP_COMMUNITY_REPO).DS."public".DS."classes".DS."SGCountdownPopup.php");
	$options = json_decode(@$popupTypeData['options'], true);
?>
<div class="panel panel-default">
	<div class="panel-heading">
	<?php 
		$popupTitles = $defaults['popupTitles'];
		echo $popupTitles[$popupType]." options";
	?>
 	</div>
	<div class="panel-body">

		<div class="sg-countdown-wrapper" id="sg-clear-coundown">
			<div class="sg-counts-content"></div>
		</div>
		
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Counter background color:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" id="sgOverlayColor" placeholder="Select color" name="countdownNumbersBgColor" class="sg-countdown-color sg-color sg-contact-btn-border-color" value="<?php echo $getData['sgCountdownNumbersBgColor'];?>"></div><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Counter text color:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" id="sgOverlayColor" placeholder="Select color" name="countdownNumbersTextColor" class="sg-countdown-color sg-color sg-contact-btn-border-color minicolors-input" value="<?php echo $getData['sgCountdownNumbersTextColor'];?>"></div><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Due date:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="sg-calndar form-control input-md" type="text" name="sg-due-date" value="<?php echo $getData['sgDueDate']?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Countdown format:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<?php echo  $functions->sgCreateSelect($defaults['sgCountdownType'],'sg-countdown-type', $getData['sgGetCountdownType'])?>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Time zone:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<?php echo $functions->sgCreateSelect($defaults['sgTimeZones'],'sg-time-zone',$getData['sgSelectedTimeZone'])?>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Select language:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<?php echo $functions->sgCreateSelect($defaults['sgCountdownlang'],'counts-language',$getData['sgCountdownLang'])?>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Show counter on the Top:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="checkbox"  class="pushToBottom" name="pushToBottom" <?php echo $getData['pushToBottom'];?>>
				</div>
			</div>
		</div>
		
		<script type="text/javascript" src="<?php echo SG_SKIN_ADMIN_JS_URL;?>sg_flipclock.js"></script>
		<script type="text/javascript" src="<?php echo SG_SKIN_ADMIN_JS_URL;?>sg_countdown.js"></script>
		<link type="text/css" rel="stylesheet" href="<?php echo SG_SKIN_ADMIN_CSS_URL;?>sg_flipclock.css">

		<?php
			echo SGCountdownPopup::renderScript(@$seconds, $options['"sg-countdown-type'], '', '$options["counts-language"]');
			echo SGCountdownPopup::renderStyle($getData['sgCountdownNumbersBgColor'], $getData['sgCountdownNumbersTextColor']);
			echo "<script type=\"text/javascript\">
				jQuery(document).ready(function() {
					var objCountdown = new SGCountdown();
					objCountdown.adminInit();
				});
			</script>";

		?>
	</div>
</div>