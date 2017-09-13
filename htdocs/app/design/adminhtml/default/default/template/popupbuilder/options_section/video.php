<?php
	$options = @json_decode($popupTypeData['options'], true);

?>
<div class="panel panel-default">
	<div class="panel-heading">
	<?php 
		$popupTitles = $defaults['popupTitles'];
		echo $popupTitles[$popupType]." options";
	?>
 	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Autoplay:</label>
			</div>
			<div class="col-xs-4">
				<div class="checkbox">
					<input class="" type="checkbox" name="video-autoplay" <?php echo $getData['sgVideoAutopaly'];?>>
				</div>
			</div>
		</div>
	</div>
</div>