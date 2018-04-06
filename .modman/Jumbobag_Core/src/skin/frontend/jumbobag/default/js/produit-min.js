!(function($) {
  jQuery(document).ready(function() {
    $(".fullscreen-before").css(
      "background-image",
      "url(" + $("#product-img").html() + ")"
    ),
      $("#product-name-and-price")
        .detach()
        .prependTo($(".height-centered")),
      $(".presentation-produit").css(
        "background-image",
        "url(" + $("#fond-pres-produit").html() + ")"
      ),
      $(".entretien").css(
        "background-image",
        "url(" + $("#fond-entretien").html() + ")"
      ),
      $(".fullscreen-before .en-savoir-plus").click(function() {
        $("html, body").animate(
          { scrollTop: $("#page_start").offset().top },
          500
        );
      });
    var e = 25,
      t = e / $(window).height(),
      r = e / $(window).width();
    $("#product-options-wrapper label.required").html("Couleurs :"),
      $(".swatches-container .swatch-img").click(function() {
        var e = $(this).attr("title");
        $("#shopper_gallery_carousel li").each(function() {
          $(this).attr("data-label") === e &&
            $(".product-images .product-image").html($(this).html());
        });
      });
  }),
    jQuery(window).load(function() {
      $(".fullscreen-before").height($(window).height());
    });
})(jQuery);
