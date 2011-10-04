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
$fileName = str_replace('.', '_', array_pop(explode('/', $video)));
		
switch ($action) {
	case 'start':
		mysql_query("INSERT INTO s3_video_analytics (video, started, client_ip) values ('$fileName', '$time', '$clientip')");
		setcookie("$fileName", mysql_insert_id(), time()+7200);
	break;
	
	case 'paused':
		$playId = $_COOKIE[$fileName];
		mysql_query("UPDATE s3_video_analytics SET paused = '$time' WHERE id = '$playId'");			
	break;	
	
	case 'resume':
		$playId = $_COOKIE[$fileName];
		mysql_query("UPDATE s3_video_analytics SET resumed = '$time' WHERE id = '$playId'");			
	break;	
		
	case 'finish':
		$playId = $_COOKIE[$fileName];
		mysql_query("UPDATE s3_video_analytics SET finished = '$time' WHERE id = '$playId'");			
	break;
}