<?php
/**
 * Genesis Developer
 *
 * This file adds the front page to the Genesis Developer Theme.
 *
 * @package Genesis Developer
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
    if ( is_active_sidebar( 'front-page-1' ) ||is_active_sidebar( 'front-page-2' ) || is_active_sidebar( 'front-page-3' ) || is_active_sidebar( 'front-page-4' ) || is_active_sidebar( 'front-page-5' ) || is_active_sidebar( 'front-page-6' ) || is_active_sidebar( 'front-page-7' ) ) {
        
        // Remove breadcrumbs.
        remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
        
        // Force full width content layout.
        add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );
        
        // Remove content-sidebar-wrap.
        add_filter( 'genesis_markup_content-sidebar-wrap', '__return_null' );
        
        // Remove Header Image
        remove_action( 'genesis_after_header', __NAMESPACE__ . '\hero', 99 );
        
        // Remove the default Genesis loop.
        remove_action( 'genesis_loop', 'genesis_do_loop' );
        // Add homepage widgets.
        add_action( 'genesis_loop', __NAMESPACE__ . '\front_page_widgets' );
        
        remove_action( 'genesis_meta', __NAMESPACE__ . 'masonry_layout', 99);
        remove_filter( 'body_class', __NAMESPACE__ . '\masonry_body_class', 999 );
        
        // Add front-page body class.
        add_filter( 'body_class', __NAMESPACE__ . '\body_class', 999 );
        
        
        
    }
    
}

/**
 * Define front-page body class.
 *
 * @since 1.0.0
 */
function body_class( $classes ) {

	$classes[] = 'front-page';

	return $classes;

}

/**
 * Setup widget counts.
 *
 * @since 1.0.0
 */
function count_widgets( $id ) {

	global $sidebars_widgets;

	if ( isset( $sidebars_widgets[ $id ] ) ) {
		return count( $sidebars_widgets[ $id ] );
	}

}

/**
 * Setup widget class based on count
 *
 * @since 1.0.0
 */
function widget_area_class( $id ) {

	$count = count_widgets( $id );

	$class = '';

	if ( $count == 1 ) {
		$class .= ' widget-full';
	} elseif ( $count % 3 == 1 ) {
		$class .= ' widget-thirds';
	} elseif ( $count % 4 == 1 ) {
		$class .= ' widget-fourths';
	} elseif ( $count % 2 == 0 ) {
		$class .= ' widget-halves uneven';
	} else {
		$class .= ' widget-halves';
	}

	return $class;

}


/**
 * Register widgets and markup
 *
 * @since 1.0.0
 */
function front_page_widgets () {

	echo '<h2 class="screen-reader-text">' . __( 'Main Content', CHILD_TEXT_DOMAIN ) . '</h2>';

	genesis_widget_area( 'front-page-1', array(
		'before' => '<div id="front-page-1" class="front-page-1" tabindex="-1"><div class="widget-area"><div class="wrap">',
		'after'  => '</div></div></div>',
	) );
    genesis_widget_area( 'front-page-2', array(
		'before' => '<div id="front-page-2" class="front-page-2" tabindex="-1"><div class="widget-area' . widget_area_class( 'front-page-2' ) . '"><div class="wrap">',
		'after'  => '</div></div></div>',
	) );
    genesis_widget_area( 'front-page-3', array(
		'before' => '<div id="front-page-3" class="front-page-3" tabindex="-1"><div class="widget-area' . widget_area_class( 'front-page-3' ) . '"><div class="wrap">',
		'after'  => '</div></div></div>',
	) );
    genesis_widget_area( 'front-page-4', array(
		'before' => '<div id="front-page-4" class="front-page-4" tabindex="-1"><div class="widget-area' . widget_area_class( 'front-page-4' ) . '"><div class="wrap">',
		'after'  => '</div></div></div>',
	) );
    genesis_widget_area( 'front-page-5', array(
		'before' => '<div id="front-page-5" class="front-page-5" tabindex="-1"><div class="widget-area' . widget_area_class( 'front-page-5' ) . '"><div class="wrap">',
		'after'  => '</div></div></div>',
	) );
}

genesis();