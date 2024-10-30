<?php
/*
Plugin Name:  Know Co. App Integration: Events
Description:  Create a client portal for your Events clients.
Version:      1.2.0
Author:       Know Co.
Author URI:   https://getknow.co/
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

defined( 'ABSPATH' ) or wp_die();

add_action('wp_head', 'know__events___ajax_url');

function know__events___ajax_url() {

   echo '<script type="text/javascript">
           var ajaxurl = "' . admin_url('admin-ajax.php') . '";
         </script>';
}

add_action( 'admin_init', 'know__events__register_settings' );
function know__events__register_settings(){
	register_setting('know--events--settings-group', 'know__events__button_styles');
	register_setting('know--events--settings-group', 'know__events__button_classes');

	register_setting('know--events--settings-group', 'know__events__alert_container_styles');
	register_setting('know--events--settings-group', 'know__events__alert_container_classes');
	register_setting('know--events--settings-group', 'know__events__alert_heading_styles');
	register_setting('know--events--settings-group', 'know__events__alert_heading_classes');

	register_setting('know--events--settings-group', 'know__events__login_button_classes');
	register_setting('know--events--settings-group', 'know__events__login_button_styles');

	register_setting('know--events--settings-group', 'know__events__payment_process_button_classes');
	register_setting('know--events--settings-group', 'know__events__payment_process_button_styles');
}

add_action('admin_menu', 'know__events__admin_menu', 11);
function know__events__admin_menu(){
    
	add_submenu_page(
	    'know_settings', // Parent
	    'Events Settings', // Page Title
	   	'Events', // Menu Title
	    'manage_options', // Capability
	    'know_events', // Menu Slug
	    'know__events__settings_init' // Render Function
	);

}

function know__events__settings_init(){


	?>

    <style>
    	.know--container{
    		padding-right: 10px;
    	}
    	.know--input{
    		width: 100%;
    	}
    </style>

    <div class="know--container">
	    <div class="wrap">
			<h1>Events Settings</h1>

			Will include fields to apply CSS classes and custom styling to components.<br>
			<br>
			To use the event portal, copy and paste the following shortcode to your page:<br>
			<code>[know--events--portal]</code>

			<?php settings_errors(); ?>

			<form method="post" action="options.php">
			    <?php settings_fields( 'know--events--settings-group' ); ?>
			    <?php do_settings_sections( 'know--events--settings-group' ); ?>

			    <h2>General</h2>
			    <table class="form-table">
			        <tr valign="top">
			        	<th scope="row">
			        		<label for="know__events__button_classes">Button Classes</label>
			        	</th>
			        	<td>
			        		<input type="text" name="know__events__button_classes" class="know--input" id="know__events__button_classes" value="<?php echo esc_attr( get_option('know__events__button_classes') ); ?>">
			        	</td>
			        </tr>
			        <tr valign="top">
			        	<th scope="row">
			        		<label for="know__events__button_styles">Button Styles</label>
			        	</th>
			        	<td>
			        		<input type="text" name="know__events__button_styles" class="know--input" id="know__events__button_styles" value="<?php echo esc_attr( get_option('know__events__button_styles') ); ?>">
			        	</td>
			        </tr>
			    </table>

			    <hr>

				<h2>Alerts</h2>
				<table class="form-table">
			        <tr valign="top">
			        	<th scope="row">
			        		<label for="know__events__alert_container_classes">Container Classes</label>
			        	</th>
			        	<td>
			        		<input type="text" name="know__events__alert_container_classes" class="know--input" id="know__events__alert_container_classes" value="<?php echo esc_attr( get_option('know__events__alert_container_classes') ); ?>">
			        	</td>
			        </tr>
			        <tr valign="top">
			        	<th scope="row">
			        		<label for="know__events__alert_container_styles">Container Styles</label>
			        	</th>
			        	<td>
			        		<input type="text" name="know__events__alert_container_styles" class="know--input" id="know__events__alert_container_styles" value="<?php echo esc_attr( get_option('know__events__alert_container_styles') ); ?>">
			        	</td>
			        </tr>
			        <tr valign="top">
			        	<th scope="row">
			        		<label for="know__events__alert_heading_classes">Heading Classes</label>
			        	</th>
			        	<td>
			        		<input type="text" name="know__events__alert_heading_classes" class="know--input" id="know__events__alert_heading_classes" value="<?php echo esc_attr( get_option('know__events__alert_heading_classes') ); ?>">
			        	</td>
			        </tr>
			        <tr valign="top">
			        	<th scope="row">
			        		<label for="know__events__alert_heading_styles">Heading Styles</label>
			        	</th>
			        	<td>
			        		<input type="text" name="know__events__alert_heading_styles" class="know--input" id="know__events__alert_heading_styles" value="<?php echo esc_attr( get_option('know__events__alert_heading_styles') ); ?>">
			        	</td>
			        </tr>
			    </table>

			    <hr>

				<h2>Login</h2>
				<table class="form-table">
			        <tr valign="top">
			        	<th scope="row">
			        		<label for="know__events__login_button_classes">Button Classes</label>
			        	</th>
			        	<td>
			        		<input type="text" name="know__events__login_button_classes" class="know--input" id="know__events__login_button_classes" value="<?php echo esc_attr( get_option('know__events__login_button_classes') ); ?>">
			        	</td>
			        </tr>
			        <tr valign="top">
			        	<th scope="row">
			        		<label for="know__events__login_button_styles">Button Styles</label>
			        	</th>
			        	<td>
			        		<input type="text" name="know__events__login_button_styles" class="know--input" id="know__events__login_button_styles" value="<?php echo esc_attr( get_option('know__events__login_button_styles') ); ?>">
			        	</td>
			        </tr>
			    </table>

			    <hr>

				<h2>Payments</h2>
				<table class="form-table">
			        <tr valign="top">
			        	<th scope="row">
			        		<label for="know__events__payment_process_button_classes">Button Classes</label>
			        	</th>
			        	<td>
			        		<input type="text" name="know__events__payment_process_button_classes" class="know--input" id="know__events__payment_process_button_classes" value="<?php echo esc_attr( get_option('know__events__payment_process_button_classes') ); ?>">
			        	</td>
			        </tr>
			        <tr valign="top">
			        	<th scope="row">
			        		<label for="know__events__payment_process_button_styles">Button Styles</label>
			        	</th>
			        	<td>
			        		<input type="text" name="know__events__payment_process_button_styles" class="know--input" id="know__events__payment_process_button_styles" value="<?php echo esc_attr( get_option('know__events__payment_process_button_styles') ); ?>">
			        	</td>
			        </tr>
			    </table>
			    
			    <?php submit_button(); ?>

			</form>
		</div>
	</div>

    <?php

}

add_action( 'wp_enqueue_scripts', 'know__events__scripts_and_stylesheets' );
function know__events__scripts_and_stylesheets() {
    
    /*wp_register_script( 'know--events--angular', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.6.6/angular.min.js');
    wp_register_script( 'know--events--angular-route', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.6.6/angular-route.min.js');
    wp_register_script( 'know--events--angular-sanitize', 'https://ajax.googleapis.com/ajax/libs/angularjs/1.6.6/angular-sanitize.min.js');
    
    wp_register_script( 'know--events--components--angular-mask', plugin_dir_url( __FILE__ ) . 'js/components/angular-mask/mask.js');
    wp_register_script( 'know--events--components--moment', plugin_dir_url( __FILE__ ) . 'js/components/moment/moment.js');

    wp_register_script( 'know--events--portal-app', plugin_dir_url( __FILE__ ) . 'portal/js/app.js', array(
    	'know--events--angular',
    	'know--events--angular-route',
    	'know--events--angular-sanitize',
    	'know--events--components--angular-mask',
    	'know--events--components--moment'
    ));

    wp_register_script( 'know--events--factories', plugin_dir_url( __FILE__ ) . 'portal/js/factories.js', array(
    	'know--events--portal-app'
    ));

    wp_register_script( 'know--events--directives', plugin_dir_url( __FILE__ ) . 'portal/js/directives.js', array(
    	'know--events--portal-app'
    ));

    wp_register_script( 'know--events--filters', plugin_dir_url( __FILE__ ) . 'portal/js/filters.js', array(
    	'know--events--portal-app'
    ));

    wp_register_script( 'know--events--portal-controllers', plugin_dir_url( __FILE__ ) . 'portal/js/controllers.js', array(
    	'know--events--portal-app'
    ));

    wp_register_script( 'know--events--portal-config', plugin_dir_url( __FILE__ ) . 'portal/js/config.js', array(
    	'know--events--portal-app'
    ));*/

    wp_register_script( 'know--events--vue', plugin_dir_url( __FILE__ ) . 'js/vue.js');
    wp_register_script( 'know--events--axios', plugin_dir_url( __FILE__ ) . 'js/axios.min.js');
    wp_register_script( 'know--events--vue-router', plugin_dir_url( __FILE__ ) . 'js/vue-router.js');
    wp_register_script( 'know--events--vue-http-loader', plugin_dir_url( __FILE__ ) . 'js/http-vue-loader.js');
    wp_register_script( 'know--events--components--moment', plugin_dir_url( __FILE__ ) . 'js/components/moment/moment.js');
    
    wp_register_script( 'know--events--portal-app', plugin_dir_url( __FILE__ ) . 'js/app.js', array(
    	'know--events--vue',
    	'know--events--axios',
    	'know--events--vue-router',
    	'know--events--vue-http-loader',
    	'know--events--components--moment'
    ));

    //https://family.fairvillefriends.org/family.js

	wp_register_style('know--events--portal-css', plugin_dir_url( __FILE__ ) . 'styles.css');
} 

add_shortcode('know--events--portal', 'know__events__my_event');
function know__events__my_event($atts = [], $content = null, $tag = ''){

	$output = '';

	if(!class_exists('know_platform')) return;

	wp_enqueue_script("jquery");
	wp_enqueue_script('know--events--portal-app');
	/*wp_enqueue_script('know--events--portal-app');
	wp_enqueue_script('know--events--factories');
	wp_enqueue_script('know--events--directives');
	wp_enqueue_script('know--events--filters');
	wp_enqueue_script('know--events--portal-controllers');
	wp_enqueue_script('know--events--portal-config');*/

	wp_enqueue_style('know--events--portal-css');


	wp_localize_script('know--events--portal-app', 'know__events__portal_config', array(
	    'plugin_url' => plugin_dir_url( __FILE__ ),
	));

	/*wp_localize_script('know--events--directives', 'know__events__portal_config', array(
	    'plugin_url' => plugin_dir_url( __FILE__ ),
	));

	wp_localize_script('know--events--portal-controllers', 'know__events__portal_config', array(
	    'ajax_url' => plugin_dir_url( __FILE__ ),
	));*/

	$atts = array_change_key_case((array)$atts, CASE_LOWER);
 
    $wporg_atts = shortcode_atts([
		'server' => '',
		'auth-key' => '',
        'link-prefix' => ''
	], $atts, $tag);
	
	$know = new know();

	if($know->server == '') return 'Please set a Know server in your wordpress settings.';
	//$know->enable_scripts();

	$result = $know->communicate('accounting', 'accounting_index');
	$output .= $result['content'];

	$events_custom_url = plugin_dir_url( __FILE__ );

	/*$outputOLD .= <<<EOF

	<div id="know__events__my_event_placeholder">...</div>
	
	<div ng-app="myApp" ng-controller="default_controller" ng-cloak="">
		<loading-animation ng-show="page_loaded.loading"></loading-animation>
		<div ng-hide="page_loaded.loading" ng-view autoscroll></div>
	</div>

	<script>
		
		//var know__events__custom_url = '$events_custom_url';
		//know__events__custom_url = know__events__custom_url.substring(0, know__events__custom_url.length - 1);
		
		//What is this?? 6/26/19
		//jQuery('.sub-menu').find('.current-menu-item').removeClass('current-menu-item');//Fixes funky menu items for app
	</script>
EOF;*/

	$output .= <<<EOF

	<div id="know__events__my_event_placeholder">...</div>
	
	<!--<div ng-app="myApp" ng-controller="default_controller" ng-cloak="">
		<loading-animation ng-show="page_loaded.loading"></loading-animation>
		<div ng-hide="page_loaded.loading" ng-view autoscroll></div>
	</div>-->

	<script>
		
		//var know__events__custom_url = '$events_custom_url';
		//know__events__custom_url = know__events__custom_url.substring(0, know__events__custom_url.length - 1);
		
		//What is this?? 6/26/19
		//jQuery('.sub-menu').find('.current-menu-item').removeClass('current-menu-item');//Fixes funky menu items for app
	</script>
EOF;

return $output;
}

add_action( 'wp_ajax_nopriv_know__events__custom_login_init', 'know__events__custom_login_init' );
add_action( 'wp_ajax_know__events__custom_login_init', 'know__events__custom_login_init' );
function know__events__custom_login_init() {

	if(!class_exists('know_platform')) return;
	
	$know = new know_platform();

	$result = $know->communicate('events', 'front_end_login_init');

	$sendArray = $result['content'];
	$sendArray['css'] = know__events__get_css();

	if($know->get_cookie('know__events__id')) $sendArray['Authenticated'] = true;
	else $sendArray['Authenticated'] = false;
	
	echo json_encode($sendArray);

	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_nopriv_know__events__custom_login_auth', 'know__events__custom_login_auth' );
add_action( 'wp_ajax_know__events__custom_login_auth', 'know__events__custom_login_auth' );
function know__events__custom_login_auth() {

	if(!class_exists('know_platform')) return;
	
	$sendArray = array(
		'Errors' => array(
			'Auth' => false
		),
		'Auth' => false
	);

	$know = new know_platform();
	//$know->confirm_session();

	$result = $know->communicate('events', 'front_end_event_authenticate', array(
		'event_id' => $_POST['Data']['eventId'],
		'event_code' => $_POST['Data']['eventCode']
	));

	$sendArray['testpost'] = $_POST;

	if(isset($result['content']['is_authenticated']) && $result['content']['is_authenticated'] == true){

		$sendArray['Auth'] = true;

		$know->set_cookie('know__events__id', $result['content']['Event_Id']);

		/*$_SESSION['know']['events'] = array(
			'Event_Id' => $result['content']['Event_Id']
		);*/

	} else {
		$sendArray['Errors']['Auth'] = true;
	}
	
	echo json_encode($sendArray);

	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_nopriv_know__events__custom_event_details', 'know__events__custom_event_details' );
add_action( 'wp_ajax_know__events__custom_event_details', 'know__events__custom_event_details' );
function know__events__custom_event_details() {

	if(!class_exists('know_platform')) return;
	
	$response = new know_response();

	$know = new know_platform();
	//$know->confirm_session();

	//!isset($_SESSION['know']['events']['Event_Id'])
	if(!$know->get_cookie('know__events__id')) wp_die($response->trigger_front_end_logout());

	$result = $know->communicate('events', 'front_end_event_details', array_merge($_POST['Data'], array(
		'Event_Id' => $know->get_cookie('know__events__id')
	)));

	$payment_result = $know->communicate('accounting', 'payment_init', array(
		'Object_API_Name' => 'data',
		'Primary_Key_Value' => $know->get_cookie('know__events__id')
	));

	$result['content']['Payments'] = $payment_result['content'];
	$result['content']['css'] = know__events__get_css();

	$response->import($result);
	$response->set_content($result['content']);

	echo $response->get_json_data();

	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_nopriv_know__events__payment_init', 'know__events__payment_init' );
add_action( 'wp_ajax_know__events__payment_init', 'know__events__payment_init' );
function know__events__payment_init() {

	if(!class_exists('know_platform')) return;
	
	$response = new know_response();

	$know = new know_platform();
	//$know->confirm_session();

	if(!$know->get_cookie('know__events__id')) wp_die($response->trigger_front_end_logout());

	$result = $know->communicate('accounting', 'payment_init', array(
		'Object_API_Name' => 'data',
		'Primary_Key_Value' => $know->get_cookie('know__events__id')
	));

	$result['content']['css'] = know__events__get_css();

	$response->import($result);
	$response->set_content($result['content']);

	echo $response->get_json_data();

	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_nopriv_know__events__payment_confirmation_init', 'know__events__payment_confirmation_init' );
add_action( 'wp_ajax_know__events__payment_confirmation_init', 'know__events__payment_confirmation_init' );
function know__events__payment_confirmation_init() {

	if(!class_exists('know_platform')) return;
	
	$response = new know_response();

	$know = new know_platform();
	//$know->confirm_session();

	if(!$know->get_cookie('know__events__id')) wp_die($response->trigger_front_end_logout());

	$result = $know->communicate('events', 'front_end_payment_confirmation_init', array(
		'Event_Id' => $know->get_cookie('know__events__id')
	));

	$response->import($result);
	$response->set_content($result['content']);

	echo $response->get_json_data();

	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_nopriv_know__events__payment_process', 'know__events__payment_process' );
add_action( 'wp_ajax_know__events__payment_process', 'know__events__payment_process' );
function know__events__payment_process() {

	if(!class_exists('know_platform')) return;

	$response = new know_response();
	
	$know = new know_platform();

	if(!$know->get_cookie('know__events__id')) wp_die($response->trigger_front_end_logout());

	//$know->confirm_session();

	$_POST['data']['method'] = 'Credit';

	$result = $know->communicate('accounting', 'payment_process', array(
		'Object_API_Name' => 'data',
		'Primary_Key_Value' => $know->get_cookie('know__events__id'),
		'Credit_Card_Processor' => $_POST['Credit_Card_Processor'],
		'data' => array_merge($_POST['data'], array(
			'card_details' => $_POST['card_details'],
			'Token' => $_POST['Token'],
			'card' => $_POST['card']
		))
	));

	//$response->add_log('TEST: '.json_encode($_POST));

	//wordpress seems to split up POST parameters since they don't like nested arrays. Need to figure out if this is happening by way of JS or wordpress.

	$response->import($result);
	/*$response->add_log(json_encode(array(
		'Object_API_Name' => 'data',
		'Primary_Key_Value' => $know->get_cookie('know__events__id'),
		'Credit_Card_Processor' => $_POST['Credit_Card_Processor'],
		'data' => $data
	)));*/
	$response->set_content($result['content']);

	echo $response->get_json_data();

	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_nopriv_know__events__payment_processed_controller', 'know__events__payment_processed_controller' );
add_action( 'wp_ajax_know__events__payment_processed_controller', 'know__events__payment_processed_controller' );
function know__events__payment_processed_controller() {

	if(!class_exists('know_platform')) return;
	
	$know = new know_platform();
	
	$result = array(
		'css' => know__events__get_css()
	);

	echo json_encode($result);

	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_nopriv_know__events__logout', 'know__events__logout' );
add_action( 'wp_ajax_know__events__logout', 'know__events__logout' );
function know__events__logout() {

	if(!class_exists('know_platform')) return;
	
	$know = new know_platform();
	//$know->confirm_session();

	$know->delete_cookie('know__events__id');

	//$_SESSION['know']['events'] = array();

	wp_die(); // this is required to terminate immediately and return a proper response
}

function know__events__get_css(){
	get_option('know--events--settings-group');

	return array(
		'know__events__button_styles' => get_option('know__events__button_styles'),
		'know__events__button_classes' => get_option('know__events__button_classes'),
		'know__events__alert_container_styles' => get_option('know__events__alert_container_styles'),
		'know__events__alert_container_classes' => get_option('know__events__alert_container_classes'),
		'know__events__alert_heading_styles' => get_option('know__events__alert_heading_styles'),
		'know__events__alert_heading_classes' => get_option('know__events__alert_heading_classes'),
		'know__events__login_button_classes' => get_option('know__events__login_button_classes'),
		'know__events__login_button_styles' => get_option('know__events__login_button_styles'),
		'know__events__payment_process_button_classes' => get_option('know__events__payment_process_button_classes'),
		'know__events__payment_process_button_styles' => get_option('know__events__payment_process_button_styles')
	);
	
}