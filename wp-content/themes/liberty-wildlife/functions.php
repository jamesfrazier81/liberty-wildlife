<?php

// Enqueue styles and scripts
function enqueue_child_theme_styles() {
    wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/bower_components/font-awesome/css/font-awesome.min.css');
    wp_enqueue_style( 'animate-css', get_stylesheet_directory_uri() . '/bower_components/animate.css/animate.min.css');
	wp_enqueue_script( 'child-script', get_stylesheet_directory_uri() . '/js/dist/scripts.min.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles');

// Customize login screen
function my_login_logo() { ?>
    <style type="text/css">
        body.login {
        	background-image: url('/wp-content/themes/liberty-wildlife/img/dist/bg-wp-login.jpg');
        	background-repeat: no-repeat;
        	background-size: cover;
        	background-position: top center;
        }
        body.login div#login {
        	/*padding: 8%;
        	margin: 0;
        	float: right;*/
        }
		body.login div#login h1 a {
			background-image: url('/wp-content/themes/liberty-wildlife/img/dist/logo-liberty-wildlife-vert.png');
			background-size: 100%;
			width: 100%;
			height: 187px;
		}
		body.login div#login form#loginform {
			background-color: rgba(255, 255, 255, 0.75);
			/*box-shadow: none;*/
		}
		body.login div#login form#loginform p label {
			font-weight: bold;
			color: #da5834;
			text-shadow: 0 1px 0 rgba(255, 255, 255, 0.75);
		}
		body.login div#login form#loginform input#user_login {
			font-weight: normal;
			background-color: rgba(255, 255, 255, 0.5);
			color: #da5834;
		}
		body.login div#login form#loginform input#user_pass {
			font-weight: normal;
			background-color: rgba(255, 255, 255, 0.5);
			color: #da5834;
		}
		body.login div#login form#loginform input#aiowps-captcha-answer {
			font-weight: normal;
			background-color: rgba(255, 255, 255, 0.5);
			color: #da5834;
		}
		body.login div#login form#loginform p.submit input#wp-submit {
			background-color: #da5834;
			border-color: transparent;
			box-shadow: none;
			text-shadow: none;
		}
		body.login div#login p#nav {}
		body.login div#login p#nav a {
			font-weight: bold;
			color: #da5834;
			text-shadow: 0 1px 0 rgba(0, 0, 0, 0.25);
		}
		body.login div#login p#backtoblog {}
		body.login div#login p#backtoblog a {
			font-weight: bold;
			color: #da5834;
			text-shadow: 0 1px 0 rgba(0, 0, 0, 0.25);
		}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

// Add current year shortcode for Enfold footer settings
function avia_year_func( $atts ){
	return date("Y");
}
add_shortcode( 'cur_year', 'avia_year_func' );

// Add Avia Builder for CPT items
add_filter('avf_builder_boxes', 'avia_register_meta_boxes', 10, 1); //Add meta boxes to custom post types
function avia_register_meta_boxes($boxes) {
	if(!empty($boxes)) {
		foreach($boxes as $key => $box)	{
			$boxes[$key]['page'][] = 'team';
			$boxes[$key]['page'][] = 'publication';
			$boxes[$key]['page'][] = 'events';
		}
	}
	return $boxes;
}

?>