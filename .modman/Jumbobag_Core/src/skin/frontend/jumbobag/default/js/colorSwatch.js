(function($) {
    var $product;
    var $swatch;
    var $name;
    var $coverContainer;
    var $cover;
    var currentProductData = null

    var init = (function () {
        $product = $('#informations-produit');
        if (!$product.length) {
            return;
        }
        $swatch = $product.find('#configurable_swatch_color');
        $name = $product.find('.panier-product-title');
        $coverContainer = $product.find('.product-image');
        $cover = $product.find('.product-image-img');
        listenEvents();
    });

    var listenEvents = (function () {
        $swatch
            .on('click', '.swatch-link', changeProduct)
        ;
        $coverContainer
            .on('click', '.product-image-link', startCarousel)
        ;
    });

    var changeProduct = (function () {
        var productId = $(this).data('productid');
        currentProductData = getProductData(productId);
        if (!currentProductData) {
            return;
        }

        updateName(currentProductData.name);
        updateCover(currentProductData.cover);
    });

    var getProductData = (function (productId) {
        return dataProductSwatches[productId] || null
    });

    var updateName = (function (name) {
        $name.html(name);
    });

    var updateCover = (function (cover) {
        $cover.attr('src', cover);
    });

    var startCarousel = (function () {
        if (!currentProductData) {
            return false;
        }
        
        $.fancybox.open(currentProductData.gallery, {
            padding : 0
        });
        return false;
    });

    window.addEventListener('load', init, true);
})(jQuery);
