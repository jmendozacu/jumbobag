(function($){
  jQuery(document).ready(function() {
    $(".fullscreen-before").css("background-image", 'url(' + $("#product-img").html() + ')');
    $("#product-name-and-price").detach().prependTo($(".height-centered"));

    $(".presentation-produit").css("background-image", 'url(' + $("#fond-pres-produit img").attr("src") + ')');
    $(".entretien").css("background-image", 'url(' + $("#fond-entretien img").attr("src") + ')');

    $(".fullscreen-before .en-savoir-plus").click(function() {
      $("html, body").animate({ scrollTop: $('#page_start').offset().top }, 500);
    });

    $(".bouton-commander button").click(function() { 
      $("html, body").animate({ scrollTop: $('#informations-produit').offset().top }, 500);
    });

    var movementStrength = 25;
    var height = movementStrength / $(window).height();
    var width = movementStrength / $(window).width();

    $(".fullscreen-before").mousemove(function(e){
      var pageX = e.pageX - ($(window).width() / 2);
      var pageY = e.pageY - ($(window).height() / 2);
      var newvalueX = width * pageX * -1 - 25;
      var newvalueY = height * pageY * -1 - 50;
      $('.fullscreen-before').css("background-position", newvalueX+"px     "+newvalueY+"px");
    });

    $("#product-options-wrapper label.required").html('Couleurs :');

    //Gestion du changement d'image
    $(".swatches-container .swatch-img").click(function() {
      var title = $(this).attr("title");

      $("#shopper_gallery_carousel li").each(function() {
        if($(this).attr("data-label") === title) {
          $(".product-images .product-image").html($(this).html());
        }
      });
    });










    $('.tissu p').click(function() {
      $('.content_tissu').slideToggle(1000); 
      $('.tissu p').toggleClass('close_icon',1000);       
      $('.tissu p .fa').toggleClass('fa-times-circle');
      $('.tissu p .fa').toggleClass('fa-plus-circle isOut'); 
      $(".tissu p .fa").fadeIn("slow");
      var isOut = $('.tissu p .fa').hasClass('isOut'); 
      $('.tissu p .fa').animate({marginTop: isOut ? '2%' : '0'}, 800);  
      
    });

    $('.remplissage p').click(function() {     
      $('.content_remplissage').slideToggle(1000); 
      $('.remplissage p').toggleClass('close_icon',1000);       
      $('.remplissage p .fa').toggleClass('fa-times-circle');
      $('.remplissage p .fa').toggleClass('fa-plus-circle isOutin'); 
      var isOutin = $('.remplissage p .fa').hasClass('isOutin') 
      $('.remplissage p .fa').animate({marginTop: isOutin ? '2%' : '0'}, 800);
    });

    $('.image-wrapper').hover(function() {
        $('.btn-wrapper').fadeIn('slow');
      }
    );

    $('.image-wrapper').hover(function() {
        $('.btn-wrapper').fadeIn('slow');
      }
    );

    var userFeed = new Instafeed({
        get: 'user',
        userId: '623597756',
        clientId: '02b47e1b98ce4f04adc271ffbd26611d',
        accessToken: '623597756.02b47e1.3dbf3cb6dc3f4dccbc5b1b5ae8c74a72',
        resolution: 'standard_resolution',
        template: '<div class="col-xs-3"><a href="{{link}}" target="_blank" id="{{id}}"><img src="{{image}}"/></a></div>',
        sortBy: 'most-recent',
        limit: 4,
        links: false
    });

    userFeed.run();

    if($(window).width() < 768) {
      $(".footer_to_hide .responsive-footer h4").click(function() {
        $(this).parent().find('.responsive-footer .to_hide_reaction').slideToggle();
      });
    }

    if($(window).width() < 768) {
      $(".image-technique-inner").insertBefore(".texte_gauche");
    }

  });

  jQuery(window).load(function() {
    $(".fullscreen-before").height($(window).height()); 
    $(".height-centered .bouton-commander button").text('Commander maintenant');
    var dh = $(window).width()/2;
    var dhsub = dh+150;
    var sl_h = dh+200;  

    if($(window).width() > 1199) { 

      $(".product-pouf-poire-scuba-xtrem-scuba .presentation-produit-wrapper .presentation-produit.row").css("min-height", dh); 
      $(".product-pouf-poire-scuba-xtrem-sunbrella1 .presentation-produit-wrapper .presentation-produit.row").css("min-height", dhsub);
      $(".product-pouf-poire-scuba-xtrem-swimbrella.catalog-product-view .entretien.row").css("min-height", dhsub);  

    }   

    if($(window).width() >1280) {
      $("#amazingslider-1").css("height", "800px");      
    } 
       
   
  }); 

})(jQuery);
