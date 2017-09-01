<?php
/**
 * The Header template for our theme
 */
  $foodrecipes_options = get_option( 'foodrecipes_theme_options' );
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width">
<title>
<?php wp_title( '|', true, 'right' ); ?>
</title>
<?php if(!empty($foodrecipes_options['favicon'])) { ?>
<link rel="shortcut icon" href="<?php echo esc_url($foodrecipes_options['favicon']);?>">
<?php } ?>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/respond.min.js"></script>
	<![endif]-->
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<header>
  <div class="foodrecipes-container-recipes container">
    <div class="col-md-12 foodrecipes-heading-title no-padding">
      <?php if(get_header_image()){ ?>
      <img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" class="foodrecipes-custom-header" alt="" />
      <?php } ?>
      <div class="col-md-3 foodrecipes-logo"> <a href="<?php echo home_url(); ?>">
        <?php if(empty($foodrecipes_options['logo'])){ ?>
        <h1 class="foodrecipes-site-title"><?php echo get_bloginfo('name'); ?></h1>
        <?php }else{
                               echo  "<img src='".esc_url($foodrecipes_options['logo'])."' class='img-responsive'/>";
                             }?>
        </a> </div>
      <div class="col-md-3 no-padding-right foodrecipes-social-icon-right ">
        <ul class="foodrecipes-social list-inline pull-right">
          <?php if(!empty($foodrecipes_options['fburl'])) {?>
          <li><a href="<?php echo esc_url($foodrecipes_options['fburl']); ?>"><i class="fa fa-facebook"></i></a></li>
          <?php } ?>
          <?php if(!empty($foodrecipes_options['twitter'])) {?>
          <li><a href="<?php echo esc_url($foodrecipes_options['twitter']); ?>"><i class="fa fa-twitter"></i></a></li>
          <?php } ?>
          <?php if(!empty($foodrecipes_options['googleplus'])) { ?>
          <li><a href="<?php echo esc_url($foodrecipes_options['googleplus']); ?>"><i class="fa fa-google-plus"></i></a></li>
          <?php } ?>
          <?php if(!empty($foodrecipes_options['dribbble'])) { ?>
          <li><a href="<?php echo esc_url($foodrecipes_options['dribbble']); ?>"><i class="fa fa-dribbble"></i></a></li>
          <?php } ?>
          <?php if(!empty($foodrecipes_options['pintrest'])) { ?>
          <li><a href="<?php echo esc_url($foodrecipes_options['pintrest']); ?>"><i class="fa fa-pinterest"></i></a></li>
          <?php } ?>
        </ul>
      </div>
      <div class="navbar-header">
        <button type="button" class="navbar-toggle navbar-toggle-top" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only"><?php _e('Toggle navigation','foodrecipes'); ?></span> <span class="icon-bar foodrecipes-icon-color"></span> <span class="icon-bar foodrecipes-icon-color"></span> <span class="icon-bar foodrecipes-icon-color"></span> </button>
      </div>
      <?php 
			$foodrecipes_defaults = array(
					'theme_location'  => 'primary',
					'container'       => 'div',
					'container_class' => 'col-md-6 no-padding food-clear',
					'container_id'    => '',
					'menu_class'      => 'col-md-6 no-padding food-clear',
					'menu_id'         => '',
					'echo'            => true,
					'fallback_cb'     => 'wp_page_menu',
					'before'          => '',
					'after'           => '',
					'link_before'     => '',
					'link_after'      => '',
					'items_wrap'      => '<ul id="menu" class="menu-foodrecipes navbar-collapse collapse menu-foodrecipes-set">%3$s</ul>',
					'depth'           => 0,
					'walker'          => ''
					);
			wp_nav_menu($foodrecipes_defaults); ?>
    </div>
    <div class="clearfix"></div>
  </div>
</header>
