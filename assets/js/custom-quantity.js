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
    $('.home .woocommerce ul.products').each(function () {
        // ul -> div
        $(this).replaceWith(function () {
            return $('<div/>', {
                class: this.className,
                html: $(this).html()
            });
        });

        // li -> div
        $('.home .woocommerce .product-category').replaceWith(function () {
            return $('<div/>', {
                class: this.className,
                html: $(this).html()
            });
        });
        $('.home .woocommerce .product').replaceWith(function () {
            return $('<div/>', {
                class: this.className,
                html: $(this).html()
            });
        });
    });

    // Re-initialize YITH Wishlist components on the homepage after replacement
    function reinitWishlist() {
        var $wishlistBlocks = $('.yith-add-to-wishlist-button-block');
        if ($wishlistBlocks.length) {
            $wishlistBlocks.removeClass('yith-add-to-wishlist-button-block--initialized').empty();
            $(document).trigger('yith_wcwl_init');
            if (window.wp && window.wp.hooks) {
                window.wp.hooks.doAction('yith_wcwl_init_add_to_wishlist_components');
            }
        }
    }
    reinitWishlist();
    // Fallback: Run again after a short delay to ensure any async loaded wishlist scripts pick it up
    setTimeout(reinitWishlist, 100);
    setTimeout(reinitWishlist, 500);




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

jQuery(document).ready(function ($) {
    // Make the entire .shop-occasion-card clickable by triggering the inner button link
    $(document).on('click', '.shop-occasion-card', function (e) {
        // If the user clicked directly on a link or button, let the browser handle it
        if ($(e.target).closest('a, button, input, select, textarea').length) {
            return;
        }

        var $link = $(this).find('.shop-occasion-cta .elementor-button-link');
        var href = $link.attr('href');
        if (href) {
            var target = $link.attr('target');
            if (target === '_blank') {
                window.open(href, '_blank');
            } else {
                window.location.href = href;
            }
        }
    });
});


jQuery(document).on('input', '.wpcf7-form .full-name', function ($) {

    let value = jQuery(this).val();

    value = value
        .replace(/[^A-Za-z ]+/g, '')  // Remove invalid characters
        .replace(/ +/g, ' ')          // Single space only
        .replace(/^ /, '');           // No leading space

    jQuery(this).val(value);

});

jQuery(document).on('input', '.wpcf7-form .phone-number', function ($) {

    let value = jQuery(this).val();

    // Sirf digits rakho
    value = value.replace(/\D/g, '');

    // Maximum 10 digits
    value = value.substring(0, 10);

    // Auto format: (XXX) XXX-XXXX
    if (value.length > 6) {
        value = `(${value.substring(0, 3)}) ${value.substring(3, 6)}-${value.substring(6)}`;
    } else if (value.length > 3) {
        value = `(${value.substring(0, 3)}) ${value.substring(3)}`;
    } else if (value.length > 0) {
        value = `(${value}`;
    }

    jQuery(this).val(value);

});


jQuery(document).on('input', '.wpcf7-form .gst-number', function () {

    let value = jQuery(this).val().toUpperCase();

    // Sirf A-Z aur 0-9 allow
    value = value.replace(/[^A-Z0-9]/g, '');

    // Max 15 characters
    value = value.substring(0, 15);

    let formatted = '';

    for (let i = 0; i < value.length; i++) {

        let ch = value[i];

        if (i < 2) {
            // 2 digits
            if (/\d/.test(ch)) formatted += ch;

        } else if (i < 7) {
            // 5 letters
            if (/[A-Z]/.test(ch)) formatted += ch;

        } else if (i < 11) {
            // 4 digits
            if (/\d/.test(ch)) formatted += ch;

        } else if (i === 11) {
            // 1 letter
            if (/[A-Z]/.test(ch)) formatted += ch;

        } else if (i === 12) {
            // 1 alphanumeric
            if (/[A-Z0-9]/.test(ch)) formatted += ch;

        } else if (i === 13) {
            // Always Z
            formatted += 'Z';

        } else if (i === 14) {
            // 1 alphanumeric
            if (/[A-Z0-9]/.test(ch)) formatted += ch;
        }
    }

    jQuery(this).val(formatted);

});

// Validate GST number on ".bundle-pop-submit" button click
jQuery(document).on('click', '.bundle-pop-submit', function (event) {
    var $form = jQuery(this).closest('form');
    var $gstInput = $form.find('.gst-number');

    if ($gstInput.length) {
        var gstVal = $gstInput.val().trim();
        // Regex pattern: ^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$
        var regex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;

        // Remove existing validation tip and error class
        $gstInput.removeClass('wpcf7-not-valid');
        $gstInput.siblings('.wpcf7-not-valid-tip').remove();

        if (gstVal === '' || !regex.test(gstVal)) {
            // Prevent submission
            event.preventDefault();
            event.stopPropagation();

            // Apply error styles and append warning
            $gstInput.addClass('wpcf7-not-valid');
            $gstInput.after('<span class="wpcf7-not-valid-tip" aria-hidden="true">Please enter a valid GST Number</span>');

            // Focus on invalid field
            $gstInput.focus();
            return false;
        }
    }
});

// Clear error tips and styling when user focuses or types in the GST input
jQuery(document).on('input focus', '.wpcf7-form .gst-number', function () {
    jQuery(this).removeClass('wpcf7-not-valid');
    jQuery(this).siblings('.wpcf7-not-valid-tip').remove();
});