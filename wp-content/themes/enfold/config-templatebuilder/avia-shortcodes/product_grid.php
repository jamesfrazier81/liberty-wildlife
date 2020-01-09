<?php
/**
 * Product Grid
 *
 * Display a Grid of Product Entries
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if( ! class_exists( 'woocommerce' ) )
{
	add_shortcode( 'av_productgrid', 'avia_please_install_woo' );
	return;
}

if ( ! class_exists( 'avia_sc_productgrid' ) )
{
	class avia_sc_productgrid extends aviaShortcodeTemplate
	{
		/**
		 * Create the config array for the shortcode button
		 */
		function shortcode_insert_button()
		{
			$this->config['self_closing']	=	'yes';
			
			$this->config['name']		= __( 'Product Grid', 'avia_framework' );
			$this->config['tab']		= __( 'Plugin Additions', 'avia_framework' );
			$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-portfolio.png";
			$this->config['order']		= 30;
			$this->config['target']		= 'avia-target-insert';
			$this->config['shortcode'] 	= 'av_productgrid';
			$this->config['tooltip'] 	= __( 'Display a Grid of Product Entries', 'avia_framework' );
			$this->config['drag-level'] = 3;
			$this->config['id_name']	= 'id';
			$this->config['id_show']	= 'yes';			
			$this->config['alb_desc_id']	= 'alb_description';
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
						"name" 	=> __("Which Entries?", 'avia_framework' ),
						"desc" 	=> __("Select which entries should be displayed by selecting a taxonomy", 'avia_framework' ),
						"id" 	=> "categories",
						"type" 	=> "select",
						"taxonomy" => "product_cat",
					    "subtype" => "cat",
						"multiple"	=> 6
				),

				array(
						"name" 	=> __("Columns", 'avia_framework' ),
						"desc" 	=> __("How many columns should be displayed?", 'avia_framework' ),
						"id" 	=> "columns",
						"type" 	=> "select",
						"std" 	=> "3",
						"subtype" => array(	__('2 Columns', 'avia_framework' )=>'2',
											__('3 Columns', 'avia_framework' )=>'3',
											__('4 Columns', 'avia_framework' )=>'4',
											__('5 Columns', 'avia_framework' )=>'5',
											)),
				array(
						"name" 	=> __("Entry Number", 'avia_framework' ),
						"desc" 	=> __("How many items should be displayed?", 'avia_framework' ),
						"id" 	=> "items",
						"type" 	=> "select",
						"std" 	=> "9",
						"subtype" => AviaHtmlHelper::number_array( 1, 100, 1, array( 'All' => '-1' ) ) ),

				array(	
						'type'			=> 'template',
						'template_id' 	=> 'wc_options_products',
						'sort_dropdown'	=> true
					),
				

				array(
						"name"		=> __( "Offset Number", 'avia_framework' ),
						"desc"		=> __( "The offset determines where the query begins pulling products. Useful if you want to remove a certain number of products because you already query them with another product grid. Attention: Use this option only if the product sorting of the product grids match and do not allow the user to pick the sort order!", 'avia_framework' ),
						"id"		=> "offset",
						"type"		=> "select",
						"std"		=> "0",
						"subtype"	=> AviaHtmlHelper::number_array( 1, 100, 1, array( __( 'Deactivate offset', 'avia_framework' ) => '0', __( 'Do not allow duplicate posts on the entire page (set offset automatically)', 'avia_framework' ) => 'no_duplicates' ) )
					),


				array(
							"name" 	=> __("Pagination", 'avia_framework' ),
							"desc" 	=> __("Should a pagination be displayed?", 'avia_framework' ),
							"id" 	=> "paginate",
							"type" 	=> "select",
							"std" 	=> "yes",
							'required' => array( 'items', 'not', '-1' ),
							"subtype" => array(
												__( 'yes',  'avia_framework' )	=> 'yes',
												__( 'no',  'avia_framework' )	=> 'no')
					),
				
				
				array(
							"type" 	=> "close_div",
							'nodescription' => true
						),
						
	
				array(	
						'type'			=> 'template',
						'template_id'	=> 'screen_options_tab'
					),
						

				array(
						"type" 	=> "close_div",
						'nodescription' => true
					),	
				

				);
		}

		/**
		 * Editor Element - this function defines the visual appearance of an element on the AviaBuilder Canvas
		 * Most common usage is to define some markup in the $params['innerHtml'] which is then inserted into the drag and drop container
		 * Less often used: $params['data'] to add data attributes, $params['class'] to modify the className
		 *
		 *
		 * @param array $params this array holds the default values for $content and $args.
		 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
		 */
		function editor_element($params)
		{
			$params = parent::editor_element( $params );
			return $params;
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
			global $avia_config, $woocommerce;
			
			$screen_sizes = AviaHelper::av_mobile_sizes( $atts );
			
			$atts['class'] = $meta['el_class'];
			$atts['el_id'] = $meta['custom_el_id'];
			$atts['custom_class'] = $meta['custom_class'];
			$atts['autoplay'] = "no";
			$atts['type'] = "grid";
			
			//fix for seo plugins which execute the do_shortcode() function before the WooCommerce plugin is loaded
			if( ! is_object( $woocommerce ) || ! is_object( $woocommerce->query ) ) 
			{
				return;
			}
			
			$atts = array_merge( $atts, $screen_sizes );
			
			$slider = new avia_product_slider( $atts );
			$slider->query_entries();
			
				//	force to ignore WC default setting - see hooked function avia_wc_product_is_visible
			$avia_config['woocommerce']['catalog_product_visibility'] = 'show_all';
			$html = $slider->html();
			
				//	reset again
			$avia_config['woocommerce']['catalog_product_visibility'] = 'use_default';
			
			return $html;
		}
	}
}



