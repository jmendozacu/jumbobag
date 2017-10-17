
(function($){
  jQuery(document).ready(function() {
    $(".fullscreen-before").css("background-image", 'url(' + $("#category-img").html() + ')');
    $("#category-name").detach().prependTo($(".height-centered"));

    $(".fullscreen-before .en-savoir-plus").click(function() {
      $("html, body").animate({ scrollTop: $('#page_start').offset().top }, 500);
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
  });
    

  jQuery(window).load(function() {
    $(".fullscreen-before").height($(window).height());
  });
})(jQuery);
