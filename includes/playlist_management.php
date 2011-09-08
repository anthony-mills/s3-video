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
	
	public function deletePlaylist()
	{
		
	}
	
	protected function _savePlaylistName($playlistName) 
	{
		mysql_query("INSERT INTO s3_video_playlists (playlist_name) VALUES ('$playlistName')");		
		return mysql_insert_id();
	}
	
	protected function _addVideoToPlaylist($fileName, $playlistId, $weight = 1) {
		mysql_query("INSERT INTO s3_video_playlist_videos (video_file, video_playlist, video_weight) VALUES ('$fileName', '$playlistId', '$weight')");		
		return mysql_insert_id();		
	}
}