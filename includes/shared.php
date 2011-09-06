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

/*
 * Get all aexisting media from an S3 Bucket
 */
function s3_video_get_all_existing_video($pluginSettings = NULL) 
{
	if (!$pluginSettings) {
		return FALSE;
	}
	$s3Access = new S3($pluginSettings['amazon_access_key'], $pluginSettings['amazon_secret_access_key'], NULL, $pluginSettings['amazon_url']);
	$bucketContents = $s3Access->getBucket($pluginSettings['amazon_video_bucket']);
	if ((is_array($bucketContents)) && (!empty($bucketContents))) {
		return $bucketContents;
	}
}

/*
 * Return a dile size in a human readable format 
 */
function humanReadableBytes($bytes)
{
   $units = array('B', 'K', 'MB', 'GB', 'TB');

    for ($i = 0, $size =$bytes; $size>1024; $size=$size/1024)
    $i++;
    return number_format($size, 2) . ' ' . $units[min($i, count($units) -1 )];
 }