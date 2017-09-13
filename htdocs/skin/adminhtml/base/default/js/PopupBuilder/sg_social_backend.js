function SGSocialPopup() {
	this.roundButton = '';
}

SGSocialPopup.prototype.init = function() {
	var that = this;
	this.roundButton = $jsg('[name = sgRoundButton]');

	this.jsSocial();

	$jsg('input[name = sgSocialLabel]').change(function() {
		var inputValue = $jsg(this).is(':checked');
		that.options.showLabel = inputValue;
		that.jsSocial();
		that.changeToRoundButtons(that.roundButton.is(':checked'));
	});

	$jsg('select[name = sgSocialShareCount]').on('change',function() {
		var countPosition = $jsg(this).val();
		that.checkCountPosition(countPosition);
		that.changeToRoundButtons(that.roundButton.is(':checked'));
	});

	$jsg('[name = sgRoundButton]').bind('change',function() {
		var inputValue = $jsg(this).is(':checked');
 		that.changeToRoundButtons(inputValue);
	});

	$jsg("[name='sgSocialButtonsSize']").bind('change',function() {
		var fontSize = $jsg('[name="sgSocialButtonsSize"]').val();
		$jsg('#share-btns-container').css({'font-size' : fontSize+"px"});
	});

	$jsg("[name='sgSocialTheme']").bind('change',function() {
		var cosialThemeName = $jsg("[name='sgSocialTheme']").val();
		that.switchTheme();
	});

	$jsg(".js-social-btn-status").bind('change',function() {
		var socialButtonStatus = $jsg(this).is(':checked');
		var socialButtonName = $jsg(this).attr('data-social-button');
		
		$jsg(this).each(function() {
			
		});
		var conetnt = $jsg(this).attr("data-social-conetnt");

		that.scoialContent($jsg(this),conetnt);
		that.buttonShowStatus(socialButtonStatus, socialButtonName);
		that.changeToRoundButtons(that.roundButton.is(':checked'));
	});

	$jsg(".js-social-btn-text").bind('input',function() {
		var btnText = $jsg(this).val();
		var btnType = $jsg(this).attr('data-social-button');

		that.changeButtonText(btnText, btnType);
		that.changeToRoundButtons(that.roundButton.is(':checked'));
	});

	$jsg('.sg-active-url').bind('change', function() {
		var shareUrl = $jsg(this).val();
		that.shareForAdminUrl(shareUrl);
	});

	$jsg('.socialTriger').trigger('change');
	$jsg('select[name = sgSocialShareCount]').trigger('change');
	$jsg("[name='sgSocialButtonsSize']").trigger('change');
	$jsg("[name='sgSocialTheme']").trigger('change');
	$jsg('[name = sgRoundButton]').trigger('change');
	$jsg('.sg-active-url').trigger('change');
}

SGSocialPopup.prototype.scoialContent = function(element, content) {

	if (!element.prop("checked")) {
		$jsg("."+content+"").css({'display':'none'});
	}
	else {
		$jsg("."+content+"").css({"display": "block"});
	}
}

SGSocialPopup.prototype.checkCountPosition = function(position) {
	if (position == 'false') {
		position = false;
	}
	else if(position == 'true')  {
		position = true;
	}
	else {
		position = position;
	}
	this.options.showCount = position;
	this.jsSocial();
}

SGSocialPopup.prototype.shareForAdminUrl = function(shareUrl) { /* Add url to admin socails */
	this.options.url = shareUrl;
	this.jsSocial();
}

SGSocialPopup.prototype.changeToRoundButtons = function(inputValue) {
	if(inputValue == true) {
		$jsg('.jssocials-share-link').addClass("js-social-round-btn");
	}
	else {
		$jsg('.jssocials-share-link').removeClass("js-social-round-btn");
	}
}

SGSocialPopup.prototype.switchTheme = function() {
	var newTheme = $jsg("[name=sgSocialTheme]").val();
	var $cssLink = $jsg('#jssocials_theme_tm-css');
	var cssPath = $cssLink.attr("href");
	$cssLink.attr("href", cssPath.replace(this.currentTheme, newTheme));
	this.currentTheme = newTheme;
}

SGSocialPopup.prototype.changeButtonText = function(buttonText, buttonName) { /* Change Social buttons Name for Admin view onchange event */
	var socialArray = this.options.shares;
	var nameIndex = '';
	for(index in socialArray) {
		if(socialArray[index] == buttonName && typeof(socialArray[index]) == 'string') {
			nameIndex = index;
		}
		else if(socialArray[index]['share'] == buttonName) {
			nameIndex = index;
		}
	}
	if($jsg("input[type='checkbox'][data-social-button="+buttonName+"]").is(":checked")) {
		this.options.shares[nameIndex] = {'share': buttonName,'label': buttonText};
		this.jsSocial();
	}

}

SGSocialPopup.prototype.buttonShowStatus = function(socialButtonStatus, socialButtonName) {
	var sharesLength = this.options.shares.length;
	var that = this;
	var buutonCustomName = $jsg("input[type='text'][data-social-button="+socialButtonName+"]").val();
	if(socialButtonStatus) {
		if(!buutonCustomName) {
			this.options.shares[sharesLength] = socialButtonName;
		}
		else {
			this.options.shares[sharesLength] = {'share': socialButtonName,'label': buutonCustomName};
		}
	}
	else {
		var elementIndex = this.options.shares.indexOf(socialButtonName);
		if(elementIndex == -1) {
			for(var i=0; i< sharesLength; i++) {
				if(typeof that.options.shares[i] !== 'string' && that.options.shares[i].share == socialButtonName) {
					elementIndex = i;
				}
			}
		}
		this.options.shares.splice(elementIndex,1);
	}
	this.jsSocial();
}


SGSocialPopup.prototype.buttonsChecked =  function() { /* For onload check hide or show */
	var elements = $jsg(".js-social-btn-status");
	this.options.showLabel = $jsg('input[name = sgSocialLabel]').is(':checked');
	var that = this;
	var sharesLength = this.options.shares.length;
	$jsg.each(elements, function() {
		var conetnt = $jsg(this).attr("data-social-conetnt");

		that.scoialContent($jsg(this),conetnt);
		var buutonCustomName = $jsg("input[type='text'][data-social-button="+$jsg(this).attr('data-social-button')+"]").val();
		if($jsg(this).is(":checked")) {
			if(!buutonCustomName) {
				that.options.shares[sharesLength] = $jsg(this).attr('data-social-button');
			}
			else {
				that.options.shares[sharesLength] = {'share': $jsg(this).attr('data-social-button'),'label': buutonCustomName}
			}

			sharesLength++;
		}
	});
	this.jsSocial();
	that.changeToRoundButtons(that.roundButton.is(':checked'));
}

SGSocialPopup.prototype.currentTheme = 'classic';

SGSocialPopup.prototype.options = {
	url: "http://sygnoos.com",
	text: "Google Search Page",
	showLabel: false,
	showCount: true,
	shares: []
};

SGSocialPopup.prototype.jsSocial = function() {
	return $jsg('#share-btns-container').jsSocials(this.options);
}

$jsg(document).ready(function($) {
	var objSocial = new SGSocialPopup();
	objSocial.init();
	objSocial.buttonsChecked();
});
