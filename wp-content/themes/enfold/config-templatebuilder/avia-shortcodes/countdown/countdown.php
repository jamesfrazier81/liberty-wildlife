<?php
/**
 * Animated Countdown
 * 
 * Display Numbers that count from 0 to a specific date
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( ! class_exists( 'avia_sc_countdown' ) ) 
{
	
	class avia_sc_countdown extends aviaShortcodeTemplate
	{
			/**
			 * @since 4.5.7.2
			 * @var array 
			 */
			protected $time_array;
			
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'yes';
				
				$this->config['name']		= __( 'Animated Countdown', 'avia_framework' );
				$this->config['tab']		= __( 'Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-countdown.png";
				$this->config['order']		= 14;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_countdown';
				$this->config['tooltip'] 	= __( 'Display a countdown to a specific date', 'avia_framework' );
				$this->config['preview'] 	= "xlarge";
				$this->config['disabling_allowed'] = true;
				$this->config['id_name']	= 'id';
				$this->config['id_show']	= 'yes';
				$this->config['alb_desc_id']	= 'alb_description';
				
				$this->time_array = array(
								__( 'Second',  	'avia_framework' ) 	=> '1',
								__( 'Minute',  	'avia_framework' ) 	=> '2',	
								__( 'Hour',  	'avia_framework' ) 	=> '3',
								__( 'Day',  	'avia_framework' ) 	=> '4',
								__( 'Week',  	'avia_framework' ) 	=> '5',
								/*
								__( 'Month',  	'avia_framework' ) 	=> '6',
								__( 'Year',  	'avia_framework' ) 	=> '7'
								*/
							);
				
			}
			
			/**
			 * @since 4.5.7.2
			 */
			public function __destruct() 
			{	
				unset( $this->time_array );
				
				parent::__destruct();
			}
			
			function extra_assets()
			{
				//load css
				wp_enqueue_style( 'avia-module-countdown' , AviaBuilder::$path['pluginUrlRoot'].'avia-shortcodes/countdown/countdown.css' , array('avia-layout'), false );
				
				//load js
				wp_enqueue_script( 'avia-module-countdown' , AviaBuilder::$path['pluginUrlRoot'].'avia-shortcodes/countdown/countdown.js' , array('avia-shortcodes'), false, true );
			}
		
		
			/**
			 * Popup Elements
			 *
			 * If this function is defined in a child class the element automatically gets an edit button, that, when pressed
			 * opens a modal window that allows to edit the element properties
			 *
			 * @return void
			 */
			function popup_elements()
			{
				$this->elements = array(
					array(
							"type" 	=> "tab_container", 'nodescription' => true
						),
						
					array(
							"type" 	=> "tab",
							"name"  => __("Content" , 'avia_framework'),
							'nodescription' => true
						),
						
													
					array(	
							"name" 	=> __("Date",'avia_framework' ),
							"desc" 	=> __("Pick a date in the future.",'avia_framework' ),
							"id" 	=> "date",
							"type" 	=> "datepicker",
							"container_class" => 'av_third av_third_first',
							"std" 	=> "",
							'dp_params'	=> array(
												'dateFormat'        => 'mm / dd / yy',
												'minDate'			=> 0
											)
						),
					
					array(	
							"name" 	=> __("Hour", 'avia_framework' ),
							"desc" 	=> __("Pick the hour of the day", 'avia_framework' ),
							"id" 	=> "hour",
							"type" 	=> "select",
							"std" 	=> "12",
							"container_class" => 'av_third',
							"subtype" => AviaHtmlHelper::number_array(0,23,1,array(),' h')),
					
					array(	
							"name" 	=> __("Minute", 'avia_framework' ),
							"desc" 	=> __("Pick the minute of the hour", 'avia_framework' ),
							"id" 	=> "minute",
							"type" 	=> "select",
							"std" 	=> "0",
							"container_class" => 'av_third',
							"subtype" => AviaHtmlHelper::number_array(0,59,1,array(),' min')),
								
					array(	
							'name' 	=> __( 'Timezone', 'avia_framework' ),
							'desc' 	=> __( 'Select the timezone of your date.', 'avia_framework' ),
							'id' 	=> 'timezone',
							'type' 	=> 'timezone_choice',
							'std' 	=> ''
						),
					
					array(	
							"name" 	=> __("Smallest time unit", 'avia_framework' ),
							"desc" 	=> __("The smallest unit that will be displayed", 'avia_framework' ),
							"id" 	=> "min",
							"type" 	=> "select",
							"std" 	=> "1",
							"subtype" => $this->time_array),
					
					
					array(	
							"name" 	=> __("Largest time unit", 'avia_framework' ),
							"desc" 	=> __("The largest unit that will be displayed", 'avia_framework' ),
							"id" 	=> "max",
							"type" 	=> "select",
							"std" 	=> "5",
							"subtype" => $this->time_array),
					
					
					
							
					array(
							"name" 	=> __("Text Alignment", 'avia_framework' ),
							"desc" 	=> __("Choose here, how to align your text", 'avia_framework' ),
							"id" 	=> "align",
							"type" 	=> "select",
							"std" 	=> "center",
							"subtype" => array(
												__('Center',  'avia_framework' ) =>'av-align-center',
												__('Right',  'avia_framework' ) =>'av-align-right',
												__('Left',  'avia_framework' ) =>'av-align-left',
												)
							),
							
					array(	"name" 	=> __("Number Font Size", 'avia_framework' ),
							"desc" 	=> __("Size of your numbers in Pixel", 'avia_framework' ),
				            "id" 	=> "size",
				            "type" 	=> "select",
				            "subtype" => AviaHtmlHelper::number_array(20,90,1, array( __("Default Size", 'avia_framework' )=>'')),
				            "std" => ""),
				            
				   array(
							"type" 	=> "close_div",
							'nodescription' => true
						),
					
					array(
							"type" 	=> "tab",
							"name"	=> __("Colors",'avia_framework' ),
							'nodescription' => true
						),
						         
				   array(
							"name" 	=> __("Colors", 'avia_framework' ),
							"desc" 	=> __("Choose the colors here", 'avia_framework' ),
							"id" 	=> "style",
							"type" 	=> "select",
							"std" 	=> "center",
							"subtype" => array(
												__('Default',	'avia_framework' ) 	=>'av-default-style',
												__('Theme colors',	'avia_framework' ) 	=>'av-colored-style',
												__('Transparent Light', 'avia_framework' ) 	=>'av-trans-light-style',
												__('Transparent Dark',  'avia_framework' )  =>'av-trans-dark-style',
												)
							),
					array(
							"type" 	=> "close_div",
							'nodescription' => true
						),
					
					array(	
							'type'				=> 'template',
							'template_id'		=> 'screen_options_tab',
							'templates_include'	=> array( 'screen_options_visibility', 'number_font_size', 'text_font_size' ),
							"subtype"			=> array( 
														'number_font_size'	=> AviaHtmlHelper::number_array( 10, 60, 1, array( __( 'Default', 'avia_framework' ) => '', __( 'Hidden', 'avia_framework' ) => 'hidden' ), 'px' ),
														'text_font_size'	=> AviaHtmlHelper::number_array( 10, 60, 1, array( __( 'Default', 'avia_framework' ) => '', __( 'Hidden', 'avia_framework' ) => 'hidden' ), 'px' )
													)
						),
				

					array(
							"type" 	=> "close_div",
							'nodescription' => true
						),
				);

			}
			
			/**
			 * Frontend Shortcode Handler
			 *
			 * @param array $atts array of attributes
			 * @param string $content text within enclosing form of shortcode element 
			 * @param string $shortcodename the shortcode found, when == callback name
			 * @return string $output returns the modified html string 
			 */
			function shortcode_handler( $atts, $content = "", $shortcodename = "", $meta = "" )
			{
				extract(AviaHelper::av_mobile_sizes($atts)); //return $av_font_classes, $av_title_font_classes and $av_display_classes 
				
				$meta = aviaShortcodeTemplate::set_frontend_developer_heading_tag( $atts, $meta );
				
				extract( shortcode_atts( array(	
							'date' 		=> '', 
							'hour' 		=> '12', 
							'minute' 	=> '0',
							'timezone'	=> '',
							'min' 		=> '1', 
							'max' 		=> '5',
							'align'		=> 'center',
							'size'		=> '', 
							'style'		=> 'av-default-style', 
							'link'		=> '',			//	used by events_countown
							'title'		=> ''			//	used by events_countown
						), $atts, $this->config['shortcode'] ) );
				
				$this->full_time_array = array(
				
					1 => array("interval" => 1000		 , 'class'=>'seconds', 	'label' => __('Second', 'avia_framework' ),	'label_multi' => __('Seconds',  'avia_framework')),
					2 => array("interval" => 60000		 , 'class'=>'minutes', 	'label' => __('Minute', 'avia_framework' ),	'label_multi' => __('Minutes',  'avia_framework')),
					3 => array("interval" => 3600000	 , 'class'=>'hours', 	'label' => __('Hour',  	'avia_framework'),	'label_multi' => __('Hours',  	'avia_framework')),
					4 => array("interval" => 86400000	 , 'class'=>'days', 	'label' => __('Day',  	'avia_framework' ), 'label_multi' => __('Days',  	'avia_framework')),
					5 => array("interval" => 604800000	 , 'class'=>'weeks', 	'label' => __('Week',  	'avia_framework' ),	'label_multi' => __('Weeks',  	'avia_framework')),
					6 => array("interval" => 2678400000	 , 'class'=>'months', 	'label' => __('Month',  'avia_framework' ),	'label_multi' => __('Months',  	'avia_framework')),
					7 => array("interval" => 31536000000 , 'class'=>'years', 	'label' => __('Year',  	'avia_framework' ),	'label_multi' => __('Years',  	'avia_framework'))
				
				);
				
				$offset = AviaHtmlHelper::get_timezone_offset( $timezone ) * 60;
				$interval 	= $this->full_time_array[$min]['interval'];
				$final_time = "";
				$output  	= "";
				$digit_style= "";
				$el = isset($meta['el_class']) ? $meta['el_class'] : "";
				
				if(!empty($date))
				{
					$date = explode("/", $date);
				
					$final_time .= " data-year='".$date[2]."'";
					$final_time .= " data-month='".((int) $date[0] - 1)."'";
					$final_time .= " data-day='".$date[1]."'";
					$final_time .= " data-hour='".$hour."'";
					$final_time .= " data-minute='".$minute."'";
					$final_time .= " data-timezone='".$offset."'";
					
					if(!empty($size)) $digit_style = "font-size:{$size}px; ";
					$tags = !empty($link) ? array( "a href='{$link}' ", "a") : array('span', 'span');
					
					$default_heading = ! empty( $meta['heading_tag'] ) ? $meta['heading_tag'] : 'h3';
					$args = array(
								'heading'		=> $default_heading,
								'extra_class'	=> $meta['heading_class']
							);

					$extra_args = array( $this, $atts, $content, 'title' );

					/**
					 * @since 4.5.5
					 * @return array
					 */
					$args = apply_filters( 'avf_customize_heading_settings', $args, __CLASS__, $extra_args );

					$heading = ! empty( $args['heading'] ) ? $args['heading'] : $default_heading;
					$css = ! empty( $args['extra_class'] ) ? $args['extra_class'] : $meta['heading_class'];
					
					$output .= "<div {$meta['custom_el_id']} class='av-countdown-timer {$av_display_classes} {$align} {$style} {$el}' {$final_time} data-interval='{$interval}' data-maximum='{$max}' >";
					
					if( is_array( $title ) && isset( $title['top'] ) )
					{
						$output .= "<{$heading}><{$tags[0]} class='av-countdown-timer-title av-countdown-timer-title-top {$css}'>".$title['top']."</{$tags[1]}></{$heading}>";
					}
					
					
					$output .= 		"<{$tags[0]} class='av-countdown-timer-inner'>";
					
					foreach(array_reverse($this->time_array) as $key => $number)
					{
						if($number >= $min && $number <= $max)
						{
							$class   = $this->full_time_array[$number]['class'];
							$single  = $this->full_time_array[$number]['label'];
							$multi   = $this->full_time_array[$number]['label_multi'];
							
							$output .= "<span class='av-countdown-cell av-countdown-". $class ."'>";
								$output .= "<span class='av-countdown-cell-inner'>";
								
									$output .= "<span class='av-countdown-time {$av_title_font_classes}' data-upate-width='{$class}' style='{$digit_style}'>0</span>";
									$output .= "<span class='av-countdown-time-label {$av_font_classes}' data-label='{$single}' data-label-multi='{$multi}'>".$multi."</span>";
									
								$output .= "</span>";
							$output .= "</span>";
						}
					}
					
					$output .= 		"</{$tags[1]}>";
					
					if( is_array( $title ) && isset( $title['bottom'] ) )
					{
						$output .= "<{$heading}><{$tags[0]} class='av-countdown-timer-title av-countdown-timer-title-bottom {$css}'>".$title['bottom']."</{$tags[1]}></{$heading}>";
					}
					
					
					$output .= "</div>";
        		}
        		
        		return $output;
			}
	}
}





