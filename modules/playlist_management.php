<?php
/**
 * 
 * Functionality relating to video playlists
 * 
 */
 
 /*
 * Create a new playlist and add videos
 */
function s3_video_create_playlist()
{
	$pluginSettings = s3_video_check_plugin_settings();	
		
	if ((!empty($_POST['playlist_contents'])) && (!empty($_POST['playlist_name']))) {
		require_once(WP_PLUGIN_DIR . '/s3-video/includes/playlist_management.php');
		$playlistManagement = new s3_playlist_management();
		
		$playlistName = sanitize_title($_POST['playlist_name']);
		$playlistExists = $playlistManagement->getPlaylistsByTitle($playlistName);
		if (!$playlistExists) {
			$playlistResult = $playlistManagement->createPlaylist($playlistName, $_POST['playlist_contents']);
			if (!$playlistResult) {
	    		$errorMsg = 'An error occurred whilst creating the play list.';			
			} else {
				$successMsg = 'New playlist saved successfully.';			
			} 
		} else {
	    		$errorMsg = 'A playlist with this name already exists.';					
		}  
	}
	$existingVideos= s3_video_get_all_existing_video($pluginSettings);
	require_once(WP_PLUGIN_DIR . '/s3-video/views/playlist-management/create_playlist.php');	
}
 
/*
 *	Manage existing playlists of S3 based media 
 */
function s3_video_show_playlists()
{
	$pluginSettings = s3_video_check_plugin_settings();			
	require_once(WP_PLUGIN_DIR . '/s3-video/includes/playlist_management.php');
	$playlistManagement = new s3_playlist_management();
	
	if (!empty($_GET['delete'])) {
		$playlistId = preg_replace('/[^0-9]/Uis', '', $_GET['delete']);
		$playlistManagement->deletePlaylist($playlistId);
	}
	
	if (((!empty($_GET['edit'])) && (is_numeric($_GET['edit']))) || ((!empty($_GET['reorder'])) && (is_numeric($_GET['reorder'])))) {
		
		if (!empty($_GET['edit'])) {
			$playlistId = preg_replace('/[^0-9]/Uis', '', $_GET['edit']);
			
			if (!empty($_POST['playlist_contents'])) {
				$playlistManagement->deletePlaylistVideos($playlistId);
				$playlistManagement->updatePlaylistVideos($playlistId, $_POST['playlist_contents']);	
				$playlistUpdated = 1;
			} 
			$existingVideos = $playlistManagement->getPlaylistVideos($playlistId);	
			$s3Videos = s3_video_get_all_existing_video($pluginSettings);
		
			require_once(WP_PLUGIN_DIR . '/s3-video/views/playlist-management/edit_playlist.php');
		} 
		
		if (!empty($_GET['reorder'])) {
			$playlistId = preg_replace('/[^0-9]/Uis', '', $_GET['reorder']);
			$playlistVideos = $playlistManagement->getPlaylistVideos($playlistId);
			require_once(WP_PLUGIN_DIR . '/s3-video/views/playlist-management/reorder_playlist.php');	
		} 	
		
	} else {
		/*
		 * If we don't have a playlist to display a list of them all  
		 */
		$existingPlaylists = $playlistManagement->getAllPlaylists();	
		require_once(WP_PLUGIN_DIR . '/s3-video/views/playlist-management/playlist_management.php');
	}
	
}

/**
 * 
 * Insert a playlist into the editor for a page or post through the media manager
 * 
 */
function s3video_playlist_media_manager()
{
	if ((isset($_POST['insertPlaylistId'])) && (!empty($_POST['insertPlaylistId']))) {
		$insertHtml = "[S3_embed_playlist id='" . $_POST['insertPlaylistId'] . "']";
		media_send_to_editor($insertHtml);
		die();
	}
		
	$pluginSettings = s3_video_check_plugin_settings();
	
	// Load playlist management class
	require_once(WP_PLUGIN_DIR . '/s3-video/includes/playlist_management.php');
	$playlistManagement = new s3_playlist_management();

	// Load all of the existing playlists
	$existingPlaylists = $playlistManagement->getAllPlaylists();	
	require_once(WP_PLUGIN_DIR . '/s3-video/views/playlist-management/media_manager_show_playlists.php');	
} 