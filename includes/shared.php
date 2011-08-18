<?php
/**
*
* Check a directory for the video uploads exists under wp-content/uploads and create 
*/
function s3_video_check_upload_directory()
{
	if ((is_dir(WP_CONTENT_DIR . '/uploads/s3_videos/')) && (is_writable(WP_CONTENT_DIR . '/uploads/s3_videos/'))) {
		return WP_CONTENT_DIR . '/uploads/s3_videos/';
	} else {
		if (!is_dir(WP_CONTENT_DIR . '/uploads/')) {
			mkdir(WP_CONTENT_DIR . '/uploads/', 0755);
		}
		mkdir(WP_CONTENT_DIR . '/uploads/s3_videos/', 0755);
		if ((is_dir(WP_CONTENT_DIR . '/uploads/s3_videos/')) && (is_writable(WP_CONTENT_DIR . '/uploads/s3_videos/'))) {
			return WP_CONTENT_DIR . '/uploads/s3_videos/';
		}
	}
}
