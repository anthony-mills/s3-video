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
add_action('admin_print_styles', 's3_video_load_css');
add_action('admin_print_scripts', 's3_video_load_js');

wp_enqueue_script('jquery');
wp_enqueue_script('swfobject');
wp_enqueue_script('flowPlayer', WP_PLUGIN_URL . '/S3-Video/js/flowplayer-3.2.6.js', array('jquery'), '1.0');

require_once('includes/shared.php');
require_once('includes/s3.php');
		
function s3_video_plugin_menu() 
{
	// Main side bar entry
	add_menu_page('S3 Video', 'S3 Video', 'manage_options', 's3-video', 's3_video');

	// S3 sidebar child pages
	add_submenu_page('s3-video', __('Plugin Settings','plugin-settings'), __('Plugin Settings','plugin-settings'), 'manage_options', 's3_video_plugin_settings', 's3_video_plugin_settings');  
	add_submenu_page('s3-video', __('Upload Video','upload-video'), __('Upload Video','upload-video'), 'manage_options', 's3_video_upload_video', 's3_video_upload_video');		
}

// Default page displaying existing media files
function s3_video()
{
	s3_video_check_user_access();
	$pluginSettings = s3_video_check_plugin_settings();
	
	if (!empty($_GET['delete'])) {
		$s3Access = new S3($pluginSettings['amazon_access_key'], $pluginSettings['amazon_secret_access_key'], NULL, $pluginSettings['amazon_url']);
		$result = $s3Access->deleteObject($pluginSettings['amazon_video_bucket'], $_GET['delete']);
		if ($result) {
			$successMsg = $_GET['delete'] . ' was successfully deleted.';
		}
	}
	$existingVideos= s3_video_get_all_existing_video($pluginSettings);
	require_once('existing-videos.php');
}

// Upload videos to S3
function s3_video_upload_video()
{
	s3_video_check_user_access();
	$pluginSettings = s3_video_check_plugin_settings();
	$tmpDirectory = s3_video_check_upload_directory();

	if ((!empty($_FILES)) && ($_FILES['upload_video']['size'] > 0)) {
			if (($_FILES['upload_video']['type'] !='video/x-flv') && ($_FILES['upload_video']['type'] !='video/mp4')) {
				$errorMsg = 'You need to provide an .flv or .mp4 file';
			} else {
				$fileName = basename($_FILES['upload_video']['name']);
				$fileName = preg_replace('/[^A-Za-z0-9_.]+/', '', $fileName);
				$videoLocation = $tmpDirectory . $fileName;
				if(move_uploaded_file($_FILES['upload_video']['tmp_name'], $videoLocation)) {
					$s3Access = new S3($pluginSettings['amazon_access_key'], $pluginSettings['amazon_secret_access_key'], NULL, $pluginSettings['amazon_url']);
					$s3Result = $s3Access->putObjectFile($videoLocation, $pluginSettings['amazon_video_bucket'], $fileName, S3::ACL_PUBLIC_READ);
					switch ($s3Result) {
		
						case 0:
							$errorMsg = 'Request unsucessful check your S3 access credentials';
						break;	
										
						case 1:
							$successMsg = 'The video has successfully been uploaded to your S3 account';					
						break;
						
					}
				}
			}
	} else {
    	$errorMsg = 'There was an error uploading the video';
	}
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
			$pluginSettings = s3_video_check_plugin_settings();
		}
	} else {
		$pluginSettings = s3_video_check_plugin_settings();
	}

	require_once('plugin-settings.php');
}

// Embed video player into page
function s3_video_embed_video($embedDetails) 
{
	$pluginSettings = s3_video_check_plugin_settings();
	if ($embedDetails['video']) {
		$videoFile =  'http://' . $pluginSettings['amazon_url'] . '/' . $pluginSettings['amazon_video_bucket'] . $embedDetails['video'];	
	}	
	require_once('plugin-settings.php');	
} 

// Preview file in colourBox
function s3_video_preview_media() 
{
	$pluginSettings = s3_video_check_plugin_settings();
	if ($_GET['media']) {
		$videoFile =  'http://' . $pluginSettings['amazon_url'] . '/' . $pluginSettings['amazon_video_bucket'] . $_GET['media'];	
	}	
	require_once('preview-media.php');	
} 

// Check if the user has configured the plugin
function s3_video_check_plugin_settings()
{
	$pluginSettings['amazon_access_key'] = get_option('amazon_access_key');
	$pluginSettings['amazon_secret_access_key'] = get_option('amazon_secret_access_key');
	$pluginSettings['amazon_url'] = get_option('amazon_url');
	$pluginSettings['amazon_video_bucket'] = get_option('amazon_video_bucket');
	if ((empty($pluginSettings['amazon_access_key'])) || (empty($pluginSettings['amazon_secret_access_key'])) || (empty($pluginSettings['amazon_secret_access_key']))) {
		require_once('configuration_required.php');
		exit;	
	} else {
		return $pluginSettings;
	}

}

// Check if the user can access the page
function s3_video_check_user_access()
{
	if( !current_user_can( 'manage_options' ) ) {
        	wp_die( 'You do not have sufficient permissions to access this page' );
   	}
}

/*
 * Load the custom style sheets for the admin pages
 */
function s3_video_load_css()
{
	wp_register_style('s3_video_default', WP_PLUGIN_URL . '/S3-Video/css/style.css');
	wp_enqueue_style('s3_video_default');
	
	wp_register_style('s3_video_colorbox', WP_PLUGIN_URL . '/S3-Video/css/colorbox.css');
	wp_enqueue_style('s3_video_colorbox');		
}

/*
 * Load javascript required by the backend administration pages
 */
function s3_video_load_js()
{	
	wp_enqueue_script('validateJS', WP_PLUGIN_URL . '/S3-Video/js/jquery.validate.js', array('jquery'), '1.0');
	wp_enqueue_script('placeholdersJS', WP_PLUGIN_URL . '/S3-Video/js/jquery.placeholders.js', array('jquery'), '1.0');
	wp_enqueue_script('colorBox', WP_PLUGIN_URL . '/S3-Video/js/jquery.colorbox.js', array('jquery'), '1.0');
}

// Add shortcodes
add_shortcode( 'embed-video', 'bartag_func' );
