function setAjaxData(a,b){"ERROR"!=a.status&&jQuery(".cart-top-container").length&&jQuery(".cart-top-container").replaceWith(a.cart_top)}function showMessage(a){jQuery("body").append('<div class="alert"></div>');var b=jQuery(".alert");b.slideDown(400),b.html(a).append("<button></button>"),jQuery("button").click(function(){b.slideUp(400)}),b.slideDown("400",function(){setTimeout(function(){b.slideUp("400",function(){jQuery(this).slideUp(400,function(){jQuery(this).detach()})})},7e3)})}jQuery(function(a){function b(b,c){b=b.replace("checkout/cart","ajax/index"),b+="isAjax/1/","https:"==document.location.protocol&&(b=b.replace("http:","https:")),a("#ajax_loading"+c).css("display","block");try{a.ajax({url:b,dataType:"jsonp",success:function(b){a("#ajax_loading"+c).css("display","none"),showMessage(b.message),"ERROR"!=b.status&&a(".cart-top-container").length&&a(".cart-top-container").replaceWith(b.cart_top)}})}catch(a){}}a(".btn-cart").live("click",function(){if(a(this).hasClass("show-options"))return!1;if(a(window).width()<769)return!1;var b=a(".cart-top"),c=a(this).parents("li.item").find("a.product-image img:not(.back_img)").eq(0);if(c){var d=c.clone().offset({top:c.offset().top,left:c.offset().left}).css({opacity:"0.7",position:"absolute",height:"150px",width:"150px","z-index":"1000"}).appendTo(a("body")).animate({top:b.offset().top+10,left:b.offset().left+30,width:55,height:55},1e3,"easeInOutExpo");d.animate({width:0,height:0},function(){a(this).detach()})}return!1}),Shopper.quick_view&&a("li.item").live({mouseenter:function(){a(this).find(".quick-view").css("display","block")},mouseleave:function(){a(this).find(".quick-view").hide()}}),a(".fancybox").live("click",function(){if(a(window).width()<769)return window.location=a(this).next().attr("href"),!1;var b=a(this),c=b.attr("href");return""!=Shopper.category&&(c+="qvc/"+Shopper.category+"/"),"https:"==document.location.protocol&&(c=c.replace("http:","https:")),a.fancybox({hideOnContentClick:!0,width:800,autoDimensions:!0,type:"iframe",href:c,showTitle:!0,scrolling:"no",onComplete:function(){a("#fancybox-frame").load(function(){a("#fancybox-content").height(a(this).contents().find("body").height()+30),a.fancybox.resize()})}}),!1}),a(".show-options").live("click",function(){return a("#fancybox"+a(this).attr("data-id")).trigger("click"),!1}),a(".ajax-cart").live("click",function(){return b(a(this).attr("data-url"),a(this).attr("data-id")),!1})});