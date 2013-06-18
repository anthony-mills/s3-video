<?php
/**
* 
* Functions related to video players and video playback
*  
*/

/*
*  Embed video player into page
*/
function s3_video_embed_video($embedDetails) 
{	
	$pluginSettings = s3_video_check_plugin_settings();
	if ($embedDetails['file']) {
		$videoFile =  'http://' . $pluginSettings['amazon_video_bucket']  . '.' .  $pluginSettings['amazon_url'] . '/' . $embedDetails['file'];	
	} else {
		return;
	}
	
	// See if the video has an associated still image
	require_once(WP_PLUGIN_DIR . '/s3-video/includes/video_management.php');
	$videoManagement = new s3_video_management();	
	$videoStill = $videoManagement->getVideoStillByVideoName($embedDetails['file']);
	if (!empty($videoStill)) {
		$videoStill =  'http://' . $pluginSettings['amazon_video_bucket']  . '.' .  $pluginSettings['amazon_url'] . '/' . $videoStill;			
	}
	
	if (!empty($videoFile)) {

		// Set up the flowplayer for video playback
		if ((empty($pluginSettings['amazon_s3_video_player'])) || ($pluginSettings['amazon_s3_video_player'] == 'flowplayer')) {
			$playerContent = s3_video_configure_player($embedDetails);
						
			$playerContent = str_replace('{videoFile}', $videoFile, $playerContent);

			// Define the playlist to support a video still
			$playlistHtml = 'playlist: [' . "\r\n";

			if (!empty($videoStill)) { 
					$playlistHtml .= '{
            				url: "' . $videoStill . '", 
            				scaling: "fit",
            				autoPlay: true
        				},'  . "\r\n";
			} else {
				if ($pluginSettings['amazon_s3_video_autoplay'] == 0) {
					$playerContent = str_replace('{videoAutoPlay}', 'false', $playerContent); 
				} else {
					$playerContent = str_replace('{videoAutoPlay}', 'true', $playerContent); 
				}			
			}

			if ((!empty($videoStill)) && ($pluginSettings['amazon_s3_video_autoplay'] == 0)) {
							$playlistHtml .= '{
								url: "' . $videoFile . '",
								title: "' . $videoFile . '",
								autoPlay: false
							}' . "\r\n";
			} else {					
							$playlistHtml .= '{
								url: "' . $videoFile . '",
								title: "' . $videoFile . '"
        					}' . "\r\n";
			}	
			$playerContent = str_replace('{videoPlaylist}', $playlistHtml . ']', $playerContent); 			
			return $playerContent;
		} else {
			// prepare a videoJS player for video playback
			$playerContent = file_get_contents( WP_PLUGIN_DIR . '/s3-video/views/video-management/play_videoJS.php');
			$swfFile = WP_PLUGIN_URL . '/s3-video/misc/video-js.swf';
			$playerContent = str_replace('{swfFile}', $swfFile, $playerContent);	

			$playerContent = str_replace('{playerId}', s3_plugin_player_id(), $playerContent);
		
			// Set the player dimensions
			if ((!empty($embedDetails['width'])) && ($embedDetails['height'])) {
				$playerContent = str_replace('{videoWidth}', $embedDetails['width'], $playerContent); 				
				$playerContent = str_replace('{videoHeight}', $embedDetails['height'], $playerContent);
			} else {
				$playerContent = str_replace('{videoWidth}', $pluginSettings['amazon_s3_video_playerwidth'], $playerContent); 		
				$playerContent = str_replace('{videoHeight}', $pluginSettings['amazon_s3_video_playerheight'], $playerContent);
			}	

			// Define the buffering settings
			if ($pluginSettings['amazon_s3_video_autobuffer'] == 0) {
				$playerContent = str_replace('{videoBuffer}', 'none', $playerContent); 
			} else {
				$playerContent = str_replace('{videoBuffer}', 'auto', $playerContent); 
			}

			if ($pluginSettings['amazon_s3_video_autoplay'] == 0) {
				$playerContent = str_replace('{videoAutoPlay}', '', $playerContent); 
			} else {
				$playerContent = str_replace('{videoAutoPlay}', 'autoplay="true"', $playerContent); 
			}
						
			if (!empty($videoStill)) {
				$playerContent = str_replace('{videoStill}', 'poster="'.$videoStill.'"', $playerContent);
			} else {
				$playerContent = str_replace('{videoStill}', '', $playerContent);
			}	
	
			$fileType = substr($videoFile, -3);
			if ($fileType == 'flv') {
				 $videoTag = '<source src="' . $videoFile . '" type="video/x-flv">';
			} else {
				 $videoTag = '<source src="' . $videoFile . '" type="video/mp4">';				
			}
			$playerContent = str_replace('{videoFile}', $videoTag, $playerContent);
			return $playerContent;			
		}
	}
} 

/*
 * Embed video player for playlist into page
 */
function s3_video_embed_playlist($embedDetails)
{			
	// Load the required modules
	require_once(WP_PLUGIN_DIR . '/s3-video/includes/playlist_management.php');
	$playlistManagement = new s3_playlist_management();
	$playlistVideos = $playlistManagement->getPlaylistVideos($embedDetails['id']);	
	$pluginSettings = s3_video_check_plugin_settings();
	$playerContent = s3_video_configure_player();
	
	$playerContent = str_replace('{playerId}', s3_plugin_player_id(), $playerContent);
	
	$baseUrl =  'http://' . $pluginSettings['amazon_video_bucket']  . '.' .  $pluginSettings['amazon_url'] . '/';
	
	// Define the playlist to support a video still
	$playlistHtml = 'playlist: [' . "\r\n";

	$x = 0;
	foreach($playlistVideos as $playlistVideo) {					
		$playlistHtml .= '{
					url: "' . $baseUrl . $playlistVideo['video_file'] . '", ' . "\r\n" .
					'title: "' . $playlistVideo['video_file'] . '",' . "\r\n";
		if (($x == 0) && ($pluginSettings['amazon_s3_video_autoplay'] == 0)) {
			$playlistHtml .= 'autoPlay: false' . "\r\n";
			$playerContent = str_replace('{videoFile}', $baseUrl . $playlistVideo['video_file'], $playerContent);				
		} else {
			$x++;
			$playlistHtml .= 'autoPlay: true'. "\r\n";
		}
        $playlistHtml .= '},' . "\r\n";
	}
	$playlistHtml = substr($playlistHtml, 0, -1);
	$playerContent = str_replace('{videoPlaylist}', $playlistHtml . ']', $playerContent);
 	print_r($playerContent);
	exit;		
	return $playerContent;
} 

/*
 * Configure the player for play back with flowplay and playlist functionality
 */
function s3_video_configure_player($embedDetails = NULL) 
{
	$playerContent = file_get_contents( WP_PLUGIN_DIR  . '/s3-video/views/video-management/play_flowplayer.php');

	$flowplayerLocation = WP_PLUGIN_URL . '/s3-video/misc/flowplayer-3.2.16.swf';		
	$playerContent = str_replace('{flowplayerLocation}', $flowplayerLocation, $playerContent);
	$playerContent = str_replace('{playerId}', s3_plugin_player_id(), $playerContent);
	$pluginSettings = s3_video_check_plugin_settings();

	// Set the player dimensions
	if ((!empty($embedDetails['width'])) && ($embedDetails['height'])) {
		$playerContent = str_replace('{videoWidth}', $embedDetails['width'], $playerContent); 		
		$playerContent = str_replace('{videoHeight}', $embedDetails['height'], $playerContent);
	} else {
		$playerContent = str_replace('{videoWidth}', $pluginSettings['amazon_s3_video_playerwidth'], $playerContent); 		
		$playerContent = str_replace('{videoHeight}', $pluginSettings['amazon_s3_video_playerheight'], $playerContent);
	}

	// Define the buffering settings
	if ($pluginSettings['amazon_s3_video_autoplay'] == 0) {
		$playerContent = str_replace('{videoAutoPlay}', 'false', $playerContent); 
	} else {
		$playerContent = str_replace('{videoAutoPlay}', 'true', $playerContent); 
	}

	// Define the buffering settings
	if ($pluginSettings['amazon_s3_video_autobuffer'] == 0) {
		$playerContent = str_replace('{videoAutoBuffer}', '"none"', $playerContent); 
	} else {
		$playerContent = str_replace('{videoAutoBuffer}', '"auto"', $playerContent); 
	}	

	return $playerContent;
}

/*
 *  Preview file in colourBoxadmin.php?page=s3_video_show_playlist
 */
function s3_video_preview_media() 
{
	$pluginSettings = s3_video_check_plugin_settings();
	if ($_GET['media']) {
		$videoFile =  'http://' . $pluginSettings['amazon_url'] . '/' . $pluginSettings['amazon_video_bucket'] . $_GET['media'];	
	}	
	require_once(WP_PLUGIN_DIR . '/s3-video/views/video-management/preview_video.php');	
} 

/**
 * 
 * Load the player dependent Javascript
 */
function s3_video_load_player_js()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('swfobject');
	
	$pluginSettings = s3_video_check_plugin_settings();
	if ((empty($pluginSettings['amazon_s3_video_player'])) || ($pluginSettings['amazon_s3_video_player'] == 'flowplayer')) {
		wp_enqueue_script('flowPlayer', WP_PLUGIN_URL . '/s3-video/js/flowplayer-3.2.12.js', array('jquery'), '1.0');
		wp_enqueue_script('flowPlayerPlaylist', WP_PLUGIN_URL . '/s3-video/js/jquery.playlist.js', array('jquery'), '1.0');	
	} else {
		// If any playlists exist load both players
		require_once(WP_PLUGIN_DIR . '/s3-video/includes/playlist_management.php');
		$playlistManagement = new s3_playlist_management();
		
		if ( $playlistManagement->getAllPlaylists() ) {
			wp_enqueue_script('flowPlayer', WP_PLUGIN_URL . '/s3-video/js/flowplayer-3.2.12.js', array('jquery'), '1.0');
			wp_enqueue_script('flowPlayerPlaylist', WP_PLUGIN_URL . '/s3-video/js/jquery.playlist.js', array('jquery'), '1.0');				
		}
		
		wp_enqueue_script('videoJS', WP_PLUGIN_URL . '/s3-video/js/video.min.js');
		wp_register_style('s3_video_videoJS_css', WP_PLUGIN_URL . '/s3-video/css/video-js.css');
		wp_enqueue_style('s3_video_videoJS_css');						
	}		
}

/**
 * Generate a quasi random number for the player embed to allow multiple videos in the same page or post
 */
function s3_plugin_player_id()
{
	return sha1(rand(1, 9999999) . microtime(true));
}
 