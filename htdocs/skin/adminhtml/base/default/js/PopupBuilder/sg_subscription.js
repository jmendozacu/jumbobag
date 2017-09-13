function SgSubscription() {
	var firstNameStatus;
	var lastNameStatus;
	var textInputWidth;
	var btnWidth;
	var tetxInputsHeight;
	var btnHeight;
	var inProgresTitle;
}

SgSubscription.prototype.changeDimensionMode = function(dimension) {
	var size;
	size =  parseInt(dimension)+"px";
	if(dimension.indexOf("%") != -1 || dimension.indexOf("px") != -1) {
		size = dimension;
	}
	return size;
}

SgSubscription.prototype.setFirstNameStatus = function(firstNameStatus) {
	this.firstNameStatus = firstNameStatus;
}

SgSubscription.prototype.getFirstNameStatus = function() {
	return this.firstNameStatus;
}

SgSubscription.prototype.setLastNameStatus = function(lastNameStatus) {
	this.lastNameStatus = lastNameStatus;
}

SgSubscription.prototype.setInProgresTitle = function(title) {
	this.inProgresTitle = title;
}

SgSubscription.prototype.getInProgresTitle = function() {
	return this.inProgresTitle;
}

SgSubscription.prototype.getLastNameStatus = function() {
	return this.lastNameStatus;
}

SgSubscription.prototype.setTextInputWidth = function(width) {
	this.textInputWidth = this.changeDimensionMode(width);
}

SgSubscription.prototype.setTextInputsHeight = function(height) {
	this.tetxInputsHeight = this.changeDimensionMode(height);
}

SgSubscription.prototype.getTextInputsHeight = function() {
	return this.tetxInputsHeight;
}

SgSubscription.prototype.setBtnHeight = function(height) {
	this.btnHeight = this.changeDimensionMode(height);
}

SgSubscription.prototype.getBtnHeight = function() {
	return this.btnHeight;
}

SgSubscription.prototype.getTextInputWidth = function() {
	return this.textInputWidth;
}

SgSubscription.prototype.setBtnWidth = function(width) {
	this.btnWidth = width;
}

SgSubscription.prototype.getBtnWidth = function(width) {
	return this.btnWidth;
}

/*
 	Seters and getters
*/

SgSubscription.prototype.toggleVisible = function(toggleElement, elementStatus) {
	if(elementStatus) {
		toggleElement.css({'display': 'block'});
	}
	else {
		toggleElement.css({'display': 'none'});
	}
}

SgSubscription.prototype.binding = function() {
	var that = this;
	$jsg(".js-checkbox-acordion").bind('click', function() {
		var isCecked = $jsg(this).is(":checked");
		var elementClassName = $jsg(this).attr("data-subs-rel");
		var element = $jsg("."+elementClassName+"");
		var optionConetent = $jsg(this).attr("data-subs-conetnt");
		var optionConetent = $jsg("."+optionConetent);
	
		that.toggleVisible(element, isCecked);
		that.toggleVisible(optionConetent, isCecked);
	});
	$jsg(".js-checkbox-acordion").each(function() {
		var isCecked = $jsg(this).is(":checked");
		var elementClassName = $jsg(this).attr("data-subs-rel");
		var element = $jsg("."+elementClassName+"");
		var optionConetent = $jsg(this).attr("data-subs-conetnt");
		var optionConetent = $jsg("."+optionConetent);

		that.toggleVisible(element, isCecked);
		that.toggleVisible(optionConetent, isCecked);
	})
}

SgSubscription.prototype.changeBorderWidth = function() {
	var that = this;
	$jsg('[name="subs-text-border-width"]').change(function() {
		var value = $jsg(this).val();
		var className = $jsg(this).attr('data-subs-rel');
		that.setupBorderWidth(className, value);
	});
}

SgSubscription.prototype.changeLabels = function() {
	$jsg(".sg-subs-fileds[data-subs-rel]").each(function() {
		$jsg(this).bind("input", function() {
			var className = $jsg(this).attr("data-subs-rel");
			var placeholderText = $jsg(this).val();
			$jsg("."+className).attr("placeholder", placeholderText);
		});
	});
}

SgSubscription.prototype.changeButtonTitle = function() {
	var that = this;
	$jsg("[name='subs-btn-title']").change(function() {
		var className = $jsg(this).attr("data-subs-rel");
		var val = $jsg(this).val();
		that.setupButtonText("."+className,val);
	});
}

SgSubscription.prototype.colorpickerChange = function() {
	var that = this;
	$jsg('.sg-subs-btn-color').minicolors({
		change: function() {
			var sgColorpicker = $jsg(this);
			var color = sgColorpicker.val();
			var classname = sgColorpicker.attr('data-subs-rel');
			that.setupBackgroundColor("."+classname, color);
		}
	});
	$jsg('.sg-subs-btn-border-color').minicolors({
		change: function() {
			var sgColorpicker = $jsg(this);
			var color = sgColorpicker.val();
			var classname = sgColorpicker.attr('data-subs-rel');
			that.setupBorderColor('.'+classname, color);
		}
	});
	$jsg('.sg-subs-btn-text-color').minicolors({
		change: function() {
			var sgColorpicker = $jsg(this);
			var color = sgColorpicker.val();
			var classname = sgColorpicker.attr('data-subs-rel');
			that.setupButtonColor("."+classname, color);
		}
	});
	$jsg('.sg-subs-placeholder-color').minicolors({
		change: function() {
			var sgColorpicker = $jsg(this);
			var color = sgColorpicker.val();
			var classname = sgColorpicker.attr('data-subs-rel');
			that.setupPlaceholderColor(classname,color);
		}
	});
	$jsg(".wp-picker-holder").bind('click',function() {
		var selectedInput = $jsg(this).prev().find('.sgOverlayColor');
	});
}

SgSubscription.prototype.setupBackgroundColor = function(element, color) {
	$jsg(element).each(function() {
		$jsg(this).css({'background': color});
	});
}

SgSubscription.prototype.setupBorderColor = function(element, color) {
	$jsg(element).each(function() {
		$jsg(this).css({'border-color': color});
	});
}

SgSubscription.prototype.setupButtonColor = function(element, color) {
	$jsg(element).css({'color': color});
}

SgSubscription.prototype.setupButtonText = function(element, value) {
	$jsg(element).val(value);
}

SgSubscription.prototype.setupPlaceholderColor = function(element, color) {
	$jsg("."+element).each(function() {
		$jsg("#sg-placeholder-style").remove()
		var styleContent = '.'+element+'::-webkit-input-placeholder {color: ' + color + ';} .'+element+'::-moz-placeholder {color: ' + color + ';} .'+element+':-ms-input-placeholder {color:$sgSubsPlaceholderColor;}';
		var styleBlock = '<style id="sg-placeholder-style">' + styleContent + '</style>';
		$jsg('head').append(styleBlock);
	});
}

SgSubscription.prototype.livePreview = function() {
	this.binding();
	this.changeLabels();
	this.changeButtonTitle();
	this.colorpickerChange();
	this.changeButtonTitle();
	this.changeBorderWidth();
}

SgSubscription.prototype.addInputWidth = function() {
	var inputsWidth = this.getTextInputWidth();
	$jsg(".js-subs-text-inputs").each(function() {
		$jsg(this).css({"width": inputsWidth,'maxWidth': '100%'});
	});
}

SgSubscription.prototype.setupBorderWidth = function(className, value) {
	var value = parseInt(value)+"px";
	$jsg("."+className).css({'border-width': value});
}

SgSubscription.prototype.addInputsHeight = function() {
	var height = this.getTextInputsHeight();
	$jsg(".js-subs-text-inputs").each(function() {
		$jsg(this).css({"height": height});
	});
}

SgSubscription.prototype.addBtnWidth = function() {
	var width = this.getBtnWidth();
	$jsg(".js-subs-submit-btn").css({"width": width,'maxWidth': '100%'});
}

SgSubscription.prototype.addBtnHeight = function() {
	var height = this.getBtnHeight();
	$jsg(".js-subs-submit-btn").css({'height': height});
}

SgSubscription.prototype.shake = function() {
	$jsg.fn.shake = function(interval,distance,times) {
		interval = typeof interval == "undefined" ? 100 : interval;
		distance = typeof distance == "undefined" ? 10 : distance;
		times = typeof times == "undefined" ? 3 : times;
		var jTarget = $jsg(this);
		jTarget.css('position','relative');
		for(var iter=0;iter<(times+1);iter++){
			jTarget.animate({ left: ((iter%2==0 ? distance : distance*-1))}, interval);
		}
		return jTarget.animate({ left: 0},interval);
	}
}

SgSubscription.prototype.sgWpAjax = function() {
	var that = this;
	var textWidth = this.getTextInputWidth();
	$jsg('#sg-subscribers-data').on('submit', function(event) {
		event.preventDefault();
		
		var form = new FormData();

		var uploadUrl = $jsg(".form-key").attr("data-upload-url");
		var key = $jsg(".form-key").val();

		var serilizeData = $jsg(this).serialize();
		var email = $jsg(".js-subs-email-name").val();
		var validationMessage = sgSupcriptionOptions.requieredField;
		var emailValidate = sgSupcriptionOptions.emailValidate;

		var requeiredFields = [];
		var validate = email.match(/(\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6})/);

		$jsg('.js-requierd-style').remove();
		$jsg('.js-subs-text-inputs').each(function() {
			if($jsg(this).val() == '') {
				requeiredFields.push($jsg(this));
				$jsg(this).after("<soan class='js-requierd-style'>"+validationMessage+"</span>");
			}
		});
		if(requeiredFields.length != 0) {
			return;
		}
		if(!validate) {
			$jsg('.js-subs-email-name').shake();
			console.log($jsg('.js-validate-email'))
			$jsg('.js-validate-email').removeClass('sg-js-hide');
		
			return;
		}

		form.append('form_key', key);
		form.append('serialize', serilizeData);
		$jsg.ajax({
			type:'POST',
			url: uploadUrl,
			data: form,
			cache:false,
			processData: false,
			contentType: false,
			success:function(data, d){
				/*console.log(data);*/
				$jsg('#sg-subscribers-data').css({'display': 'none'});
				$jsg('.sg-subs-success').removeClass('sg-js-hide');
			},
			error: function(data){
				/*console.log("error");
				console.log(data);*/
			}
		});
	});
}

SgSubscription.prototype.buildStyle = function() {
	this.shake();
	this.addInputWidth();
	this.addBtnWidth();
	this.addBtnHeight();
	this.addInputsHeight();
	this.sgWpAjax();
}
