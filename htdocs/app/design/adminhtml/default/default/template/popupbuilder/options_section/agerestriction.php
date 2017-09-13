<?php
	$options = @json_decode($popupTypeData['options'], true);
?>
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
				<label class="control-label" for="textinput"><b>&quot;Yes&quot; button</b></label>
			</div>
			<div class="col-xs-4">
				
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Label:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input  class="form-control input-md" type="text" required="required" name="yesButtonLabel" value="<?php echo @$popupTypeData['yesButton']; ?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Button background color:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" id="sgOverlayColor" name="yesButtonBackgroundColor" class="sg-color-picker sg-color sg-contact-btn-border-color" value="<?php echo $getData['yesButtonBackgroundColor'];?>"></div><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Button text color:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" id="sgOverlayColor" name="yesButtonTextColor" class="sg-color-picker sg-color sg-contact-btn-border-color" value="<?php echo $getData['yesButtonTextColor'];?>"></div><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Button radius:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input  class="form-control input-md" type="number" min="0" max="50" name="yesButtonRadius" value="<?php echo $getData['yesButtonRadius'];?>"><br>
				</div>
			</div>
			<div class="col-md-1">
				<span class="span-percent">%</span><br>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput"><b>&quot;No&quot; button </b></label>
			</div>
			<div class="col-xs-4">
				
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Label:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input class="form-control input-md" type="text" name="noButtonLabel" required="required" value="<?php echo @$popupTypeData['noButton'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Button background color:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" id="sgOverlayColor" name="noButtonBackgroundColor" class="sg-color-picker sg-color sg-contact-btn-border-color" value="<?php echo $getData['noButtonBackgroundColor'];?>"></div><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Button text color:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<div class="sg-color-picker-wrapper"><input type="text" id="sgOverlayColor" name="noButtonTextColor" class="sg-color-picker sg-color sg-contact-btn-border-color" value="<?php echo $getData['noButtonTextColor'];?>"></div><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Button radius:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input  class="form-control input-md" type="number" min="0" max="50" name="noButtonRadius" value="<?php echo $getData['noButtonRadius'];?>"><br>
				</div>
			</div>
			<div class="col-md-1">
				<span class="span-percent">%</span><br>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Restriction URL:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="text" class="form-control input-md" name="restrictionUrl" value="<?php echo @$popupTypeData['url'];?>"><br>
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Push to bottom:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<input type="checkbox"  class="pushToBottom" name="pushToBottom" <?php echo $getData['pushToBottom'];?>><br>
				</div>
			</div>
		</div>
	</div>
</div>