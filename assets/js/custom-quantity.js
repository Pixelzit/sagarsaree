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



    // home page sliders
    var prevArrowSvg = '<button type="button" class="slick-prev" aria-label="Previous"><svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button>';
    var nextArrowSvg = '<button type="button" class="slick-next" aria-label="Next"><svg viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button>';

    function initResponsiveSlider(selector, initBreakpoint, slickSettings) {
        var $slider = $(selector);
        if ($slider.length === 0) return;

        if (window.innerWidth < initBreakpoint) {
            $slider.each(function () {
                var $this = $(this);
                if (!$this.hasClass("slick-initialized")) {
                    $this.slick($.extend(true, {
                        dots: true,
                        arrows: false,
                        infinite: true,
                        speed: 500,
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        prevArrow: prevArrowSvg,
                        nextArrow: nextArrowSvg
                    }, slickSettings));
                }
            });
        } else {
            $slider.each(function () {
                var $this = $(this);
                if ($this.hasClass("slick-initialized")) {
                    $this.slick("unslick");
                }
            });
        }
    }

    function initAllSliders() {
        initResponsiveSlider(".shop-by-occasion-slider", 1441, {
            slidesToShow: 3,
            responsive: [
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2,
                    },
                },
            ],
        });

        initResponsiveSlider(".shop-by-febric-list .products", 1441, {
            speed: 600,
            slidesToShow: 4,
            responsive: [
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2,
                    },
                },
            ],
        });

        initResponsiveSlider(".home-wholesale-body", 1441, {
            slidesToShow: 3,
            responsive: [
                {
                    breakpoint: 991,
                    settings: {
                        slidesToShow: 2,
                    },
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 2,
                    },
                },
            ],
        });

        initResponsiveSlider(".best-seller-products .products", 1201, {
            speed: 600,
            slidesToShow: 3,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2,
                    },
                },
            ],
        });
    }

    // Init
    initAllSliders();

    // Resize
    $(window).on("resize", function () {
        initAllSliders();
    });
});


// Elementor Mobile Menu Overlay and Body Scroll Lock
jQuery(document).ready(function ($) {
    function getHeaderSection() {
        return $('.sagar-site-header-section, #sagar-site-header-section');
    }

    function checkMenuState() {
        var isMenuOpen = $('.elementor-menu-toggle').hasClass('elementor-active');
        if (isMenuOpen) {
            // Append overlay if not already present
            if ($('#elementor-menu-overlay').length === 0) {
                var $header = getHeaderSection();
                if ($header.length) {
                    $header.before('<div id="elementor-menu-overlay"></div>');
                } else {
                    $('body').append('<div id="elementor-menu-overlay"></div>');
                }
            }
            $('body').addClass('elementor-menu-open');
        } else {
            $('body').removeClass('elementor-menu-open');
        }
    }

    // Set up a MutationObserver to listen to class changes on the menu toggle button
    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                if (mutation.target.classList && mutation.target.classList.contains('elementor-menu-toggle')) {
                    checkMenuState();
                }
            }
        });
    });

    // Observe body for changes to catch dynamically loaded elementor menu
    observer.observe(document.body, {
        attributes: true,
        subtree: true,
        attributeFilter: ['class']
    });

    // Fallback: Bind directly to toggle click event in case observer is slow or not supported
    $(document).on('click', '.elementor-menu-toggle', function () {
        setTimeout(checkMenuState, 100);
    });

    // Close menu when clicking on the overlay
    $(document).on('click', '#elementor-menu-overlay', function () {
        var $activeToggle = $('.elementor-menu-toggle.elementor-active');
        if ($activeToggle.length) {
            $activeToggle.trigger('click');
        }
    });

    // Sticky navigation for .main-navigation-wrap on screen > 1025px
    var lastScrollTop = 0;
    $(window).on('scroll', function () {
        if (window.innerWidth > 1025) {
            var scrollTop = $(this).scrollTop();
            var $nav = $('.main-navigation-wrap');
            if ($nav.length) {
                if (scrollTop > 150) {
                    if (scrollTop > lastScrollTop) {
                        // Scrolling down -> show sticky
                        $nav.addClass('is-sticky-scroll-down').removeClass('is-hidden-scroll-up');
                    } else {
                        // Scrolling up -> hide/go up
                        $nav.removeClass('is-sticky-scroll-down').addClass('is-hidden-scroll-up');
                    }
                } else {
                    // Back to top -> reset
                    $nav.removeClass('is-sticky-scroll-down is-hidden-scroll-up');
                }
            }
            lastScrollTop = scrollTop;
        } else {
            $('.main-navigation-wrap').removeClass('is-sticky-scroll-down is-hidden-scroll-up');
        }
    });
});


