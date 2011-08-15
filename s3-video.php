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

require_once('includes/shared.php');

function s3_video_plugin_menu() 
{
	// Main side bar entry
	add_menu_page('S3 Video', 'S3 Video', 'manage_options', 's3-video', 's3_video');

	// S3 sidebar child pages
	add_submenu_page('s3-video', __('Plugin Settings','plugin-settings'), __('Plugin Settings','plugin-settings'), 'manage_options', 'plugin-settings', 'plugin_settings');  
	add_submenu_page('s3-video', __('Upload Video','upload-video'), __('Upload Video','upload-video'), 'manage_options', 'upload-video', 'upload_video');		
}

// Default page displaying existing media files
function s3_video()
{
	check_user_access();
	check_plugin_settings();
	require_once('existing-videos.php');
}

// Upload videos to S3
function upload_video()
{
	check_user_access();
	check_plugin_settings();
	$tmpDirectory = checkUploadDirectory();
	require_once('upload-video.php');
}

// Page to configure plugin settings i.e Amazon access keys etc
function plugin_settings()
{
	
	check_user_access();

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
		}
	} else {
		$pluginSettings = checkPluginSettings();
	}
	require_once('plugin-settings.php');
}

// Check if the user has configured the plugin
function check_plugin_settings()
{
	$pluginSettings = checkPluginSettings();
	if ((empty($pluginSettings['amazon_access_key'])) || (empty($pluginSettings['amazon_secret_access_key'])) || (empty($pluginSettings['amazon_secret_access_key']))) {
		require_once('configuration_required.php');
		exit;	
	} 
}

// Check if the user can access the page
function check_user_access()
{
	if( !current_user_can( 'manage_options' ) ) {
        	wp_die( 'You do not have sufficient permissions to access this page' );
   	}
}


