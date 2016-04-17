<?php
/**
 * Functions for the management of video media
 * 
 */
 

/*
 *  Default plugin page displaying existing media files
 */
function s3_video()
{
	s3_video_check_user_access();
	$pluginSettings = s3_video_check_plugin_settings();

	$videoName = filter_input(INPUT_GET, 'delete');

	if ((isset($videoName)) && (!empty($videoName))) {
		
		$s3Access = new S3(
							$pluginSettings['amazon_access_key'], 
							$pluginSettings['amazon_secret_access_key'], 
							NULL, 
							$pluginSettings['amazon_url']
						);	

		require_once(WP_PLUGIN_DIR . '/s3-video/includes/video_management.php');

		$videoManagement = new s3_video_management();

		// Delete the video from S3
		$result = $s3Access->deleteObject(
											$pluginSettings['amazon_video_bucket'], 
											$videoName
										);
		
		// Delete any stills that are associated with the video
		$videoStill = $videoManagement->getVideoStillByVideoName($videoName);

		$result = $s3Access->deleteObject(
											$pluginSettings['amazon_video_bucket'], 
											$videoStill
										);						

		$videoManagement->deleteVideoStill($videoName);		

		// Delete the video from any playlists

		$result = $s3Access->deleteObject($pluginSettings['amazon_video_bucket'], $videoName);			
	
		if ($result) {
			$successMsg = $videoName . ' was successfully deleted.';
		}
	}

	$existingVideos= s3_video_get_all_existing_video($pluginSettings);		
	
	require_once(WP_PLUGIN_DIR . '/s3-video/views/video-management/existing_videos.php');		
}

/*
 * Upload videos to S3 bucket
 */
function s3_video_upload_video()
{
	s3_video_check_user_access(); 
	$pluginSettings = s3_video_check_plugin_settings();

	$tmpDirectory = s3_video_check_upload_directory();

	$fileTypes = array(
						'video/x-flv', 
						'video/x-msvideo', 
						'video/mp4', 
						'application/octet-stream', 
						'video/avi', 
						'video/x-msvideo', 
						'video/mpeg'
					);

	if ((!empty($_FILES)) && ($_FILES['upload_video']['size'] > 0)) {

			if ((!in_array($_FILES['upload_video']['type'], $fileTypes)) && ($_FILES['upload_video']['type'] !='application/octet-stream')) {	

					$errorMsg = 'You need to provide an .flv or .mp4 file';

			} else {
				$fileName = basename($_FILES['upload_video']['name']);
				$fileName = preg_replace('/[^A-Za-z0-9_.]+/', '', $fileName);
				
				$videoLocation = $tmpDirectory . $fileName;

				if(move_uploaded_file($_FILES['upload_video']['tmp_name'], $videoLocation)) {

					//Check if the plugin config indicates that the file must be uploaded into a certain folder location on S3
					//This is because some administrators don't always give you access to write into the bucket but in a particular folder or folders instead 
					if (!empty($pluginSettings['amazon_video_folder'])) {
						$fileName =  $pluginSettings['amazon_video_folder'] . '/'. $fileName;
					}

					$s3Access = new S3(
										$pluginSettings['amazon_access_key'], 
										$pluginSettings['amazon_secret_access_key'], 
										NULL, 
										$pluginSettings['amazon_url']
									);

					$s3Result = $s3Access->putObjectFile(
															$videoLocation, 
															$pluginSettings['amazon_video_bucket'], 
															$fileName, 
															S3::ACL_PUBLIC_READ
														);

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
		if ((!empty($_POST)) || (!empty($_FILES)))  {

    		$errorMsg = 'There was an error uploading the video';
    		$errorMsg .= '<ul><li>Check the upload directory exists and is writable</li>' .
    					 '<li>Ensure the upload_max_filesize and post_max_size parameters ' .
    					 'are large enough to accomodate the size of the video.</li></ul>';
		}
	}
	require_once(WP_PLUGIN_DIR . '/s3-video/views/video-management/upload_video.php');
}

/**
 * Display a page for handling the meta data belonging to a video
 */ 
function s3_video_meta_data()
{
	$pluginSettings = s3_video_check_plugin_settings();
	$videoName = urldecode(filter_input(INPUT_GET, 'video'));

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

					$fileName = time() . '_' . basename($_FILES['upload_still']['name']);
					$fileName = preg_replace('/[^A-Za-z0-9_.]+/', '', $fileName);
					$imageLocation = $tmpDirectory . $fileName;

					if(move_uploaded_file($_FILES['upload_still']['tmp_name'], $imageLocation)) {

						$s3Access = new S3(
											$pluginSettings['amazon_access_key'], 
											$pluginSettings['amazon_secret_access_key'], 
											NULL, 
											$pluginSettings['amazon_url']
										);

						$s3Result = $s3Access->putObjectFile(
																$imageLocation, 
																$pluginSettings['amazon_video_bucket'], 
																$fileName, 
																S3::ACL_PUBLIC_READ
															);

						switch ($s3Result) {

							case 0:
								$errorMsg = 'Request unsucessful check your S3 access credentials';
							break;	
			
							case 1:
								$successMsg = 'The image has successfully been uploaded to your S3 account';					
								
								// Save the image to the database					
								$videoManagement->deleteVideoStill($videoName);

								$s3Access = new S3(
													$pluginSettings['amazon_access_key'], 
													$pluginSettings['amazon_secret_access_key'], 
													NULL, 
													$pluginSettings['amazon_url']
												);

								$result = $s3Access->deleteObject(
																	$pluginSettings['amazon_video_bucket'], 
																	filter_input(INPUT_POST, 'image_name')
																);
								
								$videoManagement->createVideoStill(
																	$fileName, 
																	$videoName
																);
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
	
	require_once(WP_PLUGIN_DIR . '/s3-video/views/video-management/meta_data.php');	
		
} 

/**
 * 
 * Delete a still thats associated with a video
 * 
 */
function s3_video_remove_video_still()
{
	if ((!empty($_POST)) && (!empty(filter_input(INPUT_POST, 'image_name'))) && (!empty(filter_input(INPUT_POST, 'video_name')))) {
		$pluginSettings = s3_video_check_plugin_settings();	
		
		require_once(WP_PLUGIN_DIR . '/s3-video/includes/video_management.php');
		$videoManagement = new s3_video_management();
		
		$videoManagement->deleteVideoStill(filter_input(INPUT_POST, 'video_name'));	
		
		$s3Access = new S3(
							$pluginSettings['amazon_access_key'], 
							$pluginSettings['amazon_secret_access_key'], 
							NULL, 
							$pluginSettings['amazon_url']
						);

		$result = $s3Access->deleteObject(
											$pluginSettings['amazon_video_bucket'], 
											filter_input(INPUT_POST, 'image_name')
										 );					
	}
	die();
}

/**
 * 
 * Post / Page Video insertion functionality for the media manager
 * 
 */
function s3video_video_media_manager()
{
	$pluginSettings = s3_video_check_plugin_settings();
	$existingVideos = s3_video_get_all_existing_video($pluginSettings);

	if ((isset(filter_input(INPUT_POST, 'insertVideoName'))) && (!empty(filter_input(INPUT_POST, 'insertVideoName')))) {

		$insertHtml = "[S3_embed_video file='" . filter_input(INPUT_POST, 'insertVideoName') . "']";
		media_send_to_editor($insertHtml);
		die();

	}

	require_once(WP_PLUGIN_DIR . '/s3-video/views/video-management/media_manager_insert_video.php');
} 