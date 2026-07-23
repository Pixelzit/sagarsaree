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
});


// jQuery(document).ready(function ($) {
//     $('.single-product .pxlt-original-image-container').appendTo('.single-product .yith-add-to-wishlist-button-block');
// });