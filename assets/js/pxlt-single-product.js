jQuery(document).ready(function ($) {
    var mediaQuery = window.matchMedia('(max-width: 768px)');
    function handleMobileViewForTab(mediaQuery) {
        var element = $('.pxlt_tab');
        if (element.length) {
            if (mediaQuery.matches) {
                element.removeClass('active');
                handleMobileViewForTabContent(element);
            } else {

            }
        }
    }

    function handleMobileViewForTabContent(element) {
        element.on('click', function () {
            $(this).toggleClass('active');
            $('#pxlt_tab_content').toggle();
        });
    }
    handleMobileViewForTab(mediaQuery);
    mediaQuery.addEventListener('change', handleMobileViewForTab);
});


jQuery(function ($) {
    var $wishlist = $('.yith-add-to-wishlist-button-block--single');
    var $image = $('.pxlt-original-image-container');

    if ($wishlist.length && $image.length) {
        $wishlist.add($image).wrapAll('<div class="pxlt-button-wrap"></div>');
    }

    function addExclGstToWpcPrices() {
        var bodyClass = $('body').attr('class') || '';
        var isWpcPage = $('body').hasClass('single-product') && (
            bodyClass.indexOf('product-type-woosb') !== -1 ||
            bodyClass.indexOf('product-type-wooco') !== -1 ||
            bodyClass.indexOf('product-type-wootv') !== -1 ||
            bodyClass.indexOf('product-type-wpc') !== -1 ||
            $('.woosb-wrap, .wooco-wrap, .wootv-wrap, .wpc-wrap, .product-type-woosb').length > 0
        );

        if (isWpcPage) {
            $('.woosb-products .wpc-price-excl-gst, .wooco-products .wpc-price-excl-gst').remove();

            $('.summary.entry-summary > p.price, .summary.entry-summary > .price').each(function () {
                var $price = $(this);
                if ($price.is(':visible') && $price.text().trim() !== '') {
                    if ($price.find('.wpc-price-excl-gst').length === 0 && $price.text().indexOf('Excl. GST') === -1) {
                        $price.append(' <span class="wpc-price-excl-gst">( Excl. GST )</span>');
                    }
                }
            });
        }
    }

    addExclGstToWpcPrices();
    $(document).on('woosb_calc woosb_change wooco_calc wootv_calc found_variation reset_data updated_wc_div', function () {
        setTimeout(addExclGstToWpcPrices, 50);
    });
});
