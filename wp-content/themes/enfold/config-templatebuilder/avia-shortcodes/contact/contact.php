<?php
/**
 * Contact Form
 * 
 * Displays a customizable contact form
 */
if ( ! defined( 'ABSPATH' ) ) {  exit;  }    // Exit if accessed directly


if ( !class_exists( 'avia_sc_contact' ) )
{
	class avia_sc_contact extends aviaShortcodeTemplate
	{
			/**
			 * Create the config array for the shortcode button
			 */
			function shortcode_insert_button()
			{
				$this->config['self_closing']	=	'no';
				
				$this->config['name']		= __('Contact Form', 'avia_framework' );
				$this->config['tab']		= __('Content Elements', 'avia_framework' );
				$this->config['icon']		= AviaBuilder::$path['imagesURL']."sc-contact.png";
				$this->config['order']		= 43;
				$this->config['target']		= 'avia-target-insert';
				$this->config['shortcode'] 	= 'av_contact';
				$this->config['shortcode_nested'] = array('av_contact_field');
				$this->config['tooltip'] 	= __('Creates a customizable contact form', 'avia_framework' );
				$this->config['preview'] 	= "large";
				$this->config['disabling_allowed'] = true;
				
				$this->config['id_name']	= 'id';
				$this->config['id_show']	= 'yes';
				$this->config['aria_label']	= 'yes';
				$this->config['alb_desc_id']	= 'alb_description';
			}
			
			function extra_assets()
			{
				//load css
				wp_enqueue_style( 'avia-module-contact' , AviaBuilder::$path['pluginUrlRoot'].'avia-shortcodes/contact/contact.css' , array('avia-layout'), false );
				
					//load js
				wp_enqueue_script( 'avia-module-contact' , AviaBuilder::$path['pluginUrlRoot'].'avia-shortcodes/contact/contact.js' , array('avia-shortcodes'), false, true );

			
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
				$link = '<a target="_blank" href="' . admin_url( 'admin.php?page=avia#goto_google' ) . '">' . __( 'activated here', 'avia_framework' ) . '</a>';
				$captcha_desc  = __( 'Do you want to display a Captcha field at the end of the form so users must prove they are human?', 'avia_framework' ) . '</br></br>';
				$captcha_desc .= __( 'Either by solving a simply mathematical question or by Google reCaptcha, that needs to be', 'avia_framework' ) . ' ' . $link . '. ';
				$captcha_desc .= __( 'In case Google reCAPTCHA is deactivated (maybe later) in theme options, Enfold captcha will be used, if you selected to use V2 then V2 will be used for this contact form (even if you selected V3 in theme options). If you selected V3 here and the score fails or you did not selected V3 in theme options then V2 will be used to check if user is a human.', 'avia_framework' );
				$captcha_desc .= '</br></br>';
				$captcha_desc .= __( '(It is recommended to only activate this if you receive spam from your contact form, since an invisible spam protection is also implemented that should filter most spam messages by robots anyway)', 'avia_framework' );

				$default_from = parse_url( home_url() );
				$default_from = ( ! empty( $default_from['host'] ) ) ? "no-reply@{$default_from['host']}" : 'no-reply@wp-message.com';
				
				$this->elements = apply_filters( 'avf_sc_contact_popup_elements',  array(
						
						array(
								"type" 	=> "tab_container", 'nodescription' => true
							),
							
						array(
								"type" 	=> "tab",
								"name"  => __("Form" , 'avia_framework'),
								'nodescription' => true
							),
						
						array(
						"name" 	=> __("Your email address", 'avia_framework' ),
						"desc" 	=> __("Enter one or more Email addresses (separated by comma) where mails should be delivered to.", 'avia_framework' ) ." (".__("Default:", 'avia_framework' ) ." ". get_option('admin_email').")",
						"id" 	=> "email",
						'container_class' =>"avia-element-fullwidth",
						"std" 	=> get_option('admin_email'),
						"type" 	=> "input"),
					
						array(
								'name'	=> __( 'Your from address', 'avia_framework' ),
								'desc'	=> sprintf( __( 'Enter your from address for the form - if left blank it will default to user email or %s', 'avia_framework' ), $default_from ),
								'id'	=> 'from_email',
								'std'	=> '',
								'type'	=> 'input'
							),
					
						array(
						"name" 	=> __("Form Title", 'avia_framework' ),
						"desc" 	=> __("Enter a form title that is displayed above the form", 'avia_framework' ),
						"id" 	=> "title",
						"std" 	=> __("Send us mail", 'avia_framework' ),
						"type" 	=> "input"),
					
						array(	
								'type'				=> 'template',
								'template_id'		=> 'heading_tag',
								'theme_default'		=> 'h3',
								'context'			=> __CLASS__
							),

						array(
							"name" => __("Add/Edit Contact Form Elements", 'avia_framework' ),
							"desc" => __("Here you can add, remove and edit the form Elements of your contact form.", 'avia_framework' )."<br/>".
									  __("Available form elements are: single line Input elements, Textareas, Checkboxes and Select-Dropdown menus.", 'avia_framework' )."<br/><br/>".
									  __("It is recommended to not delete the 'E-Mail' field if you want to use an auto responder.", 'avia_framework' ),

							"type" 			=> "modal_group",
							"id" 			=> "content",
							"modal_title" 	=> __("Edit Form Element", 'avia_framework' ),
							"std"			=> array(

													array('label'=>__('Name', 'avia_framework' ), 'type'=>'text', 'check'=>'is_empty'),
													array('label'=>__('E-Mail', 'avia_framework' ), 'type'=>'text', 'check'=>'is_email'),
													array('label'=>__('Subject', 'avia_framework' ), 'type'=>'text', 'check'=>'is_empty'),
													array('label'=>__('Message', 'avia_framework' ), 'type'=>'textarea', 'check'=>'is_empty'),

													),


							'subelements' 	=> array(

									array(
									"name" 	=> __("Form Element Label", 'avia_framework' ),
									"desc" 	=> "",
									"id" 	=> "label",
									"std" 	=> "",
									"type" 	=> "input"),


							        array(
									"name" 	=> __("Form Element Type", 'avia_framework' ),
									"desc" 	=> "",
									"id" 	=> "type",
									"type" 	=> "select",
									"std" 	=> "text",
									"no_first"=>true,
									"subtype" => array(	__('Form Element: Text Input', 'avia_framework' ) =>'text',
														__('Form Element: Text Area', 'avia_framework' ) =>'textarea',
														__('Form Element: Select Element', 'avia_framework' ) =>'select',
														__('Form Element: Checkbox', 'avia_framework' ) =>'checkbox',
														__('Form Element: Datepicker', 'avia_framework' ) =>'datepicker',
														__('Custom HTML: Add a Description', 'avia_framework' ) =>'html',
														)),

									array(
										"name" 	=> __("Form Element Options", 'avia_framework' ) ,
										"desc" 	=> __("Enter any number of options that the visitor can choose from. Separate these Options with a comma.", 'avia_framework' ) ."<br/><small>".
												   __("Example: Option 1, Option 2, Option 3", 'avia_framework' )."</small>"."<br/><small>".
												   __("Note: If you want to use a comma in the option text you have to write 2 comma.", 'avia_framework' )."</small>" ,

										"id" 	=> "options",
										"required" => array('type','equals','select'),
										"std" 	=> "",
										"type" 	=> "input"),
										
									
										 array(	
										"name" 	=> __("Multiple answers", 'avia_framework' ),
										"desc" 	=> __("Check if you want to enable multiple answers", 'avia_framework' ) ,
										"id" 	=> "multi_select",
										"required" => array('type','equals','select'),
										"std" 	=> "",
										"type" 	=> "checkbox"),	
										
									array(	
										"name" 	=> __("Preselect checkbox", 'avia_framework' ),
										"desc" 	=> __("Check if you want to preselect the checkbox", 'avia_framework' ) ,
										"id" 	=> "av_contact_preselect",
										"required" => array('type','equals','checkbox'),
										"std" 	=> "",
										"type" 	=> "checkbox"),	
										
									array(
										"name" 	=> __("Add Description", 'avia_framework' ) ,
										"id" 	=> "content",
										"required" => array('type','equals','html'),
										"std" 	=> "",
										"type" 	=> "tiny_mce"),
									
									
								    array(
									"name" 	=> __("Form Element Validation", 'avia_framework' ),
									"desc" 	=> "When selecting &quot;Valid E-Mail address with special characters&quot; keep in mind, that not all E-Mail systems support this feature properly.",
									"id" 	=> "check",
									"type" 	=> "select",
									"std" 	=> "",
									"no_first"=>true,
									"required" => array('type','not','html'),
									"subtype" => array(	__('No Validation', 'avia_framework' ) =>'',
														__('Is not empty', 'avia_framework' ) =>'is_empty',
														__('Valid E-Mail address', 'avia_framework' ) =>'is_email',
														__('Valid E-Mail address with special characters', 'avia_framework' ) =>'is_ext_email',
														__('Valid Phone Number', 'avia_framework' ) =>'is_phone',
														__('Valid Number', 'avia_framework' ) =>'is_number')),

									 array(
									"name" 	=> __("Form Element Width", 'avia_framework' ),
									"desc" 	=> __("Change the width of your elements and let them appear beside each other instead of underneath", 'avia_framework' ) ,
									"id" 	=> "width",
									"type" 	=> "select",
									"std" 	=> "",
									"no_first"=>true,
									"required" => array('type','not','html'),
									"subtype" => array(	"Fullwidth" =>'', "1/2" =>'element_half', "1/3" =>'element_third' , "2/3" =>'element_two_third', "1/4" => 'element_fourth', "3/4" => 'element_three_fourth')),

						)
					),

						array(
						"name" 	=> __("Submit Button Label", 'avia_framework' ),
						"desc" 	=> __("Enter the submit buttons label text here", 'avia_framework' ),
						"id" 	=> "button",
						"std" 	=> __("Submit", 'avia_framework' ),
						"type" 	=> "input"),
						
						
						array(
						"name" 	=> __("What should happen once the form gets sent?", 'avia_framework' ),
						"desc" 	=> "",
						"id" 	=> "on_send",
						"type" 	=> "select",
						"std" 	=> "",
						"no_first"=>true,
						"subtype" => array(	__('Display a short message on the same page', 'avia_framework' ) =>'',
											__('Redirect the user to another page', 'avia_framework' ) =>'redirect',
											)),
						

						array(
						"name" 	=> __("Message Sent label", 'avia_framework' ),
						"desc" 	=> __("What should be displayed once the message is sent?", 'avia_framework' ),
						"id" 	=> "sent",
						"required" => array('on_send','not','redirect'),
						"std" 	=> __("Your message has been sent!", 'avia_framework' ),
						"type" 	=> "input"),
						
						
						array(
	                    "name" 	=> __("Redirect", 'avia_framework' ),
	                    "desc" 	=> __("To which page do you want the user send to?", 'avia_framework' ),
	                    "id" 	=> "link",
	                    "type" 	=> "linkpicker",
	                    "fetchTMPL"	=> true,
	                    "std"	=> "",
						"required" => array('on_send','equals','redirect'),
	                    "subtype" => array(
	                        __('Set Manually', 'avia_framework' ) =>'manually',
	                        __('Single Entry', 'avia_framework' ) =>'single'
	                    ),
	                    "std" 	=> ""),
						
						array(
							"name" 	=> __("E-Mail Subject", 'avia_framework' ),
							"desc" 	=> __("You can define a custom Email Subject for your form here. If left empty the subject will be", 'avia_framework' ).": <small>".__("New Message", 'avia_framework') . " (".__('sent by contact form at','avia_framework')." ".get_option('blogname').")</small>" ,
							"id" 	=> "subject",
							"std" 	=> "",
							"type" 	=> "input"
							),
					
						array(
							"name" 	=> __("Autoresponder from email address", 'avia_framework' ),
							"desc" 	=> __("Enter the from email address for the autoresponder.", 'avia_framework' ) . " (" .__( "Default:", 'avia_framework' ) . " " . get_option( 'admin_email' ) . ")",
							"id" 	=> "autoresponder_email",
							"std" 	=> get_option('admin_email'),
							"type" 	=> "input"
							),
						
						
		 				array(
							"name" 	=> __("Autorespond Text", 'avia_framework' ),
							"desc" 	=> __("Enter a message that will be sent to the users email address once he has submitted the form.", 'avia_framework' )."<br/><br/>".
									   __("If left empty no auto-response will be sent.", 'avia_framework' ),
							"id" 	=> "autorespond",
							"std" 	=> "",
							"type" 	=> "textarea"
							),

						array(
							'name'		=> __( 'Contact Form Captcha', 'avia_framework' ),
							'desc'		=> $captcha_desc,
							'id'		=> 'captcha',
							'type'		=> 'select',
							'std'		=> '',
							'subtype'	=> array(
												__( 'Don\'t display Captcha', 'avia_framework' )								=> '', 
												__( 'Use Enfold Numeric Captcha', 'avia_framework' )							=> 'active',
												__( 'Use Google reCAPTCHA V2 if activated', 'avia_framework' )					=> 'recaptcha_v2',
												__( 'Use Google reCAPTCHA V3 if activated (fallback is V2)', 'avia_framework' )	=> 'recaptcha_v3'
											)
							),
					
						array(
							'name'		=> __( 'reCAPTCHA V2 theme color', 'avia_framework' ),
							'desc'		=> __( 'Select a theme color for this contact form widget', 'avia_framework' ),
							'id'		=> 'captcha_theme',
							'type'		=> 'select',
							'required'	=> array( 'captcha', 'parent_in_array', 'recaptcha_v2 recaptcha_v3' ),
							'std'		=> 'light',
							'subtype'	=> array( 
												__( 'Light', 'avia_framework' )		=> 'light',
												__( 'Dark', 'avia_framework' )		=> 'dark'
											)
							),
					
						array(
							'name'		=> __( 'reCAPTCHA V2 theme size', 'avia_framework' ),
							'desc'		=> __( 'Select a size for this contact form widget', 'avia_framework' ),
							'id'		=> 'captcha_size',
							'type'		=> 'select',
							'required'	=> array( 'captcha', 'parent_in_array', 'recaptcha_v2 recaptcha_v3'),
							'std'		=> 'normal',
							'subtype'	=> array(
												__( 'Normal', 'avia_framework' )		=> 'normal',
												__( 'Compact', 'avia_framework' )		=> 'compact'
											)
							),
					
						array(	
								'name'		=> __( 'Select score for human', 'avia_framework' ),
								'id'		=> 'captcha_score',
								'desc'		=> __( 'A score of 1.0 is very likely a good interaction, 0.0 is very likely a bot. Google recommends a threshold of 0.5 by default. In case we encounter a non human we ask user to verify with Version 2 chckbox.', 'avia_framework' ),
								'type'		=> 'select',
								'required'	=> array( 'captcha', 'equals', 'recaptcha_v3' ),
								'subtype'	=> AviaHtmlHelper::number_array( 0, 1, 0.1, array( __( 'Default', 'avia_framework' ) => '' ) ),
								'std'		=> '0.5'
							),
						
						array(	
							"name" 	=> __("Hide Form Labels", 'avia_framework' ),
							"desc" 	=> __("Check if you want to hide form labels above the form elements. The form will instead try to use an inline label (not supported on old browsers)", 'avia_framework' ) ,
							"id" 	=> "hide_labels",
							"std" 	=> "",
							"type" 	=> "checkbox"),
							
						array(
	                    "name" 	=> __("Label/Send Button alignment", 'avia_framework' ),
	                    "desc" 	=> __("Select how to align the form labels and the send button", 'avia_framework' ),
	                    "id" 	=> "form_align",
	                    "type" 	=> "select",
	                    "std"	=> "",
	                    "subtype" => array(
	                        __('Default', 'avia_framework' ) 		=>'',
	                        __('Centered', 'avia_framework' ) 	=> 'centered'
	                    ),
	                    "std" 	=> ""),
						
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
							"name" 	=> __("Form Color Scheme", 'avia_framework' ),
							"desc" 	=> __("Select a form color scheme here", 'avia_framework' ),
							"id" 	=> "color",
							"type" 	=> "select",
							"std" 	=> "",
							"subtype" => array( __('Default', 'avia_framework' )=>'',
												__('Light transparent', 'avia_framework' )=>'av-custom-form-color av-light-form',
												__('Dark transparent', 'avia_framework' ) =>'av-custom-form-color av-dark-form'),
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

				));


			}

			/**
			 * Editor Sub Element - this function defines the visual appearance of an element that is displayed within a modal window and on click opens its own modal window
			 * Works in the same way as Editor Element
			 * @param array $params this array holds the default values for $content and $args.
			 * @return $params the return array usually holds an innerHtml key that holds item specific markup.
			 */
			function editor_sub_element($params)
			{
				$template = $this->update_template("label", __("Element", 'avia_framework' ). ": {{label}}");

				$params['innerHtml']  = "";
				$params['innerHtml'] .= "<div class='avia_title_container'>";
				$params['innerHtml'] .=	"<div ".$this->class_by_arguments('check' ,$params['args']).">";
				$params['innerHtml'] .= "<span {$template} >".__("Element", 'avia_framework' ). ": ".$params['args']['label']."</span>";
				$params['innerHtml'] .= "<span class='av-required-indicator'> *</span>";
				$params['innerHtml'] .=	"</div>";
				$params['innerHtml'] .=	"</div>";
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
				
				extract( AviaHelper::av_mobile_sizes( $atts ) ); //return $av_font_classes, $av_title_font_classes and $av_display_classes 
				
				$meta = aviaShortcodeTemplate::set_frontend_developer_heading_tag( $atts, $meta );
				
				$atts =  shortcode_atts( apply_filters( 'avf_sc_contact_default_atts', array(
					
								'email'				=> get_option( 'admin_email' ),
								'from_email'		=> '',
								'button'			=> __( "Submit", 'avia_framework' ),
								'autoresponder_email'	=> '',
								'autorespond'		=> '',
								'captcha'			=> '',
								'captcha_theme'		=> 'light',
								'captcha_size'		=> 'normal',
								'captcha_score'		=> '',
								'subject'			=> '',
								'on_send'			=> '',
								'link'				=> '',
								'sent'				=> __( "Your message has been sent!", 'avia_framework' ),
								'title'				=> __( "Send us mail", 'avia_framework' ),
								'color'				=> "",
								'hide_labels'		=> "",
								'form_align'		=> ""

					) ), $atts, $this->config['shortcode'] );
				
				/**
				 * For backwards comp. only - can be removed in future versions
				 * In this case set default value in shortcode_atts to get_option('admin_email')
				 * 
				 * @since 4.4.2
				 */
				if( empty( $atts['autoresponder_email'] ) )
				{
					$atts['autoresponder_email'] = $atts['email'];
				}
				
				/**
				 * For backwards comp. only - can be removed in future versions
				 * 
				 * @since 4.6.2
				 */
				if( empty( $atts['captcha_theme'] ) )
				{
					$atts['captcha_theme'] = 'light';
				}
				
				extract($atts);

				$post_id  = function_exists('avia_get_the_id') ? avia_get_the_id() : get_the_ID();
				$redirect = !empty($on_send) ? AviaHelper::get_url($link) : "";
				
				if(!empty($form_align)) $meta['el_class'] .= " av-centered-form ";
				
				$default_heading = ! empty( $meta['heading_tag'] ) ? $meta['heading_tag'] : 'h3';
				$args = array(
							'heading'		=> $default_heading,
							'extra_class'	=> $meta['heading_class']
						);

				$extra_args = array( $this, $atts, $content, 'title' );

				/**
				 * @since 4.5.7.2
				 * @return array
				 */
				$args = apply_filters( 'avf_customize_heading_settings', $args, __CLASS__, $extra_args );

				$heading = ! empty( $args['heading'] ) ? $args['heading'] : $default_heading;
				$css = ! empty( $args['extra_class'] ) ? $args['extra_class'] : $meta['heading_class'];
				
				
				$form_args = array(
								"heading" 				=> $title ? "<{$heading} class='{$css}'>{$title}</{$heading}>" : "",
								"success" 				=> "<{$heading} class='avia-form-success {$css}'>{$sent}</{$heading}>",
								"submit"  				=> $button,
								"myemail" 				=> $email,
								'myfrom'				=> $from_email,
								"action"  				=> get_permalink($post_id),
								"myblogname" 			=> get_option('blogname'),
								"autoresponder" 		=> $autorespond,
								"autoresponder_subject" => __( 'Thank you for your Message!', 'avia_framework' ),
								"autoresponder_email" 	=> $autoresponder_email,
								"subject"				=> $subject,
								"form_class" 			=> $meta['el_class']." ".$color." ".$av_display_classes,
								"multiform"  			=> true, //allows creation of multiple forms without id collision
								"label_first"  			=> true,
								"redirect"				=> $redirect,
								"placeholder"			=> $hide_labels,
								"numeric_names"			=> true,
								'el-id'					=> $meta['custom_el_id'],
								'aria_label'			=> $meta['aria_label'],
							);
				
				if(trim($form_args['myemail']) == '') $form_args['myemail'] = get_option('admin_email');


				$content = str_replace("\,", "&#44;", $content );
				
				//form fields passed by the user
				$form_fields = $this->helper_array2form_fields(ShortcodeHelper::shortcode2array($content, 1));

				//fake username field that is not visible. if the field has a value a spam bot tried to send the form
				$elements['avia_username']  = array('type'=>'decoy', 'label'=>'', 'check'=> 'must_empty' );

				//captcha field for the user to verify that he is real
				$google = in_array( $captcha, array( 'recaptcha_v2', 'recaptcha_v3' ) );
				if( 'active' == $captcha || ( $google && Avia_Google_reCAPTCHA()->is_loading_prohibited() ) )
				{
					$elements['avia_age'] = array(
												'type'	=> 'captcha', 
												'check'	=> 'captcha', 
												'label'	=> __( 'Please prove that you are human by solving the equation', 'avia_framework' )
											);
				}
				else if( $google )
				{	
					$elements['avia_age'] = array(
												'type'				=> 'grecaptcha', 
												'container_class'	=> '',
												'custom_class'		=> '',
												'context'			=> 'av_contact_form',
												'token_input'		=> 'av_recaptcha_token',
												'version'			=> 'avia_' . $captcha,
												'theme'				=> $captcha_theme,
												'size'				=> $captcha_size,
												'score'				=> $captcha_score,
												'text_to_preview'	=> Avia_Builder()->in_text_to_preview_mode()
											);
				}

				//merge all fields
				$form_fields = apply_filters('avia_contact_form_elements', array_merge($form_fields, $elements));
				$form_fields = apply_filters('avf_sc_contact_form_elements', $form_fields, $atts );
				$form_args   = apply_filters('avia_contact_form_args', $form_args, $post_id);

				$contact_form = new avia_form($form_args);
				$contact_form->create_elements($form_fields);
				$output = $contact_form->display_form(true);

				return $output;
			}



			/*helper function that converts the shortcode sub array into the format necessary for the contact form*/
			function helper_array2form_fields($base)
			{
				$form_fields = array();
                $labels = array();


				if(is_array($base))
				{
					foreach($base as $key => $field)
					{
                        			$sanizited_id = trim(strtolower($field['attr']['label']));

                        			$labels[$sanizited_id] = empty($labels[$sanizited_id]) ? 1 : $labels[$sanizited_id] + 1;
                        			if($labels[$sanizited_id] > 1) $sanizited_id = $sanizited_id . '_' . $labels[$sanizited_id];

						$form_fields[$sanizited_id] = $field['attr'];
						if(!empty($field['content'])) $form_fields[$sanizited_id]['content'] = ShortcodeHelper::avia_apply_autop($field['content']);
					}
				}

				return $form_fields;
			}




	}
}
