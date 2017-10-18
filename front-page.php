<?php
/**
 * Furniture.
 *
 * This file adds the front page to the Furniture Theme.
 *
 * @package Furniture
 * @author  Tony Armaidllo
 * @license GPL-2.0+
 * @link    
 */
namespace TonyArmadillo\Developers;

// Remove title
remove_action( 'genesis_after_header', 'genesis_do_post_title' );

add_action( 'genesis_meta', __NAMESPACE__ . '\front_page_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 * @since 1.0.0
 */
function front_page_genesis_meta() {

    // Add front-page body class.
    add_filter( 'body_class', __NAMESPACE__ . '\body_class' );

    // Force full width content layout.
    add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

    // Remove breadcrumbs.
    remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
        
    remove_action( 'genesis_meta', __NAMESPACE__ . 'masonry_layout', 99);

    
    if ( is_active_sidebar( 'front-page-1' ) ||is_active_sidebar( 'front-page-2' ) || is_active_sidebar( 'front-page-3' ) || is_active_sidebar( 'front-page-4' ) || is_active_sidebar( 'front-page-5' ) || is_active_sidebar( 'front-page-6' ) || is_active_sidebar( 'front-page-7' ) ) {
        
        // Remove Header Image
        remove_action( 'genesis_after_header', __NAMESPACE__ . '\hero', 99 );
        
        // Remove the default Genesis loop.
        remove_action( 'genesis_loop', 'genesis_do_loop' );
        
        remove_filter( 'body_class', __NAMESPACE__ . '\masonry_body_class', 999 );
        add_filter( 'body_class', __NAMESPACE__ . '\body_class', 999 );
        
        
        // Add homepage widgets after grid.
        add_action( 'genesis_loop', __NAMESPACE__ . '\front_page_widgets' );
    }
    
}

// Define front-page body class.
function body_class( $classes ) {

	$classes[] = 'front-page';

	return $classes;

}

function front_page_widgets () {

	echo '<h2 class="screen-reader-text">' . __( 'Main Content', CHILD_TEXT_DOMAIN ) . '</h2>';

	genesis_widget_area( 'front-page-1', array(
		'before' => '<div id="front-page-1" class="front-page-1" tabindex="-1"><div class="widget-area"><div class="wrap">',
		'after'  => '</div></div></div>',
	) );
    
    genesis_widget_area( 'front-page-2', array(
		'before' => '<div id="front-page-2" class="front-page-2" tabindex="-1"><div class="widget-area">',
		'after'  => '</div></div>',
	) );
    genesis_widget_area( 'front-page-3', array(
		'before' => '<div id="front-page-3" class="front-page-3" tabindex="-1"><div class="widget-area">',
		'after'  => '</div></div>',
	) );
    genesis_widget_area( 'front-page-4', array(
		'before' => '<div id="front-page-4" class="front-page-4" tabindex="-1"><div class="widget-area">',
		'after'  => '</div></div>',
	) );
    genesis_widget_area( 'front-page-5', array(
		'before' => '<div id="front-page-5" class="front-page-5" tabindex="-1"><div class="widget-area">',
		'after'  => '</div></div>',
	) );
}

genesis();