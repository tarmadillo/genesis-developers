<?php
/**
 * Genesis Developer
 *
 * This file adds the required WooCommerce setup functions to the Genesis Developer Theme.
 *
 * @package Genesis Developer
 * @author  Tony Armadillo
 * @license GPL-2.0+
 */
namespace TonyArmadillo\Developers;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\products_match_height', 99 );
/**
 * Print an inline script to the footer to keep products the same height.
 *
 * @since 1.0.0
 */
function products_match_height() {

	// If Woocommerce is not activated, or a product page isn't showing, exit early.
	if ( ! class_exists( 'WooCommerce' ) || ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
		return;
	}

	wp_enqueue_script( __NAMESPACE__ . '\match-height', get_stylesheet_directory_uri() . '/js/jquery.matchHeight.min.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_add_inline_script( __NAMESPACE__ . '\match-height', "jQuery(document).ready( function() { jQuery( '.product .woocommerce-LoopProduct-link').matchHeight(); });" );

}

add_filter( 'woocommerce_style_smallscreen_breakpoint', __NAMESPACE__ . '\woocommerce_breakpoint' );
/**
 * Modify the WooCommerce breakpoints.
 *
 * @since 1.0.0
 *
 * @return string Pixel width of the theme's breakpoint.
 */
function woocommerce_breakpoint() {

	$current = genesis_site_layout();
	$layouts = array(
		'one-sidebar' => array(
			'content-sidebar',
			'sidebar-content',
		),
		'two-sidebar' => array(
			'content-sidebar-sidebar',
			'sidebar-content-sidebar',
			'sidebar-sidebar-content',
		),
	);

	if ( in_array( $current, $layouts['two-sidebar'] ) ) {
		return '2000px'; // Show mobile styles immediately.
	}
	elseif ( in_array( $current, $layouts['one-sidebar'] ) ) {
		return '1200px';
	}
	else {
		return '860px';
	}

}

add_filter( 'genesiswooc_products_per_page', __NAMESPACE__ . '\default_products_per_page' );
/**
 * Set the default products per page.
 *
 * @since 1.0.0
 *
 * @return int Number of products to show per page.
 */
function default_products_per_page() {
	return 8;
}

add_filter( 'woocommerce_pagination_args', 	__NAMESPACE__ . '\woocommerce_pagination' );
/**
 * Update the next and previous arrows to the default Genesis style.
 *
 * @since 1.0.0
 *
 * @return string New next and previous text string.
 */
function woocommerce_pagination( $args ) {

	$args['prev_text'] = sprintf( '&laquo; %s', __( 'Previous Page', 'genesis-sample' ) );
	$args['next_text'] = sprintf( '%s &raquo;', __( 'Next Page', 'genesis-sample' ) );

	return $args;

}

add_action( 'after_switch_theme', __NAMESPACE__ . '\woocommerce_image_dimensions_after_theme_setup', 1 );
/**
* Define WooCommerce image sizes on theme activation.
*
* @since 1.0.0
*/
function woocommerce_image_dimensions_after_theme_setup() {

	global $pagenow;

	if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' || ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	update_woocommerce_image_dimensions();

}

add_action( 'activated_plugin', __NAMESPACE__ . '\woocommerce_image_dimensions_after_woo_activation', 10, 2 );
/**
 * Define the WooCommerce image sizes on WooCommerce activation.
 *
 * @since 1.0.0
 */
function woocommerce_image_dimensions_after_woo_activation( $plugin ) {

	// Check to see if WooCommerce is being activated.
	if ( $plugin !== 'woocommerce/woocommerce.php' ) {
		return;
	}

	update_woocommerce_image_dimensions();

}

/**
 * Update WooCommerce image dimensions.
 *
 * @since 1.0.0
 */
function update_woocommerce_image_dimensions() {

	$catalog = array(
		'width'  => '500', // px
		'height' => '500', // px
		'crop'   => 1,     // true
	);
	$single = array(
		'width'  => '655', // px
		'height' => '655', // px
		'crop'   => 1,     // true
	);
	$thumbnail = array(
		'width'  => '180', // px
		'height' => '180', // px
		'crop'   => 1,     // true
	);

	// Image sizes.
	update_option( 'shop_catalog_image_size', $catalog );     // Product category thumbs.
	update_option( 'shop_single_image_size', $single );       // Single product image.
	update_option( 'shop_thumbnail_image_size', $thumbnail ); // Image gallery thumbs.

}

/**
 * Remove default sidebar, add shop sidebar
 *
 * @since 1.0.0
 */

add_action( 'genesis_before', __NAMESPACE__ . '\add_woo_sidebar', 20 );
function add_woo_sidebar() {
    if( is_woocommerce() ) {
        remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
        remove_action( 'genesis_sidebar_alt', 'genesis_do_sidebar_alt' );
        add_action( 'genesis_sidebar', __NAMESPACE__. '\woo_sidebar' );
    }
     function woo_sidebar() {
        if ( ! dynamic_sidebar( 'woo_primary_sidebar' ) && current_user_can( 'edit_theme_options' )  ) {
            genesis_default_widget_area_content( __( 'WooCommerce Primary Sidebar', 'genesis' ) );
        }
    }
}

    