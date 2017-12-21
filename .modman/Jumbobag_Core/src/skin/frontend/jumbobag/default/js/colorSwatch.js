(function($) {
    var $product;
    var $swatch;

    var init = (function () {
        $product = $('#informations-produit');
        if (!$product.length) {
            return;
        }
        $swatch = $product.find('#configurable_swatch_color');
        listenEvents();
    });

    var listenEvents = (function () {
        $swatch
            .on('click', '.swatch-link', changeProduct)
        ;
    });

    var changeProduct = (function () {
        var productId = $(this).data('productid');
        console.log(productId);
    });

    window.addEventListener('load', init, true);
})(jQuery);
