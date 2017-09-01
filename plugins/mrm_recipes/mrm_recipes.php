<?php

/**
 * Plugin Name: MRM Recipes
 
 * Description: This plugin adds receipes to MRM SITE.
 * Version: 1.0.6
 * Author: MyRecipeMagic
 * Author URI: http://dotsquares.com
 * License: GPL2
 */

// create custom plugin settings menu
ob_start();
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'my_plugin_action_links' );
function my_plugin_action_links( $links ) {
   $links[] = '<a href="'. esc_url( get_admin_url(null, 'admin.php?page=mrm_recipes%2Fmrm_recipes.php') ) .'">Settings</a>';
   return $links;
}

add_action('admin_menu', 'mrm_create_menu');
function mrm_create_menu() {

	//create new top-level menu
	add_menu_page('MRM Settings', 'MRM Settings', 'administrator', __FILE__, 'mrm_plugin_settings_page' );
}

function mrm_plugin_settings_page() {
	global $current_user;
	
	if(isset($_REQUEST) && ($_REQUEST['savedata']=='savedata')){
		$mrm_username = $_REQUEST['mrm_username'];
		$mrm_button_location = $_REQUEST['mrm_button_location'];
		update_user_meta( $current_user->ID, 'mrm_username', $mrm_username); 
		update_user_meta( $current_user->ID, 'mrm_button_location', $mrm_button_location);
	}
	 $get_mrm_username = get_user_meta( $current_user->ID, 'mrm_username',true);
	 $get_mrm_button_location = get_user_meta( $current_user->ID, 'mrm_button_location',true);
	 
	?>

	<div class="wrap">
	<h2>MRM Settings</h2>

	<form method="post" action="">
		
		<table class="form-table">
			<tr valign="top">
			<th scope="row">MRM UserName</th>
			<td><input type="text" name="mrm_username" value="<?php echo $get_mrm_username; ?>" /></td>
			</tr>
			 
			<tr valign="top">
			<th scope="row">Magic Button location</th>
			<td>
			<select name="mrm_button_location">
			<option value="above_post_left" <?php if($get_mrm_button_location == 'above_post_left'){ echo "selected";}?>>Above the Post&ndash;Left</option>
			<option value="above_post_right"  <?php if($get_mrm_button_location == 'above_post_right'){ echo "selected";}?>>Above the Post&ndash;Right</option>
			<option value="below_post_left"  <?php if($get_mrm_button_location == 'below_post_left'){ echo "selected";}?>>Below the Post&ndash;Left</option>
			<option value="below_post_right"  <?php if($get_mrm_button_location == 'below_post_right'){ echo "selected";}?>>Below the Post&ndash;Right</option>
			</select>
			<input type="hidden" value="savedata" name="savedata"/>
		</td>
			</tr>
				  
		</table>
		
		<?php submit_button(); ?>

	</form>
	</div>
<?php }




add_action( 'init', 'process_post' );
function process_post() {
		require (ABSPATH . WPINC . '/pluggable.php');
		global $current_user;
		get_currentuserinfo();

		$mrm_username = get_user_meta( $current_user->ID, 'mrm_username',true); //true if return value else return an array
		$mrm_location = get_user_meta( $current_user->ID, 'mrm_button_location',true);  


		if(($mrm_username !='') && ($mrm_location !=''))
		{	   
			# Adds a box to the main column on the Post and Page edit screens:
			function mrm_magic_button($post_type) {
				 global $current_user;
				  get_currentuserinfo();
				
				# Allowed post types to show meta box:
				//$post_types = array('post', 'page');  //if post type page is included, sample only
				$post_types = array('post'); 
				
				$mrm_location = get_user_meta( $current_user->ID, 'mrm_button_location',true);
				$position = '';
				$type= '';
				
				if($mrm_location == 'above_post_left')
				{
					$position = 'advanced';
					$type= 'high';
				}
				else if($mrm_location == 'above_post_right')
				{
					$position = 'side';
					$type= 'high';
				}
				else if($mrm_location == 'below_post_left')
				{
					$position = 'normal';
					$type= 'low';
				}
				else if($mrm_location == 'below_post_right')
				{
					$position = 'side';
					$type= 'low';
				}
				
				if (in_array($post_type, $post_types)) { 
					   # Add a meta box to the administrative interface:
					add_meta_box(
						'magic-button-meta-box', // HTML 'id' attribute of the edit screen section.
						'Magic Button',              // Title of the edit screen section, visible to user.
						'magic_button_meta_box', // Function that prints out the HTML for the edit screen section.
						$post_type,          // The type of Write screen on which to show the edit screen section.
						$position,          // The part of the page where the edit screen section should be shown.
						$type              // The priority within the context where the boxes should show.
					);
				}
			}
			
			# Callback that prints the box content:
			function magic_button_meta_box($post) {
				 global $current_user;
				  get_currentuserinfo();
				
				# Use `get_post_meta()` to retrieve an existing value from the database and use the value for the form:
				$magic_button = get_post_meta($post->ID, '_magic_button', true);
				# Form field to display:
				?>
				  <label class="screen-reader-text" for="mrm_magic_button">Magic Button</label>
				 <input type="checkbox" name="mrm_magic_button" value="1" id="mrm_magic_button"  <?php if(esc_attr($magic_button) == 1){ echo "checked" ;}?>/>Add This Recipe to MRM
			
			<?php 
				//display a hat with the myrecipemagic link
				if(esc_attr($magic_button) == 1){
					//$get_mrm_username = get_user_meta( $current_user->ID, 'mrm_username',true);
					$current_user = wp_get_current_user();
					//echo '<a href="http://myrecipemagic.com/recipe/recipedetail/'.$post->post_name.'" target="_blank"><img src="' . plugins_url( 'images/chef_icon.png', __FILE__ ) . '" > </a>';
					//echo '<a href="http://myrecipemagic.com/recipe/recipedetail/'.$get_mrm_username.'/'.$post->post_name.'.html" target="_blank"><img src="' . plugins_url( 'images/chef_icon.png', __FILE__ ) . '" > </a>';
					echo '<a href="http://myrecipemagic.com/recipe/recipedetail/'.$current_user->user_login.'-'.$post->post_name.'" target="_blank"><img src="' . plugins_url( 'images/chef_icon.png', __FILE__ ) . '" > </a>';
				}
				?>
				
				<?php
				# Display the nonce hidden form field:
				wp_nonce_field(
					plugin_basename(__FILE__), // Action name.
					'magic_button_meta_box'        // Nonce name.
				);
			}
			
			# Save our custom data when the post is saved:
			function mrm_magic_button_save_postdata($post_id) {
				# Is the current user is authorised to do this action?
				if ((($_POST['post_type'] === 'page') && current_user_can('edit_page', $post_id) || current_user_can('edit_post', $post_id))) { // If it's a page, OR, if it's a post, can the user edit it? 
					# Stop WP from clearing custom fields on autosave:
					if ((( ! defined('DOING_AUTOSAVE')) || ( ! DOING_AUTOSAVE)) && (( ! defined('DOING_AJAX')) || ( ! DOING_AJAX))) {
						# Nonce verification:
						if (wp_verify_nonce($_POST['magic_button_meta_box'], plugin_basename(__FILE__))) {
							# Get the posted magic_button:
							$magic_button = sanitize_text_field($_POST['mrm_magic_button']);
							# Add, update or delete?
							if ($magic_button == 1) {
								# Magic Button exists, so add OR update it:
								add_post_meta($post_id, '_magic_button', $magic_button, true) OR update_post_meta($post_id, '_magic_button', $magic_button);
							} else {
									$magic_button = '0'; //for uncheck magic button 
								# Magic Button empty or removed:
								 add_post_meta($post_id, '_magic_button', $magic_button, true) OR update_post_meta($post_id, '_magic_button', $magic_button);
							}

						}

					}
				}
			}
			
			# Get the magic_button: ????????????????  jacob
			function mrm_get_magic_button($post_id = FALSE) {
				$post_id = ($post_id) ? $post_id : get_the_ID();
				return apply_filters('mrm_the_magic_button', get_post_meta($post_id, '_magic_button', TRUE));
			}
			
			# Display magic_button (this will feel better when OOP):   ????????????? jacob
			function mrm_the_magic_button() {
				echo mrm_get_magic_button(get_the_ID());
			}
			
			# Conditional checker:
			function mrm_has_subtitle($post_id = FALSE) {
				if (mrm_get_magic_button($post_id)) return TRUE;
			}
			
			# Define the custom box:
			add_action('add_meta_boxes', 'mrm_magic_button');
			
			# Do something with the data entered:
			add_action('save_post', 'mrm_magic_button_save_postdata');
			
			# Now move advanced meta boxes after the title:  for above_post_left position only
			function mrm_move_magic_button() {

				# Get the globals:
				global $post, $wp_meta_boxes;

				# Output the "advanced" meta boxes:
				do_meta_boxes(get_current_screen(), 'advanced', $post);

				# Remove the initial "advanced" meta boxes
				unset($wp_meta_boxes['post']['advanced']);

			}
			add_action('edit_form_after_title', 'mrm_move_magic_button'); //Fires after the title field.
		}
		else 
		{
			function my_admin_error_notice() {

				$class = "update-nag";
				$message = "Please add MRM Username and magic button location into MRM Plugin setting";
					echo"<div class=\"$class\"> <p>$message</p></div>"; 
			}
			add_action( 'admin_notices', 'my_admin_error_notice' ); 
		}

}




//genrate xml
function mrm_create_xml($post_id) {	
	global $wpdb;
   
	if ( wp_is_post_revision( $post_id ) )
		return;
	$status = get_post_status ($post_id ); 
	//var_dump($status);
	if($status == "publish")
	{
		$magic_button = get_post_meta($post_id, '_magic_button', true);
		
		if($magic_button == '1')
		{	
			
			//jacob Meal Planner Pro Recipes
			$table = 'mpprecipe_recipes';
			$result_test = $wpdb->get_row( "SELECT * FROM $wpdb->prefix$table WHERE post_id=$post_id" );
			//var_dump($result_test->summary);die();
			
						
			$content_post = get_post($post_id);
			$post_content = $content_post->post_content;
			//print_r($post_content);
			//call dom element
			$domelement = new DOMDocument();

			$domelement->loadHTML($post_content);

			$xpath = new DOMXPath($domelement);
			
			//var_dump($xpath);die();
			//get  Ingredients data from post content
			$Ingredients = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " ingredients ")]');
				//print_r($Ingredients );echo 'hello';die;
			$Ingredientsitem = $Ingredients->item(0);
			
			if($Ingredientsitem !="")
			{
				$ingredient =  $domelement->saveXML($Ingredientsitem);
				$ingredient = preg_replace("/&#13;/",'',$ingredient);
				$ingredient =htmlentities(strip_tags($ingredient));
				$ingredient = str_replace('"',"&#34;",$ingredient);
				$ingredient = str_replace("'","&#039;",$ingredient);
			}
			else 
			{
				$ingredient = "";
			}
			
			//jacob Meal Planner Pro Recipes 33333333333333333333333
			if ($result_test->ingredients){
				$ingredient = $result_test->ingredients;	
			}
			
			
			
			
			//get  Instruction  data from post content
			$Instruction = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), " instructions ")]');

			$Instructionitem = $Instruction->item(0);
			if($Instructionitem !="")
			{
				$instruction =  $domelement->saveXML($Instructionitem);
				 $instruction = preg_replace("/&#13;/",'',$instruction);
				 $instruction = htmlentities(strip_tags($instruction));
			}
			else {
				$instruction = "";
			}
			
			//jacob Meal Planner Pro Recipes 2222222222222
			if ($result_test->instructions){
				$instruction = $result_test->instructions;	
			}
			
			
			
			//get summary  data from post content
			$Content = $xpath->query('//*[contains(concat(" ", normalize-space(@class), " "), "summary ")]');

			$Contentitem = $Content->item(0);
			if($Contentitem !="")
			{
				$summary =  $domelement->saveXML($Contentitem);
				$summary = preg_replace("/&#13;/",'',$summary);
				$summary = htmlentities(strip_tags($summary));
				$summary = str_replace('"',"&#34;",$summary);
				$summary = str_replace("'","&#039;",$summary);
			}
			else 
			{
				$summary = "";
			}
			
			//jacob Meal Planner Pro Recipes 6666666666666666
			if ($result_test->summary){
				$summary = $result_test->summary;	
			}
			
			
			$Prep = $xpath->query('//*[@itemprop="prepTime"]');
			$Total = $xpath->query('//*[@itemprop="totalTime"]');
			$Serve = $xpath->query('//*[@class="yield"]');
			//prep time  data from post content
			$Prepitem = $Prep->item(0);
			
			if($Prepitem !="")
			{
				$prep =  $domelement->saveXML($Prepitem);
				$prep = strip_tags($prep);
				$prep = str_replace('"',"&#34;",$prep);
				$prep = str_replace("'","&#039;",$prep);
				if (strstr($prep, 'hour'))
				{	
					$prep =	hoursToMinutes($prep);
				}
				else 
				{
					$prep = str_replace(" mins","",$prep);
                                        $prep = str_replace(" min","",$prep);
				}
			}
			else 
			{
				$prep = "";
			}
			
			
			
			//total time  data from post content
			$Totalitem = $Total->item(0);
			if($Totalitem !="")
			{
				$totaltime =  $domelement->saveXML($Totalitem);
				$totaltime = strip_tags($totaltime);
				$totaltime = str_replace('"',"&#34;",$totaltime);
				$totaltime = str_replace("'","&#039;",$totaltime);
				if (strstr($totaltime, 'hour'))
				{	
					$totaltime =	hoursToMinutes($totaltime);
				}
				else 
				{
					$totaltime = str_replace(" mins","",$totaltime);
                                        $totaltime = str_replace(" min","",$totaltime);
				}
			}
			else 
			{
				$totaltime = "";
			}
			//jacob Meal Planner Pro Recipes 444444444444444444444
			if ($result_test->total_time){
				$totaltime = $result_test->total_time;	
			}
			
			
			//serve time  data from post content
			$Serveitem = $Serve->item(0);
			if($Serveitem !="")
			{
				$servetime =  $domelement->saveXML($Serveitem);
				$servetime = str_replace("serves","",$servetime);
				$servetime = str_replace("servings","",$servetime);
				$servetime = strip_tags($servetime);
				$servetime = str_replace('"',"&#34;",$servetime);
				$servetime = str_replace("'","&#039;",$servetime);
			}
			else 
			{
				$servetime = "";
			}
			
			//jacob Meal Planner Pro Recipes 5555555555555555555555
			if ($result_test->serving_size){
				$servetime = $result_test->serving_size;	
			}
			
			$urlname = preg_replace('#^http?://#', '', get_permalink($post_id));
			
			$slug =  $content_post->post_name;
			/*image_url_full = wp_get_attachment_image_src( get_post_thumbnail_id($post_id),'full' );
			$image_url_thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id),'thumbnail' ); 
			$image_url_medium = wp_get_attachment_image_src( get_post_thumbnail_id($post_id),'medium' );*/
                    		   
			/*$attached_image = get_attached_media('image', $post_id);
            #use reset to choose the first media attached , get_post_thumbnail_id is not working for media attached
            $image_url_full = wp_get_attachment_image_src(reset($attached_image)->ID,'full' ); 
			$image_url_thumb = wp_get_attachment_image_src(reset($attached_image)->ID,'thumbnail' ); 
			$image_url_medium = wp_get_attachment_image_src(reset($attached_image)->ID,'medium' );  */
			
			if (has_post_thumbnail( $post_id)){
				//$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' ); 
				$image_url_full = wp_get_attachment_image_src( get_post_thumbnail_id($post_id),'full' );
				$image_url_thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id),'thumbnail' ); 
				$image_url_medium = wp_get_attachment_image_src( get_post_thumbnail_id($post_id),'medium' );
			}
			$author_id = $content_post->post_author;
			$created_date = date('Y-m-d H:i:s');
			
			//jacob Meal Planner Pro Recipes 1111111111111111111111111
			/*if ($result_test->author){
				$authorname = $result_test->author;	
			}else{*/
				$authorname = get_the_author_meta( 'mrm_username', $author_id );	
			//}
			
			
			$current_user = wp_get_current_user();
			
			$slug = $current_user->user_login.'-'.$slug;
			$slug = str_replace('@', '-', $slug);
			$slug = str_replace('.', '-', $slug);
			
			//create xml
			 $xml_data ="<?xml version='1.0'?><rss version='2.0'>".
			'<item>'.
				//'<title>'.$current_user->user_login.'-'.get_the_title($post_id).'</title>'.
				'<title>'.get_the_title($post_id).'</title>'.
				'<link>'.get_permalink($post_id).'</link>'.
				'<owner>'.$authorname.'</owner>'.
				'<urlname>'.$urlname.'</urlname>'.
				'<image>'.$image_url_full[0].'</image>'.
				'<imagethumb>'.$image_url_thumb[0].'</imagethumb>'.
				'<imagemedium>'.$image_url_medium[0].'</imagemedium>'.
				'<Instruction>'.$instruction.'</Instruction>'.
				'<Ingredient>'.$ingredient.'</Ingredient>'.
				/*'<PrepTime>'.$prep.'</PrepTime>'.
				'<TotalTime>'.$totaltime.'</TotalTime>'.*/
				'<PrepTime>1</PrepTime>'.
				'<TotalTime>1</TotalTime>'.
				'<ServeTime>'.$servetime.'</ServeTime>'.
				
				//'<slug>'.$current_user->user_login.'-'.$slug.'</slug>'.
				'<slug>'.$slug.'</slug>'.
				#post from wp should be not be display on the front end of mcm site.
				'<cleared>0</cleared>'.
				'<is_child>0</is_child>'.
				'<type>0</type>'.
				'<description>'.$summary.'</description>'.
				'<date>'.$created_date.'</date>'.
			'</item></rss>';
			//var_dump($authorname, '@', $urlname, '@', $image_url_full[0], '@' , $instruction, '@',  $ingredient, '@', $prep, '@', $totaltime, '@', $servetime , '@', $current_user->user_login.'-'.$slug, '@', $summary); die();
			$URL = "http://myrecipemagic.com/recipiexml/get_data.php";
				//$URL = "http://ds09.projectstatus.co.uk/recipiexml/get_data.php";
			$ch = curl_init($URL);
			curl_setopt($ch, CURLOPT_MUTE, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_POST,1);
			//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
			curl_setopt($ch, CURLOPT_POSTFIELDS,  "xmlRequest=" . rawurlencode($xml_data));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$output = curl_exec($ch);
		
			curl_close($ch);
			
		
		}	
	}
}
//add_action('save_post', 'mrm_create_xml', 10, 3);
add_action('wp_insert_post', 'mrm_create_xml', 10, 3);


function hoursToMinutes($hours)
{ 
	if (strstr($hours, 'hour'))
	{
		# Split hours and minutes.
		
		$separatedData = split('hour',"1 hour");
		
		$separatedData[1] = str_replace("mins","",$separatedData[1]);
		 $minutesInHours    = $separatedData[0] * 60;
		 $minutesInDecimals = $separatedData[1];
		
		$totalMinutes = $minutesInHours + $minutesInDecimals;
	}
	
	return $totalMinutes;
}
# Transform minutes like "105" into hours like "1:45".


#Display the button into the post or page
add_filter ('the_content', 'insert_mrm_button');
function insert_mrm_button($content) {
	 global $post;
	 $post_id = $post->ID;
	 $post_title = $post->post_title;
	 
	global $current_user;
	get_currentuserinfo();

	 #check postmeta
	$magic_button = get_post_meta($post_id, '_magic_button', true);

	#replace spaces with - in post title and make it lowercase to match the url of mrm
	$post_title = strtolower(str_replace(" ","-",$post_title));

	#get mrm username needed for the mrm url
	$mrm_username = get_user_meta( $current_user->ID, 'mrm_username',true); 
	
	if(is_single() && $magic_button==1) {
	  $content.= '<div style="text-align:center; padding:10px;">';
	  $content.=  '<center><a href="http://myrecipemagic.com/recipe/recipedetail/'.$current_user->user_login.'-'.$post->post_name.'" target="_blank" ><img src="' . plugins_url( 'images/magic-button.png', __FILE__ ) . '" > </a></center>';
	  //$content.= '<img src="' . plugins_url( 'images/magic-button.png', __FILE__ ) . '" > ';
	  $content.= '</div>';
	}
	return $content;
}