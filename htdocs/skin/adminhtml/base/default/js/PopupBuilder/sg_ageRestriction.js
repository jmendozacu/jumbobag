function SGAgeRestriction() {

}

SGAgeRestriction.prototype.init = function(id) {
	restrictionData = SG_POPUP_DATA[id];
	yesButtonBackgroundColor = this.sgSafeStr(restrictionData['yesButtonBackgroundColor']);
	noButtonBackgroundColor = this.sgSafeStr(restrictionData['noButtonBackgroundColor']);
	yesButtonTextColor = this.sgSafeStr(restrictionData['yesButtonTextColor']);
	noButtonTextColor = this.sgSafeStr(restrictionData['noButtonTextColor']);
	yesButtonRadius = this.sgSafeStr(restrictionData['yesButtonRadius']);
	noButtonRadius = this.sgSafeStr(restrictionData['noButtonRadius']);

	$jsg('#sgYesButton').css({
		'background-color' : yesButtonBackgroundColor,
		'color' : yesButtonTextColor,
		'border-radius': yesButtonRadius+"%",
		'height' : '20px !important',
		'border-color' : yesButtonBackgroundColor,
		'padding': '12px',
		'border' : 'none',
		'font-weight' : 'bold',
		'font-size' : '15px'
	});

	$jsg('#sgNoButton').css({
		'background-color' : noButtonBackgroundColor,
		'color' : noButtonTextColor,
		'borderRadius' : noButtonRadius+"%",
		'height' : '20px !important',
		'border-color' : noButtonBackgroundColor,
		'padding': '12px',
		'border' : 'none',
		'font-weight' : 'bold',
		'font-size' : '15px'
	});

};

SGAgeRestriction.prototype.sgSafeStr = function(variable) {
	if(variable) {
		return variable;
	}
	else {
		return '';
	}
}
