<?php
class s3_playlist_management 
{
	protected $_playlistName = NULL;
	
	/**
	 * Set the database id of the playlist
	 * 
	 * @param integer $playList
	 */
	public function setPlaylist($playList = NULL) 
	{
		if (!empty($playList)) {
			$this->_playlistName = $playList;	
		}
	}
	
	/**
	 * 
	 * Save playlist to the database
	 * 
	 * @param string $playlistName
	 * @param array $playlistContents
	 * 
	 * @return integer
	 */
	public function createPlaylist($playlistName, $playlistContents)
	{
		$playlistId = $this->_savePlaylistName($playlistName);
		if (!$playlistId) {
			return FALSE;	
		}
		
		$x = 1;
		foreach($playlistContents as $fileName) {
			$videoId = $this->_addVideoToPlaylist($fileName, $playlistId, $x); 
			$x++;	
		}
		return $playlistId;		
	}
	
	/**
	 * 
	 * Update the contents of a video stored in the database
	 * 
	 * @param integer $playlistId
	 * @param array $playlistContents
	 * 
	 * @return boolean
	 */
	public function updatePlaylistVideos($playlistId, $playlistContents)
	{
		if (!$playlistId) {
			return FALSE;	
		}
		
		$x = 1;
		foreach($playlistContents as $fileName) {
			$videoId = $this->_addVideoToPlaylist($fileName, $playlistId, $x); 
			$x++;	
		}
		return TRUE;				
	}
	
	/**
	 * 
	 * Delete a play list stored in the database
	 * 
	 * @param $playlistId
	 */
	public function deletePlaylist($playlistId)
	{
		mysql_query("DELETE FROM s3_video_playlists WHERE id = '$playlistId'");
		$this->deletePlaylistVideos($playlistId);
	}
	
	/**
	 * 
	 * Delete all of the videos belonging to a play list from the database
	 * 
	 * @param integer $playlistId
	 */
	public function deletePlaylistVideos($playlistId)
	{
		mysql_query("DELETE FROM s3_video_playlist_videos WHERE video_playlist = '$playlistId'")  or die(mysql_error());			
	}
	
	/**
	 * 
	 * Fetch all of the play lists currently stored in the database
	 * 
	 * @return array
	 */
	public function getAllPlaylists() 
	{
		$playlists = mysql_query("SELECT * FROM s3_video_playlists");
		
		$existingPlaylists = array();
		while($playlist = mysql_fetch_assoc($playlists)) {
			$existingPlaylists[] = $playlist;	
		}
		
		return $existingPlaylists;	
	}

	/**
	 * 
	 * Fetch a playlist from the database via its name
	 * 
	 * @param string $playlistName
	 * @return array
	 */
	public function getPlaylistsByTitle($playlistName) 
	{
		$playlists = mysql_query("SELECT * FROM s3_video_playlists WHERE playlist_name LIKE '$playlistName'");
		$existingPlaylists = array();
		while($playlist = mysql_fetch_assoc($playlists)) {
			$existingPlaylists[] = $playlist;	
		}
		
		return $existingPlaylists;	
	}
		
	/**
	 * 
	 * Fetch the videos that belong to a play list from the database
	 * 
	 * @param integer $playlistId
	 * 
	 * @return array
	 */	
	public function getPlaylistVideos($playlistId)
	{
		$videos = mysql_query("SELECT * FROM s3_video_playlist_videos WHERE video_playlist LIKE '$playlistId' ORDER BY video_weight ASC");
		$playlistVideos = array();
		while($video = mysql_fetch_assoc($videos)) {
			$playlistVideos[] = $video;	
		}
		
		return $playlistVideos;			
	}
		
	/**
	 * 
	 * Save a new play list to the database
	 * 
	 * @param string $playlistName
	 */	
	protected function _savePlaylistName($playlistName) 
	{
		$time = time();
		mysql_query("INSERT INTO s3_video_playlists (playlist_name,created) VALUES ('$playlistName', '$time')") or die(mysql_error());	
		return mysql_insert_id();
	}
	
	/**
	 * 
	 * Add a video to the play list
	 * 
	 * @param string $fileName
	 * @param integer $playlistId
	 * @param integer $weight
	 * 
	 * @return integer
	 */
	protected function _addVideoToPlaylist($fileName, $playlistId, $weight = 1) {
		mysql_query("INSERT INTO s3_video_playlist_videos (video_file, video_playlist, video_weight) VALUES ('$fileName', '$playlistId', '$weight')");		
		return mysql_insert_id();		
	}
}
