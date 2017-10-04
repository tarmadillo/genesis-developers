<?php
/**
 * Genesis Sample 2.3.0 Developer.
 *
 * This file adds the required helper functions used in the Genesis Sample Theme.
 *
 * @package Genesis Sample 2.3.0 Developer.
 * @author  Tony Armadillo
 * @license GPL-2.0+
 */
namespace TonyArmadillo\Developers;

add_action('genesis_meta',__NAMESPACE__ . '\masonry_layout');
/**
 * Do masonry actions
 *
 * @since 2.0.0
 *
 * @return void
 */
function masonry_layout() {
	if (is_home() || is_category()) {
        add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

        /**
         * Add blog-masonry body class.
         *
         * @param array $classes Default body classes.
         * @return array $classes Default body classes.
         */
        function masonry_body_class( $classes ) {
            $classes[] = 'blog';
            $classes[] = 'masonry';
            return $classes;
        }
        add_filter( 'body_class', __NAMESPACE__ . '\masonry_body_class', 999 );

        /**
         * Enqueue masonry script.
         *
         * Uses the masonry script from wp-includes/js/masonry.min.js
         */
        function blog_masonry_scripts() {

            // Enqueue script.
            wp_enqueue_script( 'masonry', '', array( 'js' ), CHILD_THEME_VERSION, true );

            // Add inline script.
            wp_add_inline_script( 'masonry',
                'jQuery( window ).on( "load resize scroll", function() {
                    jQuery(".content").masonry({
                        itemSelector: ".entry",
                        columnWidth: ".entry",
                        gutter: 30,
                    });
                });'
            );
        }
        add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\blog_masonry_scripts' );

        /**
         * Display featured image before post content on blog.
         */
        function masonry_featured_image() {

            // Check display featured image option.
            $genesis_settings = get_option( 'genesis-settings' );

            if ( 1 === $genesis_settings['content_archive_thumbnail'] ) {
                // Display featured image.
                add_action( 'genesis_entry_header', 'genesis_do_post_image', 1 );
            }
        }
        add_action( 'genesis_before', __NAMESPACE__ . '\masonry_featured_image' );
        remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
        remove_action( 'genesis_post_content', 'genesis_do_post_image' );

        /**
         * Customize the post info function.
         *
         * @return string $post_info Post info string.
         */
        function post_info_filter() {
            $post_info = '[post_date]';
            return $post_info;
        }
        add_filter( 'genesis_post_info', __NAMESPACE__ . '\post_info_filter' );
        remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
        add_action( 'genesis_entry_header', 'genesis_post_info', 2 );

        /**
         * Customize Entry Meta Filed Under and Tagged Under.
         *
         * @return string $post_meta Post meta string.
         */
        function entry_meta_footer() {
            $categories = get_the_category();
            $separator = ', ';
            $output = '';
            
            if ( ! empty( $categories ) ) {
                foreach( $categories as $category ) {
                    $output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" alt="' . esc_attr( sprintf( __( 'View all posts in %s', CHILD_TEXT_DOMAIN ), $category->name ) ) . '">' . esc_html( $category->name ) . '</a>' . $separator;
                }
            }
        }
        add_filter( 'genesis_post_meta',  __NAMESPACE__ . '\entry_meta_footer' );

        /**
         * Custom loop for masonry grid.
         *
         * @return void
         */
        function blog_masonry_loop() {
            if ( is_category()) {
                $include = get_queried_object_id();
            } else {
                $include = genesis_get_option( 'blog_cat' );
            }
            
            $exclude = genesis_get_option( 'blog_cat_exclude' ) ? explode( ',', str_replace( ' ', '', genesis_get_option( 'blog_cat_exclude' ) ) ) : '';
            $paged   = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

            $query_args = wp_parse_args(
                genesis_get_custom_field( 'query_args' ),
                array(
                    'cat'              => $include,
                    'category__not_in' => $exclude,
                    'showposts'        => genesis_get_option( 'blog_cat_num' ),
                    'paged'            => $paged,
                )
            );
            genesis_custom_loop( $query_args );
            wp_reset_query();
        }
        add_action( 'genesis_loop',  __NAMESPACE__ . '\blog_masonry_loop' );
        remove_action( 'genesis_loop', 'genesis_do_loop' );
    }
}





