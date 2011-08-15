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

function s3_video_plugin_menu() {
	add_menu_page('S3 Video', 's3', 'manage_options', 's3-video', 's3_video',);	
}

function s3_video(){
	if( !current_user_can( 'manage_options' ) ) {
        wp_die( 'You do not have sufficient permissions to access this page' );
    }
	require_once('current_videos.php');
}
