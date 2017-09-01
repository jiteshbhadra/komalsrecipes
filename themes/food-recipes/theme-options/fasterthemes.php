<?php
function fasterthemes_options_init(){
 register_setting( 'ft_options', 'foodrecipes_theme_options','ft_options_validate');
} 
add_action( 'admin_init', 'fasterthemes_options_init' );
function ft_options_validate($input)
{
	 $input['logo'] = esc_url( $input['logo'] );
	 $input['favicon'] = esc_url( $input['favicon'] );
	 $input['footertext'] = wp_filter_nohtml_kses( $input['footertext'] );
	 
	 $input['fburl'] = esc_url( $input['fburl'] );
	 $input['twitter'] = esc_url( $input['twitter'] );
	 $input['googleplus'] = esc_url( $input['googleplus'] ); 
	 $input['dribbble'] = esc_url( $input['dribbble'] );
	 $input['pintrest'] = esc_url( $input['pintrest'] );
    return $input;
}
function fasterthemes_framework_load_scripts(){
	wp_enqueue_media();
	wp_enqueue_style( 'fasterthemes_framework', get_template_directory_uri(). '/theme-options/css/fasterthemes_framework.css' ,false, '1.0.0');
	wp_enqueue_style( 'fasterthemes_framework' );	
	// Enqueue custom option panel JS
	wp_enqueue_script( 'options-custom', get_template_directory_uri(). '/theme-options/js/fasterthemes-custom.js', array( 'jquery' ) );
	wp_enqueue_script( 'media-uploader', get_template_directory_uri(). '/theme-options/js/media-uploader.js', array( 'jquery') );		
	wp_enqueue_script('media-uploader');
}
add_action( 'admin_enqueue_scripts', 'fasterthemes_framework_load_scripts' );
function fasterthemes_framework_menu_settings() {
	$foodrecipes_menu = array(
				'page_title' => __( 'FasterThemes Options', 'foodrecipes'),
				'menu_title' => __('Theme Options', 'foodrecipes'),
				'capability' => 'edit_theme_options',
				'menu_slug' => 'fasterthemes_framework',
				'callback' => 'fastertheme_framework_page'
				);
	return apply_filters( 'fasterthemes_framework_menu', $foodrecipes_menu );
}
add_action( 'admin_menu', 'theme_options_add_page' ); 
function theme_options_add_page() {
	$foodrecipes_menu = fasterthemes_framework_menu_settings();
   	add_theme_page($foodrecipes_menu['page_title'],$foodrecipes_menu['menu_title'],$foodrecipes_menu['capability'],$foodrecipes_menu['menu_slug'],$foodrecipes_menu['callback']);
} 
function fastertheme_framework_page(){ 
		global $select_options; 
		if ( ! isset( $_REQUEST['settings-updated'] ) ) 
		$_REQUEST['settings-updated'] = false;				
?>
<div class="fasterthemes-themes">
	<form method="post" action="options.php" id="form-option" class="theme_option_ft">
  <div class="fasterthemes-header">
    <div class="logo">
      <?php
		$foodrecipes_image=get_template_directory_uri().'/theme-options/images/logo.png';
		echo "<a href='http://fasterthemes.com' target='_blank'><img src='".$foodrecipes_image."' alt='FasterThemes' /></a>";
		?>
    </div>
    <div class="header-right">
      <?php
			echo "<h1>". __( 'Theme Options', 'foodrecipes' ) . "</h1>"; 			
			?>
			<div class='btn-save'><input type='submit' class='button-primary' value='<?php _e('Save Options','foodrecipes') ?>' /></div>
    </div>
  </div>
  <div class="fasterthemes-details">
    <div class="fasterthemes-options">
      <div class="right-box">
        <div class="nav-tab-wrapper">
          <ul>
            <li><a id="options-group-1-tab" class="nav-tab basicsettings-tab" title="Basic Settings" href="#options-group-1"><?php _e('Basic Settings','foodrecipes') ?></a></li>
  			<li><a id="options-group-2-tab" class="nav-tab socialsettings-tab" title="Social Settings" href="#options-group-2"><?php _e('Social Settings','foodrecipes') ?></a></li>
            <li><a id="options-group-3-tab" class="nav-tab profeatures-tab" title="Pro Settings" href="#options-group-3"><?php _e('PRO Theme Features','foodrecipes') ?></a></li>
  		  </ul>
        </div>
      </div>
      <div class="right-box-bg"></div>
      <div class="postbox left-box"> 
        <!--======================== F I N A L - - T H E M E - - O P T I O N ===================-->
          <?php settings_fields( 'ft_options' );  
		$foodrecipes_options = get_option( 'foodrecipes_theme_options' ); ?>
     
          <!-------------- First group ----------------->
          <div id="options-group-1" class="group faster-inner-tabs">
          	<div class="section theme-tabs theme-logo">
            <a class="heading faster-inner-tab active" href="javascript:void(0)"><?php _e('Site Logo','foodrecipes') ?></a>
            <div class="faster-inner-tab-group active">
            	<div class="explain"><?php _e('Size of logo should be exactly 117x43px for best results. Leave blank to use text heading.','foodrecipes') ?></div>
              	<div class="ft-control">
                <input id="logo-img" class="upload" type="text" name="foodrecipes_theme_options[logo]" value="<?php if(!empty($foodrecipes_options['logo'])) { echo esc_url($foodrecipes_options['logo']); } ?>" placeholder="<?php _e('No file chosen','foodrecipes') ?>" />
                <input id="1upload_image_button" class="upload-button button" type="button" value="<?php _e('Upload','foodrecipes') ?>" />
                <div class="screenshot" id="logo-image">
                  <?php if(!empty($foodrecipes_options['logo'])) { echo "<img src='".esc_url($foodrecipes_options['logo'])."' /><a class='remove-image'>Remove</a>"; } ?>
                </div>
              </div>
            </div>
          </div>
            <div class="section theme-tabs theme-favicon">
              <a class="heading faster-inner-tab" href="javascript:void(0)"><?php _e('Favicon','foodrecipes') ?></a>
              <div class="faster-inner-tab-group">
              	<div class="explain"><?php _e('Size of favicon should be exactly 32x32px for best results.','foodrecipes') ?></div>
                <div class="ft-control">
                  <input id="favicon-img" class="upload" type="text" name="foodrecipes_theme_options[favicon]" 
                            value="<?php if(!empty($foodrecipes_options['favicon'])) { echo esc_url($foodrecipes_options['favicon']); } ?>" placeholder="<?php _e('No file chosen','foodrecipes') ?>" />
                  <input id="upload_image_button1" class="upload-button button" type="button" value="<?php _e('Upload','foodrecipes') ?>" />
                  <div class="screenshot" id="favicon-image">
                    <?php  if(!empty($foodrecipes_options['favicon'])) { echo "<img src='".esc_url($foodrecipes_options['favicon'])."' /><a class='remove-image'>Remove</a>"; } ?>
                  </div>
                </div>
                
              </div>
            </div>
            <div id="section-footertext2" class="section theme-tabs">
            	<a class="heading faster-inner-tab" href="javascript:void(0)"><?php _e('Copyright Text','foodrecipes') ?></a>
              <div class="faster-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('Some text regarding copyright of your site, you would like to display in the footer.','foodrecipes') ?></div>                
                  	<input type="text" id="footertext2" class="of-input" name="foodrecipes_theme_options[footertext]" size="32"  value="<?php if(!empty($foodrecipes_options['footertext'])) { echo esc_attr($foodrecipes_options['footertext']); } ?>">
                </div>                
              </div>
            </div>            
          </div>             
          <!-------------- Second group ----------------->
          <div id="options-group-2" class="group faster-inner-tabs">            
            <div id="section-facebook" class="section theme-tabs">
            	<a class="heading faster-inner-tab active" href="javascript:void(0)"><?php _e('Facebook','foodrecipes') ?></a>
              <div class="faster-inner-tab-group active">
              	<div class="ft-control">
              		<div class="explain"><?php _e('Facebook profile or page URL i.e.','foodrecipes') ?> http://facebook.com/username/ </div>                
                  	<input id="facebook" class="of-input" name="foodrecipes_theme_options[fburl]" size="30" type="text" value="<?php if(!empty($foodrecipes_options['fburl'])) { echo esc_url($foodrecipes_options['fburl']); } ?>" />
                </div>                
              </div>
            </div>
            <div id="section-twitter" class="section theme-tabs">
            	<a class="heading faster-inner-tab" href="javascript:void(0)"><?php _e('Twitter','foodrecipes') ?></a>
              <div class="faster-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('Twitter profile or page URL i.e.','foodrecipes') ?> http://www.twitter.com/username/</div>                
                  	<input id="twitter" class="of-input" name="foodrecipes_theme_options[twitter]" type="text" size="30" value="<?php if(!empty($foodrecipes_options['twitter'])) { echo esc_url($foodrecipes_options['twitter']); } ?>" />
                </div>                
              </div>
            </div>
            <div id="section-googleplus" class="section theme-tabs">
            	<a class="heading faster-inner-tab" href="javascript:void(0)"><?php _e('Google Plus','foodrecipes') ?></a>
              <div class="faster-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('Google Plus profile or page URL i.e.','foodrecipes') ?> https://plus.google.com/username/</div>                
                  	 <input id="googleplus" class="of-input" name="foodrecipes_theme_options[googleplus]" type="text" size="30" value="<?php if(!empty($foodrecipes_options['googleplus'])) { echo esc_url($foodrecipes_options['googleplus']); } ?>" />
                </div>                
              </div>
            </div>
            <div id="section-dribbble" class="section theme-tabs">
            	<a class="heading faster-inner-tab" href="javascript:void(0)"><?php _e('Dribbble','foodrecipes') ?></a>
              <div class="faster-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('dribbble profile or page URL i.e.','foodrecipes') ?> https://dribbble.com/username/</div>                
                  	<input id="dribbble" class="of-input" name="foodrecipes_theme_options[dribbble]" type="text" size="30" value="<?php if(!empty($foodrecipes_options['dribbble'])) { echo esc_url($foodrecipes_options['dribbble']); } ?>" />
                </div>                
              </div>
            </div>
            <div id="section-pintrest" class="section theme-tabs">
            	<a class="heading faster-inner-tab" href="javascript:void(0)"><?php _e('Pinterest','foodrecipes') ?></a>
              <div class="faster-inner-tab-group">
              	<div class="ft-control">
              		<div class="explain"><?php _e('Google Plus profile or page URL i.e.','foodrecipes') ?> https://pintrest.com/username/</div>                
                  	<input id="pintrest" class="of-input" name="foodrecipes_theme_options[pintrest]" type="text" size="30" value="<?php if(!empty($foodrecipes_options['pintrest'])) { echo esc_url($foodrecipes_options['pintrest']); } ?>" />
                </div>                
              </div>
            </div>
          </div>   
          <!-------------- Third group ----------------->
          <div id="options-group-3" class="group faster-inner-tabs fasterthemes-pro-image">
          	<div class="fasterthemes-pro-header">
              <img src="<?php echo get_template_directory_uri(); ?>/theme-options/images/theme-logo.png" class="fasterthemes-pro-logo" />
              <a href="http://fasterthemes.com/wordpress-themes/FoodRecipes" target="_blank"><img src="<?php echo get_template_directory_uri(); ?>/theme-options/images/buy-now.png" class="fasterthemes-pro-buynow" /></a>
              </div>
          	<img src="<?php echo get_template_directory_uri(); ?>/theme-options/images/pro-featured.png" />
          </div>
        <!--======================== F I N A L - - T H E M E - - O P T I O N S ===================--> 
      </div>
     </div>
	</div>
	<div class="fasterthemes-footer">
      	<ul>
        	<li>&copy; <a href="http://fasterthemes.com" target="_blank"><?php _e('fasterthemes.com','foodrecipes') ?></a></li>
            <li class="btn-save"><input type="submit" class="button-primary" value="<?php _e('Save Options','foodrecipes') ?>" /></li>
        </ul>
    </div>
    </form>    
</div>
<div class="save-options"><h2><?php _e('Options saved successfully.','foodrecipes') ?></h2></div>
<?php } ?>
