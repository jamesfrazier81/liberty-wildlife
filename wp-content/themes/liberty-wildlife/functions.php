<?php

// Enqueue styles and scripts
function enqueue_child_theme_styles() {
    wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri() . '/bower_components/font-awesome/css/font-awesome.min.css');
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
		body.login div#login h1 a {
			background-image: url('/wp-content/themes/liberty-wildlife/img/dist/logo-liberty-wildlife.png');
			background-size: 100%;
			width: 100%;
			height: 187px;
		}
		body.login div#login form#loginform {
			background-color: rgba(255, 255, 255, 0.5);
			/*box-shadow: none;*/
		}
		body.login div#login form#loginform p label {
			font-weight: bold;
			color: #da5834;
			text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
		}
		body.login div#login form#loginform input#user_login {
			background-color: rgba(255, 255, 255, 0.5);
			color: #da5834;
		}
		body.login div#login form#loginform input#user_pass {
			background-color: rgba(255, 255, 255, 0.5);
			color: #da5834;
		}
		body.login div#login form#loginform p.submit input#wp-submit {
			background-color: #da5834;
			border-color: transparent;
			box-shadow: none;
			text-shadow: none;
		}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

// Add current year shortcode for Enfold footer settings
function avia_year_func( $atts ){
	return date("Y");
}
add_shortcode( 'cur_year', 'avia_year_func' );

?>