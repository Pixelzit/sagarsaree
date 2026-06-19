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
    // [product_categories parent=""  number="8" hide_empty=""] shortcode ul to div
    $('.woocommerce ul.products').each(function () {
        // ul -> div
        $(this).replaceWith(function () {
            return $('<div/>', {
                class: this.className,
                html: $(this).html()
            });
        });

        // li -> div
        $('.woocommerce .product-category').replaceWith(function () {
            return $('<div/>', {
                class: this.className,
                html: $(this).html()
            });
        });
        $('.woocommerce .product').replaceWith(function () {
            return $('<div/>', {
                class: this.className,
                html: $(this).html()
            });
        });
    });

    function initOccasionSlider() {
        var $occasionSlider = $(".shop-by-occasion-slider");

        if (window.innerWidth < 1441) {
            $occasionSlider.each(function () {
                var $this = $(this);

                if (!$this.hasClass("slick-initialized")) {
                    $this.slick({
                        dots: true,
                        arrows: false,
                        infinite: true,
                        speed: 500,
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        prevArrow:
                            '<button type="button" class="slick-prev" aria-label="Previous"><svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button>',
                        nextArrow:
                            '<button type="button" class="slick-next" aria-label="Next"><svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button>',
                        responsive: [
                            {
                                breakpoint: 767,
                                settings: {
                                    slidesToShow: 2,
                                },
                            },
                            {
                                breakpoint: 601,
                                settings: {
                                    slidesToShow: 1,
                                },
                            },
                        ],
                    });
                }
            });
        } else {
            $occasionSlider.each(function () {
                var $this = $(this);

                if ($this.hasClass("slick-initialized")) {
                    $this.slick("unslick");
                }
            });
        }
    }

    function initFabricSlider() {
        var $fabricSlider = $(".shop-by-febric-list .products");

        if (window.innerWidth < 1441) {
            $fabricSlider.each(function () {
                var $this = $(this);

                if (!$this.hasClass("slick-initialized")) {
                    $this.slick({
                        dots: true,
                        arrows: false,
                        infinite: true,
                        speed: 600,
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        prevArrow:
                            '<button type="button" class="slick-prev" aria-label="Previous"><svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button>',
                        nextArrow:
                            '<button type="button" class="slick-next" aria-label="Next"><svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button>',
                        responsive: [
                            {
                                breakpoint: 991,
                                settings: {
                                    slidesToShow: 3,
                                },
                            },
                            {
                                breakpoint: 767,
                                settings: {
                                    slidesToShow: 2,
                                },
                            },
                            {
                                breakpoint: 601,
                                settings: {
                                    slidesToShow: 1,
                                },
                            },
                        ],
                    });
                }
            });
        } else {
            $fabricSlider.each(function () {
                var $this = $(this);

                if ($this.hasClass("slick-initialized")) {
                    $this.slick("unslick");
                }
            });
        }
    }

    function initBestSellerSlider() {
        var $bestSellerSlider = $(".best-seller-products .products");

        if (window.innerWidth < 1201) {
            $bestSellerSlider.each(function () {
                var $this = $(this);

                if (!$this.hasClass("slick-initialized")) {
                    $this.slick({
                        dots: true,
                        arrows: false,
                        infinite: true,
                        speed: 600,
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        prevArrow:
                            '<button type="button" class="slick-prev" aria-label="Previous"><svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button>',
                        nextArrow:
                            '<button type="button" class="slick-next" aria-label="Next"><svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button>',
                        responsive: [
                            {
                                breakpoint: 768,
                                settings: {
                                    slidesToShow: 2,
                                },
                            },
                            {
                                breakpoint: 601,
                                settings: {
                                    slidesToShow: 1,
                                },
                            },
                        ],
                    });
                }
            });
        } else {
            $bestSellerSlider.each(function () {
                var $this = $(this);

                if ($this.hasClass("slick-initialized")) {
                    $this.slick("unslick");
                }
            });
        }
    }

    // Init
    initOccasionSlider();
    initFabricSlider();
    initBestSellerSlider();

    // Resize
    $(window).on("resize", function () {
        initOccasionSlider();
        initFabricSlider();
        initBestSellerSlider();
    });
});
