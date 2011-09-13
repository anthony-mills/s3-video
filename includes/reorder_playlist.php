<?php
ini_set('display_errors', 0);
error_reporting(0);

$playlistId = $_GET['playlist'];
$playlistContents = $_GET['playlistVideos'];

if (empty($playlistContents)) {
	echo 'Play list order unable to be updated';
	exit;	
}
/*
 * Include the wordpress database class
 */
include_once('../../../../wp-config.php');
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Test the connection:
if (mysqli_connect_errno()){
    exit("Couldn't connect to the database: ".mysqli_connect_error());
}

$x = 1;
foreach ($playlistContents as $video) {
	$data = array('video_weight' => $x);
	mysql_query("UPDATE s3_video_playlist_videos SET  video_weight = '$x' WHERE id = '$video' AND video_playlist = '$playlistId'");	
	$x++;
}
unset($db);
echo 'S3 playlist order updated';