<?php
/* 
Plugin Name: S3 Video Plugin
Plugin URI: https://github.com/anthony-mills/s3-video
Description: Upload and embed videos using your Amazon S3 account
Version: 0.96
Author: Anthony Mills
Author URI: http://www.development-cycle.com
*/

if ('s3-video.php' == basename($_SERVER['SCRIPT_FILENAME'])){
	die ('Access denied');
}

register_activation_hook(__FILE__, 'S3_plugin_activate');
register_deactivation_hook(__FILE__, 'S3_plugin_deactivate');

add_action('admin_menu', 's3_video_plugin_menu');
add_action('admin_print_styles', 's3_video_load_css');
add_action('admin_print_scripts', 's3_video_load_js');
add_action('wp_enqueue_scripts', 's3_video_load_player_js');

// Add Ajax calls
add_action('wp_ajax_remove_video_still', 's3_video_remove_video_still');

require_once(WP_PLUGIN_DIR . '/s3-video/includes/shared.php');
require_once(WP_PLUGIN_DIR . '/s3-video/includes/s3.php');

// Add shortcodes
add_shortcode( 'S3_embed_video', 's3_video_embed_video' );
add_shortcode( 'S3_embed_playlist', 's3_video_embed_playlist' );

// Add deactivation hook
register_deactivation_hook( __FILE__, 's3_video_deactivate');
		
function s3_video_plugin_menu() 
{
	// Main side bar entry
	add_menu_page('S3 Video', 'S3 Video', 'manage_options', 's3-video', 's3_video');

	// S3 sidebar child pages
	add_submenu_page('s3-video', __('Upload Video','upload-video'), __('Upload Video','upload-video'), 'manage_options', 's3_video_upload_video', 's3_video_upload_video');		
	add_submenu_page('s3-video', __('Playlist Management','show-playlists'), __('Playlist Management','show_playlists'), 'manage_options', 's3_video_show_playlist', 's3_video_show_playlists');
	add_submenu_page('s3-video', __('Create Playlist','create-playlist'), __('Create Playlist','create_playlist'), 'manage_options', 's3_video_create_playlist', 's3_video_create_playlist');
	add_submenu_page('s3-video', __('Plugin Settings','plugin-settings'), __('Plugin Settings','plugin-settings'), 'manage_options', 's3_video_plugin_settings', 's3_video_plugin_settings');  		

	// Add page with no parent
	add_submenu_page(NULL, __('Video Meta','video-meta'), __('Video Meta','video-meta'), 'manage_options', 's3_video_meta_data', 's3_video_meta_data');  		 		
}

/*
 *  Default plugin page displaying existing media files
 */
function s3_video()
{
	s3_video_check_user_access();
	$pluginSettings = s3_video_check_plugin_settings();
	
	if (!empty($_GET['delete'])) {
		$s3Access = new S3($pluginSettings['amazon_access_key'], $pluginSettings['amazon_secret_access_key'], NULL, $pluginSettings['amazon_url']);
		$result = $s3Access->deleteObject($pluginSettings['amazon_video_bucket'], $_GET['delete']);
		if ($result) {
			$successMsg = $_GET['delete'] . ' was successfully deleted.';
		}
	}
	$existingVideos= s3_video_get_all_existing_video($pluginSettings);
	require_once(WP_PLUGIN_DIR . '/s3-video/views/video-management/existing-videos.php');
}

/*
 * Upload videos to S3 bucket
 */
function s3_video_upload_video()
{
	s3_video_check_user_access(); 
	$pluginSettings = s3_video_check_plugin_settings();
	$tmpDirectory = s3_video_check_upload_directory();
	$fileTypes = array('video/x-flv', 'video/x-msvideo', 'video/mp4', 'application/octet-stream', 'video/avi', 'video/x-msvideo', 
						'video/mpeg');
	if ((!empty($_FILES)) && ($_FILES['upload_video']['size'] > 0)) {
			if ((!in_array($_FILES['upload_video']['type'], $fileTypes)) && ($_FILES['upload_video']['type'] !='application/octet-stream')) {					
					$errorMsg = 'You need to provide an .flv or .mp4 file';
			} else {
				$fileName = basename($_FILES['upload_video']['name']);
				$fileName = preg_replace('/[^A-Za-z0-9_.]+/', '', $fileName);
				$videoLocation = $tmpDirectory . $fileName;
				if(move_uploaded_file($_FILES['upload_video']['tmp_name'], $videoLocation)) {
					$s3Access = new S3($pluginSettings['amazon_access_key'], $pluginSettings['amazon_secret_access_key'], NULL, $pluginSettings['amazon_url']);
					$s3Result = $s3Access->putObjectFile($videoLocation, $pluginSettings['amazon_video_bucket'], $fileName, S3::ACL_PUBLIC_READ);
					switch ($s3Result) {
		
						case 0:
							$errorMsg = 'Request unsucessful check your S3 access credentials';
						break;	
		
						case 1:
							$successMsg = 'The video has successfully been uploaded to your S3 account';					
						break;
						
					}
				} else {
            $errorMsg = 'Unable to move file to ' . $videoLocation . ' check the permissions and try again.';
        }
			}
	} else {
		if (!empty($_POST)) {
    		$errorMsg = 'There was an error uploading the video';
		}
	}
	require_once(WP_PLUGIN_DIR . '/s3-video/views/video-management/upload-video.php');
}

/*
 * Page to configure plugin settings i.e Amazon access keys etc
 */
function s3_video_plugin_settings()
{
	if (!empty($_POST)) {
		if ((!empty($_POST['amazon_access_key'])) && (!empty($_POST['amazon_secret_access_key'])) && (!empty($_POST['amazon_video_bucket']))) {
			register_setting( 'amazon_s3_video', 'amazon_access_key' );
			register_setting( 'amazon_s3_video', 'amazon_secret_access_key' );
			register_setting( 'amazon_s3_video', 'amazon_video_bucket' );
			register_setting( 'amazon_s3_video', 'amazon_url' );
			register_setting( 's3-video-results-limit', 's3_video_page_result_limit' );
			
			register_setting( 'amazon_s3_video_autoplay', 'video_autoplay' );
			register_setting( 'amazon_s3_video_autobuffer', 'video_autobuffer' );
			register_setting( 'amazon_s3_playlist_autoplay', 'playlist_autoplay' );
			register_setting( 'amazon_s3_playlist_autobuffer', 'playlist_autobuffer' );	
			register_setting( 'amazon_s3_video_player', 'video_player' );		

			update_option( 'amazon_access_key', trim($_POST['amazon_access_key'] ));
			update_option( 'amazon_secret_access_key', trim($_POST['amazon_secret_access_key'] ));
			update_option( 'amazon_video_bucket', trim($_POST['amazon_video_bucket'] ));
			update_option( 'amazon_s3_video_player', trim($_POST['video_player'] ));
						
			update_option( 'amazon_s3_video_autoplay', $_POST['video_autoplay'] );
			update_option( 'amazon_s3_video_autobuffer', $_POST['video_autobuffer'] );
			
			update_option( 'amazon_s3_playlist_autoplay', $_POST['playlist_autoplay'] );
			update_option( 'amazon_s3_playlist_autobuffer', $_POST['playlist_autobuffer'] );	
			update_option( 'amazon_s3_video_player', $_POST['video_player'] );					
			
			if (!empty($_POST['amazon_url'])) {
				update_option( 'amazon_url', $_POST['amazon_url']);
			} else {
				update_option( 'amazon_url', 's3.amazonaws.com');
			}
			
			if (!empty($_POST['page_result_limit'])) {
				update_option( 's3_video_page_result_limit', $_POST['page_result_limit']);
			} else {
				update_option( 's3_video_page_result_limit', 15);
			}
			
			$successMsg = 'Plugin settings saved successfully.';
			$pluginSettings = s3_video_check_plugin_settings();
		}
	} else {
		$pluginSettings = s3_video_check_plugin_settings(FALSE);
	}

	require_once(WP_PLUGIN_DIR . '/s3-video/views/settings/plugin-settings.php');
}

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
	require_once(WP_PLUGIN_DIR . '/s3-video/views/playlist-management/create-playlist.php');	
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
					
			require_once(WP_PLUGIN_DIR . '/s3-video/views/playlist-management/edit-playlist.php');
		} 
		
		if (!empty($_GET['reorder'])) {
			$playlistId = preg_replace('/[^0-9]/Uis', '', $_GET['reorder']);
			$playlistVideos = $playlistManagement->getPlaylistVideos($playlistId);
			require_once(WP_PLUGIN_DIR . '/s3-video/views/playlist-management/reorder-playlist.php');	
		} 	
		
	} else {
		/*
		 * If we don't have a playlist to display a list of them all  
		 */
		$existingPlaylists = $playlistManagement->getAllPlaylists();	
		require_once(WP_PLUGIN_DIR . '/s3-video/views/playlist-management/playlist-management.php');
	}
	
}

/**
 * Display a page for handling the meta data belonging to a video
 */ 
function s3_video_meta_data()
{
	$pluginSettings = s3_video_check_plugin_settings();
	$videoName = urldecode($_GET['video']);
	if (empty($videoName)) {
		die('Video not found..');
	}
		
	require_once(WP_PLUGIN_DIR . '/s3-video/includes/video_management.php');
	$videoManagement = new s3_video_management();			
				
	s3_video_check_user_access(); 
	$pluginSettings = s3_video_check_plugin_settings();
	$tmpDirectory = s3_video_check_upload_directory();	
	
	if ((!empty($_FILES)) && ($_FILES['upload_still']['size'] > 0)) {
			$stillTypes = array('image/gif', 'image/png', 'image/jpeg');
			if ((!in_array($_FILES['upload_still']['type'], $stillTypes)) || ($_FILES['upload_still']['error'] > 0)) {
				$errorMsg = 'The uploaded file is not able to be used as a video still.';
			} else {
				$imageDimensions = getimagesize($_FILES['upload_still']['tmp_name']);
				if (($imageDimensions[0] < 200) || ($imageDimensions[1] < 200) || ($imageDimensions[0] > 3000) || ($imageDimensions[1] > 3000)) {
					$errorMsg = 'Your video still needs to be over 200px x 200px in size and under 3000px x 3000px';
				} else {				
					$fileName = basename($_FILES['upload_still']['name']);
					$fileName = preg_replace('/[^A-Za-z0-9_.]+/', '', $fileName);
					$imageLocation = $tmpDirectory . $fileName;
					if(move_uploaded_file($_FILES['upload_still']['tmp_name'], $imageLocation)) {
						$s3Access = new S3($pluginSettings['amazon_access_key'], $pluginSettings['amazon_secret_access_key'], NULL, $pluginSettings['amazon_url']);
						$s3Result = $s3Access->putObjectFile($imageLocation, $pluginSettings['amazon_video_bucket'], $fileName, S3::ACL_PUBLIC_READ);
						switch ($s3Result) {
							case 0:
								$errorMsg = 'Request unsucessful check your S3 access credentials';
							break;	
			
							case 1:
								$successMsg = 'The image has successfully been uploaded to your S3 account';					
								
								// Save the image to the database
								$videoManagement->createVideoStill($fileName, $videoName);
							break;
						}
				}
			}
		}
	}

	// Check and see if there is a still in the database for this video
	$videoStill = $videoManagement->getVideoStillByVideoName($videoName);
	$stillFile = '';
	if (!empty($videoStill)) {
		$stillFile = $videoStill;
		$videoStill = 'http://' . $pluginSettings['amazon_video_bucket'] .'.'.$pluginSettings['amazon_url'] . '/' . urlencode($videoStill);
	}
	
	require_once(WP_PLUGIN_DIR . '/s3-video/views/video-management/meta-data.php');	
		
} 

/**
 * 
 * Delete a still thats associated with a video
 * 
 */
function s3_video_remove_video_still()
{
	if ((!empty($_POST)) && (!empty($_POST['image_name'])) && (!empty($_POST['video_name']))) {
		$pluginSettings = s3_video_check_plugin_settings();	
		
		require_once(WP_PLUGIN_DIR . '/s3-video/includes/video_management.php');
		$videoManagement = new s3_video_management();
		
		$videoManagement->deleteVideoStill($_POST['video_name'], $_POST['image_name']);	
		
		$usedBy = $videoManagement->getVideoStillByImageName($_POST['image_name']);
		
		// If the still is not used by any other videos delete from s3
		if (empty($usedBy)) {
			$s3Access = new S3($pluginSettings['amazon_access_key'], $pluginSettings['amazon_secret_access_key'], NULL, $pluginSettings['amazon_url']);
			$result = $s3Access->deleteObject($pluginSettings['amazon_video_bucket'], $_POST['image_name']);			
		}		
	}
	die();
}


/*
 *  Embed video player into page
 */
function s3_video_embed_video($embedDetails) 
{	
	$pluginSettings = s3_video_check_plugin_settings();
	if ($embedDetails['file']) {
		$videoFile =  'http://' . $pluginSettings['amazon_video_bucket']  . '.' .  $pluginSettings['amazon_url'] . '/' . $embedDetails['file'];	
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
			$playerContent = file_get_contents( dirname(__FILE__) .'/views/video-management/play-flowplayer.php');
			$playerContent = str_replace('{videoFile}', $videoFile, $playerContent);	

			$flowplayerLocation = WP_PLUGIN_URL . '/s3-video/misc/flowplayer-3.2.11.swf';		
			$playerContent = str_replace('{flowplayerLocation}', $flowplayerLocation, $playerContent);

			// Set the player dimensions
			if ((!empty($embedDetails['width'])) && ($embedDetails['height'])) {
				$playerContent = str_replace('{videoHeight}', $embedDetails['height'], $playerContent);
				$playerContent = str_replace('{videoWidth}', $embedDetails['width'], $playerContent); 
			} else {
				$playerContent = str_replace('{videoHeight}', 330, $playerContent);
				$playerContent = str_replace('{videoWidth}', 520, $playerContent); 
			}

			// Define the buffering settings
			if ($pluginSettings['amazon_s3_video_autobuffer'] == 0) {
				$playerContent = str_replace('{videoAutoBuffer}', 'false', $playerContent); 
			} else {
				$playerContent = str_replace('{videoAutoBuffer}', 'true', $playerContent); 
			}
			
			// Define the autoplay status
			if ($pluginSettings['amazon_s3_video_autoplay'] == 0) {
				if (!empty($videoStill)) { 
					$playerContent = str_replace('{videoAutoPlay}', 'false', $playerContent); 
				} else {
					$playerContent = str_replace('{videoAutoPlay}', 'true', $playerContent); 
				}
			}
			
			// Define the playlist to support a video still
			$playlistHtml = 'playlist: [' . "\r\n";
			if (!empty($videoStill)) { 
					$playlistHtml .= '{
            				url: "' . $videoStill . '", 
            				scaling: "fit",
            				autoPlay: true
        				},'  . "\r\n";
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
			$playerContent = file_get_contents( dirname(__FILE__) .'/views/video-management/play-videoJS.php');
			$swfFile = WP_PLUGIN_URL . '/s3-video/misc/video-js.swf';
			$playerContent = str_replace('{swfFile}', $swfFile, $playerContent);	

			// Set the player dimensions
			if ((!empty($embedDetails['width'])) && ($embedDetails['height'])) {
				$playerContent = str_replace('{videoHeight}', $embedDetails['height'], $playerContent);
				$playerContent = str_replace('{videoWidth}', $embedDetails['width'], $playerContent); 
			} else {
				$playerContent = str_replace('{videoHeight}', 360, $playerContent);
				$playerContent = str_replace('{videoWidth}', 640, $playerContent); 
			}	

			// Define the buffering settings
			if ($pluginSettings['amazon_s3_video_autobuffer'] == 0) {
				$playerContent = str_replace('{videoBuffer}', 'none', $playerContent); 
			} else {
				$playerContent = str_replace('{videoBuffer}', 'auto', $playerContent); 
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
	require_once(WP_PLUGIN_DIR . '/s3-video/includes/playlist_management.php');
	$playlistManagement = new s3_playlist_management();
	$playlistVideos = $playlistManagement->getPlaylistVideos($embedDetails['id']);		
	$pluginSettings = s3_video_check_plugin_settings();
	$baseUrl =  'http://' . $pluginSettings['amazon_video_bucket']  . '.' .  $pluginSettings['amazon_url'] . '/';
	
	require_once(WP_PLUGIN_DIR . '/s3-video/views/video-management/play-playlist.php');	
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
	require_once(WP_PLUGIN_DIR . '/s3-video/views/video-management/preview-video.php');	
} 

/*
 * Check if the user has configured the plugin
 */
function s3_video_check_plugin_settings($redirect = TRUE)
{
	$pluginSettings = array('amazon_access_key' => get_option('amazon_access_key'),
							'amazon_secret_access_key' => get_option('amazon_secret_access_key'),
							'amazon_url' => get_option('amazon_url'),
							'amazon_video_bucket' => get_option('amazon_video_bucket'),
							'amazon_s3_video_player' => get_option('amazon_s3_video_player'),								
							's3_video_page_result_limit' => get_option('s3_video_page_result_limit'),
							'amazon_s3_video_autoplay' => get_option('amazon_s3_video_autoplay'),
							'amazon_s3_video_autobuffer' => get_option('amazon_s3_video_autobuffer'),
							'amazon_s3_playlist_autoplay' => get_option('amazon_s3_playlist_autoplay'),
							'amazon_s3_playlist_autobuffer' => get_option('amazon_s3_playlist_autobuffer'));		
		
	if ((empty($pluginSettings['amazon_access_key'])) || (empty($pluginSettings['amazon_secret_access_key'])) || (empty($pluginSettings['amazon_secret_access_key']))) {
		if ($redirect) { 
			require_once(WP_PLUGIN_DIR . '/s3-video/views/settings/configuration_required.php');
			exit;
		} else {
			return FALSE;	
		}	
	} else {
		return $pluginSettings;
	}

}

/*
* Check if the user can access the page
*/
function s3_video_check_user_access()
{
	if( !current_user_can( 'manage_options' ) ) {
        	wp_die( 'You do not have sufficient permissions to access this page' );
   	}
}

/*
 * Load the custom style sheets for the admin pages
 */
function s3_video_load_css()
{
	wp_register_style('s3_video_default', WP_PLUGIN_URL . '/s3-video/css/style.css');
	wp_enqueue_style('s3_video_default');
	
	wp_register_style('s3_video_colorbox', WP_PLUGIN_URL . '/s3-video/css/colorbox.css');
	wp_enqueue_style('s3_video_colorbox');	
	
	wp_register_style('multiselect_css', WP_PLUGIN_URL . '/s3-video/css/chosen.css');
	wp_enqueue_style('multiselect_css');			
}

/*
 * Load javascript required by the backend administration pages
 */
function s3_video_load_js()
{	
	wp_enqueue_script('validateJSs', WP_PLUGIN_URL . '/s3-video/js/jquery.validate.js', array('jquery'), '1.0');
	wp_enqueue_script('placeholdersJS', WP_PLUGIN_URL . '/s3-video/js/jquery.placeholders.js', array('jquery'), '1.0');
	wp_enqueue_script('colorBox', WP_PLUGIN_URL . '/s3-video/js/jquery.colorbox.js', array('jquery'), '1.0');
	wp_enqueue_script('tableSorter', WP_PLUGIN_URL . '/s3-video/js/jquery.tablesorter.js', array('jquery'), '1.0');	
	wp_enqueue_script('tablePaginator', WP_PLUGIN_URL . '/s3-video/js/jquery.paginator.js', array('jquery'), '1.0');	
	wp_enqueue_script('multiSelect', WP_PLUGIN_URL . '/s3-video/js/jquery.multiselect.js', array('jquery'), '1.0');		
	wp_enqueue_script('dragDropTable', WP_PLUGIN_URL . '/s3-video/js/jquery.tablednd.js', array('jquery'), '1.0');			
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
		wp_enqueue_script('flowPlayer', WP_PLUGIN_URL . '/s3-video/js/flowplayer-3.2.10.js', array('jquery'), '1.0');
		wp_enqueue_script('flowPlayerPlaylist', WP_PLUGIN_URL . '/s3-video/js/jquery.playlist.js', array('jquery'), '1.0');	
	} else {
		wp_enqueue_script('videoJS', WP_PLUGIN_URL . '/s3-video/js/video.min.js');
		wp_register_style('s3_video_videoJS_css', WP_PLUGIN_URL . '/s3-video/css/video-js.css');
		wp_enqueue_style('s3_video_videoJS_css');						
	}		
}

/*
 * Clear the saved plugin settings on deactivation
 */
function s3_video_deactivate()
{
	delete_option('amazon_access_key');
	delete_option('amazon_secret_access_key');	
	delete_option('amazon_video_bucket');	
	delete_option('amazon_url');
	delete_option('s3_video_page_result_limit');			
}

/*
 * Install the required database tables for the plugin on activation 
 */
function s3_plugin_activate()
{
	require_once(WP_PLUGIN_DIR . '/s3-video/includes/plugin_setup.php');
	$pluginSetup = new s3_video_plugin_setup();
	$dbVersion = $pluginSetup->activate_plugin();
	
	if (!empty($dbVersion))  {
		add_option("s3_plugin_db_version", $dbVersion);
	}			
}

/*
 * Deactivate the plugin and remove all associate database tables
 */
function s3_plugin_deactivate()
{
	require_once(WP_PLUGIN_DIR . '/s3-video/includes/plugin_setup.php');	
	$pluginSetup = new s3_video_plugin_setup();
	$pluginSetup->deactivate_plugin();
}	
