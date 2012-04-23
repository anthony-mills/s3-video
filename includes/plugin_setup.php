<?php
/*
 * Deal with the activation and deactivation processes needed by the S3 video plugin
 * 
 */
class s3_video_plugin_setup
{
	protected $_databaseDebug = FALSE;
	protected $_databaseDump = 's3_video.sql';
	protected $_dbVersion = '0.2';
	
	function set_db_dump($databaseDump = NULL)
	{
		if (!empty($dbDump)) {
			$this->_databaseDump = $dbDump;	
		}		
	}
	
	function activate_plugin()
	{
		$sql = file_get_contents(dirname(__FILE__) . '/' . $this->_databaseDump);
		
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		$tables = explode('{table}', $sql);
		
		// Create the tables from the dump
		foreach($tables as $table) {
			dbDelta($table);		
		}
		return $this->_dbVersion;
	}
	
	function deactivate_plugin()
	{
		mysql_query("DROP TABLE IF EXISTS `s3_video_playlists`") or die(mysql_error());		
		mysql_query("DROP TABLE IF EXISTS `s3_video_playlist_videos`") or die(mysql_error());	
		mysql_query("DROP TABLE IF EXISTS `s3_video_analytics`") or die(mysql_error());			
	}
} 
