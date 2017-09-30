<?php
/**
 * Portfolio Archive.
 *
 * This template overrides the default archive template to clean
 * up the output.
 *
 * @package      Studio Pro
 * @link         https://seothemes.net/studio-pro
 * @author       Seo Themes
 * @copyright    Copyright © 2017 Seo Themes
 * @license      GPL-2.0+
 */
namespace TonyArmadillo\Developers;

/**
 * Add portfolio body class.
 *
 * @param array $classes Default body classes.
 * @return array $classes Default body classes.
 */
function portfolio_body_class( $classes ) {
	$classes[] = 'portfolio';
	$classes[] = 'masonry';
	return $classes;
}
add_filter( 'body_class', __NAMESPACE__ . '\portfolio_body_class', 999 );

// Force full width content layout.
add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

// Remove the breadcrumbs.
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

// Remove standard loop (optional).
remove_action( 'genesis_loop', 'genesis_do_loop' );

function portfolio_masonry_scripts() {

	// Enqueue script.
	wp_enqueue_script( 'masonry', '', array( 'js' ), CHILD_THEME_VERSION, true );

	// Add inline script.
	wp_add_inline_script( 'masonry',
		'jQuery( window ).on( "load resize scroll", function() {
			jQuery(".content").masonry({
				itemSelector: ".portfolio-item",
				columnWidth: ".portfolio-item",
				gutter: 30,
			});
		});'
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\portfolio_masonry_scripts' );

// Add our custom loop.
add_action( 'genesis_loop', __NAMESPACE__ . '\filterable_portfolio' );
/**
 * Output filterable portfolio items.
 *
 * @since 1.0
 */

function filterable_portfolio() {

	global $post;
	$terms = get_terms( 'portfolio-type' );
	?>

	<div class="archive-description">
		<?php if ( have_posts() ) { ?>
		<div class="portfolio-content">
		<?php

		while ( have_posts() ) : the_post();

			$terms = get_the_terms( get_the_ID(), 'portfolio-type' );

			// Display portfolio items.
			if ( has_post_thumbnail( $post->ID ) ) {
				?>
				<article class="portfolio-item <?php if ( $terms ) { foreach ( $terms as $term ) { echo ' ' . $term->slug;	} } ?>">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<?php
						echo genesis_get_image( array(
							'size'     => 'featured-image',
							'itemprop' => 'image',
						) );
						printf( '<p class="entry-title" itemprop="name"><span>%s</span></p>', get_the_title() );
					?>
					</a>
				</article>
				<?php

			}
		endwhile; ?>
		</div>
		<?php } ?>
	</div>

<?php

}

// Run genesis.
genesis();
