<?php
class s3_video_management {

	public function createVideoStill($imageName, $videoName)
	{
		global $wpdb;
		$time = time();
		$wpdb->insert('s3_video_stills', array('video_file' => $videoName, 'image_file' => $imageName, 'created' => time()));
	
		return $wpdb->insert_id;
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
		global $wpdb;
		$videoRow = $wpdb->get_row("SELECT image_file FROM s3_video_stills WHERE video_file = '$videoName' LIMIT 1");
				
		if (!empty($videoRow)) {
			return $videoRow->image_file;
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
		global $wpdb;
		$videoStill = $wpdb->get_row("SELECT * FROM s3_video_stills WHERE image_file = '$imageName'");

		if (!empty($videoStill)) {
			return $videoStill;	
		}
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
	  public function deleteVideoStill($videoName)
	  {
		global $wpdb;
		$wpdb->query($wpdb->prepare("DELETE FROM s3_video_stills WHERE video_file = '$videoName'"));	  	
	  }

	 /**
	 *
	 * Delete video from all playlists	 
	 *
	 * @param string $videoName 
	 */ 
	 public function removeVideoFromPlaylists($videoName)
	 {
		global $wpdb;
		$wpdb->query($wpdb->query($wpdb->prepare("DELETE FROM s3_video_playlist_videos WHERE video_file = '$videoName'")));	 
	 }
	 
} 
