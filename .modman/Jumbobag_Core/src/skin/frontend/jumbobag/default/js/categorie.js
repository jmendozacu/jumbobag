(function($) {
  jQuery(document).ready(function() {
    $(".fullscreen-before").css(
      "background-image",
      "url(" + $("#category-img").html() + ")"
    );
    $("#category-name")
      .detach()
      .prependTo($(".height-centered"));

    $(".fullscreen-before .en-savoir-plus").click(function() {
      $("html, body").animate(
        { scrollTop: $("#page_start").offset().top },
        500
      );
    });
  });

  jQuery(window).load(function() {
    $(".fullscreen-before").height($(window).height());
  });
})(jQuery);
