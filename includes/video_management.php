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
	public function getVideoStillByVideoName($videoName)
	{
		$sqlQuery = mysql_query("SELECT image_file FROM s3_video_stills WHERE video_file = '$videoName' LIMIT 1");
				
		$videoStill = mysql_fetch_object($sqlQuery);
		if (!empty($videoStill->image_file)) {
			return $videoStill->image_file;	
		}
	}
	
	/**
	 * 
	 * Get video still by its file name
	 * 
	 * @param string $imageName
	 * @return object
	 */
	 public function getVideoStillByImageName($imageName)
	 {
		$stillsSQL = mysql_query("SELECT * FROM s3_video_stills WHERE image_file = '$imageName'");
		
		$existingStills = array();
		while($videoStill = mysql_fetch_assoc($stillsSQL)) {
			$existingStills[] = $videoStill;	
		}
		
		return $existingStills;		 	
	 }
	 
	 /**
	  * 
	  * Delete video still
	  * 
	  * @param string $videoName
	  * @param string $imageName 
	  * 
	  * @return void
	  */
	  public function deleteVideoStill($videoName, $imageName)
	  {
		mysql_query("DELETE FROM s3_video_stills WHERE video_file = '$videoName' AND image_file = '$imageName'")  or die(mysql_error());	  	
	  }
	 
} 