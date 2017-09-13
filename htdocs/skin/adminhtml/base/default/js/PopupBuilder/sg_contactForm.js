function SgContactForm() {

	var textInputWidth = contactFrontend.inputsWidth;
	var btnWidth = contactFrontend.buttnsWidth;
	var tetxInputsHeight = contactFrontend.inputsHeight;
	var btnHeight = contactFrontend.buttonHeight;
	var inProgresTitle = contactFrontend.procesingTitle;
}

SgContactForm.prototype.changeDimensionMode = function(dimension) {
	var size;
	size =  parseInt(dimension)+"px";
	if(dimension.indexOf("%") != -1 || dimension.indexOf("px") != -1) {
		size = dimension;
	}
	return size;
}

/*
 	Seters and getters
*/

SgContactForm.prototype.changeBorderWidth = function() {
	var that = this;
	$jsg('[name="contact-inputs-border-width"]').change(function() {
		var value = $jsg(this).val();
		var className = $jsg(this).attr('data-contact-rel');
		that.setupBorderWidth(className, value);
	});
}

SgContactForm.prototype.changeInputsWIdth = function() {
	var that = this;
	$jsg('[name="contact-inputs-width"]').change(function() {
		var value = $jsg(this).val();
		var classname = $jsg(this).attr('data-contact-rel');
		that.addInputWidth(classname, value);
	});
}

SgContactForm.prototype.changeInputsHight = function() {
	var that = this;
	$jsg('[name="contact-inputs-height"]').change(function() {
		var value = $jsg(this).val();
		var className = $jsg(this).attr('data-contact-rel');
		that.addInputsHeight(className, value);
	})
}

SgContactForm.prototype.changeBtnWidth = function() {
	var that = this;
	$jsg('[name="contact-btn-width"]').change(function() {
		var value = $jsg(this).val();
		var className = $jsg(this).attr('data-contact-rel');
		that.addInputWidth(className, value);
	});
}

SgContactForm.prototype.changeBtnHeight = function() {
	var that = this;
	$jsg('[name="contact-btn-height"]').change(function() {
		var value = $jsg(this).val();
		var className = $jsg(this).attr('data-contact-rel');
		that.addInputsHeight(className, value);
	});
}

SgContactForm.prototype.changeAreaWidth = function() {
	var that = this;
	$jsg('[name="contact-area-width"]').change(function() {
		var value = $jsg(this).val();
		var className = $jsg(this).attr('data-contact-rel');
		that.addInputWidth(className, value);
	});
}

SgContactForm.prototype.changeAreaHeight = function() {
	var that = this;
	$jsg('[name="contact-area-height"]').change(function() {
		var value = $jsg(this).val();
		var className = $jsg(this).attr('data-contact-rel');
		that.addInputsHeight(className, value);
	});
}

SgContactForm.prototype.changeTextAreaWidth = function() {
	var that = this;
	$jsg('contact-area-width').change(function() {
		var value = $jsg(this).val();
		var className = $jsg(this).attr('data-contact-rel');
		that.addInputWidth(className, value);
	})
}

SgContactForm.prototype.changeTextAreaHeight = function() {
	var that = this;
	$jsg('contact-area-height').change(function() {
		var value = $jsg(this).val();
		var className = $jsg(this).attr('data-contact-rel');
		that.addInputsHeight(className, value);
	})
}

SgContactForm.prototype.textAreaResizeState = function() {
	var that = this;
	$jsg('[name="sg-contact-resize"]').change(function() {
		var value = $jsg(this).val();
		that.addTextAreastatState(value);
	});
}

SgContactForm.prototype.changeLabels = function() {
	$jsg(".sg-contact-fileds[data-contact-rel]").each(function() {
		$jsg(this).bind("input", function() {
			var className = $jsg(this).attr("data-contact-rel");
			var placeholderText = $jsg(this).val();
			$jsg("."+className).attr("placeholder", placeholderText);
		});
	});
}

SgContactForm.prototype.changeButtonTitle = function() {
	var that = this;

	$jsg("[name='contact-btn-title']").bind('input', function() {
		var className = $jsg(this).attr("data-contact-rel");
		var val = $jsg(this).val();
		that.setupButtonText("."+className,val);
	});
	$jsg("[name='contact-btn-title']").trigger('input');
}

SgContactForm.prototype.fieldsColor = function(element) {
	var color = element.val();
	var classname = element.attr('data-contact-rel');
	this.setupBackgroundColor("."+classname, color);
	var textAreaClass = element.attr('data-contact-area-rel');
	this.setupBackgroundColor("."+textAreaClass, color);
}

SgContactForm.prototype.fieldsBorderColor = function(element) {
	var color = element.val();
	var classname = element.attr('data-contact-rel');
	this.setupBorderColor('.'+classname, color);
	var textAreaClass = element.attr('data-contact-area-rel');
	this.setupBorderColor('.'+textAreaClass, color);
}

SgContactForm.prototype.buttonTextColor = function(element) {
	var color = element.val();
	var classname = element.attr('data-contact-rel');
	this.setupButtonColor("."+classname, color);
	var textAreaClass = element.attr('data-contact-area-rel');
	this.setupButtonColor("."+textAreaClass, color);
}

SgContactForm.prototype.placeholderColor = function(element) {
	var color = element.val();
	var classname = element.attr('data-contact-rel');
	var textAreaClass = element.attr('data-contact-area-rel');
	classes = [classname,textAreaClass];

	this.setupPlaceholderColor(classes, color);
}

SgContactForm.prototype.colorpickerChange = function() {
	var that = this;
	$jsg('.sg-contact-btn-color').minicolors({ /*Inputs  and text area color */
		change: function() {
			var sgColorpicker = $jsg(this);
			that.fieldsColor(sgColorpicker);
		}
	});

	$jsg('.sg-contact-btn-border-color').minicolors({ /*Inputs and text area border color */
		change: function() {
			var sgColorpicker = $jsg(this);
			that.fieldsBorderColor(sgColorpicker);
		}
	});

	$jsg('.sg-contact-btn-text-color').minicolors({
		change: function() {
			var sgColorpicker = $jsg(this);
			that.buttonTextColor(sgColorpicker);
		}
	});

	$jsg('.sg-contact-placeholder-color').minicolors({
		change: function() {
			var sgColorpicker = $jsg(this);
			that.placeholderColor(sgColorpicker);
		}
	});
	
}

SgContactForm.prototype.setupBackgroundColor = function(element, color) {
	$jsg(element).each(function() {
		$jsg(this).css({'background': color});
	});
}

SgContactForm.prototype.setupBorderColor = function(element, color) {
	$jsg(element).each(function() {
		$jsg(this).css({'border-color': color});
	});
}

SgContactForm.prototype.setupButtonColor = function(element, color) {
	$jsg(element).css({'color': color});
}

SgContactForm.prototype.setupButtonText = function(element, value) {
	$jsg(element).val(value);
}

SgContactForm.prototype.setupPlaceholderColor = function(elements, color) {
	var styleContent = '';
	for(element in elements) {
		//element = elements[element];

		$jsg("."+element).each(function() {
			$jsg("#sg-placeholder-style").remove()
			styleContent += '.'+element+'::-webkit-input-placeholder {color: ' + color + ';} .'+element+'::-moz-placeholder {color: ' + color + ';} .'+element+':-ms-input-placeholder {color:$sgSubsPlaceholderColor;}';

		});
		var styleBlock = '<style id="sg-placeholder-style">' + styleContent + '</style>';
		$jsg('head').append(styleBlock);
	}
}

SgContactForm.prototype.addInputWidth = function(classname, width) {
	var width = this.changeDimensionMode(width);
	$jsg("."+classname).each(function() {
		$jsg(this).css({"width": width,'maxWidth': '100%'});
	});
}

SgContactForm.prototype.setupBorderWidth = function(className, value) {
	var value = parseInt(value)+"px";
	$jsg("."+className).css({'border-width': value});
}

SgContactForm.prototype.addInputsHeight = function(className, value) {
	var height = this.changeDimensionMode(value);
	$jsg("."+className).each(function() {
		$jsg(this).css({"height": height});
	});
}

SgContactForm.prototype.addTextAreastatState = function(value) {
	$jsg('.js-contact-text-area').css({'resize': value});
}

SgContactForm.prototype.shake = function() {
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

SgContactForm.prototype.addFieldsColor = function() {
	placeholderClasses = ['js-contact-text-area', 'js-contact-text-inputs'];
	this.setupPlaceholderColor(placeholderClasses, contactFrontend.placeholderColor);
	this.setupButtonColor('.js-contact-submit-btn', contactFrontend.btnTextColor);
	this.setupButtonColor('.js-contact-text-inputs', contactFrontend.inputsColor);
	this.setupButtonColor('.js-contact-text-area', contactFrontend.inputsColor);
	this.setupBackgroundColor('.js-contact-submit-btn', contactFrontend.btnBackgroundColor);
	this.setupBackgroundColor('.js-contact-text-inputs', contactFrontend.inputsBackgroundColor);
	this.setupBackgroundColor('.js-contact-text-area', contactFrontend.inputsBackgroundColor);
	this.setupBorderColor('.js-contact-text-inputs', contactFrontend.inputsBorderColor);

}

SgContactForm.prototype.addFieldsDiemntions = function() {
	this.addInputWidth('js-contact-text-inputs', contactFrontend.inputsWidth);
	this.setupBorderWidth('js-contact-text-inputs', contactFrontend.contactInputsBorderWidth);
	this.addInputsHeight('js-contact-text-inputs', contactFrontend.inputsHeight);
	this.addInputWidth('js-contact-submit-btn', contactFrontend.buttnsWidth);
	this.addInputsHeight('js-contact-submit-btn', contactFrontend.buttonHeight);
	this.addInputWidth('js-contact-text-area', contactFrontend.contactAreaWidth);
	this.addInputsHeight('js-contact-text-area', contactFrontend.contactAreaHeight);
	this.addTextAreastatState(contactFrontend.contactResize)
}

SgContactForm.prototype.sgWpAjax = function() {
	var that = this;
	var textWidth = contactFrontend.inputsWidth;

	var validationMessage = contactFrontend.validateMessage;

	$jsg('#sg-contact-data').on('submit', function(event) {
		event.preventDefault();

		var form = new FormData();

		var uploadUrl = $jsg(".form-key").attr("data-upload-url");
		var key = $jsg(".form-key").val();

		var serilizeData = $jsg(this).serialize();
		var requeiredFields = [];
		var sgContactData = $jsg(this).serialize();
		var email = $jsg(".js-contact-email").val();
		var validate = email.match(/(\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,6})/);

		$jsg('.js-requierd-style').remove();
		$jsg('.sg-contact-required').each(function() {
			if($jsg(this).val() == '') {
				requeiredFields.push($jsg(this));
				$jsg(this).after("<span class='js-requierd-style'>"+validationMessage+"</span>");
			}
		});
		if(requeiredFields.length != 0) {
			return;
		}
		if(!validate) {
			$jsg('.js-contact-email').shake();
			$jsg('.js-validate-email').removeClass('sg-js-hide');
			$jsg('.js-validate-email').css({
				'width': textWidth,
				'margin': '0px auto 5px auto',
				'font-size': '12px',
				'color': 'red',
				'display': 'block'
			});
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

				if(data == true) {
					$jsg('#sg-contact-data').css({'display': 'none'});
					$jsg('#sg-contact-success').removeClass('sg-js-hide');
					$jsg("#sg-contact-faild").css({'display': 'none'});
				}
				else {
					$jsg("#sg-contact-faild").removeClass('sg-js-hide');
				}

			},
			error: function(data){
				/*console.log("error");
				console.log(data);*/
			}
		});
	});
}

SgContactForm.prototype.buildStyle = function() {
	this.shake();
	this.sgWpAjax();
	this.addFieldsColor();
	this.addFieldsDiemntions();
}

SgContactForm.prototype.livePreview = function() {
	this.changeLabels();
	this.changeButtonTitle();
	this.colorpickerChange();
	this.changeBorderWidth();
	this.changeInputsWIdth();
	this.changeInputsHight();
	this.changeBtnWidth();
	this.changeBtnHeight();
	this.changeAreaWidth();
	this.changeAreaHeight();
	this.changeTextAreaWidth();
	this.changeTextAreaHeight();
	this.textAreaResizeState();
}
