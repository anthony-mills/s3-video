<?php

/*
 * Include the wordpress database class
 */
require_once('../../../../wp-config.php');
require_once('shared.php');
$db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

$clientip = getClientIp();
$video = $_GET['video'];
$action = $_GET['action'];

if ((!$video) && (!$action)) {
	exit;
}

$time = time();

switch ($action) {
	case 'start':
		mysql_query("INSERT INTO s3_video_analytics (video, started, client_ip) values ('$video', '$time', '$clientip')");
		setcookie("$video", mysql_insert_id());
	break;
	
	case 'finish':
		if ((!empty($_COOKIE[$video])) && (is_numeric($_COOKIE[$video]))) {
			$playId = $_COOKIE[$video];
			mysql_query("UPDATE s3_video_analytics SET finished = '$time' WHERE id = '$playId'");			
		}
	break;
}