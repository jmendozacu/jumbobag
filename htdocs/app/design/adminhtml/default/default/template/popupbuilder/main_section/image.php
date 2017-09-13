<div class="sg-wp-editor-container">
	<h3 class="image-popup-headline">Please choose your picture</h3>

	<div class="image-uploader-wrapper">
		<div class="sg-file-upload-container">
			<div class="sg-file-upload-override-button sg-left">
				<span class="sg-upload-label">Choose file</span>
				<input type="file" name="ad_image" data-image-value="<?php echo @$popupTypeData['path'];?>" class="sg-file-upload-button" id="file-upload-button"/>
				<input type="hidden" class="sg-skin-url" data-Skin-url="<?php echo SG_SKIN_IAMGE_URL."/";?>">
			</div>
			<div class="sg-file-upload-filename left" id="file-upload-filename">No file selected</div>
			<div class="celar"></div>
		</div>
	</div>
	<div class="show-image-contenier">
		<span class="no-image">(No image selected)</span>
	</div>
</div>