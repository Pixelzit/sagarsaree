jQuery(document).ready(function($){
    // Increase quantity
    $('.quantity .plus').on('click', function() {
        var quantity = $(this).siblings('input.qty');
        var currentVal = parseInt(quantity.val());
        // var max = parseInt(quantity.attr('max'));
        // if (!isNaN(currentVal) && currentVal < max) {
            quantity.val(currentVal + 1).change();
        // }
    });

    // Decrease quantity
    $('.quantity .minus').on('click', function() {
        var quantity = $(this).siblings('input.qty');
        var currentVal = parseInt(quantity.val());
        var min = parseInt(quantity.attr('min'));

        if (!isNaN(currentVal) && currentVal > min) {
            quantity.val(currentVal - 1).change();
        }
    });
});

jQuery(document).ready(function($) {
    $("#show-hidden-filter").click(function() {
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

jQuery(document).ready(function($) {
    $('#site-navigation-menu-toggle').click(function() {
        $('body').toggleClass('open-menu');
    });
});



