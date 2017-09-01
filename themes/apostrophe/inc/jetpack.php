<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Apostrophe
 */

function apostrophe_jetpack_setup() {
	/**
	 * Add theme support for Infinite Scroll.
	 * See: http://jetpack.me/support/infinite-scroll/
	 */
	add_theme_support( 'infinite-scroll', array(
		'container'      => 'main',
		'footer'         => 'colophon',
		'footer_widgets' => 'footer-sidebar',
		'wrapper'        => false,
	) );

	/**
	 * Add theme support for Jetpack responsive videos
	 * See: http://jetpack.me/support/responsive-videos/
	 */
	add_theme_support( 'jetpack-responsive-videos' );

	/**
	 * Add theme support for Jetpack featured content
	 * See: http://jetpack.me/support/featured-content/
	 */
	add_theme_support( 'featured-content', array(
		'filter'    => 'apostrophe_get_featured_posts',
		'max_posts' => 2,
	) );

	/**
	 * Add theme support for Jetpack site logo
	 * See: http://jetpack.me/support/site-logo/
	 */
	add_image_size( 'apostrophe-logo', 9999, 300 ); // Resize based on height!
	add_theme_support( 'site-logo', array( 'size' => 'apostrophe-logo' ) );

}
add_action( 'after_setup_theme', 'apostrophe_jetpack_setup' );
