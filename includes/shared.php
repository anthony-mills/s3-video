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
			if (is_writable(WP_CONTENT_DIR)) {
				mkdir(WP_CONTENT_DIR . '/uploads/', 0755);
			} else {
				die('<p><span style="color: red;"><b>ERROR:</b></span> Cannot write to the file uploads directory ( '. WP_CONTENT_DIR . '/uploads/ ), please ensure it exists and is writable by the webserver process.</p>');
			}
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
	if (!empty($pluginSettings['amazon_prefix'])) {
		$bucketContents = $s3Access->getBucket($pluginSettings['amazon_video_bucket'], $pluginSettings['amazon_prefix']);		
	} else {
		$bucketContents = $s3Access->getBucket($pluginSettings['amazon_video_bucket']);
	}
	if ((is_array($bucketContents)) && (!empty($bucketContents))) {
		return $bucketContents;
	}
}

/*
 * Return a file size in a human readable format 
 */
function s3_humanReadableBytes($bytes)
{
   $units = array('B', 'K', 'MB', 'GB', 'TB');

    for ($i = 0, $size =$bytes; $size>1024; $size=$size/1024)
    $i++;
    return number_format($size, 2) . ' '  . $units[min($i, count($units) -1 )];
		}

function getClientIp()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$clientIp = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$clientIp = $_SERVER['REMOTE_ADDR'];
	}
	return $clientIp;
}
 