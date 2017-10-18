<?php
/**
 * Genesis Sample 2.3.0 Developer.
 *
 * This file adds the default theme settings to the Genesis Sample Theme.
 *
 * @package Genesis Sample 2.3.0 Developer.
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://www.studiopress.com/
 */
namespace TonyArmadillo\Developers;

add_filter( 'genesis_theme_settings_defaults', __NAMESPACE__ . '\theme_defaults' );
/**
* Updates theme settings on reset.
*
* @since 1.0.0
*/
function theme_defaults( $defaults ) {

	$defaults['blog_cat_num']              = 12;
	$defaults['content_archive']           = 'excerpt';
	$defaults['content_archive_limit']     = 300;
	$defaults['content_archive_thumbnail'] = 1;
	$defaults['posts_nav']                 = 'numeric';
	$defaults['image_size']                = 'large';
	$defaults['site_layout']               = 'content-sidebar';

	return $defaults;

}

add_action( 'after_switch_theme', __NAMESPACE__ . '\theme_setting_defaults' );
/**
* Updates theme settings on activation.
*
* @since 1.0.0
*/
function theme_setting_defaults() {

	if ( function_exists( 'genesis_update_settings' ) ) {

		genesis_update_settings( array(
			$defaults['blog_cat_num']              = 12,
            $defaults['content_archive']           = 'excerpt',
            $defaults['content_archive_limit']     = 300,
            $defaults['content_archive_thumbnail'] = 1,
            $defaults['posts_nav']                 = 'numeric',
            $defaults['image_size']                = 'large',
            $defaults['site_layout']               = 'content-sidebar',
		) );

	}

	update_option( 'posts_per_page', 12 );

}
