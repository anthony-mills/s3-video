<?php
/* 
Plugin Name: S3 Video Plugin
Plugin URI: https://github.com/anthony-mills/s3-video
Description: Upload and embed videos using your Amazon S3 account
Version: 0.981
Author: Anthony Mills
Author URI: http://www.development-cycle.com
*/

if ('s3-video.php' == basename($_SERVER['SCRIPT_FILENAME'])){
	die ('Access denied');
}

// Load required modules
require_once(WP_PLUGIN_DIR . '/s3-video/modules/player_management.php');
require_once(WP_PLUGIN_DIR . '/s3-video/modules/plugin_functionality.php');

// Load other required libraries
require_once(WP_PLUGIN_DIR . '/s3-video/includes/shared.php');
require_once(WP_PLUGIN_DIR . '/s3-video/includes/s3.php');

register_activation_hook(__FILE__, 'S3_plugin_activate');
register_deactivation_hook(__FILE__, 'S3_plugin_deactivate');

add_action('admin_menu', 's3_video_plugin_menu');
add_action('admin_enqueue_scripts', 's3_video_load_css');
add_action('admin_enqueue_scripts', 's3_video_load_js');
add_action('wp_enqueue_scripts', 's3_video_load_player_js');

// Add Ajax calls
add_action('wp_ajax_remove_video_still', 's3_video_remove_video_still');

// Add shortcodes
add_shortcode( 'S3_embed_video', 's3_video_embed_video' );
add_shortcode( 'S3_embed_playlist', 's3_video_embed_playlist' );


// Add deactivation hook
register_deactivation_hook( __FILE__, 's3_video_deactivate');