<?php

function checkPluginSettings()
{
	$pluginSettings['amazon_access_key'] = get_option('amazon_access_key');
	$pluginSettings['amazon_secret_access_key'] = get_option('amazon_secret_access_key');
	$pluginSettings['amazon_url'] = get_option('amazon_url');
	$pluginSettings['amazon_video_bucket'] = get_option('amazon_video_bucket');

	return $pluginSettings;
}

/**
*
* Check a directory for the video uploads exists under wp-content/uploads and create 
*/
function checkUploadDirectory()
{
	if ((is_dir(WP_CONTENT_DIR . '/uploads/s3_videos/')) && (is_writable(WP_CONTENT_DIR . '/uploads/s3_videos/'))) {
		return TRUE;
	} else {
		mkdir(WP_CONTENT_DIR . '/uploads/s3_videos/', 0755);
		if ((is_dir(WP_CONTENT_DIR . '/uploads/s3_videos/')) && (is_writable(WP_CONTENT_DIR . '/uploads/s3_videos/'))) {
			return TRUE;
		}
	}
}
