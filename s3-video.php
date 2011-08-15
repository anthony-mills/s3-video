<?php
/* 
Plugin Name: S3 Video Plugin
Plugin URI: http://www.development-cycle.com
Description: Upload and embed videos using your Amazon S3 account
Version: 0.1 
Author: Anthony Mills
Author URI: http://www.development-cycle.com
*/

if ('wp-brightcove-video-plugin.php' == basename($_SERVER['SCRIPT_FILENAME'])){
	die ('Access denied');
}

add_action('admin_menu', 's3_video_plugin_menu');
add_action('wp_head', 'loadStyleSheets');

wp_enqueue_script('jquery');
wp_enqueue_script('validateJS', WP_PLUGIN_URL . '/S3-Video/js/jquery.validate.js', array('jquery'), '1.0');
wp_enqueue_script('placeholdersJS', WP_PLUGIN_URL . '/S3-Video/js/jquery.placeholders.js', array('jquery'), '1.0');

require_once('includes/shared.php');

function s3_video_plugin_menu() 
{
	// Main side bar entry
	add_menu_page('S3 Video', 'S3 Video', 'manage_options', 's3-video', 's3_video');

	// S3 sidebar child pages
	add_submenu_page('s3-video', __('Plugin Settings','plugin-settings'), __('Plugin Settings','plugin-settings'), 'manage_options', 's3_video_plugin_settings', 's3_video_plugin_settings');  
	add_submenu_page('s3-video', __('Upload Video','upload-video'), __('Upload Video','upload-video'), 'manage_options', 's3_video_upload_video', 's3_video_upload-video');		
}

// Default page displaying existing media files
function s3_video()
{
	s3_video_check_user_access();
	s3_video_check_plugin_settings();
	require_once('existing-videos.php');
}

// Upload videos to S3
function s3_video_upload_video()
{
	s3_video_check_user_access();
	s3_video_check_plugin_settings();
	$tmpDirectory = checkUploadDirectory();
	require_once('upload-video.php');
}

// Page to configure plugin settings i.e Amazon access keys etc
function s3_video_plugin_settings()
{
	
	s3_video_check_user_access();

	if (!empty($_POST)) {
		if ((!empty($_POST['amazon_access_key'])) && (!empty($_POST['amazon_secret_access_key'])) && (!empty($_POST['amazon_video_bucket']))) {
			register_setting( 'amazon-s3-video', 'amazon_access_key' );
			register_setting( 'amazon-s3-video', 'amazon_secret_access_key' );
			register_setting( 'amazon-s3-video', 'amazon_video_bucket' );
			register_setting( 'amazon-s3-video', 'amazon_url' );

			update_option( 'amazon_access_key', $_POST['amazon_access_key']);
			update_option( 'amazon_secret_access_key', $_POST['amazon_secret_access_key'] );
			update_option( 'amazon_video_bucket', $_POST['amazon_video_bucket'] );
			if (!empty($_POST['amazon_url'])) {
				update_option( 'amazon_url', $_POST['amazon_url']);
			} else {
				update_option( 'amazon_url', 's3.amazonaws.com');
			}
			
			$successMsg = 'Details saved successfully.';
			$pluginSettings = s3_video_checkPluginSettings();
		}
	} else {
		$pluginSettings = s3_video_checkPluginSettings();
	}

	require_once('plugin-settings.php');
}

// Check if the user has configured the plugin
function s3_video_check_plugin_settings()
{
	$pluginSettings = s3_video_checkPluginSettings();
	if ((empty($pluginSettings['amazon_access_key'])) || (empty($pluginSettings['amazon_secret_access_key'])) || (empty($pluginSettings['amazon_secret_access_key']))) {
		require_once('configuration_required.php');
		exit;	
	} 
}

// Check if the user can access the page
function s3_video_check_user_access()
{
	if( !current_user_can( 'manage_options' ) ) {
        	wp_die( 'You do not have sufficient permissions to access this page' );
   	}
}

function loadStyleSheets()
{
	echo '<link type="text/css" rel="stylesheet" href="' . WP_PLUGIN_URL . '/S3-Video/css/style.css" />' . "\n";	
	echo '<link type="text/css" rel="stylesheet" href="' . WP_PLUGIN_URL . '/S3-Video/css/uploadify.css" />' . "\n";		
}

