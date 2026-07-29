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

    // Single Product ACF Video Lightbox & Slider Controller
    var $videoModal = $('#pxlt-video-lightbox-modal');
    var $floatingBadge = $('#pxlt-floating-video-badge');

    if ($videoModal.length && $floatingBadge.length) {
        var $slides = $videoModal.find('.pxlt-video-slide');
        var $dots = $videoModal.find('.pxlt-slider-dot');
        var totalSlides = $slides.length;
        var currentIndex = 0;

        function goToSlide(index) {
            if (index < 0) {
                index = totalSlides - 1;
            } else if (index >= totalSlides) {
                index = 0;
            }

            // Pause video or reset iframe on previous slide
            var $prevSlide = $slides.eq(currentIndex);
            var prevVid = $prevSlide.find('video').get(0);
            if (prevVid) {
                prevVid.pause();
            }
            var $prevIframe = $prevSlide.find('iframe');
            if ($prevIframe.length) {
                var src = $prevIframe.attr('src');
                $prevIframe.attr('src', src);
            }

            currentIndex = index;

            $slides.removeClass('active').eq(currentIndex).addClass('active');
            $dots.removeClass('active').eq(currentIndex).addClass('active');

            // Play video on new active slide
            var $activeSlide = $slides.eq(currentIndex);
            var activeVid = $activeSlide.find('video').get(0);
            if (activeVid) {
                activeVid.play().catch(function () {});
            }
        }

        function openVideoModal() {
            $videoModal.addClass('is-open');
            $('body').addClass('pxlt-video-modal-open');
            goToSlide(currentIndex);
        }

        function closeVideoModal() {
            $videoModal.removeClass('is-open');
            $('body').removeClass('pxlt-video-modal-open');

            $slides.find('video').each(function () {
                this.pause();
            });
            $slides.find('iframe').each(function () {
                var src = $(this).attr('src');
                $(this).attr('src', src);
            });
        }

        // Open Lightbox on floating badge click
        $floatingBadge.on('click', '.pxlt-floating-video-inner', function (e) {
            e.preventDefault();
            openVideoModal();
        });

        // Close floating badge button
        $floatingBadge.on('click', '.pxlt-floating-video-close', function (e) {
            e.stopPropagation();
            $floatingBadge.fadeOut(300);
        });

        // Close lightbox modal
        $videoModal.on('click', '.pxlt-video-lightbox-close, .pxlt-video-lightbox-overlay', function (e) {
            e.preventDefault();
            closeVideoModal();
        });

        // ESC key to close
        $(document).on('keyup', function (e) {
            if (e.key === 'Escape' && $videoModal.hasClass('is-open')) {
                closeVideoModal();
            }
        });

        // Slider Arrow Controls
        $videoModal.on('click', '.pxlt-slider-prev', function (e) {
            e.preventDefault();
            goToSlide(currentIndex - 1);
        });

        $videoModal.on('click', '.pxlt-slider-next', function (e) {
            e.preventDefault();
            goToSlide(currentIndex + 1);
        });

        // Dots Navigation
        $videoModal.on('click', '.pxlt-slider-dot', function (e) {
            e.preventDefault();
            var slideIdx = parseInt($(this).data('slide'), 10);
            goToSlide(slideIdx);
        });

        // Mobile Touch Swipe Support
        var touchStartX = 0;
        var touchEndX = 0;

        $videoModal.find('.pxlt-video-slider-container').on('touchstart', function (e) {
            touchStartX = e.originalEvent.touches[0].clientX;
        });

        $videoModal.find('.pxlt-video-slider-container').on('touchend', function (e) {
            touchEndX = e.originalEvent.changedTouches[0].clientX;
            var diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 40) {
                if (diff > 0) {
                    goToSlide(currentIndex + 1);
                } else {
                    goToSlide(currentIndex - 1);
                }
            }
        });
    }
});
