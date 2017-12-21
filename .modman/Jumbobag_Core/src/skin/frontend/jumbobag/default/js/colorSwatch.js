(function($) {
    var $product;
    var $swatch;
    var $name;
    var $coverContainer;
    var $cover;
    var $gallery;
    var currentProductData = null;

    var init = (function () {
        $product = $('#informations-produit');

        if (!$product.length) {
            return;
        }

        $swatch = $product.find('#configurable_swatch_color');
        $name = $product.find('.panier-product-title');
        $coverContainer = $product.find('.product-image');
        $cover = $product.find('.product-image-img');
        $gallery = $product.find('.product-gallery');

        listenEvents();
        initCarousel();
        selectFirstItem();
    });

    var listenEvents = (function () {
        $swatch
            .on('click', '.swatch-link', changeProduct)
        ;
        $coverContainer
            .on('click', '.product-image-link', startCarousel)
        ;
    });

    var initCarousel = (function () {
        $(".product-gallery-item-link").fancybox({
            padding : 0
        });
    });

    var selectFirstItem = (function () {
        $swatch.find('.swatch-link').first().trigger('click');
    });

    var changeProduct = (function () {
        var productId = $(this).data('productid');
        currentProductData = getProductData(productId);

        if (!currentProductData) {
            return;
        }

        updateName(currentProductData.name);
        updateCover(currentProductData.cover);
        updateGallery(currentProductData.gallery);
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

    var updateGallery = (function (gallery) {
        $gallery.html('');
        $.each(gallery, function (index, image) {
            $("\
                <li class='product-gallery-item'>\
                    <a class='product-gallery-item-link' rel='product-gallery-item-link' href='"+ image.href +"' title='"+ image.title +"'>\
                        <img class='product-gallery-item-img' src='"+ image.href +"' alt='"+ image.title +"'>\
                    </a>\
                </li>\
            ").appendTo($gallery);
        });
    });

    var startCarousel = (function () {
        $gallery.find('.product-gallery-item-link').first().trigger('click');
        return false;
    });

    window.addEventListener('load', init, true);
})(jQuery);
