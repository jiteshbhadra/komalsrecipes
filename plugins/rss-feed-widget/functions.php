<?php
/*
	if(!function_exists('rfw_cache')){
		function rfw_cache(){
			
		}
	}*/

	if(!function_exists('pre')){
	function pre($data){
			if(isset($_GET['debug'])){
				pree($data);
			}
		}	 
	} 	

	if(!function_exists('pree')){
	function pree($data){
				echo '<pre>';
				print_r($data);
				echo '</pre>';	
		}	 
	} 
	
class rfw_dock extends WP_Widget {
	
	public $rfw_cache_val, $list_type;

	public function __construct() {
		global $rfw_data;
		// widget actual processes
		parent::__construct(
			'rfw_dock', // Base ID
			__($rfw_data['Name'].' ('.$rfw_data['Version'].')', 'text_domain'), // Name
			array( 'description' => __( 'A feed widget with sliding effect.', 'text_domain' ), ) // Args
		);
		
	}
	

	function clean_xhtml($string)
	{
		/*if(function_exists('ereg_replace'))
		$string = ereg_replace("<[^>]*>", "", $string);
		
		if(function_exists('preg_replace'))
		$string = preg_replace("@<p[^>]*?>.*?@siu", '',$string);
		
		$string = str_replace(array('</p>'), '', $string);*/
		
		if(function_exists('strip_tags'))
		$string = strip_tags($string);
		
		return $string;
	}

	public function rfw_cache(){
		return $this->rfw_cache_val;
	}
	
	public function list_type(){
		return $this->list_type;
	}	
	public function img_size(){
		return $this->img_size;
	}	
	
	public function is_url($url){
		$ret = true;
		if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
			$ret = false;
		}
		return $ret;
	}
	
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		// pree($args); pree($instance);//exit;
		global $wpdb, $rfw_chameleon_installed, $rfw_chameleon_activated;
		//pree($instance);
		$limit_feeds = ($instance['number']>0?$instance['number']:1);
		
		$rfw_style = '';
		if($rfw_chameleon_installed && $rfw_chameleon_activated)
		$rfw_style = get_option('rfw_style', '');
		
		
		$title = $instance['title'];
		//pree($title);
		$rss_url = $instance['rss_url'];
		//pree($rss_url);
		$fb_page_id = !$this->is_url($rss_url);
		//pree($fb_page_id);
		$show_feed_title = $instance['show_feed_title'];
		
		$feed_words = ($instance['feed_words']>0?$instance['feed_words']:60);
		
		$display_type = $instance['content_display'];
		
		$content_order = $instance['content_order']; 
		
		$content_height = $instance['content_height']; 
		
		$speed    = isset( $instance['speed'] ) ? absint( $instance['speed'] ) : 500;
		
		$this->rfw_cache_val = isset($instance['rfw_cache'])?$instance['rfw_cache']:''; 
		$this->list_type = isset($instance['list_type'])?$instance['list_type']:'slider'; 
		
		$this->img_size = isset($instance['img_size'])?$instance['img_size']:'small'; 
		
		$content_sort = (substr($content_order, 0, 4)=='date');
		//pree($content_sort);

		//pree($this->img_size);
		$scripts = "<script type=\"text/javascript\" language=\"javascript\">jQuery(document).ready(function($){	
						$('#".$args['widget_id']." .rfw_dock.rfw_slider').bxSlider({
							  auto: true,
							  adaptiveHeight: true,
							  pager: true,
							  controls: false,
							  infiniteLoop: true,
							  speed: $speed,
							  mode: 'horizontal',
							  pause: 10000,
							  ticker: false,
							  pagerType: 'full',
							  randomStart: true,
							  hideControlOnEnd: true,
							  easing: 'linear',
							  captions: false,
							  video: true,
							  responsive: true,
							  useCSS: true,
							  preloadImages: 'visible',
							  touchEnabled: true
						});
					});
				</script>";		
				 
		 $html = $scripts.'<aside id="'.$args['widget_id'].'" class="rfw-class '.$rfw_style.'">';
		 $html.= ($title!='')?'<h3 class="widget-title">'.$title.'</h3>':'';
		 $html.= '<nav class="add-nav widget_dock" id="rfw-authors">';
		 //pree($title);
				
				
		
			//pree($rss_url);
		$html_arr = array();

				
		$html_ul = '<ul class="rfw_dock rfw_'.$this->list_type.'" style="'.($content_height!=''?'height:'.$content_height:'').'">';
		
			if($fb_page_id){
				$access_token_array = array(
					'1489500477999288|KFys5ppNi3sreihdreqPkU2ChIE',
					'859332767418162|BR-YU8zjzvonNrszlll_1a4y_xE',
					'360558880785446|4jyruti_VkxxK7gS7JeyX-EuSXs',
					'1487072591579718|0KQzP-O2E4mvFCPxTLWP1b87I4Q',
					'640861236031365|2rENQzxtWtG12DtlZwqfZ6Vu6BE',
					'334487440086538|hI_NNy1NvxQiQxm-TtXsrmoCVaE',
					'755471677869105|Jxv8xVDad7vUUTauk8K2o71wG2w',
					'518353204973067|dA7YTe-k8eSvgZ8lqa51xSm16DA',
					'444286039063163|5qkYu2qxpERWO3gcs2f3nxeqhpg',
					'944793728885704|XJ6QqKK8Ldsssr4n5Qrs2tVr7rs',
					'1444667452511509|wU7tzWiuj6NadfpHfgkIGLGO86o',
					'1574171666165548|ZL9tXNXxpnCdAvdUjCX5HtRnsR8'
				);
				$access_token = $access_token_array[rand(0, 11)];	
				
				$json_resp = 'https://graph.facebook.com/'.$rss_url.'/posts?fields=object_id,message,story,link,type&access_token='.$access_token.'&limit='.$limit_feeds.'&locale=en_US';
				$response = wp_remote_get( $url );
				//pree($json_resp);
				$response = wp_remote_get($json_resp);
				
				
				$img_url = 'https://graph.facebook.com/ITEM-ID?fields=object_id';
				$img_path = 'https://graph.facebook.com/ITEM-ID/picture';
				

				if( is_wp_error( $response ) ) {
				   $error_message = $response->get_error_message();
				   //echo "Something went wrong: $error_message";
				} else {
					if( is_array($response) ) {
					  $header = $response['headers']; // array of http header lines
					  $body = wp_remote_retrieve_body($response);
					 
					  $body = json_decode($body);
					  $body = (isset($body->data)?$body->data:$body);
					 
					 //pree($body);
					 				  
					  if(!empty($body)){
						  
						  foreach($body as $items){
							  
							//pree($items);exit;
							
							$items->message = (isset($items->message)?$items->message:'');
							$items->story = (isset($items->story)?$items->story:$items->message);
							
							switch($content_sort){
								default:
								
									$unique_key = strtolower(substr($items->story, 0, 1));
							
								break;
								
								case 'date':
									$unique_key = strtolower(substr($items->story, 0, 1));
								break;
							}
							
							$content = '';
							
							$img = '';
							$story_img = str_replace('ITEM-ID', $items->id, $img_url);
							//pree($items->id.' - '.$story_img);
							
							//pree($story_img);
							
							$link = '<a title="'.$items->story.'" href="'.esc_url( $items->link ).'" target="_blank" rel="nofollow">_LINK_TEXT_</a>';
							
							$content .= $show_feed_title?str_replace('_LINK_TEXT_', '<h3 class="entry-title rfw1">'.esc_html( $items->story ).'</h3>', $link):'';
							
							$description = $items->story;	
							
							//pree($items);//exit;
							
							$atts = array();
							
							//pree($items->type);
							switch($items->type){
							
								case 'video':
									$attachments = wp_remote_get( 'https://graph.facebook.com/v2.2/'.$items->id.'?fields=attachments&fields=attachments{url,subattachments}&access_token='.$access_token );
									
											
									$attachments = wp_remote_retrieve_body($attachments);
									
									$attachments = json_decode($attachments);
									$attachments = (isset($attachments->data)?$attachments->data:$attachments);
							  
									
									if(!empty($attachments) && isset($attachments->attachments) && isset($attachments->attachments->data) && !empty($attachments->attachments->data)){
										foreach($attachments->attachments->data as $urls){
											$atts[] = $urls->url;	
										}
									}
								break;							
						 	}
							
							//pree($atts);exit;
							//pree($display_type);exit;
														  
							switch($display_type){
								
								case 'text_only':						
										
									$content .= ($description!=''?'<div class="text_div">'.$this->string_limit_words($this->clean_xhtml($description), $feed_words).' ...</div>':'');													
								break;
								
								default:
									
									$media = '';
									switch($items->type){
										case 'photo':
											$object_id = $items->object_id;
											if(!isset($items->object_id)){
												//pree($story_img);
												$story_img = wp_remote_get($story_img);
												$story_img = wp_remote_retrieve_body($story_img);
												$story_img = json_decode($story_img);
												
												if(isset($story_img->object_id))
												$object_id = $story_img->object_id;
											}
											
											$media = '<img alt="'.$items->story.'" src="'.str_replace('ITEM-ID', $object_id, $img_path).'" />';
										break;
										case 'video':
											$media = '<iframe src="https://web.facebook.com/plugins/video.php?href='.$atts[0].'&show_text=0&height=315" width="auto" height="315" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe>';
										break;
										
									}
									
									//pree($media);
									
									
									$temp_desc = $this->clean_xhtml($description);
									$temp_desc = substr($temp_desc, 0, 10);
									//pree($temp_desc);
									$desc_final = $this->string_limit_words($description, $feed_words);
									$desc_final = ($desc_final!=''?$desc_final.'...':'');
									//pree($desc_final);
									$description = '<div class="feed_img">'.str_replace($temp_desc, ($media!=''?'_IMG_URL_':'').'</div><div class="text_div">'.$temp_desc, $desc_final).'</div>';
									
									//pree($description);
									$content = ((!$this->is_url($description) && $description!='')?str_replace('_IMG_URL_', $media, $description):'');	
									
									
								break;
								
								case 'video_only':	
									if($items->type=='video')
									$content .= '<iframe src="https://web.facebook.com/plugins/video.php?href='.$atts[0].'&show_text=0&width=560" width="560" height="315" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe>';
									
								break;
								
								case 'image_only':	
									$media = '';
									switch($items->type){
										case 'photo':
											$object_id = $items->object_id;
											
											if(!isset($items->object_id)){
												//pree($story_img);
												$story_img = wp_remote_get($story_img);
												$story_img = wp_remote_retrieve_body($story_img);
												$story_img = json_decode($story_img);
												
												if(isset($story_img->object_id))
												$object_id = $story_img->object_id;
											}
											
											$media = '<img alt="'.$items->story.'" src="'.str_replace('ITEM-ID', $object_id, $img_path).'" />';
										break;
										case 'video':
											$media = '<iframe src="https://web.facebook.com/plugins/video.php?href='.$atts[0].'&show_text=0&height=315" width="auto" height="315" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe>';
										break;
										
									}
									
									
									$content .= $media;
									
								break;
							}
							
							
							if($img!=''){			
										   
								$content .= str_replace('_LINK_TEXT_', $img, $link);						
							}
						   
							
							$html_arr[$unique_key][] = ($content!=''?'<li>'.$content.'</li>':'');

						  }
					  }
					}
				}				
							
			}else{
					
				if($this->rfw_cache()>0)
				add_filter( 'wp_feed_cache_transient_lifetime' , array($this, 'rfw_cache') );
				
				$rss = fetch_feed($rss_url);

			 
			 	if($this->rfw_cache()>0)
				remove_filter( 'wp_feed_cache_transient_lifetime' , array($this, 'rfw_cache') );
					
					
				 if ( ! is_wp_error( $rss ) ){
					 
					$maxitems = $rss->get_item_quantity( $limit_feeds );  
					
					$rss_items = $rss->get_items( 0, $maxitems );
					
					$rfw_mutes = get_option('rfw_mutes', '');
					
					if ( $maxitems > 0 ){
						
						
				   		//pree($rss_items);
						foreach ( $rss_items as $item ){
							
							//pree($item);exit;
							
							
							switch($content_sort){
								default:
								
									$unique_key = strtolower(substr($item->get_title(), 0, 1));
							
								break;
								
								case 'date':
									$unique_key = strtotime($item->get_date());
								break;
							}
							//pree($unique_key);
														
							
							$html_arr[$unique_key][]='<li>';
							
							$img = '';
							
							$link = '<a href="'.esc_url( $item->get_permalink() ).'" target="_blank" rel="nofollow">_LINK_TEXT_</a>';
							
							$html_arr[$unique_key][]= $show_feed_title?str_replace('_LINK_TEXT_', '<h3 class="entry-title rfw2">'.esc_html( $item->get_title() ).'</h3>', $link):'';
							
							$description = $item->get_description();	
							
							if($rfw_mutes!=''){
								$mute_arr = nl2br($rfw_mutes);
								$mute_arr = explode('<br />', $mute_arr);
								$mute_arr = array_filter($mute_arr, 'trim');
								$mute_arr = array_filter($mute_arr, 'strlen');
								if(!empty($mute_arr)){		
									$ma = array();
									foreach($mute_arr as $mute_str){
										$ma[] = trim($mute_str);
									}
									$description = str_replace($ma, '', $description);		
								}
							}
													
							$enclosure_images = array();
												
							if ($enclosure = $item->get_enclosure())
							{								
								if(isset($enclosure->type) && substr($enclosure->type, 0, strlen('image'))=='image'){
									$enclosure_images[] = $enclosure->link;
								}elseif(isset($enclosure->thumbnails) && !empty($enclosure->thumbnails)){
									$enclosure_images = $enclosure->thumbnails;
									//pree($enclosure->thumbnails);
								}
							}	
							//pree($enclosure_images);
							//pree($item->get_enclosure());
							//exit;						
							//pree($display_type);
							
							switch($display_type){
								
								case 'image_only':						
								
									
									//preg_match_all('/<img[^>]+>/i', $description, $img); 
									//
									//preg_match_all('/src="([^"]*)"/', $description, $img); 
									preg_match_all('@<img.*src="([^"]*)"[^>/]*/?>@Ui', $description, $img); 
									
									
									if(!empty($img)){
										
										$img = end($img);
										
										if(isset($img[0]) && $img[0]!=''){
											$img = $img[0];
											
											$img = current(explode('?', $img));
											
											
											
										}else{
											$img = '';
										}
										
									}
									if($img == '' && !empty($enclosure_images)){
										$img = current($enclosure_images);
									}
									
									
								break;		
			
								
								case 'text_only':	
									$content_str = (!$this->is_url($description)?($description!=''?'<div class="text_div">'.$this->string_limit_words($this->clean_xhtml($description), $feed_words).' ...</div>':''):'');
														
									//$html_arr[$unique_key][]= $content_str;							
								break;
								
								default:
									preg_match_all('@<img.*src="([^"]*)"[^>/]*/?>@Ui', $description, $chk_img); 
									$chk_img = $this->rfw_filter_recursive($chk_img);
									//pree($chk_img);exit;
									
									$temp_description = $this->clean_xhtml($description);
									//pree($temp_desc);
									$temp_desc = substr($temp_description, 0, 10);
									
									$description = '<div class="feed_img">'.str_replace($temp_desc, '</div><div class="text_div">'.$temp_desc, $this->string_limit_words($temp_description, $feed_words)).'...</div>';
									
									$content_str = (!$this->is_url($description)?($description!=''?$description:''):'');	
									
									if($img==''){
									
										if(!empty($chk_img)){											
											$img = $this->rfw_image_size($chk_img);											
										}
										if(!empty($enclosure_images)){
											$img = current($enclosure_images);											
										}
									}
									
									
								break;
								
							}
						   
						   
							//pree($img);
						   
							if($img!=''){
							   
								$html_arr[$unique_key][]= str_replace('_LINK_TEXT_', '<div class="image_only imgn"><img src="'.$img.'" /></div>', $link);
							
							}
							
							$html_arr[$unique_key][]= $content_str;	
							$html_arr[$unique_key][]= '</li>';
						  
						}
					}
				 }			
			}


		 
		 if(is_array($html_arr) && !empty($html_arr)){
			switch($content_order){
			
				 case 'aa':
				 case 'date_aa':
					ksort($html_arr); 
				 break;
				
				 case 'ad':
				 case 'date_ad':
					krsort($html_arr); 
				 break;
			
			}
			 
			//pree($html_arr);exit;
			 
			foreach($html_arr as $alphabet=>$feeds){
		 		$html_ul .= implode(' ', $feeds);
			}
		 
		 }
		 $html .= $html_ul.'</ul></nav></aside>';
		 echo $html;
	
	}
		
	private function rfw_image_size($chk_img){
		
		$ret = '';
		$img_size = (is_numeric($this->img_size)?$this->img_size-1:$this->img_size);
		//pree($img_size);
		switch($img_size){
			
			case ($img_size>=1):
				
				$ret = (isset($chk_img[1]) && !empty($chk_img[1]) && isset($chk_img[1][$img_size]))?$chk_img[1][$img_size]:current($chk_img[1]);
			break;
			
			default:
			
				$ret = (isset($chk_img[1]) && !empty($chk_img[1]))?current($chk_img[1]):'';
			
			break;
			
			case 'large':
				//pree($this->rfw_img_size);
				$ret = $this->rfw_image_size_inner($chk_img[1]);
				
			break;
		}
		
		return $ret;
	}
	
	private function rfw_image_size_inner($chk_img){
		$ret = end($chk_img);
		if(function_exists('getimagesize')){			
			$larger = 0;
			foreach($chk_img as $ci){
				list($originalWidth, $originalHeight) = getimagesize($ci);
				//$ratio = ($originalWidth / $originalHeight);
				//$ret[$ratio] = $ci;										
				if(($originalWidth*$originalHeight)>$larger){
					$larger = $originalWidth*$originalHeight;
					$ret = $ci;
				}else{
					
				}
			}	
		}
		return $ret;
	}	
	private function rfw_filter_recursive($array) {
	   foreach ($array as $key => &$value) {
		  if (empty($value)) {
			 unset($array[$key]);
		  }
		  else {
			 if (is_array($value)) {
				$value = $this->rfw_filter_recursive($value);
				if (empty($value)) {
				   unset($array[$key]);
				}
			 }
		  }
	   }
	
	   return $array;
	}

	private function string_limit_words($string, $word_limit)
	{
	  $words = explode(' ', $string, ($word_limit + 1));
	  if(count($words) > $word_limit)
	  array_pop($words);
	  return implode(' ', $words);
	}
	
 	public function form( $instance ) {
		// outputs the options form on admin
		global $rfw_chameleon_installed, $rfw_chameleon_activated;
		
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$rss_url     = isset( $instance['rss_url'] ) ? esc_attr( $instance['rss_url'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 6;
		$speed    = isset( $instance['speed'] ) ? absint( $instance['speed'] ) : '';
		
		$show_feed_title = isset( $instance['show_feed_title'] ) ? esc_attr( $instance['show_feed_title'] ) : true;
		$feed_words    = isset( $instance['feed_words'] ) ? absint( $instance['feed_words'] ) : 60;
		$content_display    = isset( $instance['content_display'] ) ? $instance['content_display'] : 'default';
		$content_order    = isset( $instance['content_order'] ) ? $instance['content_order'] : 'default';
		$content_height    = isset( $instance['content_height'] ) ? $instance['content_height'] : '';
		$rfw_cache    = isset( $instance['rfw_cache'] ) ? $instance['rfw_cache'] : '';		
		$list_type    = isset( $instance['list_type'] ) ? $instance['list_type'] : '';
		$img_size    = isset( $instance['img_size'] ) ? $instance['img_size'] : '';
		
		
		
		?>
        
        <div class="rwf-required">        
        <strong>Required</strong>
        <p class="rss_url_area"><label for="<?php echo $this->get_field_id( 'rss_url' ); ?>"><?php _e('Enter the RSS feed URL or Facebook Page ID here: <a title="Click here for examples" target="_blank" href="http://www.tutorsloop.net/app/live.php?id=4330772">(What\'s this?)</a>&nbsp;|&nbsp;<a target="_blank" href="https://plugins.svn.wordpress.org/rss-feed-widget/assets/styling-guide.pdf" title="Click here for styling guide">Styling</a>&nbsp;|&nbsp;<a target="_blank" href="https://plugins.svn.wordpress.org/rss-feed-widget/assets/basic-guide.pdf" title="Click here for guide">Help</a>'); ?></label>
	<input placeholder="Facebook Page ID or RSS Feed URL here..." class="widefat" id="<?php echo $this->get_field_id( 'rss_url' ); ?>" name="<?php echo $this->get_field_name( 'rss_url' ); ?>" type="text" value="<?php echo esc_attr( $rss_url ); ?>" />
    	<?php
		$url = 'plugin-install.php?tab=search&s=chameleon';
		$tip = 'Use WordPress Plugin Chameleon for Styles';
		if($rfw_chameleon_installed){
			$url = 'plugins.php?s=chameleon&plugin_status=inactive';
			$tip = 'Activate WordPress Plugin Chameleon';
			if($rfw_chameleon_activated){				
				$url = 'options-general.php?page=rfw_options';
				$tip = 'Select Styles from Chameleon';
			}
		}
		?>
    	<a title="<?php echo $tip; ?>" href="<?php echo $url; ?>" target="_blank">Click here for styles</a></p>
    	</div>
    	
		<div class="rwf-layout">   
        
        <strong>Optional</strong> 
  		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (optional):' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p><label for="<?php echo $this->get_field_id( 'content_display' ); ?>"><?php _e( 'Display type:' ); ?></label>
        <select id="<?php echo $this->get_field_id( 'content_display' ); ?>" name="<?php echo $this->get_field_name( 'content_display' ); ?>">
        	<option value="default" <?php echo $content_display=='default'?'selected="selected"':''; ?>>Default</option>
            <option value="text_only" <?php echo $content_display=='text_only'?'selected="selected"':''; ?>>Text Only</option>
            <option value="image_only" <?php echo $content_display=='image_only'?'selected="selected"':''; ?>>Image Only</option>
        </select></p>
                <p><label for="<?php echo $this->get_field_id( 'show_feed_title' ); ?>"><?php _e( 'Display feed title (Yes/No):' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'show_feed_title' ); ?>" name="<?php echo $this->get_field_name( 'show_feed_title' ); ?>" type="checkbox" value="true" <?php echo $show_feed_title?'checked="checked"':''; ?>  /></p>
        
        <p><label for="<?php echo $this->get_field_id( 'feed_words' ); ?>"><?php _e( 'No. of feed words to show:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'feed_words' ); ?>" name="<?php echo $this->get_field_name( 'feed_words' ); ?>" type="text" value="<?php echo $feed_words; ?>" size="3" /></p>
        
        <p><label for="<?php echo $this->get_field_id( 'content_height' ); ?>"><?php _e( 'Widget Height:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'content_height' ); ?>" name="<?php echo $this->get_field_name( 'content_height' ); ?>" type="text" value="<?php echo $content_height; ?>" size="6" /><small><br />
e.g. 400px<br />
        <?php _e( 'Leave empty for auto.' ); ?></small>
        </p>
        <p><label for="<?php echo $this->get_field_id( 'content_order' ); ?>"><?php _e( 'Sort order:' ); ?></label>
        <select id="<?php echo $this->get_field_id( 'content_order' ); ?>" name="<?php echo $this->get_field_name( 'content_order' ); ?>">
        	<option value="default" <?php echo $content_order=='default'?'selected="selected"':''; ?>>Default</option>
            <option value="aa" <?php echo $content_order=='aa'?'selected="selected"':''; ?>>Alphabetical Ascending</option>
            <option value="ad" <?php echo $content_order=='ad'?'selected="selected"':''; ?>>Alphabetical Descending</option>
            <option value="date_aa" <?php echo $content_order=='date_aa'?'selected="selected"':''; ?>>Date Ascending</option>
            <option value="date_ad" <?php echo $content_order=='date_ad'?'selected="selected"':''; ?>>Date Descending</option>
        </select></p>
        
        <p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of feeds to show:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
        
		</div>
        
        <div class="rwf-advance">
        <strong>Advance</strong>
        <p><label for="<?php echo $this->get_field_id( 'speed' ); ?>"><?php _e( 'Transition Speed:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'speed' ); ?>" name="<?php echo $this->get_field_name( 'speed' ); ?>" type="text" value="<?php echo $speed; ?>" size="4" /></p>
        
        <p><label for="<?php echo $this->get_field_id( 'list_type' ); ?>"><?php _e( 'List Type:' ); ?></label>
		 <select id="<?php echo $this->get_field_id( 'list_type' ); ?>" name="<?php echo $this->get_field_name( 'list_type' ); ?>">
        	<option value="slider" <?php echo $list_type=='slider'?'selected="selected"':''; ?>>Slider</option>
            <option value="list" <?php echo $list_type=='list'?'selected="selected"':''; ?>>List Only</option>
        </select>        
       </p>
        
		<p><label for="<?php echo $this->get_field_id( 'img_size' ); ?>"><?php _e( 'Image Pick:' ); ?></label>
		 <select id="<?php echo $this->get_field_id( 'img_size' ); ?>" name="<?php echo $this->get_field_name( 'img_size' ); ?>">
        	<option value="small" <?php echo $img_size=='small'?'selected="selected"':''; ?>>Default</option>
            <option value="1" <?php echo $img_size==1?'selected="selected"':''; ?>>First Image</option>
           	<option value="2" <?php echo $img_size==2?'selected="selected"':''; ?>>Second Image</option>
            
            <option value="large" <?php echo $img_size=='large'?'selected="selected"':''; ?>>Large Image (Slow Loading)</option>
        </select>        
       </p>
                
        <p><label for="<?php echo $this->get_field_id( 'rfw_cache' ); ?>"><?php _e( 'Cache Period:' ); ?></label>
		<input id="<?php echo $this->get_field_id( 'rfw_cache' ); ?>" name="<?php echo $this->get_field_name( 'rfw_cache' ); ?>" type="text" value="<?php echo $rfw_cache; ?>" placeholder="Enter in seconds" size="20" /><small><br />
e.g. 7200 seconds = 2 hours<br />
        <?php _e( 'Leave empty or 0 for no cache.' ); ?></small>
        </p>
        </div>

		<?php 
	}

	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		
		$instance = array();
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['rss_url'] = trim(strip_tags($new_instance['rss_url']));
		$instance['number'] = (int) $new_instance['number'];
		$instance['speed'] = (int) $new_instance['speed'];
		
		$instance['show_feed_title'] = ($new_instance['show_feed_title']==true);
		$instance['feed_words'] = (int) $new_instance['feed_words'];
		$instance['content_display'] = strip_tags($new_instance['content_display']);
		$instance['content_order'] = strip_tags($new_instance['content_order']);
		$instance['content_height'] = strip_tags($new_instance['content_height']);
		$instance['list_type'] = strip_tags($new_instance['list_type']);
		$instance['img_size'] = strip_tags($new_instance['img_size']);

		return $instance;
		
	}
}
if(!function_exists('rfw_init')){
	function rfw_init(){
		 register_widget( 'rfw_dock' );
		}
	}
	
	function rfw_settings(){
		

		global $rfw_data;		
		//pree($rfw_data);exit;
		add_options_page($rfw_data['Name'].' ('.$rfw_data['Version'].') - Settings', $rfw_data['Name'].' ('.$rfw_data['Version'].')', 'manage_options', 'rfw_options', 'rfw_options_page');
		

	}
		
	function register_rfwsettings() {
		register_setting('rfw_settings_group', 'rfw_rss_image_size');
	}	
	
	function rfw_options_page(){
		include('inc/settings.php');
	}
		
	function rfw_featuredtoRSS($content) {
		$fir_rss_image_size = get_option('rfw_rss_image_size');
		global $post;
		if ( has_post_thumbnail( $post->ID ) ){
			$content = '' . get_the_post_thumbnail( $post->ID, $fir_rss_image_size, array( 'style' => 'float:left; margin:0 15px 15px 0;' ) ) . '' . $content;
		}
		return $content;
	}
	
	function rfw_styles_selection(){
		
		if(isset($_GET['page']) && $_GET['page']=='rfw_options' && !empty($_POST)){
		
		
			if (isset($_POST['rfw_style'])){
				if ( 
					! isset( $_POST['rfw_styles'] ) 
					|| ! wp_verify_nonce( $_POST['rfw_styles'], 'rfw_styles_act' ) 
				) {
				
				   print 'Sorry, your nonce did not verify.';
				   exit;
				
				} else {
					
					update_option('rfw_style', esc_attr($_POST['rfw_style']));
				   // process form data
				}
			
			}
			
				
			if (isset($_POST['rfw_mutes'])){
				//pree($_POST);
				if(
						! isset( $_POST['rfw_mutes_field'] ) 
					|| 
						! wp_verify_nonce( $_POST['rfw_mutes_field'], 'rfw_mutes_action' ) 
				
				) {
				
				   print 'Sorry, your nonce did not verify.';
				   exit;
				
				} else {
				
				   update_option('rfw_mutes', $_POST['rfw_mutes']);		
				}	
			}			
			
		}
		

			
	}
	add_action('admin_init', 'rfw_styles_selection');
	
	function rfw_plugin_links($links) { 
		global $rfw_premium_link, $rfw_pro;
		
		$settings_link = '<a href="options-general.php?page=rfw_options">Settings</a>';
		$guide_link_1 = '<a target="_blank" href="https://plugins.svn.wordpress.org/rss-feed-widget/assets/basic-guide.pdf">Help</a>';
		$guide_link_2 = '<a target="_blank" href="https://plugins.svn.wordpress.org/rss-feed-widget/assets/styling-guide.pdf">Styling</a>';
		
		if($rfw_pro){
			array_unshift($links, $settings_link, $guide_link_2, $guide_link_1); 
		}else{
			 
			$rfw_premium_link = '<a href="'.$rfw_premium_link.'" title="Go Premium" target=_blank>Go Premium</a>'; 
			array_unshift($links, $settings_link, $rfw_premium_link); 
		
		}
		
		
		return $links; 
	}	