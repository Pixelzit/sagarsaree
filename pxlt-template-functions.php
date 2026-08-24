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

    if ( ( is_product_category() || is_shop() || is_product_taxonomy() ) && ( is_active_sidebar( 'filter-sidebar' ) || is_active_sidebar( 'sidebar-1' ) ) ) {
        add_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
        remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
    }

    if ( is_product() ) {
        remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
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

add_action( 'wp_footer', 'pxlt_add_handheld_footer_bar_to_wp_footer', 999 );
function pxlt_add_handheld_footer_bar_to_wp_footer() {
	if ( did_action( 'storefront_footer' ) ) {
		return;
	}
	if ( function_exists( 'storefront_handheld_footer_bar' ) ) {
		storefront_handheld_footer_bar();
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
// add_action('woocommerce_after_add_to_cart_button', 'pxlt_add_wishlist_btn_for_mobile_view',30);

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
        <div class="size_grid">
            <a href="<?php echo home_url(). '/size-chart'; ?>" class="pxlt-common-button" target="_blank" rel="noopener noreferrer">View Size Chart</a>
        </div>
        <?php
    }
}
add_action('woocommerce_single_product_summary', 'pxlt_view_size_chart', 25);

// upload svg
function allow_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg_upload');

add_action( 'woocommerce_single_product_summary', 'pxlt_add_get_original_image_button', 32 );
function pxlt_add_get_original_image_button() {
    global $product;
    if ( ! is_a( $product, 'WC_Product' ) ) {
        return;
    }
    
    $sku = $product->get_sku();
    if ( empty( $sku ) ) {
        $sku = 'N/A';
    }
    
    $message = sprintf( 'Hello, I want to request the original image for product SKU: %s', $sku );
    $whatsapp_url = 'https://wa.me/918858099308?text=' . rawurlencode( $message );
    
    ?>
    <div class="pxlt-original-image-container">
        <a href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" class="pxlt-get-original-image-btn pxlt-common-button">
            <span class="pxit-icon">
                <svg class="whatsapp-btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
            </span>
            Get live image
        </a>
    </div>
    <?php
}

/**
 * Helper function to check if product is out of stock.
 */
function pxlt_is_product_out_of_stock( $product ) {
    if ( ! is_a( $product, 'WC_Product' ) ) {
        return false;
    }
    if ( ! $product->is_in_stock() || ( $product->managing_stock() && $product->get_stock_quantity() !== null && $product->get_stock_quantity() <= 0 ) ) {
        return true;
    }
    return false;
}

/**
 * Display discount percentage in the sale flash badge instead of 'Sale!', or 'SOLD' if out of stock.
 */
function pxlt_custom_sale_percentage_flash( $html, $post, $product ) {
    if ( ! $product ) {
        return $html;
    }

    if ( pxlt_is_product_out_of_stock( $product ) ) {
        return '<span class="onsale sold-out">' . esc_html__( 'SOLD', 'woocommerce' ) . '</span>';
    }

    if ( ! $product->is_on_sale() ) {
        return $html;
    }

    $percentage = 0;

    if ( $product->is_type( 'variable' ) ) {
        $percentages = array();
        $prices      = $product->get_variation_prices();

        if ( ! empty( $prices['regular_price'] ) ) {
            foreach ( $prices['regular_price'] as $key => $regular_price ) {
                $sale_price = isset( $prices['sale_price'][ $key ] ) ? (float) $prices['sale_price'][ $key ] : 0;
                $reg_price  = (float) $regular_price;
                if ( $reg_price > 0 && $sale_price > 0 && $sale_price < $reg_price ) {
                    $percentages[] = round( ( ( $reg_price - $sale_price ) / $reg_price ) * 100 );
                }
            }
        }

        if ( ! empty( $percentages ) ) {
            $percentage = max( $percentages );
        }
    } elseif ( $product->is_type( 'grouped' ) ) {
        $percentages = array();
        $children    = $product->get_children();

        foreach ( $children as $child_id ) {
            $child_product = wc_get_product( $child_id );
            if ( $child_product && $child_product->is_on_sale() ) {
                $regular_price = (float) $child_product->get_regular_price();
                $sale_price    = (float) $child_product->get_sale_price();
                if ( empty( $sale_price ) ) {
                    $sale_price = (float) $child_product->get_price();
                }
                if ( $regular_price > 0 && $sale_price > 0 && $sale_price < $regular_price ) {
                    $percentages[] = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                }
            }
        }

        if ( ! empty( $percentages ) ) {
            $percentage = max( $percentages );
        }
    } else {
        $regular_price = (float) $product->get_regular_price();
        $sale_price    = (float) $product->get_sale_price();

        if ( empty( $sale_price ) ) {
            $sale_price = (float) $product->get_price();
        }

        if ( $regular_price > 0 && $sale_price > 0 && $sale_price < $regular_price ) {
            $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
        }
    }

    if ( $percentage > 0 ) {
        return '<span class="onsale">' . esc_html( $percentage . '% OFF' ) . '</span>';
    }

    return $html;
}
add_filter( 'woocommerce_sale_flash', 'pxlt_custom_sale_percentage_flash', 20, 3 );

/**
 * Display 'SOLD' badge for out of stock products that are not on sale.
 */
function pxlt_display_out_of_stock_badge() {
    global $product;
    if ( ! is_a( $product, 'WC_Product' ) ) {
        return;
    }

    if ( pxlt_is_product_out_of_stock( $product ) && ! $product->is_on_sale() ) {
        echo '<span class="onsale sold-out">' . esc_html__( 'SOLD', 'woocommerce' ) . '</span>';
    }
}
add_action( 'woocommerce_before_shop_loop_item_title', 'pxlt_display_out_of_stock_badge', 9 );
add_action( 'woocommerce_before_single_product_summary', 'pxlt_display_out_of_stock_badge', 9 );

/**
 * Display 'Sold' when product stock is 0 or out of stock.
 */
function pxlt_custom_stock_availability( $availability, $product ) {
    if ( ! is_a( $product, 'WC_Product' ) ) {
        return $availability;
    }

    if ( pxlt_is_product_out_of_stock( $product ) ) {
        $availability['availability'] = __( 'Sold', 'woocommerce' );
    }

    return $availability;
}
add_filter( 'woocommerce_get_availability', 'pxlt_custom_stock_availability', 20, 2 );

function pxlt_custom_stock_availability_text( $availability, $product ) {
    if ( ! is_a( $product, 'WC_Product' ) ) {
        return $availability;
    }

    if ( pxlt_is_product_out_of_stock( $product ) ) {
        $availability = __( 'Sold', 'woocommerce' );
    }

    return $availability;
}
add_filter( 'woocommerce_get_availability_text', 'pxlt_custom_stock_availability_text', 20, 2 );




