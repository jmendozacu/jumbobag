function SGPopup() {
	this.positionLeft = '';
	this.positionTop = '';
	this.positionBottom = '';
	this.positionRight = '';
	this.initialPositionTop = '';
	this.initialPositionLeft = '';
	this.isOnLoad = '';
	this.openOnce = '';
	this.popupData = new Array();
	this.popupEscKey = true;
	this.popupOverlayClose = true;
	this.popupContentClick = false;
	this.popupCloseButton = true;
	this.sgTrapFocus = true;
}

SGPopup.prototype.init = function() {
	var that = this;

	this.onCompleate();
	$jsg(".sg-show-popup").bind('click',function() {
		var sgPopupID = $jsg(this).attr("data-sgpopupid");
		that.showPopup(sgPopupID,false);
	});
	$jsg("[class*='sg-popup-id-']").each(function() {
		$jsg(this).bind("click", function() {
			var className = $jsg(this).attr("class");
			var sgPopupID =  className.split("sg-popup-id-")['1'];
			that.showPopup(sgPopupID,false);
		})
	});
};

SGPopup.prototype.popupOpenById = function (popupId) {
	sgOnScrolling = (SG_POPUP_DATA [popupId]['onScrolling']) ? SG_POPUP_DATA [popupId]['onScrolling'] : '';
	sgInActivity = (SG_POPUP_DATA [popupId]['inActivityStatus']) ? SG_POPUP_DATA [popupId]['inActivityStatus'] : '';
	beforeScrolingPrsent = (SG_POPUP_DATA [popupId]['onScrolling']) ? SG_POPUP_DATA [popupId]['beforeScrolingPrsent'] : '';
	autoClosePopup = (SG_POPUP_DATA [popupId]['autoClosePopup']) ? SG_POPUP_DATA [popupId]['autoClosePopup'] : '';
	popupClosingTimer = (SG_POPUP_DATA [popupId]['popupClosingTimer']) ? SG_POPUP_DATA [popupId]['popupClosingTimer'] : '';
	sgPoupFrontendObj = new SGPopup();

	if (sgOnScrolling) {
		sgPoupFrontendObj.onScrolling(popupId);
	}
	else if (sgInActivity) {
		sgPoupFrontendObj.showPopupAfterInactivity(popupId);
	}
	else {
		sgPoupFrontendObj.showPopup(popupId, true);
	}
};

SGPopup.prototype.onCompleate = function() {

	$jsg("#sgcolorbox").bind("sgColorboxOnCompleate", function() {
		
		/* Scroll only inside popup */
		$jsg('#sgcboxLoadedContent').isolatedScroll();
	});
	this.isolatedScroll();
};

SGPopup.prototype.isolatedScroll = function() {

	$jsg.fn.isolatedScroll = function() {
		this.bind('mousewheel DOMMouseScroll', function (e) {
			var delta = e.wheelDelta || (e.originalEvent && e.originalEvent.wheelDelta) || -e.detail,
				bottomOverflow = this.scrollTop + $jsg(this).outerHeight() - this.scrollHeight >= 0,
				topOverflow = this.scrollTop <= 0;

			if ((delta < 0 && bottomOverflow) || (delta > 0 && topOverflow)) {
				e.preventDefault();
			}
		});
		return this;
	};
};

SGPopup.prototype.varToBool = function(optionName) {
	returnValue = (optionName) ? true : false;
	return returnValue;
};

SGPopup.prototype.canOpenPopup = function(id, openOnce, isOnLoad) {
	if (!isOnLoad) {
		return true;
	}
	if(openOnce) {
		return this.canOpenOnce(id);
	}

	return true;
};

SGPopup.prototype.setFixedPosition = function(sgPositionLeft, sgPositionTop, sgPositionBottom, sgPositionRight, sgFixedPositionTop, sgFixedPositionLeft) {
	this.positionLeft = sgPositionLeft;
	this.positionTop = sgPositionTop;
	this.positionBottom = sgPositionBottom;
	this.positionRight = sgPositionRight;
	this.initialPositionTop = sgFixedPositionTop;
	this.initialPositionLeft = sgFixedPositionLeft;
};

SGPopup.prototype.percentToPx = function(percentDimention, screenDimension) {
	var dimension = parseInt(percentDimention)*screenDimension/100;
	return dimension;
};

SGPopup.prototype.getPositionPercent = function(needPercent, screenDimension, dimension) {
	var sgPosition = (((this.percentToPx(needPercent,screenDimension)-dimension/2)/screenDimension)*100)+"%";
	return sgPosition;
};

SGPopup.prototype.showPopup = function(id, isOnLoad) {
	var that = this;
	/*if($jsg("body").hasClass("sg-popup-opened")) return;
	$jsg('body').addClass("sg-popup-opened");*/

	this.popupData = SG_POPUP_DATA[id];
	this.isOnLoad = isOnLoad;
	this.openOnce = this.varToBool(this.popupData['repeatPopup']);

	if(typeof that.removeCookie !== 'undefined') {
		that.removeCookie(this.openOnce);
	}

	if (!this.canOpenPopup(this.popupData['id'], this.openOnce, isOnLoad)) {
		return;
	}

	popupColorboxUrl = SG_APP_POPUP_URL+'/sgcolorbox/'+this.popupData['theme'];
	$jsg('[id=sg_colorbox_theme-css]').remove();
	head = document.getElementsByTagName('head')[0];
	link = document.createElement('link')
	link.type = "text/css";
	link.id = "sg_colorbox_theme-css";
	link.rel = "stylesheet"
	link.href = popupColorboxUrl;
	document.getElementsByTagName('head')[0].appendChild(link);
	var img = document.createElement('img');
	sgAddEvent(img, "error", function() {
		that.sgShowColorboxWithOptions();
	});
	setTimeout(function(){img.src = popupColorboxUrl;},0);
};

SGPopup.prototype.sgShowColorboxWithOptions = function() {
	var that = this;
	setTimeout(function() {

		sgPopupFixed = that.varToBool(that.popupData['popupFixed']);
		that.popupOverlayClose = that.varToBool(that.popupData['overlayClose']);
		that.popupContentClick = that.varToBool(that.popupData['contentClick']);
		var popupReposition = that.varToBool(that.popupData['reposition']);
		var popupScrolling = that.varToBool(that.popupData['scrolling']);
		that.popupEscKey = that.varToBool(that.popupData['escKey']);
		that.popupCloseButton = that.varToBool(that.popupData['closeButton']);
		var popupForMobile = that.varToBool(that.popupData['forMobile']);
		var onlyMobile = that.varToBool(that.popupData['openMobile']);
		var popupCantClose = that.varToBool(that.popupData['disablePopup']);
		var disablePopupOverlay = that.varToBool(that.popupData['disablePopupOverlay']);
		var popupAutoClosePopup = that.varToBool(that.popupData['autoClosePopup']);
		popupClosingTimer = that.popupData['popupClosingTimer'];

		if (popupCantClose) {
			that.cantPopupClose();
		}
		var popupPosition = that.popupData['fixedPostion'];
		var popupHtml = (that.popupData['html'] == '') ? '&nbsp;' : that.popupData['html'];
		var popupImage = that.popupData['image'];
		var popupIframeUrl = that.popupData['iframe'];
		var popupShortCode = that.popupData['shortcode'];
		var popupVideo = that.popupData['video'];
		var popupOverlayColor = that.popupData['sgOverlayColor'];
		var contentBackgroundColor = that.popupData['sgBackgroundColor'];
		var popupWidth = that.popupData['width'];
		var popupHeight = that.popupData['height'];
		var popupOpacity = that.popupData['opacity'];
		var popupMaxWidth = that.popupData['maxWidth'];
		var popupMaxHeight = that.popupData['maxHeight'];
		var popupInitialWidth = that.popupData['initialWidth'];
		var popupInitialHeight = that.popupData['initialHeight'];
		var popupEffectDuration = that.popupData['duration'];
		var popupEffect = that.popupData['effect'];
		var pushToBottom = that.popupData['pushToBottom'];
		var onceExpiresTime = parseInt(that.popupData['onceExpiresTime']);
		var sgType = that.popupData['type'];
		var overlayCustomClass = that.popupData['sgOverlayCustomClasss'];
		var contentCustomClass = that.popupData['sgContentCustomClasss'];
		var popupTheme = that.popupData['theme'];
		var themeStringLength = popupTheme.length;
		var customClassName = popupTheme.substring(0, themeStringLength-4);
		var closeButtonText = that.popupData['theme-close-text'];

		popupHtml = (popupHtml) ? popupHtml : false;
		var popupIframe = (popupIframeUrl) ? true: false;
		popupVideo = (popupVideo) ? popupVideo : false;
		popupImage = (popupImage) ? SG_APP_POPUP_IMAGE_URL+"img/"+popupImage : false;
		var popupPhoto = (popupImage) ? true : false;
		popupShortCode = (popupShortCode) ? popupShortCode : false;
		if (popupShortCode && popupHtml == false) {
			popupHtml = popupShortCode;
		}
		
		if(popupHtml && popupWidth == '' &&  popupHeight == '' && popupMaxWidth =='' && popupMaxHeight == '') {
			$jsg(popupHtml).find('img:first').attr('onload', '$jsg.sgcolorbox.resize();');
		}
		if (popupIframeUrl) {
			popupImage = popupIframeUrl;
		}
		if (popupVideo) {
			if (popupWidth == '') {
				popupWidth = '50%';
			}
			if (popupHeight == '') {
				popupHeight = '50%';
			}
			popupIframe = true;
			popupImage = popupVideo;
		}
		var sgScreenWidth = $jsg(window).width();
		var sgScreenHeight = $jsg(window).height();

		var sgIsWidthInPercent = popupWidth.indexOf("%");
		var sgIsHeightInPercent = popupHeight.indexOf("%");
		var sgPopupHeightPx = popupHeight;
		var sgPopupWidthPx = popupWidth;
		if (sgIsWidthInPercent != -1) {
			sgPopupWidthPx = that.percentToPx(popupWidth, sgScreenWidth);
		}
		if (sgIsHeightInPercent != -1) {
			sgPopupHeightPx = that.percentToPx(popupHeight, sgScreenHeight);
		}
		popupPositionTop = that.getPositionPercent("50%", sgScreenHeight, sgPopupHeightPx);
		popupPositionLeft = that.getPositionPercent("50%", sgScreenWidth, sgPopupWidthPx);

		if(popupPosition == 1) { // Left Top
			that.setFixedPosition('0%','3%', false, false, 0, 0);
		}
		else if(popupPosition == 2) { // Left Top
			that.setFixedPosition(popupPositionLeft,'3%', false, false, 0, 50);
		}
		else if(popupPosition == 3) { //Right Top
			that.setFixedPosition(false,'3%', false, '0%', 0, 90);
		}
		else if(popupPosition == 4) { // Left Center
			that.setFixedPosition('0%', popupPositionTop, false, false, popupPositionTop, 0);
		}
		else if(popupPosition == 5) { // center Center
			sgPopupFixed = true;
			that.setFixedPosition(false, false, false, false, 50, 50);
		}
		else if(popupPosition == 6) { // Right Center
			that.setFixedPosition('0%', popupPositionTop, false,'0%', 50, 90);
		}
		else if(popupPosition == 7) { // Left Bottom
			that.setFixedPosition('0%', false, '0%', false, 90, 0);
		}
		else if(popupPosition == 8) { // Center Bottom
			that.setFixedPosition(popupPositionLeft, false, '0%', false, 90, 50);
		}
		else if(popupPosition == 9) { // Right Bottom
			that.setFixedPosition(false, false, '0%', '0%', 90, 90);
		}
		if(!sgPopupFixed) {
			that.setFixedPosition(false, false, false, false, 50, 50);
		}

		var userDevice = false;
		if (popupForMobile) {
			userDevice = that.forMobile();
		}

		if (popupAutoClosePopup) {
			setTimeout(that.autoClosePopup, popupClosingTimer*1000);
		}

		if(disablePopupOverlay) {
			that.sgTrapFocus = false;
			that.disablePopupOverlay();
		}

		if(onlyMobile) {
			openOnlyMobile = false;
			openOnlyMobile = that.forMobile();
			if(openOnlyMobile == false) {
				return;
			}
		}

		if (userDevice) {
			return;
		}
		
		SG_POPUP_SETTINGS = {
			width: popupWidth,
			height: popupHeight,
			className: customClassName,
			close: closeButtonText,
			overlayCutsomClassName: overlayCustomClass,
			contentCustomClassName: contentCustomClass,
			onOpen:function() {
				$jsg('#sgcolorbox').removeAttr('style');
				$jsg('#sgcolorbox').removeAttr('left');
				$jsg('#sgcolorbox').css('top',''+that.initialPositionTop+'%');
				$jsg('#sgcolorbox').css('left',''+that.initialPositionLeft+'%');
				$jsg('#sgcolorbox').css('animation-duration', popupEffectDuration+"s");
				$jsg('#sgcolorbox').css('-webkit-animation-duration', popupEffectDuration+"s");
				$jsg("#sgcolorbox").addClass('sg-animated '+popupEffect+'');
				$jsg("#sgcboxOverlay").addClass("sgcboxOverlayBg");
				$jsg("#sgcboxOverlay").removeAttr('style');

				if (popupOverlayColor) {
					$jsg("#sgcboxOverlay").css({'background' : 'none', 'background-color' : popupOverlayColor});
				}

				$jsg('#sgcolorbox').trigger("sgColorboxOnOpen", []);
			},
			onLoad: function(){
			},
			onComplete: function(){
				if (contentBackgroundColor) {
					jQuery("#sgcboxLoadedContent").css({'background-color': contentBackgroundColor})
				}
				$jsg('#sgcolorbox').trigger("sgColorboxOnCompleate", [pushToBottom]);
				if(popupWidth == '' && popupHeight == '') {
					$jsg.sgcolorbox.resize();
				}
				$jsg('.sg-popup-close').bind('click', function() {
					$jsg.sgcolorbox.close();
				});

				//Facebook reInit
				if($jsg('#sg-facebook-like').length && typeof FB !== 'undefined') {
					FB.XFBML.parse();
				}
				
			},
			onClosed: function() {
				$jsg('#sgcolorbox').trigger("sgPopupClose", []);
			},
			trapFocus: that.sgTrapFocus,
			html: popupHtml,
			photo: popupPhoto,
			iframe: popupIframe,
			href: popupImage,
			opacity: popupOpacity,
			escKey: that.popupEscKey,
			closeButton: that.popupCloseButton,
			fixed: sgPopupFixed,
			top: that.positionTop,
			bottom: that.positionBottom,
			left: that.positionLeft,
			right: that.positionRight,
			scrolling: popupScrolling,
			reposition: popupReposition,
			overlayClose: that.popupOverlayClose,
			maxWidth: popupMaxWidth,
			maxHeight: popupMaxHeight,
			initialWidth: popupInitialWidth,
			initialHeight: popupInitialHeight
		};

		$jsg.sgcolorbox(SG_POPUP_SETTINGS);
		if (that.popupData['id'] && that.isOnLoad==true && that.openOnce != '') {
			$jsg.cookie.defaults = {path:'/'};
			$jsg.cookie("sgPopupNumbers",that.popupData['id'], { expires: onceExpiresTime});
		}

		if (that.popupContentClick) {
			$jsg('#sgcolorbox').bind('click',function() {
				$jsg.sgcolorbox.close();
			});
		}

		$jsg('#sgcolorbox').bind('sgPopupClose', function(e) {
			$jsg('#sgcolorbox').removeClass(customClassName); /* Remove custom class for another popup */
			$jsg('#sgcboxOverlay').removeClass(customClassName);
			$jsg('#sgcolorbox').removeClass(popupEffect); /* Remove animated effect for another popup */
		});

	},this.popupData['delay']*1000);
};

$jsg(document).ready(function($) {
	var popupObj = new SGPopup();
	popupObj.init();

	$jsg('#sgcolorbox').on('sgColorboxOnCompleate',function(e) {
		if(typeof sgInitPopupVariables != 'undefined') {
			sgInitPopupVariables();
		}
	});
});
