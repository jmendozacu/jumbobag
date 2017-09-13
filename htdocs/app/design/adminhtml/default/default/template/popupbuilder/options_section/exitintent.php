<div class="panel panel-default">
	<div class="panel-heading">
	<?php 
		$popupTitles = $defaults['popupTitles'];
		echo @$popupTitles[$popupType]." options";
	?>
 	</div>
	<div class="panel-body">
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput"><b>Mode</b></label>
			</div>
			<div class="col-xs-4">
				
			</div>
		</div>
		<div class="sg-static-margin-bottom">
		<?php echo $functions->createRadiobuttons($defaults['radiobuttons'], "exit-intent-type", true, $getData['sgExitIntentTpype'], "liquid-width"); ?>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Show Popup:</label>
			</div>
			<div class="col-xs-4">
				<?php echo $functions->sgCreateSelect($defaults['sgExitIntentSelectOptions'], "exit-intent-expire-time", $getData['sgExitIntntExpire'])?>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Alert text:</label>
			</div>
			<div class="col-xs-4">
				<input  class="form-control" type="text" name="exit-intent-alert" value="<?php echo $getData['sgExitIntentAlert']; ?>">
			</div>
		</div>

	</div>
</div>