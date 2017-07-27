<?php  if ( ! defined('AVIA_FW')) exit('No direct script access allowed');

 
if( ! class_exists( 'avia_gmaps_check' ) )
{
	class avia_gmaps_check
	{
		function __construct($key)
		{
			$this->key = $key;
		}
		
		function check_api_key()
		{
			$valid = false;
			//function that checks if the value of $this->key is a valid api key
		
		
			return $valid;
		}
		
		function store_key()
		{
			update_option('av_gmaps_api_key', $this->key);
		}
		
		function delete_key()
		{
			delete_option('av_gmaps_api_key');
		}
		
		
		static function backend_html($value = "", $ajax = true, $valid_key = false)
		{
			$valid_key  = $valid_key == "true" ? true : false;
			$gmaps 		= false;
			$response_text  = __("Could not connect to Google Maps with this API Key.",'avia_framework');
			$response_class = "av-notice-error";
			$content_default  =			'<h4>' . esc_html__( 'Troubleshooting:', 'avia_framework' ) . '</h4>';
			$content_default .=			'<ol>';
			$content_default .=				'<li>';
			$content_default .=					esc_html__( 'Check if you typed the key correctly.', 'avia_framework' );
			$content_default .=				'</li>';
			$content_default .=				'<li>';
			$content_default .=					esc_html__( 'If you use the restriction setting on Google try to remove that, wait a few minutes for google to apply your changes and then check again if the key works here. If it does, you probably have a syntax error in your referrer url', 'avia_framework' );
			$content_default .=				'</li>';
			$content_default .=				'<li>';
			$content_default .=					esc_html__( 'If none of this helps: deactivate all plugins and then check if the API works by using the button above. If thats the case then one of your plugins is interfering. ', 'avia_framework' );
			$content_default .=				'</li>';
			$content_default .=			'</ol>';
			
			
		
			//if called by user pressing the ajax check button
			if($ajax)
			{	
				$api = new avia_gmaps_check($value);
				
				if($valid_key)
				{	
					$api->store_key();
					
					$response_class = "";
					$response_text  = __("We were able to properly connect to google maps with your API key",'avia_framework');
					
					
					//will be stripped from the final output but tells the ajax script to save the page after the check was performed
					$response_text .= " avia_trigger_save"; 				
				}
				else
				{
					$api->delete_key();
				}
			}
			else // is called on a normal page load. in this case we either show the stored result or if we got no stored result we show nothing
			{
				$valid_key = get_option('av_gmaps_api_key');
				
				if($valid_key)
				{
					$response_class = "";
					$response_text  = __("Last time we checked we were able to connected to google maps with your API key",'avia_framework');
				}
			}
			
			
			if($valid_key)
			{
				$content_default  = __("If you ever change your API key or the URL restrictions of the key please verify the key here again, to test if it works properly",'avia_framework');
			}
			
			

			$output  = "<div class='av-verification-response-wrapper'>";
			$output .= "<div class='av-text-notice {$response_class}'>";
			$output .= $response_text;
			$output .= "</div>";
			$output .= "<div class='av-verification-cell'>".$content_default."</div>";
			$output .= "</div>";
			
			
			return $output;
		}
		
	}
}

if (!function_exists('av_maps_api_check')){
	
	function av_maps_api_check($value, $ajax = true, $js_value = NULL)
	{
		return avia_gmaps_check::backend_html($value, $ajax, $js_value);
	}

}