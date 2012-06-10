<?php
class s3_playlist_management 
{
	protected $_playlistName = NULL;
	
	public function setPlaylist($playList = NULL) 
	{
		if (!empty($playList)) {
			$this->_playlistName = $playList;	
		}
	}
	
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
		return $playlistId;				
	}
	
	public function deletePlaylist($playlistId)
	{
		mysql_query("DELETE FROM s3_video_playlists WHERE id = '$playlistId'");
		$this->deletePlaylistVideos($playlistId);
	}
	
	public function deletePlaylistVideos($playlistId)
	{
		mysql_query("DELETE FROM s3_video_playlist_videos WHERE video_playlist = '$playlistId'")  or die(mysql_error());			
	}
	
	public function getAllPlaylists() 
	{
		$playlists = mysql_query("SELECT * FROM s3_video_playlists");
		
		$existingPlaylists = array();
		while($playlist = mysql_fetch_assoc($playlists)) {
			$existingPlaylists[] = $playlist;	
		}
		
		return $existingPlaylists;	
	}

	public function getPlaylistsByTitle($playlistName) 
	{
		$playlists = mysql_query("SELECT * FROM s3_video_playlists WHERE playlist_name LIKE '$playlistName'");
		$existingPlaylists = array();
		while($playlist = mysql_fetch_assoc($playlists)) {
			$existingPlaylists[] = $playlist;	
		}
		
		return $existingPlaylists;	
	}
		
	public function getPlaylistVideos($playlistId)
	{
		$videos = mysql_query("SELECT * FROM s3_video_playlist_videos WHERE video_playlist LIKE '$playlistId' ORDER BY video_weight ASC");
		$playlistVideos = array();
		while($video = mysql_fetch_assoc($videos)) {
			$playlistVideos[] = $video;	
		}
		
		return $playlistVideos;			
	}
		
	protected function _savePlaylistName($playlistName) 
	{
		$time = time();
		mysql_query("INSERT INTO s3_video_playlists (playlist_name,created) VALUES ('$playlistName', '$time')") or die(mysql_error());	
		return mysql_insert_id();
	}
	
	protected function _addVideoToPlaylist($fileName, $playlistId, $weight = 1) {
		mysql_query("INSERT INTO s3_video_playlist_videos (video_file, video_playlist, video_weight) VALUES ('$fileName', '$playlistId', '$weight')");		
		return mysql_insert_id();		
	}
}
