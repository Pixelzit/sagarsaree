<?php
if ( ! function_exists( 'storefront_header_container' ) ) {
    function storefront_header_container() {
        echo '<div class="logo">';
    }
}

if ( ! function_exists( 'storefront_skip_links' ) ) {
    function storefront_skip_links() {
        ?>
        <a class="skip-link screen-reader-text" href="#site-navigation"><?php esc_html_e( 'Skip to navigation', 'storefront' ); ?></a>
        <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'storefront' ); ?></a>
        <?php
    }
}

if ( ! function_exists( 'storefront_site_branding' ) ) {
    function storefront_site_branding() {
        ?>
        <div class="site-branding">
            <?php storefront_site_title_or_logo(); ?>
        </div>
        <?php
    }
}

if ( ! function_exists( 'storefront_secondary_navigation' ) ) {
    function storefront_secondary_navigation() {
        if ( has_nav_menu( 'secondary' ) ) {
            ?>
            <nav class="secondary-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Secondary Navigation', 'storefront' ); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'secondary',
                        'fallback_cb'    => '',
                    )
                );
                ?>
            </nav><!-- #site-navigation -->
            <?php
        }
    }
}

if ( ! function_exists( 'storefront_header_container_close' ) ) {
    function storefront_header_container_close() {
        echo '</div>';
    }
}

if ( ! function_exists( 'storefront_primary_navigation_wrapper' ) ) {
    function storefront_primary_navigation_wrapper() {
        echo '<div class="storefront-primary-navigation">';
    }
}

if ( ! function_exists( 'storefront_primary_navigation' ) ) {
    function storefront_primary_navigation() {
        ?>
        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Navigation', 'storefront' ); ?>">
            <button id="site-navigation-menu-toggle" class="menu-toggle" aria-controls="site-navigation" aria-expanded="false"><span><?php echo esc_html( apply_filters( 'storefront_menu_toggle_text', __( '' ) ) ); ?></span></button>
            <?php
            wp_nav_menu(
                array(
                    'theme_location'  => 'primary',
                    'container_class' => 'primary-navigation',
                )
            );

            wp_nav_menu(
                array(
                    'theme_location'  => 'handheld',
                    'container_class' => 'handheld-navigation',
                )
            );
            ?>
        </nav><!-- #site-navigation -->
        
        <div class="mobile-only">
            <?php echo do_shortcode('[fibosearch]'); ?>
        </div>
        <?php
    }
}

if ( ! function_exists( 'storefront_primary_navigation_wrapper_close' ) ) {
    function storefront_primary_navigation_wrapper_close() {
        echo '</div>';
    }
}

/* The function below is the custom equivalent for hide storefront default cart icon */
// if ( ! function_exists( 'storefront_header_cart' ) ) {
//     function storefront_header_cart() {
//         echo do_shortcode('[xoo_wsc_cart]');
//     }
// }

/* The function action is the custom equivalent for hide storefront search bar */
add_action( 'wp', 'pxlt_custom_actions' );
function pxlt_custom_actions() {
    remove_action( 'storefront_header', 'storefront_product_search', 40 );
    remove_action( 'storefront_header', 'storefront_header_cart', 60 );
    remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
    remove_action( 'woocommerce_before_shop_loop', 'storefront_woocommerce_pagination', 30 );
    remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );

    if ( is_product_category() && is_active_sidebar( 'filter-sidebar' ) ) {
        add_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    }
}


if ( ! function_exists( 'storefront_credit' ) ) {
   function storefront_credit() {
       $links_output = '<p class="copyright"> © 2024 Copyright Sagar Saree | Sitemap | All Rights Reserved | Powered by <a href="https://pixelzit.com/">pixelzit.com</a></p>';
                echo wp_kses_post( $links_output ); ?>
                <?php 
}}

// function add_last_nav_item($items, $args) {
//     if( $args->theme_location == 'primary-navigation-menu' ){
//      $items  .= '<li><a href="#myModal" role="button" data-toggle="modal"><svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m19.25 19.25-3.75-3.75m-10.75-4.5c0-3.45178 2.79822-6.25 6.25-6.25 3.4518 0 6.25 2.79822 6.25 6.25 0 3.4518-2.7982 6.25-6.25 6.25-3.45178 0-6.25-2.7982-6.25-6.25z" stroke="#141414" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/></svg></a></li>';
//     }
//     return $items;
//   }
//   add_filter('wp_nav_menu_items','add_last_nav_item');

if ( ! function_exists( 'pxlt_single_product_page_ele_data' ) ) {
    function pxlt_single_product_page_ele_data() {
       echo do_shortcode('[elementor-template id="3224"]');
        }
    add_action('woocommerce_single_product_summary', 'pxlt_single_product_page_ele_data', 35);
}


wp_enqueue_script( 'custom-quantity-js', get_stylesheet_directory_uri() . '/assets/js/custom-quantity.js', array('jquery'), null, true );

add_filter( 'storefront_handheld_footer_bar_links', 'jk_remove_handheld_footer_links' );
function jk_remove_handheld_footer_links( $links ) {
	unset( $links['search'] );
	return $links;
}

add_filter( 'storefront_handheld_footer_bar_links', 'pxlt_add_footer_link' );
function pxlt_add_footer_link( $links ) {
	$new_links = array(
		'home' => array(
			'priority' => 10,
			'callback' => 'pxlt_home_link',
		),
		'wishlist' => array(
			'priority' => 10,
			'callback' => 'pxlt_wishlist_link',
		),
	);

	$links = array_merge( $new_links, $links );

	return $links;
}

function pxlt_home_link() {
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">
        <i class="fas fa-home"></i>
      </a>';
}

function pxlt_wishlist_link() {
    if ( class_exists( 'YITH_WCWL' ) ) {
    echo '<a href="' . esc_url( YITH_WCWL()->get_wishlist_url() ) . '" class="wishlist-icon" title="View Wishlist">';
    echo '<i class="fa fa-heart">CART</i>'; // You can change this to any icon of your choice.
    echo '</a>';
}

}

function pxlt_add_wishlist_btn_for_mobile_view(){
    ?>
    <div class="custom-wishlist">
        <?php 
            echo do_shortcode('[yith_wcwl_add_to_wishlist]');   
        ?>
    </div>
      <?php
    
}
add_action('woocommerce_after_add_to_cart_button', 'pxlt_add_wishlist_btn_for_mobile_view',30);

function pxlt_view_size_chart() {
    $categoriesSlug = [
            'lehenga-cholis',
            'salwar-suits',
            'suits',
            'saree',
            'co-ord-set',
            ];

    if ( has_term( $categoriesSlug, 'product_cat' ) ) {

        ?>
        <div class="size_grid" style="text-align: center; margin: 15px">
            <a href="<?php echo home_url(). '/size-chart'; ?>" style="cursor: pointer;" target="_blank" rel="noopener noreferrer">View Size Chart</a>

        </div>
        <?php
    }
}
add_action('woocommerce_single_product_summary', 'pxlt_view_size_chart', 25);

