<?php 
	global $rfw_data, $rfw_chameleon_installed, $rfw_chameleon_activated;

	$rfw_rss_image_size = get_option('rfw_rss_image_size');
	//pree($rfw_rss_image_size);
	if (empty($rfw_rss_image_size)){		
		update_option('rfw_rss_image_size', 'thumbnail');		
		$rfw_rss_image_size	= get_option('rfw_rss_image_size');		
	}
	
	$rfw_mutes = get_option('rfw_mutes');
	if (empty($rfw_mutes)){		
		update_option('rfw_mutes', '');		
		$rfw_mutes	= get_option('rfw_mutes');		
	}
	
?>	
<style type="text/css">
.form-table.noborder td, .form-table.noborder th{ border:none;}
.notice-warning, .update-nag{ display:none; }
</style>
<div class="wrap">
<h2><?php echo $rfw_data['Name'].' ('.$rfw_data['Version'].')'; ?> - Settings</h2><br/>

<table width="100%">
	<tr>
    	<td valign="top">          
        
        <table class="wp-list-table widefat fixed bookmarks">
            	<thead>
                <tr>
                	<th>Instructions</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                	<td>
                    	<ol>
                        	<li>Select image size which you want to use in your rss feeds.</li>
                            
                            <li>Save Changes</li>

							<li>That's it.</li>
                            
                            <li>If you still have any query visit my <a href="<?php echo $rfw_data['PluginURI']; ?>" target="_blank">website</a> and contact me.</li>
                            
                        </ol>
                        
                    </td>
                </tr>
                </tbody>
            </table>
            <br/>
            
            <br/>
        
        </td>
        <td width="15">&nbsp;</td>
        <td width="250" valign="top">
        	
            <br/>
            
            <br/>
            
            <br/>
        </td>
    </tr>
</table>



<table width="100%">
	<tr>
    	<td valign="top">          
        
        <table class="wp-list-table widefat fixed styles">
            	<thead>
                <tr>
                	<th>Styles</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                	<td>
                    
                    <?php 
						if($rfw_chameleon_installed){
							if($rfw_chameleon_activated){
								
								global $wpc_assets_loaded, $wpc_dir, $wpc_url;
								$styles = array();
								
								
						
					?>								
                    	<form method="post" action="">
                        <?php wp_nonce_field( 'rfw_styles_act', 'rfw_styles' ); ?>
                        
                        <?php
						if(!empty($wpc_assets_loaded) && array_key_exists('rfw', $wpc_assets_loaded) && !empty($wpc_assets_loaded['rfw'])){
							$rfw_style = get_option('rfw_style');
						?>
                        <input type="hidden" name="rfw_style" value="<?php echo $rfw_style; ?>" />
                        <ul>
                        <?php
							foreach($wpc_assets_loaded['rfw'] as $name=>$data){
						?>
                        	<li <?php echo ($rfw_style==$name?'class="selected"':''); ?> title="<?php echo $name; ?>" data-id="<?php echo $name; ?>"><img src="<?php echo str_replace($wpc_dir, $wpc_url, $data['images']['screenshot']); ?>" alt="<?php echo $name; ?>" /><span><?php echo ucwords($name); ?></span></li>
								
						<?php
                            }
						?>
                        </ul>
                        <div style="float:left; width:100%;">
                        <input type="submit" value="Apply Style" class="button-primary" />
                        </div>
                        
                        <?php
						}else{
						?>
                        No styles found.
						<?php							
						}
						?>
                    	
                        	
                            
                        </form>
					<?php
							}else{
					?>
                    		Wow, you have installed <a href="https://downloads.wordpress.org/plugin/chameleon.zip" target="_blank">Chameleon</a> already. <a href="plugins.php?s=chameleon&plugin_status=inactive" target="_blank">Click here</a> to activate styles for <?php echo $rfw_data['Name']; ?>.
                    <?php								
							}
						}else{
					?>
                    		Good news, now you can install <a href="https://downloads.wordpress.org/plugin/chameleon.zip" target="_blank">Chameleon</a> to get awesome styles for for <?php echo $rfw_data['Name']; ?>.
                    <?php								
						}
					?>						
                   
                        
                    </td>
                </tr>
                </tbody>
            </table>
            <br/>
            
            <br/>
        
        </td>
        <td width="15">&nbsp;</td>
        <td width="250" valign="top">
        	
            <br/>
            
            <br/>
            
            <br/>
        </td>
    </tr>
</table>



<table width="100%">
	<tr>
    	<td valign="top">          
        
        <table class="wp-list-table widefat fixed bookmarks">
            	<thead>
                <tr>
                	<th>Filter RSS Feeds</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                	<td>
                    <form method="post" action="">
                    	<?php wp_nonce_field( 'rfw_mutes_action', 'rfw_mutes_field' ); ?>
                    	<textarea name="rfw_mutes" style="width:100%; height:200px"><?php echo $rfw_mutes; ?></textarea>
                        <br />
						<p>Enter text/words/sentences which you want to filter or mute. One per line.</p>
                    
					

                        <input type="submit" name="submit-bpu" class="button-primary" value="<?php _e('Save Changes') ?>" />
                    </form>
                        
                    </td>
                </tr>
                </tbody>
            </table>
            <br/>
            
            <br/>
        
        </td>
        <td width="15">&nbsp;</td>
        <td width="250" valign="top">
        	
            <br/>
            
            <br/>
            
            <br/>
        </td>
    </tr>
</table>


<table width="100%">
	<tr>
    	<td valign="top">          
<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th>Select Image Size For Rss Feed</th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
			<form method="post" action="options.php">
			<?php settings_fields( 'rfw_settings_group' ); ?>
            <table class="form-table noborder">
                <tr valign="top">
                    <th scope="row">Image Size</th>
                    <td>
                        
                        <?php $image_sizes = get_intermediate_image_sizes(); ?>
                        <select name="rfw_rss_image_size">
                          <?php foreach ($image_sizes as $size_name => $size_attrs): //var_dump($size_attrs);?>
                            <option value="<?php echo $size_attrs ?>" <?php echo $rfw_rss_image_size == $size_attrs?'selected="selected"':''; ?>><?php echo ucwords(str_replace(array('-','_'),' ',$size_attrs)); ?></option>                    
                          <?php endforeach; ?>
                          <option value="full" <?php echo $rfw_rss_image_size == 'full'?'selected="selected"':''; ?>>Full Size</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">&nbsp;</th>
                    <td bordercolor="red">
                        <input type="submit" name="submit-bpu" class="button-primary" value="<?php _e('Save Changes') ?>" />
                    </td>
                </tr>
               
            </table><br />
<br />

            <p><?php echo $rfw_data['Description']; ?></p>
        </form><br />

            
</td>

</tr>
</tbody>
</table>
</td>
     <td width="15">&nbsp;</td>
        <td width="250" valign="top">
        	
            <br/>
            
            <br/>
            
            <br/>
        </td>
    </tr>
</table>
</div>        