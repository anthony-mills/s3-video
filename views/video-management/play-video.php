<?php if (!empty($videoFile)) { ?>
	<?php if ((empty($pluginSettings['amazon_s3_video_player'])) || ($pluginSettings['amazon_s3_video_player'] == 'flowplayer')) { ?>
		<?php // embed video with flowplayer ?>
		<?php if ((empty($embedDetails['width'])) && (empty($embedDetails['height']))) { ?>
			<a href="<?php echo $videoFile; ?>" style="display:block;width:520px;height:330px"  id="player"></a> 
		<?php } else { ?>
			<a href="<?php echo $videoFile; ?>" style="display:block;width:<?php echo $embedDetails['width']; ?>px;height:<?php echo $embedDetails['height']; ?>px"  id="player"></a> 		
		<?php } ?>
		<?php 
			if ($pluginSettings['amazon_s3_video_autobuffer'] == 0) {
				$autoBuffer = 'autoBuffering: false,' . "\r\n";
			} else {
				$autoBuffer = 'autoBuffering: true,' . "\r\n";			
			}
			
			if ($pluginSettings['amazon_s3_video_autoplay'] == 0) {
				if (!empty($videoStill)) { 
					$autoPlay = 'autoPlay: true,' . "\r\n";
				} else {
					$autoPlay = 'autoPlay: false,' . "\r\n";					
				}
			} else {
				$autoPlay = 'autoPlay: true,' . "\r\n";			
			}		
		?>
		<script>
			flowplayer("player", '<?php echo WP_PLUGIN_URL; ?>/s3-video/misc/flowplayer-3.2.11.swf', {
			    clip:  {
			        <?php echo $autoBuffer; ?>
			        <?php echo $autoPlay; ?>
			        bufferLength: 5,				
			    },
			    
			    playlist: [
					<?php if (!empty($videoStill)) { ?>
							{
            					url: '<?php echo $videoStill; ?>', 
            					scaling: 'fit',
            					autoPlay: true
        					},
					<?php } ?>
					<?php if ((!empty($videoStill)) && ($pluginSettings['amazon_s3_video_autoplay'] == 0)) { ?>
							{
								url: '<?php echo  $videoFile;?>',
								title: '<?php echo  $videoFile;?>',
								autoPlay: false
        					},
					<?php } else { ?>					
							{
								url: '<?php echo  $videoFile;?>',
								title: '<?php echo  $videoFile;?>'
        					},
					<?php } ?>				
				]
			});
		</script>
	<?php } else { ?>
    		<script>
    			_V_.options.flash.swf = "<?php echo WP_PLUGIN_URL; ?>/s3-video/misc/video-js.swf";
  			</script>  
  					
			<?php 
				// embed video with videojs player
				if ((empty($embedDetails['width'])) && (empty($embedDetails['height']))) {
					$playerWidth = 640;
					$playerHeight = 360;
				} else {
					$playerWidth = $embedDetails['width'];
					$playerHeight = $embedDetails['height'];
				}
					 
				if ($pluginSettings['amazon_s3_video_autobuffer'] == 0) {
					$autoBuffer = 'none';
				} else {
					$autoBuffer = 'auto';			
				}
								
				if (!empty($videoStill)) {
					echo '<video id="video_preview" class="video-js vjs-default-skin" controls preload="'.$autoBuffer.'" width="'.$playerWidth.'" height="'.$playerHeight.'" poster="'.$videoStill.'" data-setup="{}">';
				} else {
					echo '<video id="video_preview" class="video-js vjs-default-skin" controls preload="'.$autoBuffer.'" width="'.$playerWidth.'" height="'.$playerHeight.'" data-setup="{}">';					
				}
				  $fileType = substr($videoFile, -3);
				  if ($fileType == 'flv') {
				 ?>
				  	<source src="<?php echo $videoFile; ?>" type='video/x-flv'>
				<?php } else { ?>
				  	<source src="<?php echo  $videoFile; ?>" type='video/mp4'>				
				<?php } ?>
			</video>					
			
	<?php } ?>
<?php } else { ?>
		<p>Media not found</p>
<?php } ?>	

