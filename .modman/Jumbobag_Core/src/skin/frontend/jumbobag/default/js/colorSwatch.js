(function($) {
    var $product;
    var $swatch;
    var $name;
    var $cover;

    var init = (function () {
        $product = $('#informations-produit');
        if (!$product.length) {
            return;
        }
        $swatch = $product.find('#configurable_swatch_color');
        $name = $product.find('.panier-product-title');
        $cover = $product.find('.product-image img');
        listenEvents();
    });

    var listenEvents = (function () {
        $swatch
            .on('click', '.swatch-link', changeProduct)
        ;
    });

    var changeProduct = (function () {
        var productId = $(this).data('productid');
        var productData = getProductData(productId)

        if (!productData) {
            return;
        }
        
        updateName(productData.name);
        updateCover(productData.cover);
        updateCarousel(productData.gallery);
    });

    var getProductData = (function (productId) {
        return dataProductSwatches[productId] || false
    });

    var updateName = (function (name) {
        $name.html(name);
    });

    var updateCover = (function (cover) {
        $cover.attr('src', cover);
    });

    var updateCarousel = (function (gallery) {
        // console.log($productCarousel);
    });

    window.addEventListener('load', init, true);
})(jQuery);
