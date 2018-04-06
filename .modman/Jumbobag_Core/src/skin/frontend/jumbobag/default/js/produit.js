(function($) {
  jQuery(document).ready(function() {
    $(".fullscreen-before").css(
      "background-image",
      "url(" + $("#product-img").html() + ")"
    );
    $("#product-name-and-price")
      .detach()
      .prependTo($(".height-centered"));

    $(".presentation-produit").css(
      "background-image",
      "url(" +
        $("#fond-pres-produit")
          .find("img")
          .attr("src") +
        ")"
    );
    $(".entretien").css(
      "background-image",
      "url(" +
        $("#fond-entretien")
          .find("img")
          .attr("src") +
        ")"
    );

    $(".fullscreen-before .en-savoir-plus").click(function() {
      $("html, body").animate(
        { scrollTop: $("#page_start").offset().top },
        500
      );
    });

    $(".bouton-commander button").click(function() {
      $("html, body").animate(
        { scrollTop: $("#informations-produit").offset().top },
        500
      );
    });

    $("#product-options-wrapper")
      .find("label.required")
      .html("Couleurs :");

    //Gestion du changement d'image
    $(".swatches-container")
      .find(".swatch-img")
      .click(function() {
        var title = $(this).attr("title");

        $("#shopper_gallery_carousel")
          .find("li")
          .each(function() {
            if ($(this).attr("data-label") === title) {
              $(".product-images .product-image").html($(this).html());
            }
          });
      });

    $(".tissu p").click(function() {
      $(".content_tissu").slideToggle(1000);
      $(".tissu p").toggleClass("close_icon", 1000);
      $(".tissu p")
        .find(".fa")
        .toggleClass("fa-times-circle");
      $(".tissu p")
        .find(".fa")
        .toggleClass("fa-plus-circle isOut");
      $(".tissu p")
        .find(".fa")
        .fadeIn("slow");
      var isOut = $(".tissu p")
        .find(".fa")
        .hasClass("isOut");
      $(".tissu p")
        .find(".fa")
        .animate({ marginTop: isOut ? "2%" : "0" }, 800);
    });

    $(".remplissage p").click(function() {
      $(".content_remplissage").slideToggle(1000);
      $(".remplissage p").toggleClass("close_icon", 1000);
      $(".remplissage p")
        .find(".fa")
        .toggleClass("fa-times-circle");
      $(".remplissage p")
        .find(".fa")
        .toggleClass("fa-plus-circle isOutin");
      var isOutin = $(".remplissage p")
        .find(".fa")
        .hasClass("isOutin");
      $(".remplissage p")
        .find(".fa")
        .animate({ marginTop: isOutin ? "2%" : "0" }, 800);
    });

    $(".image-wrapper").hover(function() {
      $(".btn-wrapper").fadeIn("slow");
    });

    $(".image-wrapper").hover(function() {
      $(".btn-wrapper").fadeIn("slow");
    });

    var userFeed = new Instafeed({
      get: "user",
      userId: "288979969",
      clientId: "63d7bf6ca0984ef582595bca832fd64e",
      accessToken: "288979969.63d7bf6.eaf8bbb470d24c58ab5248aa3eb93c61",
      resolution: "standard_resolution",
      template:
        '<div class="col-xs-3"><a href="{{link}}" target="_blank" id="{{id}}"><img src="{{image}}"/></a></div>',
      sortBy: "most-recent",
      limit: 4,
      links: false
    });

    userFeed.run();

    if ($(window).width() < 768) {
      $(".footer_to_hide")
        .find(".responsive-footer h4")
        .click(function() {
          $(this)
            .parent()
            .find(".responsive-footer .to_hide_reaction")
            .slideToggle();
        });
    }

    if ($(window).width() < 768) {
      $(".image-technique-inner").insertBefore(".texte_gauche");
    }
  });

  jQuery(window).load(function() {
    $(".fullscreen-before").height($(window).height());
    $(".height-centered")
      .find(".bouton-commander button")
      .text("Commander maintenant");
    var dh = $(window).width() / 2;
    var dhsub = dh + 150;
    var sl_h = dh + 200;

    if ($(window).width() > 1199) {
      $(".product-pouf-poire-scuba-xtrem-scuba")
        .find(".presentation-produit-wrapper .presentation-produit.row")
        .css("min-height", dh);
      $(".product-pouf-poire-scuba-xtrem-sunbrella1")
        .find(".presentation-produit-wrapper .presentation-produit.row")
        .css("min-height", dhsub);
      $(".product-pouf-poire-scuba-xtrem-swimbrella.catalog-product-view")
        .find(".entretien.row")
        .css("min-height", dhsub);
    }

    if ($(window).width() > 1280) {
      $("#amazingslider-1").css("height", "800px");
    }
  });
})(jQuery);
