<?php
	/**
 * @package Apostrophe
 *
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 */

get_header(); ?>
<meta name="description" content="Food Blog for Delicious and Tasty Vegetarian Recipes | Veg Recipes cooked by Komal and Laxmi. You can find many veg recipes made by us from the different region of the world." />
	<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Responsive for Komal's Recipes -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-9710134700300696"
     data-ad-slot="5477728415"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script><br clear="all" /><br clear="all" />
	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>
		
		</main><!-- #main -->
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-format="fluid"
     data-ad-layout="image-side"
     data-ad-layout-key="-fe+6i+3a-sw+166"
     data-ad-client="ca-pub-9710134700300696"
     data-ad-slot="4961438837"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
<br clear="all" />
<br clear="all" />
		<?php apostrophe_paging_nav(); ?>
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
