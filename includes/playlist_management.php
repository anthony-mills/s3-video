class s3_playlist_management 
{
	protected $_playlistName = NULL;
	
	public function setPlaylist($playList = NULL) 
	{
		if (!empty($playList)) {
			$this->_playlistName = $playList;	
		}
	}
	
	public function createPlaylist($playlistName)
	{
		$this->setPlaylist($playlistName);
				
	}
	
	public function deletePlaylist()
	{
		
	}
	
}