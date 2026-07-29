<?php
function pxlt_astra_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
    wp_enqueue_style('child-main-style', get_stylesheet_directory_uri() . '/assets/css/main.css', array());
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
    if ( ( is_product_category() || is_shop() || is_product_taxonomy() ) && ( is_active_sidebar( 'filter-sidebar' ) || is_active_sidebar( 'sidebar-1' ) ) ) {
        $key = array_search( 'storefront-full-width-content', $classes );
        if ( $key !== false ) {
            unset( $classes[$key] );
        }
    }

    if ( is_product() ) {
        $classes[] = 'storefront-full-width-content';
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


/**
 * Helper to check if the current user is a wholesaler, administrator, or shop manager.
 */
function sagar_is_user_wholesaler() {
    if ( ! is_user_logged_in() ) {
        return false;
    }
    $user = wp_get_current_user();
    $allowed_roles = array( 'wholesaler', 'administrator', 'shop_manager' );
    return ! empty( array_intersect( $allowed_roles, (array) $user->roles ) );
}

/**
 * Filter the "Get a Quote" button visibility.
 * Show only on bundle products (type 'woosb') and only for non-wholesalers.
 */
add_filter( 'get_post_metadata', 'sagar_restrict_quote_button_visibility', 100, 4 );
function sagar_restrict_quote_button_visibility( $value, $object_id, $meta_key, $single ) {
    static $in_filter = false;
    if ( $in_filter ) {
        return $value;
    }

    if ( $meta_key === '_wpb_gqb_disable' && ! is_admin() ) {
        $in_filter = true;
        $product = wc_get_product( $object_id );
        $in_filter = false;
        
        if ( $product ) {
            // If it is NOT a bundle product, hide the quote button (disable = yes)
            if ( $product->get_type() !== 'woosb' ) {
                return $single ? 'yes' : array( 'yes' );
            }
            // If it IS a bundle product, but the user is a logged-in wholesaler, hide the quote button (disable = yes)
            if ( sagar_is_user_wholesaler() ) {
                return $single ? 'yes' : array( 'yes' );
            }
            // Otherwise, show the quote button (disable = no)
            return $single ? 'no' : array( 'no' );
        }
    }
    return $value;
}

/**
 * Hide price of bundle products for guests and non-wholesalers.
 */
add_filter( 'woocommerce_get_price_html', 'sagar_hide_bundle_price_for_non_wholesalers', 100, 2 );
function sagar_hide_bundle_price_for_non_wholesalers( $price, $product ) {
    if ( is_admin() ) {
        return $price;
    }
    if ( $product && $product->get_type() === 'woosb' ) {
        if ( ! sagar_is_user_wholesaler() ) {
            return ''; // Hide price
        }
    }
    return $price;
}

/**
 * Append "Excl. GST" after price on single product page ONLY for main bundle product total price.
 */
add_filter( 'woocommerce_get_price_html', 'sagar_wpc_product_price_excl_gst', 101, 2 );
function sagar_wpc_product_price_excl_gst( $price, $product ) {
    if ( is_admin() || empty( $price ) ) {
        return $price;
    }

    if ( is_product() && $product ) {
        $wpc_types = array( 'woosb', 'wooco', 'wootv', 'woofbt', 'woosg', 'wpcgp', 'wpc_product' );
        $type      = $product->get_type();
        $main_id   = get_the_ID();

        // Only append to the main page product (the bundle product itself), not its child items in the list
        if ( in_array( $type, $wpc_types, true ) && (int) $product->get_id() === (int) $main_id ) {
            if ( strpos( $price, 'wpc-price-excl-gst' ) === false && strpos( $price, 'Excl. GST' ) === false ) {
                $price .= ' <span class="wpc-price-excl-gst">( Excl. GST )</span>';
            }
        }
    }

    return $price;
}


/**
 * Prevent adding bundle products to cart for guests and non-wholesalers.
 */
add_filter( 'woocommerce_add_to_cart_validation', 'sagar_prevent_bundle_cart_addition', 9999, 3 );
function sagar_prevent_bundle_cart_addition( $passed, $product_id, $quantity ) {
    $product = wc_get_product( $product_id );
    if ( $product && $product->get_type() === 'woosb' ) {
        if ( ! sagar_is_user_wholesaler() ) {
            wc_add_notice( __( 'You must be a wholesaler to purchase bundle products.', 'storefront' ), 'error' );
            return false;
        }
    }
    return $passed;
}

/**
 * Add a body class to handle styling/hiding of add-to-cart or price details dynamically.
 */
add_filter( 'body_class', 'sagar_bundle_body_classes' );
function sagar_bundle_body_classes( $classes ) {
    if ( sagar_is_user_wholesaler() ) {
        $classes[] = 'sagar-user-wholesaler';
    } else {
        $classes[] = 'sagar-user-not-wholesaler';
    }
    
    if ( is_product() ) {
        $product = wc_get_product( get_the_ID() );
        if ( $product && $product->get_type() === 'woosb' ) {
            if ( ! sagar_is_user_wholesaler() ) {
                $classes[] = 'sagar-hide-purchase';
            }
        }
    }
    return $classes;
}

/**
 * Server-side GST validation for Contact Form 7.
 */
add_filter( 'wpcf7_validate_text*', 'sagar_cf7_gst_validation', 10, 2 );
add_filter( 'wpcf7_validate_text', 'sagar_cf7_gst_validation', 10, 2 );
function sagar_cf7_gst_validation( $result, $tag ) {
    if ( $tag->name === 'gst-number' ) {
        $value = isset( $_POST['gst-number'] ) ? trim( $_POST['gst-number'] ) : '';
        $regex = '/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/';
        
        if ( ! empty( $value ) && ! preg_match( $regex, $value ) ) {
            $result->invalidate( $tag, 'Please enter a valid GST Number.' );
        }
    }
    return $result;
}

/**
 * Disable Cash on Delivery (COD) for bundle products (type 'woosb') in the cart.
 */
function sagar_disable_cod_for_bundle_products( $available_gateways ) {
    if ( is_admin() ) {
        return $available_gateways;
    }
    
    if ( ! isset( $available_gateways['cod'] ) ) {
        return $available_gateways;
    }

    if ( WC()->cart && is_callable( array( WC()->cart, 'get_cart' ) ) ) {
        foreach ( WC()->cart->get_cart() as $cart_item ) {
            $product = $cart_item['data'];
            if ( $product && $product->get_type() === 'woosb' ) {
                unset( $available_gateways['cod'] );
                break;
            }
        }
    }

    return $available_gateways;
}
add_filter( 'woocommerce_available_payment_gateways', 'sagar_disable_cod_for_bundle_products', 20 );
