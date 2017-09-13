SGPopup.prototype.canOpenOnce = function(id) {
	if ($jsg.cookie('sgPopupNumbers') != 'undefined' && $jsg.cookie('sgPopupNumbers') == id) {
		return false;
	}
	else {
		return true
	}
}

SGPopup.prototype.cantPopupClose = function() {
	this.popupEscKey  = false;
	this.popupCloseButton = false;
	this.popupOverlayClose = false;
	this.popupContentClick = false;
}

SGPopup.prototype.forMobile = function() {
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 		return true;
	}
	return false;
}

SGPopup.prototype.onScrolling = function(popupId) {
	that = this;
	var scrollStatus = false;
	$jsg(window).on('scroll', function(){
		var scrollTop = $jsg(window).scrollTop();
		var docHeight = $jsg(document).height();
		var winHeight = $jsg(window).height();
		var scrollPercent = (scrollTop) / (docHeight - winHeight);
		var scrollPercentRounded = Math.round(scrollPercent*100);
		if(beforeScrolingPrsent < scrollPercentRounded) {
			if(scrollStatus == false) {
				that.showPopup(popupId,true);
				scrollStatus = true;
			}
		}
	});
}

SGPopup.prototype.removeCookie = function(openOnce) {
	if (openOnce === false) {
		$jsg.removeCookie("sgPopupNumbers");
	}
}

SGPopup.prototype.proInit = function() {
	$jsg('#sgcolorbox').on('sgColorboxOnCompleate',function(e) {
		if(arguments[1] == 'on') { /* push to bottom param */
			$jsg('#sgcboxLoadedContent').css({'position': 'relative'});
		}

	});
}

SGPopup.prototype.disablePopupOverlay = function() {
	$jsg('#sgcolorbox').on("sgColorboxOnOpen", function() {
		$jsg(".sgcboxOverlayBg").remove();
	});
}

SGPopup.prototype.sgPopupShouldOpen = function(popupId) {

	if(typeof SG_POPUP_DATA[popupId] == "undefined") {
		return false;
	}

	var openMobile = SG_POPUP_DATA[popupId]['openMobile']; /* on or '' */
	var hideForMobile = SG_POPUP_DATA[popupId]['forMobile']; /* on or '' */
	var popupThisOften = SG_POPUP_DATA[popupId]['repeatPopup'];
	var popupType = SG_POPUP_DATA[popupId]['type'];
	var popupNumberLimit = SG_POPUP_DATA[popupId]['popup-appear-number-limit']
	var hasPopupCustomEvent = SG_POPUP_DATA[popupId]['customEvent'];

	var isMobile = this.forMobile(); //if fasle it's mean not mobile

	/*if not show in desktop */
	if(isMobile == false && openMobile) {
		return false;
	}
	if(isMobile == true && hideForMobile) {
		return false;
	}
	if(popupType == 'exitintent') {
		return false;
	}
	if(popupThisOften) {
		this.numberLimit = popupNumberLimit;
		var canOpen = this.canOpenOnce(popupId);
		if(!canOpen) {
			return false;
		}
	}
	if(typeof hasPopupCustomEvent != 'undefined' && hasPopupCustomEvent != '0') {
		return false;
	}

	return true;
};

SGPopup.prototype.popupOpenOnWindowLoad = function(popupId) {
	sgAddEvent(window, 'load', this.popupOpenById(popupId));
};

SGPopup.prototype.autoClosePopup = function() {
	$jsg.sgcolorbox.close();
}

$jsg(document).ready(function($) {
	var popupObj = new SGPopup();
	popupObj.proInit();
});