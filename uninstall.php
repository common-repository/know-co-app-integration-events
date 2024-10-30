<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$names = array(
	'know__events__button_styles',
	'know__events__button_classes',
	'know__events__alert_container_styles',
	'know__events__alert_container_classes',
	'know__events__alert_heading_styles',
	'know__events__alert_heading_classes',
	'know__events__login_button_classes',
	'know__events__login_button_styles',
	'know__events__payment_process_button_classes',
	'know__events__payment_process_button_styles'
);

foreach($names as $name){
	delete_option($name);
	delete_site_option($name);// for site options in Multisite
}