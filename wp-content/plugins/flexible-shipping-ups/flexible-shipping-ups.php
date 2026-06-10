<?php
/**
 * Plugin Name: Shipping Live Rates and Access Points for UPS for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/flexible-shipping-ups/
 * Description: Shipping Live Rates and Access Points for UPS WooCommerce shipping methods with real-time calculated shipping rates based on the established UPS API connection.
 * Version: 3.6.9
 * Author: Octolize
 * Author URI: https://octol.io/ups-author
 * Text Domain: flexible-shipping-ups
 * Domain Path: /lang/
 * Requires at least: 6.4
 * Tested up to: 7.0
 * WC requires at least: 10.5
 * WC tested up to: 10.9
 * Requires PHP: 7.4
 * ​
 * Copyright 2017 WP Desk Ltd.
 * ​
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * ​
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * ​
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package Flexible_Shipping_UPS
 */

defined( 'ABSPATH' ) || exit;

/* THIS VARIABLE CAN BE CHANGED AUTOMATICALLY */
$plugin_version = '3.6.9';

$plugin_name        = 'Flexible Shipping UPS';
$plugin_class_name  = '\WPDesk\FlexibleShippingUps\Plugin';
$plugin_text_domain = 'flexible-shipping-ups';
$product_id         = 'Flexible Shipping UPS';
$plugin_file        = __FILE__;
$plugin_dir         = __DIR__;
$plugin_shops       = [
	'default' => 'https://octolize.com/',
];

define( 'FLEXIBLE_SHIPPING_UPS_VERSION', $plugin_version );
define( $plugin_class_name, $plugin_version );

$requirements = [
	'php'     => '7.4',
	'wp'      => '4.5',
	'plugins' => [
		[
			'name'      => 'woocommerce/woocommerce.php',
			'nice_name' => 'WooCommerce',
		],
	],
	'modules' => [
		[
			'name'      => 'intl',
			'nice_name' => 'Internationalization Functions (intl)',
		],
		[
			'name'      => 'curl',
			'nice_name' => 'cURL',
		],
	],
];

require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow-common/src/plugin-init-php52-free.php';

add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
	}
} );

require_once __DIR__ . '/vendor_prefixed/guzzlehttp/guzzle/src/functions_include.php';
require_once __DIR__ . '/vendor_prefixed/guzzlehttp/promises/src/functions_include.php';
require_once __DIR__ . '/vendor_prefixed/guzzlehttp/psr7/src/functions_include.php';
