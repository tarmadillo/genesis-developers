<?php
/**
 * Genesis Sample 2.3.0 Developer.
 *
 * This file adds the required helper functions used in the Genesis Sample Theme.
 *
 * @package Genesis Sample 2.3.0 Developer.
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 */
namespace TonyArmadillo\Developers;

/**
 * Get default link color for Customizer.
 * Abstracted here since at least two functions use it.
 *
 * @since 2.2.3
 *
 * @return string Hex color code for link color.
 */
function customizer_get_default_link_color() {
	return '#c3251d';
}

/**
 * Get default accent color for Customizer.
 * Abstracted here since at least two functions use it.
 *
 * @since 2.2.3
 *
 * @return string Hex color code for accent color.
 */
function customizer_get_default_accent_color() {
	return '#c3251d';
}

/**
 * Calculate the color contrast.
 *
 * @since 2.2.3
 *
 * @return string Hex color code for contrast color
 */
function color_contrast( $color ) {

	$hexcolor = str_replace( '#', '', $color );
	$red      = hexdec( substr( $hexcolor, 0, 2 ) );
	$green    = hexdec( substr( $hexcolor, 2, 2 ) );
	$blue     = hexdec( substr( $hexcolor, 4, 2 ) );

	$luminosity = ( ( $red * 0.2126 ) + ( $green * 0.7152 ) + ( $blue * 0.0722 ) );

	return ( $luminosity > 128 ) ? '#333333' : '#ffffff';

}

/**
 * Calculate the color brightness.
 *
 * @since 2.2.3
 *
 * @return string Hex color code for the color brightness
 */
function color_brightness( $color, $change ) {

	$hexcolor = str_replace( '#', '', $color );

	$red   = hexdec( substr( $hexcolor, 0, 2 ) );
	$green = hexdec( substr( $hexcolor, 2, 2 ) );
	$blue  = hexdec( substr( $hexcolor, 4, 2 ) );

	$red   = max( 0, min( 255, $red + $change ) );
	$green = max( 0, min( 255, $green + $change ) );
	$blue  = max( 0, min( 255, $blue + $change ) );

	return '#'.dechex( $red ).dechex( $green ).dechex( $blue );

}

/**
 * Display featured image before post content on blog.
 *
 * @return array Featured image size.
 */
function display_featured_image() {

	// Check display featured image option.
	$genesis_settings = get_option( 'genesis-settings' );

	if ( ( ! is_archive() && ! is_home() && ! is_page_template( 'blog-masonry.php' ) ) || ( 1 !== $genesis_settings['content_archive_thumbnail'] ) ) {
		return;
	}

	// Display featured image.
	add_action( 'genesis_entry_header', 'genesis_do_post_image', 1 );
}

/**
 * Remove Page Templates.
 *
 * The Genesis Blog & Archive templates are not needed and can
 * create problems for users so it's safe to remove them. If
 * you need to use these templates, simply remove this function.
 *
 * @param  array $page_templates All page templates.
 * @return array Modified templates.
 */
function remove_templates( $page_templates ) {
	unset( $page_templates['page_archive.php'] );
	unset( $page_templates['page_blog.php'] );
	return $page_templates;
}


/**
 * Remove blog metabox.
 *
 * Also remove the Genesis blog settings metabox from the
 * Genesis admin settings screen as it is no longer required
 * if the Blog page template has been removed.
 *
 * @param string $hook The metabox hook.
 */
function remove_metaboxes( $hook ) {
	remove_meta_box( 'genesis-theme-settings-blogpage', $hook, 'main' );
}

/**
 * Custom header image callback.
 *
 * Loads image or video depending on what is set.
 * If a featured image is set it will override the
 * header image. If a video is set it will be used
 * on the home page only.
 *
 * @since 1.0.0
 */
function custom_header() {

	// Get the featured image if one is set.
	if ( get_the_post_thumbnail_url() ) {

		$image = '';

		if ( class_exists( 'WooCommerce' ) && is_shop() ) {

			$image = get_the_post_thumbnail_url( get_option( 'woocommerce_shop_page_id' ) );

			if ( ! $image ) {
				$image = get_header_image();
			}
		} elseif ( is_home() ) {

			$image = get_the_post_thumbnail_url( get_option( 'page_for_posts' ) );

			if ( ! $image ) {
				$image = get_header_image();
			}
		} elseif ( is_archive() || is_category() || is_tag() || is_tax() || is_home() ) {
			$image = get_header_image();
            
		} elseif ( 'portfolio' == get_post_type( get_the_ID() ) ) {
            $image = get_the_post_thumbnail_url();
        
        } elseif ( is_single() ) {
            $image = get_header_image();
            
        } else {
			$image = get_the_post_thumbnail_url();

		}
	} elseif ( has_header_image() ) {
		$image = get_header_image();

	}

	if ( ! empty( $image ) ) {
		printf( '<style>.hero-section,.before-footer{ background-image:url(%s);}</style>', esc_url( $image ) );
	}
}