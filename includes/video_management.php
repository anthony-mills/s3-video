<?php
class s3_video_management {
	
	public function createVideoStill($imageName, $videoName)
	{
		$time = time();
		mysql_query("INSERT INTO s3_video_stills (video_file, image_file, created) VALUES ('$videoName', '$imageName', '$time')") or die(mysql_error());	
		return mysql_insert_id();
	}
	
	/**
	 * 
	 * Get the still for a video in the database 
	 * 
	 * @param string $videoName
	 * @return string $imageName
	 */
	public function getVideoStill($videoName)
	{
		$sqlQuery = mysql_query("SELECT image_file FROM s3_video_stills WHERE video_file = '$videName' LIMIT 1");
				
		$videoStill = mysql_fetch_object($sqlQuery);
		print_r($videoStill);
	}
} 