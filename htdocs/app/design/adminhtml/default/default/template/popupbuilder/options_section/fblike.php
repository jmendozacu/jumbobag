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
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Url:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					
					<input class="form-control input-md" type="text" name="fblike-like-url" value="<?php echo $options['fblike-like-url']?>">
				</div>
			</div>
		</div>
		<div class="row sg-static-margin-bottom">
			<div class="col-xs-4">
				<label class="control-label" for="textinput">Layout:</label>
			</div>
			<div class="col-xs-4">
				<div class="sg-align-checkbox">
					<?php echo $functions->sgCreateSelect($defaults['sgFbLikeButtons'],'fblike-layout',$options['fblike-layout']);?>
				</div>
			</div>
		</div>

	</div>
</div>