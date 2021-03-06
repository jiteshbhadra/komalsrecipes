<?php
/**
Plugin Name: Table of content
Plugin Tag: plugin, table of content, toc, content
Description: <p>Insert a *table of content* in your posts. </p><p>You only have to insert the shortcode <code>[toc]</code> in your post to display the table of content. </p><p>Please note that you can also configure a text to be inserted before the title of you post such as <code>Chapter</code> or <code>Section</code> with numbers. </p><p>Plugin developped from the orginal plugin <a href="http://wordpress.org/plugins/toc-for-wordpress/">Toc for Wordpress</a>. </p><p>This plugin is under GPL licence. </p>
Version: 1.5.1
Author: SedLex
Author Email: sedlex@sedlex.fr
Framework Email: sedlex@sedlex.fr
Author URI: http://www.sedlex.fr/
Plugin URI: http://wordpress.org/plugins/content-table/
License: GPL3
*/

require_once('core.php') ; 

class tableofcontent extends pluginSedLex {
	
	var $tableofcontent_used_names ;
	var $niv2 ;
	var $niv3 ;
	var $niv4 ;
	var $niv5 ;
	var $niv6 ;

	/** ====================================================================================================================================================
	* Initialisation du plugin
	* 
	* @return void
	*/
	static $instance = false;

	protected function _init() {
		global $wpdb ; 
		
		// Configuration
		$this->pluginName = 'Table of content' ; 
		$this->tableSQL = "" ; 
		$this->table_name = $wpdb->prefix . "pluginSL_" . get_class() ; 
		$this->path = __FILE__ ; 
		$this->pluginID = get_class() ; 
		
		//Init et des-init
		register_activation_hook(__FILE__, array($this,'install'));
		register_deactivation_hook(__FILE__, array($this,'deactivate'));
		register_uninstall_hook(__FILE__, array('tableofcontent','uninstall_removedata'));

		//Paramètres supplementaires
		$this->tableofcontent_used_names = array();
		$this->niv2 = 1 ; 
		$this->niv3 = 1 ; 
		$this->niv4 = 1 ; 
		$this->niv5 = 1 ; 
		$this->niv6 = 1 ; 		
	}
	/**
	 * Function to instantiate our class and make it a singleton
	 */
	public static function getInstance() {
		if ( !self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	/** ====================================================================================================================================================
	* In order to uninstall the plugin, few things are to be done ... 
	* (do not modify this function)
	* 
	* @return void
	*/
	
	public function uninstall_removedata () {
		global $wpdb ;
		// DELETE OPTIONS
		delete_option('tableofcontent'.'_options') ;
		if (is_multisite()) {
			delete_site_option('tableofcontent'.'_options') ;
		}
		
		// DELETE SQL
		if (function_exists('is_multisite') && is_multisite()){
			$old_blog = $wpdb->blogid;
			$old_prefix = $wpdb->prefix ; 
			// Get all blog ids
			$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM ".$wpdb->blogs));
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				$wpdb->query("DROP TABLE ".str_replace($old_prefix, $wpdb->prefix, $wpdb->prefix . "pluginSL_" . 'tableofcontent')) ; 
			}
			switch_to_blog($old_blog);
		} else {
			$wpdb->query("DROP TABLE ".$wpdb->prefix . "pluginSL_" . 'tableofcontent' ) ; 
		}
		
		// DELETE FILES if needed
		//SLFramework_Utils::rm_rec(WP_CONTENT_DIR."/sedlex/my_plugin/"); 
		$plugins_all = 	get_plugins() ; 
		$nb_SL = 0 ; 	
		foreach($plugins_all as $url => $pa) {
			$info = pluginSedlex::get_plugins_data(WP_PLUGIN_DIR."/".$url);
			if ($info['Framework_Email']=="sedlex@sedlex.fr"){
				$nb_SL++ ; 
			}
		}
		if ($nb_SL==1) {
			SLFramework_Utils::rm_rec(WP_CONTENT_DIR."/sedlex/"); 
		}
	}
	
	/** ====================================================================================================================================================
	* Define the default option value of the plugin
	* 
	* @return variant of the option
	*/
	public function get_default_option($option) {
		switch ($option) {
			case 'html' 	: return "*<div class='toc tableofcontent'>
   <p id='title'>%title%</p>
   %toc%
</div>" 	; break ; 
			case 'css' 	: return "*.tableofcontent {
      border: 0px;
      border-left: 3px solid #D6341D ;
      padding: 5px;
      padding-left: 20px;
      padding-right: 20px;
      padding-bottom: 10px;
      font-size: 0.95em;
      min-width:200px;
      float:left ;
      margin: 0px;
      margin-left: 50px;
      line-height:1em;
}" ;break ; 
			case 'css_title' 	: return "*.tableofcontent p#title{
	text-align: center;
	font-size: 1.5em;
	font-weight: bold;
	margin : 3px ; 
	padding-top : 5px ;
	padding-bottom : 5px ;
        color:#D6341D ;
}
.tableofcontent p{
	margin-bottom:0px !important;
	margin-top:0px !important;
}" 	; break ; 
			case 'padding' 	: return 20 	; break ; 
			case 'title' 	: return "Table Of Content" 	; break ; 
			case 'h2' 		: return "Chapter #2." 			; break ; 
			case 'h3' 		: return "Section #3." 			; break ; 
			case 'h4' 		: return "#4)" 					; break ; 
			case 'h5' 		: return "#4.#5." 				; break ; 
			case 'h6' 		: return "" 					; break ; 
			case 'modify_without_toc' 		: return true 					; break ; 
			case 'style_h2' 		: return "font-weight:bold; size:14px;" 			; break ; 
			case 'style_h3' 		: return "" 			; break ; 
			case 'style_h4' 		: return "" 					; break ; 
			case 'style_h5' 		: return "" 				; break ; 
			case 'style_h6' 		: return "" 					; break ; 
			case 'entry_max_font_size' 		: return 14 					; break ; 
			case 'entry_min_font_size' 		: return 10 					; break ; 
			case 'entry_max_color' 		: return "#000000" 					; break ; 
			case 'entry_min_color' 		: return "#555555" 					; break ; 
			case 'first_level' 		: return 2 					; break ; 
			case 'excluded_class' 		: return ""					; break ; 
		}
		return null ;
	}

	/** ====================================================================================================================================================
	* Add a button in the TinyMCE Editor
	*
	* To add a new button, copy the commented lines a plurality of times (and uncomment them)
	* 
	* @return array of buttons
	*/
	
	function add_tinymce_buttons() {
		$buttons = array() ; 
		$buttons[] = array(__('Display the table of content', $this->pluginID), '[toc]', '', plugin_dir_url("/").'/'.str_replace(basename( __FILE__),"",plugin_basename( __FILE__)).'img/toc.png') ; 
		return $buttons ; 
	}
	
	/** ====================================================================================================================================================
	* Init css for the public side
	* If you want to load a style sheet, please type :
	*	<code>$this->add_inline_css($css_text);</code>
	*	<code>$this->add_css($css_url_file);</code>
	*
	* @return void
	*/
	
	function _public_css_load() {	
	
		$css = $this->get_param('css') ; 
		$css .= "\r\n".$this->get_param('css_title') ; 
		// Add style for h2
		$list_nb = array(2, 3, 4, 5, 6) ; 
		foreach ($list_nb as $nb) {
			$font_size = floor($this->get_param('entry_max_font_size')-($this->get_param('entry_max_font_size')-$this->get_param('entry_min_font_size'))/4*($nb-2)) ; 

			$r2 = hexdec(substr($this->get_param('entry_min_color'), 1, 2)) ; 
  			$g2 = hexdec(substr($this->get_param('entry_min_color'), 3, 2)) ; 
  			$b2 = hexdec(substr($this->get_param('entry_min_color'), 5, 2)) ; 
  			
			$r1 = hexdec(substr($this->get_param('entry_max_color'), 1, 2)) ; 
  			$g1 = hexdec(substr($this->get_param('entry_max_color'), 3, 2)) ; 
  			$b1 = hexdec(substr($this->get_param('entry_max_color'), 5, 2)) ; 
			
			$r3 = floor($r1 - ($r1-$r2)/4*($nb-2) ) ; 
			$g3 = floor($g1 - ($g1-$g2)/4*($nb-2) ) ; 
			$b3 = floor($b1 - ($b1-$b2)/4*($nb-2) ) ; 
			$color = "#".str_pad(dechex($r3), 2, '0', STR_PAD_LEFT).str_pad(dechex($g3), 2, '0', STR_PAD_LEFT).str_pad(dechex($b3), 2, '0', STR_PAD_LEFT);

			$css .= "\r\n" ; 
			$css .= ".contentTable_h".$nb." {\r\n" ; 
			$css .= "   font-size:".$font_size."px;\r\n" ; 
			$css .= "   line-height:".$font_size."px;\r\n" ; 
			$css .= "   padding-left:".($this->get_param('padding')*($nb-2))."px;\r\n" ; 
			$css .= str_replace(";", ";\r\n   ",$this->get_param('style_h'.$nb)) ; 
			$css .= "\r\n}\r\n" ; 
			$css .= "\r\n" ; 
			$css .= ".contentTable_h".$nb." a:link, .contentTable_h".$nb." a:visited, .contentTable_h".$nb." a:hover {\r\n" ; 
			$css .= "   color:".$color.";\r\n" ; 
			$css .= "}\r\n" ; 
		}
		
		$this->add_inline_css($css) ; 
	}
	
	/** ====================================================================================================================================================
	* The configuration page
	* 
	* @return void
	*/
	
	public function configuration_page() {
		global $wpdb;
	
		?>
		<div class="plugin-titleSL">
			<h2><?php echo $this->pluginName ?></h2>
		</div>
		
		<div class="plugin-contentSL">		
			<?php echo $this->signature ; ?>
			
		<?php
	
			$this->check_folder_rights( array() ) ; 
			
			//==========================================================================================
			//
			// Mise en place du systeme d'onglet
			//
			//==========================================================================================

			$tabs = new SLFramework_Tabs() ; 
			
			ob_start() ; 
				$params = new SLFramework_Parameters($this, "tab-parameters") ; 
				$params->add_title(__('General',$this->pluginID)) ; 
				$params->add_param('title', __('Title of the table of content:',$this->pluginID)) ; 
				$params->add_param('first_level', __('What is the first level?',$this->pluginID)) ; 
				$params->add_comment(sprintf(__('If you set this option to %s, then the first level would be %s!',$this->pluginID), "<code>2</code>", "<code>&lt;h2&gt;</code>")) ; 
				
				$params->add_title(__('Add prefix in your title:',$this->pluginID)) ; 
				$params->add_param('h2', __('Prefix of the first level:',$this->pluginID)) ; 
				$params->add_comment(__('If you leave the field blank, nothing will be added!',$this->pluginID).'<br/>'.sprintf(__('Note that if you want to display the number of level 2, just write %s ...',$this->pluginID),"<i>#2</i>")) ; 
				$params->add_param('h3', __('Prefix of the second level:',$this->pluginID)) ; 
				$params->add_param('h4', __('Prefix of the third level:',$this->pluginID)) ; 
				$params->add_param('h5', __('Prefix of the fourth level:',$this->pluginID)) ; 
				$params->add_param('h6', __('Prefix of the fifth level:',$this->pluginID)) ; 
				$params->add_param('modify_without_toc', sprintf(__('Modify the title even if the %s is not present in the article:',$this->pluginID),"<code>[toc]</code>")) ; 
				
				$params->add_title(__('Customize the global visual appearance:',$this->pluginID)) ; 
				$params->add_param('html', __('The HTML:',$this->pluginID)) ; 
				$params->add_comment(__('The default HTML is:',$this->pluginID)) ; 
				$params->add_comment_default_value('html') ; 
				$params->add_comment(sprintf(__('Please note that %s will be replaced with the given title of the table of content and %s will be replaced with the current chapter/section/etc. title', $this->pluginID) , "<code>%title%</code>", "<code>%toc%</code>") ) ; 
				$params->add_param('css', __('The CSS:',$this->pluginID)) ; 
				$params->add_comment(__('The default CSS is:',$this->pluginID)) ; 
				$params->add_comment_default_value('css') ; 
				
				$params->add_title(__('Customize the visual appearance of the title:',$this->pluginID)) ; 
				$params->add_param('css_title', __('The CSS:',$this->pluginID)) ; 
				$params->add_comment(__('The default CSS for title is:',$this->pluginID)) ; 
				$params->add_comment_default_value('css_title') ; 
				
				$params->add_title(__('Customize the visual appearance of each entry in the TOC:',$this->pluginID)) ; 
				$params->add_param('padding', __('The indentation of the TOC (in pixels):',$this->pluginID)) ; 
				$params->add_param('entry_max_font_size', __('The max font size:',$this->pluginID)) ; 
				$params->add_param('entry_min_font_size', __('The max font size:',$this->pluginID)) ; 
				$params->add_param('entry_max_color', __('The color of the upper level:',$this->pluginID)) ; 
				$params->add_param('entry_min_color', __('The color of the lower level:',$this->pluginID)) ; 
				$params->add_comment(__('The color of entry will be a transition color between these two colors (depending of their levels).', $this->pluginID)."<br/> ".sprintf(__('Please add the # character before the code. If you do not know what code to use, please visit this website: %s',$this->pluginID),"<a href='http://html-color-codes.info/'>http://html-color-codes.info/</a>")) ; 
				
				$params->add_title(__('Customize the visual appearance of each entry in the TOC (for Experts):',$this->pluginID)) ; 
				$params->add_param('style_h2', __('The CSS style of the first level:',$this->pluginID)) ; 
				$params->add_comment(sprintf(__('For instance, %s',$this->pluginID),"<code>font-weight:bold; size:12px</code>")) ; 
				$params->add_param('style_h3', __('The CSS style of the second level:',$this->pluginID)) ; 
				$params->add_param('style_h4', __('The CSS style of the third level:',$this->pluginID)) ; 
				$params->add_param('style_h5', __('The CSS style of the fourth level:',$this->pluginID)) ; 
				$params->add_param('style_h6', __('The CSS style of the fifth level:',$this->pluginID)) ; 
				
				$params->add_title(__('Advanced options:',$this->pluginID)) ; 
				$params->add_param('excluded_class', __('Coma separated list of class headers to be excluded:',$this->pluginID)) ; 
				$params->add_comment(sprintf(__('For instance, to exclude the headers like %s or %s, you may type %s',$this->pluginID),"<code>&lt;h3 class='foo'&gt;</code>","<code>&lt;h3 class='bar'&gt;</code>","<code>foo,bar</code>")) ; 
				
				$params->flush() ; 
			$tabs->add_tab(__('Parameters',  $this->pluginID), ob_get_clean() , plugin_dir_url("/").'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_param.png") ; 	
			

			
			ob_start() ;
				echo "<p>".__("This table enables the display of a list of all titles of the page/post to create a table of contents.", $this->pluginID)."</p>" ;
			$howto1 = new SLFramework_Box (__("Purpose of that plugin", $this->pluginID), ob_get_clean()) ; 
			ob_start() ;
				echo "<p>".sprintf(__("If you want that the table of contents appears in your post, just type: %s, that is all!", $this->pluginID)," <code>[toc]</code>")."</p>" ;
				echo "<p>".__("If no shortcode is in the page/post, no table of contents will be displayed.", $this->pluginID)."</p>" ;
				echo "<p>".__("A button is available in the post/page editor to ease the display.", $this->pluginID)."</p>" ;
			$howto2 = new SLFramework_Box (__("How to display the table of contents", $this->pluginID), ob_get_clean()) ; 
			ob_start() ;
				echo "<p>".sprintf(__("In addition, you may modify dynamically your title in order to add before them %s or anystring you want.", $this->pluginID), "<code>Chapter 2.3</code>")."</p>" ;
				echo "<p>".__("Numbering is possible.", $this->pluginID)."</p>" ;
			$howto3 = new SLFramework_Box (__("Modification of the titles", $this->pluginID), ob_get_clean()) ; 
			ob_start() ;
				 echo $howto1->flush() ; 
				 echo $howto2->flush() ; 
				 echo $howto3->flush() ; 
			$tabs->add_tab(__('How To',  $this->pluginID), ob_get_clean() , plugin_dir_url("/").'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_how.png") ; 				

			ob_start() ; 
				$plugin = str_replace("/","",str_replace(basename(__FILE__),"",plugin_basename( __FILE__))) ; 
				$trans = new SLFramework_Translation($this->pluginID, $plugin) ; 
				$trans->enable_translation() ; 
			$tabs->add_tab(__('Manage translations',  $this->pluginID), ob_get_clean() , plugin_dir_url("/").'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_trad.png") ; 	

			ob_start() ; 
				$plugin = str_replace("/","",str_replace(basename(__FILE__),"",plugin_basename( __FILE__))) ; 
				$trans = new SLFramework_Feedback($plugin, $this->pluginID) ; 
				$trans->enable_feedback() ; 
			$tabs->add_tab(__('Give feedback',  $this->pluginID), ob_get_clean() , plugin_dir_url("/").'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_mail.png") ; 	
			
			ob_start() ; 
				$trans = new SLFramework_OtherPlugins("sedLex", array('wp-pirates-search')) ; 
				$trans->list_plugins() ; 
			$tabs->add_tab(__('Other plugins',  $this->pluginID), ob_get_clean() , plugin_dir_url("/").'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__))."core/img/tab_plug.png") ; 	
			
			echo $tabs->flush() ; 
			
			echo $this->signature ; ?>
		</div>
		<?php
	}

	/** ====================================================================================================================================================
	* Generate a unique name from a header
	* 
	* @return string the unique name
	*/	
	
	public function get_unique_name($heading) {		
		$n = $heading ; 

		$n = str_replace(" ", "_", strip_tags($n));
		$n = str_replace("'", "_", strip_tags($n));
		$n = str_replace("\"", "_", strip_tags($n));
		$n = preg_replace("/^[0-9]*?([A-Za-z0-9\-_]*)$/u", "$1", $n);
		
		return $n;
	}

	/** ====================================================================================================================================================
	* Call when meet "[toc]" in an article
	* 
	* @return string the replacement string
	*/	
	
	function shortcode_toc() {	
		$out = "</p>" ; 
		$out .= $this->get_param('html') ; 
		$out .= "<div class='tableofcontent-end'> </div><p>" ; 
		
		$out = str_replace('%title%', $this->get_param('title'), $out);
		
		//Ré-initialisation
		$this->niv2 = 1 ; 
		$this->niv3 = 1 ; 
		$this->niv4 = 1 ; 
		$this->niv5 = 1 ; 
		$this->niv6 = 1 ; 
		
		$out_toc = "" ; 
		// headings...
		if (isset($this->used_names)) {
			foreach($this->used_names as $i => $heading) {
				// We check if we have to add something here
				$add = "" ; 
				if ($heading['level']==$this->get_param('first_level')+0) {
					$add = $this->get_param('h2')." " ; 
					$add = preg_replace(array("/#2/","/#3/","/#4/","/#5/","/#6/"), array($this->niv2,$this->niv3,$this->niv4,$this->niv5,$this->niv6), $add) ; 
					$this->niv2 ++ ; 
					$this->niv3 = 1 ; 
					$this->niv4 = 1 ; 
					$this->niv5 = 1 ; 
					$this->niv6 = 1 ; 
				} else if ($heading['level']==$this->get_param('first_level')+1) {
					$add = $this->get_param('h3')." " ; 
					$add = preg_replace(array("/#2/","/#3/","/#4/","/#5/","/#6/"), array($this->niv2-1,$this->niv3,$this->niv4,$this->niv5,$this->niv6), $add) ; 
					$this->niv3 ++ ; 
					$this->niv4 = 1 ; 
					$this->niv5 = 1 ; 
					$this->niv6 = 1 ; 
				} else if ($heading['level']==$this->get_param('first_level')+2) {
					$add = $this->get_param('h4')." " ; 
					$add = preg_replace(array("/#2/","/#3/","/#4/","/#5/","/#6/"), array($this->niv2-1,$this->niv3-1,$this->niv4,$this->niv5,$this->niv6), $add) ; 
					$this->niv4 ++ ; 
					$this->niv5 = 1 ; 
					$this->niv6 = 1 ; 
				} else if ($heading['level']==$this->get_param('first_level')+3) {
					$add = $this->get_param('h5')." " ; 
					$add = preg_replace(array("/#2/","/#3/","/#4/","/#5/","/#6/"), array($this->niv2-1,$this->niv3-1,$this->niv4-1,$this->niv5,$this->niv6), $add) ; 
					$this->niv5 ++ ; 
					$this->niv6 = 1 ; 
				} else if ($heading['level']==$this->get_param('first_level')+4) {
					$add = $this->get_param('h6')." " ; 
					$add = preg_replace(array("/#2/","/#3/","/#4/","/#5/","/#6/"), array($this->niv2-1,$this->niv3-1,$this->niv4-1,$this->niv5-1,$this->niv6), $add) ; 
					$this->niv6 ++ ; 
				}		
				
				$out_toc .= "<p class='contentTable_h".($heading['level']-$this->get_param('first_level')+2)."'><a href=\"#" . $i. "\">" .trim($add. strip_tags($heading['value'])) . "</a></p>\n";
			}
		}
		
		$out = str_replace('%toc%', $out_toc , $out) ; 
		
		//Ré-initialisation
		$this->niv2 = 1 ; 
		$this->niv3 = 1 ; 
		$this->niv4 = 1 ; 
		$this->niv5 = 1 ; 
		$this->niv6 = 1 ; 
		 
		return $out;
	}

	/** ====================================================================================================================================================
	* Callback pour modifier les titres dans le content
	* 
	* @return void
	*/	
	
	
	function heading_anchor($match) {
	
	
		// To be excluded ?
		$classes = explode(',',$this->get_param('excluded_class')) ; 
		foreach ($classes as $c) {
			if (preg_match("/class='[^']*".addslashes($c)."[^']*'/u", $match[2])) {
				return $match[0] ; 
			} elseif (preg_match('/class="[^"]*'.str_replace('"','\\"',$c).'[^"]*"/u', $match[2])) {
				return $match[0] ; 
			}
		}
	
		$name = $this->get_unique_name($match[3]);
		
		if (isset($this->used_names[$name])) {
			$name = $name.rand(0,10000000) ; 
		}
		$this->used_names[$name] = array() ;
		
		$this->used_names[$name]['level'] = $match[1] ; 
		$this->used_names[$name]['value'] = $match[3] ; 
		
		// We check if we have to add something here
		$add = "" ; 
		if ($match[1]==($this->get_param('first_level')+0)."") {
			$add = $this->get_param('h2')." " ; 
			$add = preg_replace(array("/#2/","/#3/","/#4/","/#5/","/#6/"), array($this->niv2,$this->niv3,$this->niv4,$this->niv5,$this->niv6), $add) ; 
			$this->niv2 ++ ; 
			$this->niv3 = 1 ; 
			$this->niv4 = 1 ; 
			$this->niv5 = 1 ; 
			$this->niv6 = 1 ; 
		} else if ($match[1]==($this->get_param('first_level')+1)."") {
			$add = $this->get_param('h3')." " ; 
			$add = preg_replace(array("/#2/","/#3/","/#4/","/#5/","/#6/"), array($this->niv2-1,$this->niv3,$this->niv4,$this->niv5,$this->niv6), $add) ; 
			$this->niv3 ++ ; 
			$this->niv4 = 1 ; 
			$this->niv5 = 1 ; 
			$this->niv6 = 1 ; 
		} else if ($match[1]==($this->get_param('first_level')+2)."") {
			$add = $this->get_param('h4')." " ; 
			$add = preg_replace(array("/#2/","/#3/","/#4/","/#5/","/#6/"), array($this->niv2-1,$this->niv3-1,$this->niv4,$this->niv5,$this->niv6), $add) ; 
			$this->niv4 ++ ; 
			$this->niv5 = 1 ; 
			$this->niv6 = 1 ; 
		} else if ($match[1]==($this->get_param('first_level')+3)."") {
			$add = $this->get_param('h5')." " ; 
			$add = preg_replace(array("/#2/","/#3/","/#4/","/#5/","/#6/"), array($this->niv2-1,$this->niv3-1,$this->niv4-1,$this->niv5,$this->niv6), $add) ; 
			$this->niv5 ++ ; 
			$this->niv6 = 1 ; 
		} else if ($match[1]==($this->get_param('first_level')+4)."") {
			$add = $this->get_param('h6')." " ; 
			$add = preg_replace(array("/#2/","/#3/","/#4/","/#5/","/#6/"), array($this->niv2-1,$this->niv3-1,$this->niv4-1,$this->niv5-1,$this->niv6), $add) ; 
			$this->niv6 ++ ; 
		}
		
		$other_content = trim(preg_replace('/id="[^"]*"/u',"",preg_replace("/id='[^']*'/u","",$match[2]))) ; 
		
		return '<h'.$match[1].' id="' . $name . '" '.$other_content.'>' . trim($add . $match[3]) . '</h'.$match[1].'>';
	}

	/** ====================================================================================================================================================
	* Called when the content is displayed
	*
	* @param string $content the content which will be displayed
	* @param string $type the type of the article (e.g. post, page, custom_type1, etc.)
	* @param boolean $excerpt if the display is performed during the loop
	* @return string the new content
	*/
	
	function _modify_content($content, $type, $excerpt) {	
		
		//Ré-initialisation
		$this->niv2 = 1 ; 
		$this->niv3 = 1 ; 
		$this->niv4 = 1 ; 
		$this->niv5 = 1 ; 
		$this->niv6 = 1 ; 
		$shortcode = "toc" ;
		
		$out = $content ;  
		
		$this->used_names = array();
		if ($this->get_param('modify_without_toc')||preg_match("#\[$shortcode(.*?)?\](?:(.+?)?\[\/$shortcode\])?#iu", $content)) {
			$out = preg_replace_callback("#<h([1-6])([^>]*)>(.*?)</h[1-6]>#iu", array($this,"heading_anchor"), $out);
		}
		
		//Ré-initialisation
		$this->niv2 = 1 ; 
		$this->niv3 = 1 ; 
		$this->niv4 = 1 ; 
		$this->niv5 = 1 ; 
		$this->niv6 = 1 ; 
		
		// manually replace the shortcode
		$out = preg_replace("#\[$shortcode(.*?)?\](?:(.+?)?\[\/$shortcode\])?#iu", $this->shortcode_toc(), $out);

		return $out;
	}
	
}

$tableofcontent = tableofcontent::getInstance();

?>