<?php
function pxlt_astra_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
}
add_action('wp_enqueue_scripts', 'pxlt_astra_child_enqueue_styles');

require_once get_stylesheet_directory() . '/pxlt-template-functions.php';

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