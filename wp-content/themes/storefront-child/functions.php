<?php
function pxlt_astra_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
}
add_action('wp_enqueue_scripts', 'pxlt_astra_child_enqueue_styles');

require_once get_stylesheet_directory() . '/pxlt-template-functions.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/home-page-banner-slider.php';
require_once get_stylesheet_directory() . '/inc/shortcodes/home-services-shortcode.php';

add_action( 'widgets_init', 'pxlt_widgets' );
function pxlt_widgets(){
    $args = array(
        'name' => 'Filter Sidebar',
        'id' => 'filter-sidebar',
        'description' => 'Add filters in your custom sidebar',
        'class' => '',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '',
        'after_title' => ''
    );
    register_sidebar($args);
}

function custom_body_class( $classes ) {
    if ( is_product_category() && is_active_sidebar( 'filter-sidebar' ) ) {
        $key = array_search( 'storefront-full-width-content', $classes );
        if ( $key ) {
            unset( $classes[$key] );
        }
    }

    return $classes;
}
add_filter( 'body_class', 'custom_body_class', 20 );

function pxlt_related_products_args( $args ) {
    $args['posts_per_page'] = 4;
    $args['columns'] = 4;
    return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'pxlt_related_products_args', 20);

function pxlt_remove_woo_script(){
    if ( is_product() ) {
        wp_enqueue_script('pxlt-wc-single-product-changes', get_stylesheet_directory_uri() . '/assets/js/pxlt-single-product.js', array('jquery', 'wc-single-product'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'pxlt_remove_woo_script', 20);

add_action('wp_footer','pxlt_currency_convert_back');
add_filter('pxlt_custom_price_back','pxlt_currency_convert_back');


function pxlt_currency_convert_back($price) {
    global $WCCS;
    if(empty($price)){
        return  $price;
    }
    $coversion_rate = $WCCS->wccs_get_currency_rate();
    $decimals       = $WCCS->wccs_get_currency_decimals();
    if ( empty( $coversion_rate ) ) {
        $price = $price;
    } else {
        $price = round( ( $price / $coversion_rate ), $decimals );
    }
    return $price;
}

function pxlt_currency_convert($price) {
    global $WCCS;
    if(empty($price)){
        return  $price;
    }
    $coversion_rate = $WCCS->wccs_get_currency_rate();
    $decimals       = $WCCS->wccs_get_currency_decimals();
    if ( empty( $coversion_rate ) ) {
        $price = $price;
    } else {
        $price = round( ( $price * $coversion_rate ), $decimals );
    }
    return $price;
}


function pxlt_currency_convert_2($price) {
    global $WCCS;
    if(empty($price)){
        return  $price;
    }
    $coversion_rate = $WCCS->wccs_get_currency_rate();
    $decimals       = $WCCS->wccs_get_currency_decimals();
    if ( empty( $coversion_rate ) ) {
        $price = $price;
    } else {
        $price = round( ( $price * $coversion_rate ), $decimals );
    }
    return $price;
}



add_filter('ppom_option_price','pxlt_currency_convert');
//add_filter('ppom_product_price','pxlt_currency_convert_back');



function pxlt_convert_currency($price, $product) {
    global $WCCS;
    return $WCCS->wccs_custom_price($price,$product);
}

add_action('init',function (){
    remove_action( 'woocommerce_single_product_summary', 'shiprocket_show_check_pincode', 20 );
    global $WCCS;
    remove_action( 'wp_enqueue_scripts', array( $WCCS, 'wccs_add_sticky_callback' ) );
    add_action( 'wp_footer', array( $WCCS, 'wccs_add_sticky_callback' ) );
});

add_action( 'woocommerce_price_filter_widget_min_amount', 'pxlt_currency_convert_2' );
add_action( 'woocommerce_price_filter_widget_max_amount', 'pxlt_currency_convert_2'  );
add_filter('berocket_min_max_filter',  'pixlt_invert_custom_price_one'  );

function pixlt_invert_custom_price_one($price) {
    if( is_array($price) ) {
        foreach($price as &$single) {
            $single = apply_filters('pxlt_custom_price_back', $single);
        }
    }
    return $price;
}


add_action('wp_footer',function(){
?>
<script>
    jQuery(document).ready(function($){
        $('.wcc-sticky-list li').on('click', function(event) {

            var currentUrl = window.location.href;

            var url = new URL(currentUrl);

            // Remove the 'filters' query parameter if it exists
            url.searchParams.delete('filters');

            // Update the URL in the browser without reloading the page
            history.replaceState(null, '', url.toString());

            // Now submit the form
            // The form will be submitted with the updated referer URL
           //jQuery(this).submit();
        });

    })
    </script>
    <?php
});

add_filter('ppom_product_meta_id','pxlt_ppom_product_meta_id');

function pxlt_ppom_product_meta_id($meta_id){
    
 
    if(is_array($meta_id)){
        return array_unique($meta_id);
    }
    return $meta_id;
}

function restrict_cod_to_india( $available_gateways ) {
    // Get the country code from the shipping address
    if ( isset( WC()->customer ) && WC()->customer->get_shipping_country() ) {
        $shipping_country = WC()->customer->get_shipping_country();
        
        // Check if the shipping country is 'IN' (India)
        if ( $shipping_country !== 'IN' ) {
            // Disable COD if the country is not India
            if ( isset( $available_gateways['cod'] ) ) {
                unset( $available_gateways['cod'] );
            }
        }
    }

    return $available_gateways;
}
add_filter( 'woocommerce_available_payment_gateways', 'restrict_cod_to_india' );