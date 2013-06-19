<script type="text/javascript">
	jQuery(function() {
			jQuery("#pluginSettings").validate({
				errorLabelContainer: jQuery("#validationError"),
				messages: {
					amazon_access_key: {
					    required: 'Please enter an Amazon API access key<br>'
					},			
			
					amazon_secret_access_key: {
					    required: 'Please enter an Amazon API shared key<br>'
					},      	        
	
					amazon_video_bucket: {
					    required: 'Please enter the name of the bucket your videos are stored in<br>'
					}, 	        	        
			 	}
			});
			
			JT_init();
	
			jQuery(':input[placeholder]').placeholder();
		
	});	
</script>

<div class="wrap">
	<h2>Plugin Settings</h2>

	<div id="validationError"></div>
	
	<?php if (!empty($successMsg)) { ?>
			<div id="successMsg">
				<?php echo $successMsg; ?>
			</div>
	<?php } ?>
	<form method="POST" id="pluginSettings">	
		<table>
			<tr>
				<td>
					<p>
						<strong>AWS Details:</strong>
					</p>
				</td>

				<td>
				</td>
			</tr>
						
			<tr>
				<td class="heading">
					<em>*</em>
					Access Key ID: 
				</td>

				<td>
					<input type="text" name="amazon_access_key" class="required" maxlength="21" placeholder="Amazon Access Key" value="<?php echo $pluginSettings['amazon_access_key']; ?>">
				</td>
			</tr>

			<tr>
				<td class="heading">
					<em>*</em>
					Secret Access Key: 
				</td>

				<td>
					<input type="text" name="amazon_secret_access_key" class="required" maxlength="50" placeholder="Secret Access Key" value="<?php echo $pluginSettings['amazon_secret_access_key']; ?>">
				</td>
			</tr>

			<tr>
				<td class="heading">
					<em>*</em>
					Video Bucket: 
				</td>

				<td>
					<input type="text" name="amazon_video_bucket" class="required" maxlength="50" placeholder="Amazon Video Bucket" value="<?php echo $pluginSettings['amazon_video_bucket']; ?>">
				</td>
			</tr>
			
            <tr>
                <td class="heading">
                   Fetch prefix:
                </td>

                <td>
                    <input type="text" name="amazon_prefix" placeholder="Prefix"  value="<?php echo $pluginSettings['amazon_prefix']; ?>"> 
                    - <a href="<?php echo WP_PLUGIN_URL; ?>/s3-video/views/tips/s3_prefix.html" class="jTip" id="fetchPrefixTip">More Info?</a>
                </td>
            </tr>
            
			<tr>
				<td class="heading">
					S3 Host: 
				</td>

				<td>
					<input type="text" name="amazon_url" placeholder="s3.amazonaws.com"  value="<?php echo $pluginSettings['amazon_url']; ?>">
				</td>
			</tr>

			<tr>
				<td>
					<br>
					<p>
						<strong>General Settings:</strong>
					</p>
				</td>

				<td>
				</td>
			</tr>
			
			<tr>
				<td class="heading">
					<em>*</em>
					# Of Video Results Per Page: 
				</td>

				<td>
					<?php 
						if (empty($pluginSettings['s3_video_page_result_limit'])) {
							$pluginSettings['s3_video_page_result_limit'] = 15;
						}
					?>
					<input type="text" name="page_result_limit" value="<?php echo $pluginSettings['s3_video_page_result_limit']; ?>" class="required">					
				</td>
			</tr>

			<tr>

				<td class="heading">
					Video Player: 
				</td>

				<td>
					<?php if ((empty($pluginSettings['amazon_s3_video_player'])) || ($pluginSettings['amazon_s3_video_player'] == 'flowplayer')) { ?>
							<input type="radio" name="video_player" value="flowplayer" checked> Flowplayer 
							- <a href="<?php echo WP_PLUGIN_URL; ?>/s3-video/views/tips/flowplayer_info.html" class="jTip" id="flowplayerTip">More Info?</a> 							
							- <a href="http://flowplayer.org/" target="_blank">Player Website</a>
							<br />
							<input type="radio" name="video_player" value="videojs"> VideoJS 
							- <a href="<?php echo WP_PLUGIN_URL; ?>/s3-video/views/tips/videojs_info.html" class="jTip" id="videojsTip">More Info?</a>							
							- <a href="http://videojs.com/" target="_blank">Player Website</a>
					<?php } else { ?>	
							<input type="radio" name="video_player" value="flowplayer"> Flowplayer 
							- <a href="<?php echo WP_PLUGIN_URL; ?>/s3-video/views/tips/flowplayer_info.html" class="jTip" id="flowplayerTip">More Info?</a>							
							- <a href="http://flowplayer.org/" target="_blank">Player Website</a>
							<br />
							<input type="radio" name="video_player" value="videojs" checked> VideoJS 
							- <a href="<?php echo WP_PLUGIN_URL; ?>/s3-video/views/tips/videojs_info.html" class="jTip" id="videojsTip">More Info?</a>							
							- <a href="http://videojs.com/" target="_blank">Player Website</a>
					<?php } ?>		
				</td>
			</tr>			

			<tr>

				<td class="heading">
					Default Player Size: 
				</td>

				<td>
					<?php if ((empty($pluginSettings['amazon_s3_video_playerwidth'])) || (empty($pluginSettings['amazon_s3_video_playerheight']))) { ?>
							Width: <input type="text" name="video_playerwidth" value="530" size="4"> px <a href="<?php echo WP_PLUGIN_URL; ?>/s3-video/views/tips/player_dimensions.html" class="playerDimensionsTip" id="three">More Info?</a>
							<br />
							Height: <input type="text" name="video_playerheight" value="330" size="4"> px
					<?php } else { ?>
							Width: <input type="text" name="video_playerwidth" value="<?php echo $pluginSettings['amazon_s3_video_playerwidth']; ?>" size="4"> px -
							<a href="<?php echo WP_PLUGIN_URL; ?>/s3-video/views/tips/player_dimensions.html" class="jTip" id="playerDimensionsTip">More Info?</a>
							<br />
							Height: <input type="text" name="video_playerheight" value="<?php echo $pluginSettings['amazon_s3_video_playerheight']; ?>" size="4"> px							
					<?php } ?>		
				</td>
			</tr>			

			<tr>
				<td>
					<br>
					<p>
						<strong>Single Video Playback:</strong>
					</p>
				</td>

				<td>
				</td>
			</tr>
			
			<tr>
				<td class="heading">
					Autoplay: 
				</td>

				<td>
					<?php if ((empty($pluginSettings['amazon_s3_video_autoplay'])) || ($pluginSettings['amazon_s3_video_autoplay'] ==0)) { ?>
							True<input type="radio" name="video_autoplay" value="1">
							False<input type="radio" name="video_autoplay" value="0" checked>
					<?php } else { ?>	
							True<input type="radio" name="video_autoplay" value="1" checked>
							False<input type="radio" name="video_autoplay" value="0">
					<?php } ?>				
				</td>
			</tr>
			
			<tr>
				<td class="heading">
					Autobuffer: 
				</td>

				<td>
					<?php if ((empty($pluginSettings['amazon_s3_video_autobuffer'])) || ($pluginSettings['amazon_s3_video_autobuffer'] ==0)) { ?>
							True<input type="radio" name="video_autobuffer" value="1">
							False<input type="radio" name="video_autobuffer" value="0" checked>
					<?php } else { ?>	
							True<input type="radio" name="video_autobuffer" value="1" checked>
							False<input type="radio" name="video_autobuffer" value="0">
					<?php } ?>		
				</td>
			</tr>		

			<tr>
				<td>
					<br>
					<p>
						<strong>Video Playlist Playback:</strong>
					</p>
				</td>

				<td>
				</td>
			</tr>
			
			<tr>
				<td class="heading">
					Autoplay: 
				</td>

				<td>
					<?php if ((empty($pluginSettings['amazon_s3_playlist_autoplay'])) || ($pluginSettings['amazon_s3_playlist_autoplay'] == 0)) { ?>
							True<input type="radio" name="playlist_autoplay" value="1">
							False<input type="radio" name="playlist_autoplay" value="0" checked>
					<?php } else { ?>	
							True<input type="radio" name="playlist_autoplay" value="1" checked>
							False<input type="radio" name="playlist_autoplay" value="0">
					<?php } ?>				
				</td>
			</tr>
			
			<tr>
				<td class="heading">
					Autobuffer: 
				</td>

				<td>
					<?php if ((empty($pluginSettings['amazon_s3_playlist_autobuffer'])) || ($pluginSettings['amazon_s3_playlist_autobuffer'] == 0)) { ?>
							True<input type="radio" name="playlist_autobuffer" value="1">
							False<input type="radio" name="playlist_autobuffer" value="0" checked>
					<?php } else { ?>	
							True<input type="radio" name="playlist_autobuffer" value="1" checked>
							False<input type="radio" name="playlist_autobuffer" value="0">
					<?php } ?>		
				</td>
			</tr>		
								
			<tr>
				<td> 
				</td>

				<td>
					<div align="center">
						<input type="submit" value="Save">
					</div>
				</td>
			</tr>
		</table>
		
	</form>
</div>
