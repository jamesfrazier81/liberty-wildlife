<?php
/**
 * Class defines option templates for ALB elements
 * These templates replace an element in the options array.
 * Nested templates are supported.
 * 
 * Basic structure (example):
 * 
 *			array(	
 *						'type'					=> 'template',
 *						'template_id'			=> 'date_query',
 *						'required'				=> ! isset() | array()     //	used for all elements
 *						'template_required'		=> array( 
 *														0	=> array( 'slide_type', 'is_empty_or', 'entry-based' )
 *													),
 *						'templates_include'		=> ! isset() | array( list of needed subtemplates ),
 *						'subtype'				=> mixed
 *						
 *													
 *					),
 * 
 * @added_by Günter
 * @since 4.5.7.1
 */

if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly

if( ! class_exists( 'Avia_Popup_Templates' ) )
{
	
	class Avia_Popup_Templates
	{
		
		/**
		 * Holds the instance of this class
		 * 
		 * @since 4.5.7.1
		 * @var Avia_Popup_Templates 
		 */
		static private $_instance = null;
		
		/**
		 * Return the instance of this class
		 * 
		 * @since 4.5.7.1
		 * @return Avia_Popup_Templates
		 */
		static public function instance()
		{
			if( is_null( Avia_Popup_Templates::$_instance ) )
			{
				Avia_Popup_Templates::$_instance = new Avia_Popup_Templates();
			}
			
			return Avia_Popup_Templates::$_instance;
		}
		
		/**
		 * @since 4.5.7.1
		 */
		protected function __construct()
		{
			
		}
		
		/**
		 * Main entry function:
		 * ====================
		 * 
		 * Replaces predefined templates for easier maintainnance of code
		 * Recursive function. Also supports nested templates.
		 * 
		 * @since 4.5.6.1
		 * @param array $elements
		 * @return array
		 */
		public function replace_templates( array $elements )
		{
			$start_check = true;
			
			while( $start_check )
			{
				$offset = 0;
				foreach( $elements as $key => $element ) 
				{
					if( isset( $element['subelements'] ) )
					{
						$elements[ $key ]['subelements'] = $this->replace_templates( $element['subelements'] );
					}
					
					if( ! isset( $element['type'] ) || $element['type'] != 'template' )
					{
						$offset++;
						if( $offset >= count( $elements ) )
						{
							$start_check = false;
							break;
						}
						continue;
					}

					$replace = $this->get_template( $element );
					if( false === $replace )
					{
						$offset++;
						if( $offset >= count( $elements ) )
						{
							$start_check = false;
							break;
						}
						continue;
					}

					array_splice( $elements, $offset, 1, $replace );
					break;
				}
			}
			
			return $elements;
		}

		
		/**
		 * Returns the array elements to replace the template array element
		 * 
		 * @since 4.5.6.1
		 * @param array $element
		 * @return array|false
		 */
		protected function get_template( array $element )
		{
			if( ! isset( $element['template_id'] ) )
			{
				return false;
			}
			
			if( ! method_exists( $this, $element['template_id'] ) )
			{
				return false;
			}
			
			$result = call_user_func_array( array( $this, $element['template_id'] ), array( $element ) );
			return $result;
		}
		
				
		/**
		 * Date Query Template
		 * 
		 * @since 4.5.6.1
		 * @param array $element
		 * @return array
		 */
		protected function date_query( array $element )
		{
			$template = array(
				
					array(	
							'name' 		=> __( 'Do you want to filter entries by date?', 'avia_framework' ),
							'desc' 		=> __( 'Do you want to display entries within date boundaries only? Can be used e.g. to create archives.', 'avia_framework' ),
							'id' 		=> 'date_filter',
							'type' 		=> 'select',
							'std'		=> '',
							'subtype'	=> array( 
												__( 'Display all entries', 'avia_framework' )		=> '',
												__( 'Filter entries by date', 'avia_framework' )	=> 'date_filter'
											)
						),
					
					array(	
							'name'		=> __( 'Start Date', 'avia_framework' ),
							'desc'		=> __( 'Pick a start date.', 'avia_framework' ),
							'id'		=> 'date_filter_start',
							'type'		=> 'datepicker',
							'required'	=> array( 'date_filter', 'equals', 'date_filter' ),
							'container_class'	=> 'av_third av_third_first',
							'std'		=> '',
							'dp_params'	=> array(
												'dateFormat'        => 'yy/mm/dd',
												'changeMonth'		=> true,
												'changeYear'		=> true,
												'container_class'	=> 'select_dates_30'
											)
						),
					
					array(	
							'name'		=> __( 'End Date', 'avia_framework' ),
							'desc'		=> __( 'Pick the end date. Leave empty to display all entries after the start date.', 'avia_framework' ),
							'id'		=> 'date_filter_end',
							'type'		=> 'datepicker',
							'required'	=> array( 'date_filter', 'equals', 'date_filter' ),
							'container_class'	=> 'av_2_third',
							'std'		=> '',
							'dp_params'	=> array(
												'dateFormat'        => 'yy/mm/dd',
												'changeMonth'		=> true,
												'changeYear'		=> true,
												'container_class'	=> 'select_dates_30'
											)
						),
					
					array(	
							'name'			=> __( 'Date Formt','avia_framework' ),
							'desc'			=> __( 'Define the same date format as used in date picker', 'avia_framework' ),
							'id'			=> 'date_filter_format',
							'container_class'	=> 'avia-hidden',
							'type'			=> 'input',
							'std'			=> 'yy/mm/dd'
						)
									
				);
			
				if( ! empty ( $element['template_required'][0] ) )
				{
					$template[0]['required'] = $element['template_required'][0];
				}
				
			return $template;
		}
		
		/**
		 * Complete Screen Options Tab with several content options
		 * 
		 * @since 4.5.7.1
		 * @param array $element
		 * @return array
		 */
		protected function screen_options_tab( array $element )
		{
			$template = array();
			
			/**
			 * This is the default template when missing
			 */
			$sub_templates =  array( 'screen_options_visibility' );
			
			if( isset( $element['templates_include'] ) && ! empty( $element['templates_include']  ) )
			{
				$sub_templates = (array) $element['templates_include'];
			}
			
				
			$template[] = array(
								'type'          => 'tab',
								'name'          => __( 'Screen Options', 'avia_framework' ),
								'nodescription' => true
							);
		
			
			foreach( $sub_templates as $sub_template ) 
			{
				if( method_exists( $this, $sub_template ) )
				{
					$temp = array(	
									'type'          => 'template',
									'template_id'   => $sub_template,
								);		
					
					if( isset( $element['subtype'][ $sub_template ] ) && is_array( $element['subtype'][ $sub_template ] ) )
					{
						$temp['subtype'] = $element['subtype'][ $sub_template ];
					}
					
					$template[] = $temp;
				}
			}
								
			$template[] = array(
								'type'          => 'close_div',
								'nodescription' => true
							);
								
						
			return $template;
		}
		
		
		/**
		 * Simple checkboxes for element visibility
		 * 
		 * @since 4.5.6.1
		 * @param array $element
		 * @return array
		 */
		protected function screen_options_visibility( array $element )
		{
			$template = array(
							
							array(
									'type' 				=> 'toggle',
									'name'              => __( 'Element Visibility', 'avia_framework' ),
									'desc'              => __( 'Set the visibility for this element, based on the device screensize.', 'avia_framework' ),
									'nodescription' 	=> true
							),
							
							array(	
									'desc'              => __( 'Hide on large screens (wider than 990px - eg: Desktop)', 'avia_framework' ),
									'id'                => 'av-desktop-hide',
									'std'               => '',
									'container_class'   => 'av-multi-checkbox',
									'type'              => 'checkbox'
								),
								
							array(	

									'desc'              => __( 'Hide on medium sized screens (between 768px and 989px - eg: Tablet Landscape)', 'avia_framework' ),
									'id'                => 'av-medium-hide',
									'std'               => '',
									'container_class'   => 'av-multi-checkbox',
									'type'              => 'checkbox'
								),
										
							array(	

									'desc'              => __( 'Hide on small screens (between 480px and 767px - eg: Tablet Portrait)', 'avia_framework' ),
									'id'                => 'av-small-hide',
									'std'               => '',
									'container_class'   => 'av-multi-checkbox',
									'type'              => 'checkbox'
								),
										
							array(	
									
									'desc'              => __( 'Hide on very small screens (smaller than 479px - eg: Smartphone Portrait)', 'avia_framework' ),
									'id'                => 'av-mini-hide',
									'std'               => '',
									'container_class'   => 'av-multi-checkbox',
									'type'              => 'checkbox'
								),
								
							array(
									'type' 				=> 'close_div',
									'nodescription' 	=> true
							),
				
						);
			
			return $template;
		}
		
		/**
		 * Selectboxes for Title Font Sizes
		 * 
		 * @since 4.5.7.1
		 * @param array $element
		 * @return array
		 */
		protected function font_sizes_title( array $element )
		{
			$subtype = AviaHtmlHelper::number_array( 10, 120, 1, array( __( 'Default', 'avia_framework' ) => '', __( 'Hidden', 'avia_framework' ) => 'hidden' ), 'px' );
			
			if( isset( $element['subtype'] ) && is_array( $element['subtype'] ) )
			{
				$subtype = $element['subtype'];
			}
			
			$template = array(
				
							array(	
									'name'		=> __( 'Font Size for medium sized screens (between 768px and 989px - eg: Tablet Landscape)', 'avia_framework' ),
									'id'		=> 'av-medium-font-size-title',
									'type'		=> 'select',
									'subtype'	=> $subtype,
									'std'		=> ''
								),
						            
							array(	
									'name'		=> __( 'Font Size for small screens (between 480px and 767px - eg: Tablet Portrait)', 'avia_framework' ),
									'id'		=> 'av-small-font-size-title',
									'type'		=> 'select',
									'subtype'	=> $subtype,
									'std'		=> ''
								),
						            
							array(	
									'name'		=> __( 'Font Size for very small screens (smaller than 479px - eg: Smartphone Portrait)', 'avia_framework' ),
									'id'		=> 'av-mini-font-size-title',
									'type'		=> 'select',
									'subtype'	=> $subtype,
									'std'		=> ''
								)
				
						);
			
			return $template;
		}
		
		/**
		 * Selectboxes for Content Font Sizes
		 * 
		 * @since 4.5.7.1
		 * @param array $element
		 * @return array
		 */
		protected function font_sizes_content( array $element )
		{
			$subtype = AviaHtmlHelper::number_array( 10, 120, 1, array( __( 'Default', 'avia_framework' ) => '', __( 'Hidden', 'avia_framework' ) => 'hidden' ), 'px' );
			
			if( isset( $element['subtype'] ) && is_array( $element['subtype'] ) )
			{
				$subtype = $element['subtype'];
			}
			
			$template = array(
							array(	
									'name'		=> __( 'Font Size for medium sized screens (between 768px and 989px - eg: Tablet Landscape)', 'avia_framework' ),
									'id'		=> 'av-medium-font-size',
									'type'		=> 'select',
									'subtype'	=> $subtype,
									'std'		=> ''
								),
						            
							array(	
									'name'		=> __( 'Font Size for small screens (between 480px and 767px - eg: Tablet Portrait)', 'avia_framework' ),
									'id'		=> 'av-small-font-size',
									'type'		=> 'select',
									'subtype'	=> $subtype,
									'std'		=> ''
								),
						            
							array(	
									'name'		=> __( 'Font Size for very small screens (smaller than 479px - eg: Smartphone Portrait)', 'avia_framework' ),
									'id'		=> 'av-mini-font-size',
									'type'		=> 'select',
									'subtype'	=> $subtype,
									'std'		=> ''
								)				
						);
			
			return $template;
		}

		/**
		 * Selectboxes for Heading Font Size
		 * 
		 * @since 4.5.7.1
		 * @param array $element
		 * @return array
		 */
		protected function heading_font_size( array $element )
		{
			$title = array(
							array(
									'name'		=> __( 'Heading Font Size', 'avia_framework' ),
									'desc'		=> __( 'Set the font size for the heading, based on the device screensize.', 'avia_framework' ),
									'type'		=> 'heading',
									'description_class' => 'av-builder-note av-neutral',
								)
							);
			
			$fonts = $this->font_sizes_title( $element );
			$template = array_merge( $title, $fonts );
			
			return $template;
		}
		
		/**
		 * Selectboxes for Content Font Size
		 * 
		 * @since 4.5.7.1
		 * @param array $element
		 * @return array
		 */
		protected function content_font_size( array $element )
		{
			$title = array(
							array(
									'name'		=> __( 'Content Font Size', 'avia_framework' ),
									'desc'		=> __( 'Set the font size for the content, based on the device screensize.', 'avia_framework' ),
									'type'		=> 'heading',
									'description_class' => 'av-builder-note av-neutral',
								)
						);
			
			$fonts = $this->font_sizes_content( $element );
			$template = array_merge( $title, $fonts );
			
			return $template;
		}
		
		/**
		 * Selectboxes for Subheading Font Size
		 * 
		 * @since 4.5.7.1
		 * @param array $element
		 * @return array
		 */
		protected function subheading_font_size( array $element )
		{
			$template = $this->content_font_size( $element );
			
			$title = array( 
							array(
								'name'		=> __( 'Subheading Font Size', 'avia_framework' ),
								'desc'		=> __( 'Set the font size for the subheading, based on the device screensize.', 'avia_framework' ),
								'type'		=> 'heading',
								'description_class'	=> 'av-builder-note av-neutral',
							)
						);
			
			
			$fonts = $this->font_sizes_content( $element );
			$template = array_merge( $title, $fonts );
			
			return $template;
		}
		
		/**
		 * Selectboxes for Number Font Size (countdown)
		 * 
		 * @since 4.5.7.1
		 * @param array $element
		 * @return array
		 */
		protected function number_font_size( array $element )
		{
			$title = array(
							array(
									'name'		=> __( 'Number Font Size', 'avia_framework' ),
									'desc'		=> __( 'Set the font size for the number, based on the device screensize.', 'avia_framework' ),
									'type'		=> 'heading',
									'description_class' => 'av-builder-note av-neutral',
							)
						);
			
			$fonts = $this->font_sizes_title( $element );
			$template = array_merge( $title, $fonts );
			
			return $template;
		}
		
		/**
		 * Selectboxes for Text Font Size (countdown)
		 * 
		 * @since 4.5.7.1
		 * @param array $element
		 * @return array
		 */
		protected function text_font_size( array $element )
		{
			$title = array(
							array(
									'name'		=> __( 'Text Font Size', 'avia_framework' ),
									'desc'		=> __( 'Set the font size for the text, based on the device screensize.', 'avia_framework' ),
									'type'		=> 'heading',
									'description_class' => 'av-builder-note av-neutral',
							)
						);
			
			$fonts = $this->font_sizes_content( $element );
			$template = array_merge( $title, $fonts );
			
			return $template;
		}
		
		/**
		 * Selectboxes for Columns ( 1 - 4 )
		 * 
		 * @since 4.5.7.1
		 * @param array $element
		 * @return array
		 */
		protected function column_count( array $element )
		{
			$subtype = AviaHtmlHelper::number_array( 1, 4, 1, array( __( 'Default', 'avia_framework' ) => '' ) );
			
			$template = array(
				
							array(	
									'name'		=> __( 'Column count for medium sized screens (between 768px and 989px - eg: Tablet Landscape)', 'avia_framework' ),
									'id'		=> 'av-medium-columns',
									'type'		=> 'select',
									'subtype'	=> $subtype,
									'std'		=> ''
								),
						            
							array(	
									'name'		=> __( 'Column count for small screens (between 480px and 767px - eg: Tablet Portrait)', 'avia_framework' ),
									'id'		=> 'av-small-columns',
									'type'		=> 'select',
									'subtype'	=> $subtype,
									'std'		=> ''
								),
						            
							array(	
									'name'		=> __( 'Column count for very small screens (smaller than 479px - eg: Smartphone Portrait)', 'avia_framework' ),
									'id'		=> 'av-mini-columns',
									'type'		=> 'select',
									'subtype'	=> $subtype,
									'std'		=> ''
								),  	
							  
				);
			
			return $template;
		}
		
		/**
		 * Selectbox for <h. > tag and inputfield for custom class
		 * 
		 * @since 4.5.7.2
		 * @param array $element
		 * @return array
		 */
		protected function heading_tag( array $element )
		{
			$setting = Avia_Builder()->get_developer_settings( 'heading_tags' );
			$class = in_array( $setting, array( 'deactivate', 'hide' ) ) ? 'avia-hidden' : '';
			
			$allowed = array( 
							__( 'Theme default', 'avia_framework' )	=> '',
							'H1'	=> 'h1', 
							'H2'	=> 'h2', 
							'H3'	=> 'h3', 
							'H4'	=> 'h4', 
							'H5'	=> 'h5', 
							'H6'	=> 'h6',
							'P'		=> 'p',
							'DIV'	=> 'div',
							'SPAN'	=> 'span'
						);
			
			
			$rendered_subtype = isset( $element['subtype'] ) ? $element['subtype'] : $allowed;
			$default = isset( $element['theme_default'] ) ? $element['theme_default'] : array_keys( $rendered_subtype )[0];
			
			/**
			 * Filter possible tags for element
			 * 
			 * @since 4.5.7.2
			 * @param array $rendered_subtype
			 * @param array $element
			 * @return array
			 */
			$subtype = apply_filters( 'avf_alb_element_heading_tags', $rendered_subtype, $element );
			if( ! is_array( $subtype ) || empty( $subtype ) )
			{
				$subtype = $rendered_subtype;
			}
			
			$std = isset( $element['std'] ) ? $element['std'] : '';
			if( ! in_array( $std, $subtype ) )
			{
				$std = ( 1 == count( $subtype ) ) ? array_values( $subtype )[0] : array_values( $subtype )[1];
			}
			
			$template = array();
				
			$templ = array(	
							'name'				=> sprintf( __( 'Heading Tag (Theme Default is &lt;%s&gt;)', 'avia_framework' ), $default ),
							'desc'				=> __( 'Select a heading tag for this element. Enfold only provides CSS for theme default tags, so it might be necessary to add a custom CSS class below and adjust the CSS rules for this element.', 'avia_framework' ),
							'id'				=> 'heading_tag',
							'container_class'	=> $class,
							'type'				=> 'select',
							'subtype'			=> $subtype,
							'std'				=> $std
						);
			
			if( isset( $element['required'] ) && is_array( $element['required'] ) )
			{
				$templ['required'] = $element['required'];
			}
			
			$template[] = $templ;
				
			$templ = array(	
							'name'				=> __( 'Custom CSS Class For Heading Tag', 'avia_framework' ),
							'desc'				=> __( 'Add a custom css class for the heading here. Make sure to only use allowed characters (latin characters, underscores, dashes and numbers).', 'avia_framework' ),
							'id'				=> 'heading_class',
							'container_class'	=> $class,
							'type'				=> 'input',
							'std'				=> ''
						);
			
			if( isset( $element['required'] ) && is_array( $element['required'] ) )
			{
				$templ['required'] = $element['required'];
			}
			
			$template[] = $templ;
			
			return $template;
		}
		
		/**
		 *  Selectboxes for WooCommerce Options for non product elements
		 * 
		 * @since 4.5.7.1
		 * @param array $element
		 * @return array
		 */
		protected function wc_options_non_products( array $element )
		{
			$required = array( 'link', 'parent_in_array', implode( ' ', get_object_taxonomies( 'product', 'names' ) ) );
			
			$sort = array( 
							__( 'Use default (defined at Dashboard -&gt; Customize -&gt; WooCommerce)', 'avia_framework' )	=> '',
							__( 'Sort alphabetically', 'avia_framework' )			=> 'title',
							__( 'Sort by most recent', 'avia_framework' )			=> 'date',
							__( 'Sort by price', 'avia_framework' )					=> 'price',
							__( 'Sort by popularity', 'avia_framework' )			=> 'popularity',
							__( 'Sort randomly', 'avia_framework' )					=> 'rand',
							__( 'Sort by menu order and name', 'avia_framework' )	=> 'menu_order',
							__( 'Sort by average rating', 'avia_framework' )		=> 'rating',
							__( 'Sort by relevance', 'avia_framework' )				=> 'relevance',
							__( 'Sort by Product ID', 'avia_framework' )			=> 'id'
						);
			
			/**
			 * @since 4.5.7.1
			 * @param array $element
			 * @return array
			 */
			$sort = apply_filters( 'avf_alb_wc_options_non_products_sort', $sort, $element );
			
			
			$template = array();
			
			$template[] = array(
								'name'		=> __( 'WooCommerce Out of Stock Product visibility', 'avia_framework' ),
								'desc'		=> __( 'Select the visibility of WooCommerce products. Default setting can be set at Woocommerce -&gt Settings -&gt Products -&gt Inventory -&gt Out of stock visibility', 'avia_framework' ),
								'id'		=> 'wc_prod_visible',
								'type'		=> 'select',
								'std'		=> '',
								'required'	=> $required,
								'subtype'	=> array(
													__( 'Use default WooCommerce Setting (Settings -&gt; Products -&gt; Out of stock visibility)', 'avia_framework' ) => '',
													__( 'Hide products out of stock', 'avia_framework' )	=> 'hide',
													__( 'Show products out of stock', 'avia_framework' )	=> 'show'
												)
							);
					
			$template[] = array(
								'name'		=> __( 'WooCommerce Sorting Options', 'avia_framework' ),
								'desc'		=> __( 'Here you can choose how to sort the products. Default setting can be set at Dashboard -&gt; Appearance -&gt; Customize -&gt; WooCommerce -&gt; Product Catalog -&gt; Default Product Sorting', 'avia_framework' ),
								'id'		=> 'prod_order_by',
								'type'		=> 'select',
								'std'		=> '',
								'required'	=> $required,
								'subtype'	=> $sort
							);
				
			$template[] = array(
								'name'		=> __( 'WooCommerce Sorting Order', 'avia_framework' ),
								'desc'		=> __( 'Here you can choose the order of the result products. Default setting can be set at Dashboard -&gt; Appearance -&gt; Customize -&gt; WooCommerce -&gt; Product Catalog -&gt; Default Product Sorting', 'avia_framework' ),
								'id'		=> 'prod_order',
								'type'		=> 'select',
								'std'		=> '',
								'required'	=> $required,
								'subtype'	=> array( 
													__( 'Use default (defined at Dashboard -&gt; Customize -&gt; WooCommerce)', 'avia_framework' ) => '',
													__( 'Ascending', 'avia_framework' )			=> 'ASC',
													__( 'Descending', 'avia_framework' )		=> 'DESC'
												)
							);
			
			return $template;
		}
		
		
		/**
		 *  Selectboxes for WooCommerce Options for product elements
		 * 
		 * @since 4.5.7.1
		 * @param array $element
		 * @return array
		 */
		protected function wc_options_products( array $element )
		{
			
			$sort = array( 
							__( 'Use default (defined at Dashboard -&gt; Customize -&gt; WooCommerce)', 'avia_framework' )	=> '0',
							__( 'Sort alphabetically', 'avia_framework' )			=> 'title',
							__( 'Sort by most recent', 'avia_framework' )			=> 'date',
							__( 'Sort by price', 'avia_framework' )					=> 'price',
							__( 'Sort by popularity', 'avia_framework' )			=> 'popularity',
							__( 'Sort randomly', 'avia_framework' )					=> 'rand',
							__( 'Sort by menu order and name', 'avia_framework' )	=> 'menu_order',
							__( 'Sort by average rating', 'avia_framework' )		=> 'rating',
							__( 'Sort by relevance', 'avia_framework' )				=> 'relevance',
							__( 'Sort by Product ID', 'avia_framework' )			=> 'id'
						);
			
			$sort_std = '0';
			
			if( ! empty( $element['sort_dropdown'] ) )
			{
				$sort = array_merge( array( __( 'Let user pick by displaying a dropdown with sort options (default value is defined at Default product sorting)', 'avia_framework' ) => 'dropdown' ), $sort );
				$sort_std = 'dropdown';
			}
			
			/**
			 * @since 4.5.7.1
			 * @param array $element
			 * @return array
			 */
			$sort = apply_filters( 'avf_alb_wc_options_non_products_sort', $sort, $element );
			
			$template = array();
			
			$template[] = array(
								'name'		=> __( 'WooCommerce Out of Stock Product visibility', 'avia_framework' ),
								'desc'		=> __( 'Select the visibility of WooCommerce products. Default setting can be set at Woocommerce -&gt Settings -&gt Products -&gt Inventory -&gt Out of stock visibility', 'avia_framework' ),
								'id'		=> 'wc_prod_visible',
								'type'		=> 'select',
								'std'		=> '',
								'subtype'	=> array(
													__( 'Use default WooCommerce Setting (Settings -&gt; Products -&gt; Out of stock visibility)', 'avia_framework' ) => '',
													__( 'Hide products out of stock', 'avia_framework' )	=> 'hide',
													__( 'Show products out of stock', 'avia_framework' )	=> 'show'
												)
							);
					
				
			$template[] = array(
								'name'		=> __( 'WooCommerce Hidden Products visibility', 'avia_framework' ),
								'desc'		=> __( 'Select the visibility of WooCommerce products depending on catalog visibility. Can be set independently for each product: Edit Product -&gt Publish panel -&gt Catalog visibility', 'avia_framework' ),
								'id'		=> 'wc_prod_hidden',
								'type'		=> 'select',
								'std'		=> '',
								'subtype'	=> array(
													__( 'Show all products', 'avia_framework' )			=> '',
													__( 'Hide hidden products', 'avia_framework' )		=> 'hide',
													__( 'Show hidden products only', 'avia_framework' )	=> 'show'
												)
							);
				
			$template[] = array(
								'name'		=> __( 'WooCommerce Featured Products visibility', 'avia_framework' ),
								'desc'		=> __( 'Select the visibility of WooCommerce products depending on checkbox &quot;This is a featured product&quot; in catalog visibility. Can be set independently for each product: Edit Product -&gt Publish panel -&gt Catalog visibility', 'avia_framework' ),
								'id'		=> 'wc_prod_featured',
								'type'		=> 'select',
								'std'		=> '',
								'subtype'	=> array(
													__( 'Show all products', 'avia_framework' )				=> '',
													__( 'Hide featured products', 'avia_framework' )		=> 'hide',
													__( 'Show featured products only', 'avia_framework' )	=> 'show'
												)
							);
				
			$template[] = array(
								'name'		=> __( 'WooCommerce Sidebar Filters', 'avia_framework' ),
								'desc'		=> __( 'Allow to filter products for this element using the 3 WooCommerce sidebar filters: Filter Products by Price, Rating, Attribute. These filters are only shown on the selected WooCommerce Shop page (WooCommerce -&gt; Settings -&gt; Products -&gt; General -&gt; Shop Page) or on product category pages. You may also use a custom widget area for the sidebar.', 'avia_framework' ),
								'id'		=> 'wc_prod_additional_filter',
								'type'		=> 'select',
								'std'		=> '',
								'subtype'	=> array(
													__( 'Ignore filters', 'avia_framework' )	=> '',
													__( 'Use filters', 'avia_framework' )		=> 'use_additional_filter'
												)
							);		
			
			$template[] = array(
								'name'		=> __( 'WooCommerce Sorting Options', 'avia_framework' ),
								'desc'		=> __( 'Here you can choose how to sort the products. Default setting can be set at Dashboard -&gt; Appearance -&gt; Customize -&gt; WooCommerce -&gt; Product Catalog -&gt; Default Product Sorting', 'avia_framework' ),
								'id'		=> 'sort',
								'type'		=> 'select',
								'std'		=> $sort_std,
								'subtype'	=> $sort
							);
									
			$template[] = array(
								'name'		=> __( 'WooCommerce Sorting Order', 'avia_framework' ),
								'desc'		=> __( 'Here you can choose the order of the result products. Default setting can be set at Dashboard -&gt; Appearance -&gt; Customize -&gt; WooCommerce -&gt; Product Catalog -&gt; Default Product Sorting', 'avia_framework' ),
								'id'		=> 'prod_order',
								'type'		=> 'select',
								'std'		=> '',
								'subtype'	=> array( 
													__( 'Use default (defined at Dashboard -&gt; Customize -&gt; WooCommerce)', 'avia_framework' ) => '',
													__( 'Ascending', 'avia_framework' )			=> 'ASC',
													__( 'Descending', 'avia_framework' )		=> 'DESC'
												)
							);
			
			return $template;
		}
	
	}
	
	/**
	 * Returns the main instance of Avia_Popup_Templates to prevent the need to use globals
	 * 
	 * @since 4.3.2
	 * @return Avia_Popup_Templates
	 */
	function AviaPopupTemplates() 
	{
		return Avia_Popup_Templates::instance();
	}
	
}		//	end Avia_Popup_Templates

