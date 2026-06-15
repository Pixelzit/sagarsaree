jQuery(document).ready(function ($) {
    // Increase quantity
    $('.quantity .plus').on('click', function () {
        var quantity = $(this).siblings('input.qty');
        var currentVal = parseInt(quantity.val());
        // var max = parseInt(quantity.attr('max'));
        // if (!isNaN(currentVal) && currentVal < max) {
        quantity.val(currentVal + 1).change();
        // }
    });

    // Decrease quantity
    $('.quantity .minus').on('click', function () {
        var quantity = $(this).siblings('input.qty');
        var currentVal = parseInt(quantity.val());
        var min = parseInt(quantity.attr('min'));

        if (!isNaN(currentVal) && currentVal > min) {
            quantity.val(currentVal - 1).change();
        }
    });
});

jQuery(document).ready(function ($) {
    $("#show-hidden-filter").click(function () {
        var toggle_switch = $(this);
        var target = $("#secondary");

        target.toggleClass('open');
        if (target.hasClass("open")) {
            toggle_switch.addClass('down');
            toggle_switch.text('Remove filter');
        } else {
            toggle_switch.removeClass('down');
            toggle_switch.text('Show filter');
        }
    });
});

jQuery(document).ready(function ($) {
    $('#site-navigation-menu-toggle').click(function () {
        $('body').toggleClass('open-menu');
    });
});


// header stcky
jQuery(window).on('scroll', function () {
    if (jQuery(window).scrollTop() > 200) {
        jQuery('.header-nav-wrap').addClass('is-sticky');
    } else {
        jQuery('.header-nav-wrap').removeClass('is-sticky');
    }
});



jQuery(document).ready(function ($) {
    var $slider = $('.shop-by-occasion-slider');

    function initOccasionSlider() {
        if (window.innerWidth < 1024) {
            if (!$slider.hasClass('slick-initialized')) {
                $slider.slick({
                    dots: false,
                    arrows: true,
                    infinite: true,
                    speed: 500,
                    slidesToShow: 3,
                    slidesToScroll: 1,

                    responsive: [
                        {
                            breakpoint: 767,
                            settings: {
                                slidesToShow: 2
                            }
                        },
                        {
                            breakpoint: 576,
                            settings: {
                                slidesToShow: 1
                            }
                        }
                    ]
                });
            }
        } else {
            if ($slider.hasClass('slick-initialized')) {
                $slider.slick('unslick');
            }
        }
    }

    initOccasionSlider();

    $(window).on('resize', function () {
        initOccasionSlider();
    });
});



jQuery(window).on('load', function () {

    const $products = jQuery('.shop-by-febric-list ul.products');

    if (window.innerWidth < 1440 && !$products.hasClass('swiper-wrapper')) {

        // wrapper
        $products.wrap('<div class="swiper fabric-swiper"></div>');

        const $swiper = $products.parent();

        // arrows add
        $swiper.append('<div class="swiper-button-prev"></div>');
        $swiper.append('<div class="swiper-button-next"></div>');

        $products.addClass('swiper-wrapper');
        $products.children('li').addClass('swiper-slide');

        new Swiper('.fabric-swiper', {
            slidesPerView: 4,
            spaceBetween: 20,

            navigation: {
                nextEl: '.fabric-swiper .swiper-button-next',
                prevEl: '.fabric-swiper .swiper-button-prev'
            },

            breakpoints: {
                // 0: {
                //     slidesPerView: 2.2
                // },
                576: {
                    slidesPerView: 1.2
                },
                768: {
                    slidesPerView: 2
                },
                1024: {
                    slidesPerView: 4
                }
            }
        });
    }

});