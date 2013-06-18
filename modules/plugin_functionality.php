<?php
/**
 * 
 * Functions providing general plugin functionality, plugin pages etc
 * 
 */

/**
 * 
 * Check if the user can access the page
 * 
 */
function s3_video_check_user_access()
{
	if( !current_user_can( 'manage_options' ) ) {
        	wp_die( 'You do not have sufficient permissions to access this page' );
   	}
}

/**
 * 
 * Check if the user has configured the plugin and if so load them
 * 
 */
function s3_video_check_plugin_settings($redirect = TRUE)
{
	$pluginSettings = array('amazon_access_key' => get_option('amazon_access_key'),
							'amazon_secret_access_key' => get_option('amazon_secret_access_key'),
							'amazon_url' => get_option('amazon_url'),
							'amazon_prefix' => get_option('amazon_prefix'),
							'amazon_video_bucket' => get_option('amazon_video_bucket'),
							'amazon_s3_video_player' => get_option('amazon_s3_video_player'),
							'amazon_s3_video_playerwidth' => get_option('amazon_s3_video_playerwidth'),	
							'amazon_s3_video_playerheight' => get_option('amazon_s3_video_playerheight'),															
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

/**
 * 
 * Add a tab to the Wordpress media manager
 */  
function s3_video_add_media_tabs($mediaTabs)
{
	$mediaTabs['s3video_video_media_manager']='S3 Video';
	$mediaTabs['s3video_playlist_media_manager']='S3 Playlists';
	return $mediaTabs;
} 

/**
 * 
 * Define the plugin menu in the Wordpress backend
 * 
 */ 
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
  
/**
 * 
 * Page to configure plugin settings i.e Amazon access keys etc
 * 
 */
function s3_video_plugin_settings()
{
	if (!empty($_POST)) {
		if ((!empty($_POST['amazon_access_key'])) && (!empty($_POST['amazon_secret_access_key'])) && (!empty($_POST['amazon_video_bucket']))) {
			register_setting( 'amazon_s3_video', 'amazon_access_key' );
			register_setting( 'amazon_s3_video', 'amazon_secret_access_key' );
			register_setting( 'amazon_s3_video', 'amazon_video_bucket' );
			register_setting( 'amazon_s3_video', 'amazon_url' );
			register_setting( 'amazon_s3_video', 'amazon_prefix' );			
			register_setting( 's3-video-results-limit', 's3_video_page_result_limit' );
			
			register_setting( 'amazon_s3_video_autoplay', 'video_autoplay' );							
			register_setting( 'amazon_s3_video_autobuffer', 'video_autobuffer' );
			register_setting( 'amazon_s3_playlist_autoplay', 'playlist_autoplay' );
			register_setting( 'amazon_s3_playlist_autobuffer', 'playlist_autobuffer' );
				
			register_setting( 'amazon_s3_video_player', 'video_player' );	
			register_setting( 'amazon_s3_video_playerwidth', 'video_playerwidth' );
			register_setting( 'amazon_s3_video_playerheight', 'video_playerheight' );				

			update_option( 'amazon_access_key', trim($_POST['amazon_access_key'] ));
			update_option( 'amazon_secret_access_key', trim($_POST['amazon_secret_access_key'] ));
			update_option( 'amazon_video_bucket', trim($_POST['amazon_video_bucket'] ));
			
			update_option( 'amazon_s3_video_player', trim($_POST['video_player'] ));
			update_option( 'amazon_s3_video_playerwidth', trim($_POST['video_playerwidth'] ));
			update_option( 'amazon_s3_video_playerheight', trim($_POST['video_playerheight'] ));						
						
			update_option( 'amazon_s3_video_autoplay', $_POST['video_autoplay'] );
			update_option( 'amazon_s3_video_autobuffer', $_POST['video_autobuffer'] );
			
			update_option( 'amazon_s3_playlist_autoplay', $_POST['playlist_autoplay'] );
			update_option( 'amazon_s3_playlist_autobuffer', $_POST['playlist_autobuffer'] );
			update_option( 'amazon_prefix', trim($_POST['amazon_prefix'] ));
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

	require_once(WP_PLUGIN_DIR . '/s3-video/views/settings/plugin_settings.php');
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
	wp_enqueue_script('jTip', WP_PLUGIN_URL . '/s3-video/js/jtip.js', array('jquery'), '1.0');				
}
