<?php
	/**
 * @package Apostrophe
 *
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 */
?>

	</div><!-- #content -->
<br clear="all" />
<a href="https://www.bloglovin.com/blog/18370975/?claim=zaard9ztrv3">Follow my blog with Bloglovin</a>
	<footer id="colophon" class="site-footer" role="contentinfo">

		<?php if ( is_active_sidebar( 'footer-sidebar' ) ) : ?>
		<div class="widget-area">
			<div id="footer-sidebar">
				<?php dynamic_sidebar( 'footer-sidebar' ); ?>
			</div>
		</div>
		<?php endif; ?>

		<div class="site-info">
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'apostrophe' ) ); ?>"><?php printf( esc_html__( 'All rights reserved %s', 'apostrophe' ), 'komalsrecipes.com' ); ?></a>&nbsp;&nbsp;
<a href="http://www.komalsrecipes.com/contact-us/">Contact Us</a>&nbsp;&nbsp;
<a href="http://www.komalsrecipes.com/feed/" target="_blank">RSS Feed</a>
			<span class="sep" style="display:none"> | </span>
			<!--<?php printf( esc_html__( 'Theme: %1$s by %2$s.', 'apostrophe' ), 'Apostrophe', '<a href="http://wordpress.com/themes/apostrophe/" rel="designer">WordPress.com</a>' ); ?>-->
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-86548304-1', 'auto');
  ga('send', 'pageview');

</script>
<script async defer src="//assets.pinterest.com/js/pinit.js"></script>
</body>
</html>
