<?php


if(!function_exists('avia_find_layersliders'))
{
	function avia_find_layersliders($names_only = false)
	{
		// Get WPDB Object
	    global $wpdb;
	 
	    // Table name
	    $table_name = $wpdb->prefix . "layerslider";
	 
	    // Get sliders
	    $sliders = $wpdb->get_results( "SELECT * FROM $table_name WHERE flag_hidden = '0' AND flag_deleted = '0' ORDER BY date_c ASC LIMIT 300" );
	 	
	 	if(empty($sliders)) return;
	 	
	 	if($names_only)
	 	{
	 		$new = array();
	 		foreach($sliders as $key => $item) 
		    {
		    	if(empty($item->name)) $item->name = __("(Unnamed Slider)","avia_framework");
		       $new[$item->name] = $item->id;
		    }
		    
		    return $new;
	 	}
	 	
	 	return $sliders;
	}
}


if(!function_exists('avia_layerslider_remove_setup_fonts'))
{
	add_action('layerslider_installed','avia_layerslider_remove_setup_fonts');
	
	function avia_layerslider_remove_setup_fonts()
	{
		 //remove google fonts from install
		update_option('ls-google-fonts', array());
	}
}


/**************************/
/* Include LayerSlider WP */
/**************************/
if(is_admin())
{	
	//dont call on plugins page so user can enable the plugin if he wants to
	if(isset($_SERVER['PHP_SELF']) && basename($_SERVER['PHP_SELF']) == "plugins.php" && (is_dir(WP_PLUGIN_DIR . '/LayerSlider') || is_dir(WPMU_PLUGIN_DIR . '/LayerSlider'))) return;
	
	add_action('init', 'avia_include_layerslider' , 1 );
	
}
else
{	
	add_action('wp', 'avia_include_layerslider' , 45 );
}

function avia_include_layerslider()
{	
	if(!is_admin() && !post_has_layerslider()) return;
	if(current_theme_supports('deactivate_layerslider')) return;
	
	// Path for LayerSlider WP main PHP file
	$layerslider = get_template_directory() . '/config-layerslider/LayerSlider/layerslider.php';
	$themeNice	 = substr(avia_backend_safe_string(THEMENAME),0,40);

	// Check if the file is available and the user didnt activate the layerslide plugin to prevent warnings
	if(file_exists($layerslider)) 
	{
		if(function_exists('layerslider')) //layerslider plugin is active
		{
			if(get_option("{$themeNice}_layerslider_activated", '0') == '0') 
			{
		        // Save a flag that it is activated, so this won't run again
		        update_option("{$themeNice}_layerslider_activated", '1');
		    }
		}
		else //not active, use theme version instead
		{	
		    // Include the file
		    include $layerslider;
		    $skins = LS_Sources::getSkins();
		    $allowed = apply_filters('avf_allowed_layerslider_skins', array('fullwidth','noskin') ); //if $allowed is set to bool true all skins are allowed
			
			if($allowed !== true)
			{
				foreach($skins as $key => $skin)
				{
					if(!in_array($key, $allowed))
					{
						LS_Sources::removeSkin( $key );
					}
				}
			}

		    $GLOBALS['lsPluginPath'] 	= get_template_directory_uri() . '/config-layerslider/LayerSlider/';
		    $GLOBALS['lsAutoUpdateBox'] = false;
			if (!defined('LS_ROOT_URL'))
			{
				define('LS_ROOT_URL', get_template_directory_uri() . '/config-layerslider/LayerSlider' );
			}
		    
		    // Activate the plugin if necessary
		    if(get_option("{$themeNice}_layerslider_activated", '0') == '0') {
		 
		        // Run activation script
		        //layerslider_activation_scripts();
		 		
		        // Save a flag that it is activated, so this won't run again
		        update_option("{$themeNice}_layerslider_activated", '1');
		        update_option('ls-show-support-notice', 0);
		    }
	    }
	}
}


