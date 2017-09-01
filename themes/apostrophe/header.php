<?php
	/**
 * @package Apostrophe
 *
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
	<meta name="p:domain_verify" content="036e77514a7afc5c1a60fe93ca999320"/>
<meta name="msvalidate.01" content="379A0F1D8AD9E40893B30B88630304D6" />
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-9710134700300696",
    enable_page_level_ads: true
  });
</script>
	</head>

	<body <?php body_class(); ?>>
		<div id="page" class="hfeed site">
			<header id="masthead" class="site-header" role="banner">
				<div class="site-branding">
					<?php
					if ( function_exists( 'jetpack_the_site_logo' ) ) :
					 	jetpack_the_site_logo();
					endif;
					?>
					<h1 class="site-title"><a href="http://komalsrecipes.com" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
				</div>

				<nav id="site-navigation" class="main-navigation" role="navigation">
					<a class="menu-toggle"><?php esc_html_e( "Menu", 'apostrophe' ); ?></a>
					<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'apostrophe' ); ?></a>

					<?php wp_nav_menu( array(
						'theme_location' => 'primary',
						'menu_class'     => 'apostrophe-navigation',
					) ); ?>

					<?php wp_nav_menu( array(
						'theme_location' => 'social',
						'menu_class'     => 'apostrophe-social',
						'link_before'    => '<span>',
						'link_after'     => '</span>',
						'fallback_cb'    => '',
						'depth'          => 1,
					) ); ?>

				</nav><!-- #site-navigation -->		
			</header><!-- #masthead -->

			<div id="content" class="site-content">
