<?php if (!empty($videoFile)) { ?>
	<?php if ((empty($pluginSettings['amazon_s3_video_player'])) || ($pluginSettings['amazon_s3_video_player'] == 'flowplayer')) { ?>
		<?php // embed video woith flowplayer ?>
		<?php if ((empty($embedDetails['width'])) && (empty($embedDetails['height']))) { ?>
			<a href="<?= $videoFile; ?>" style="display:block;width:520px;height:330px"  id="player"></a> 
		<?php } else { ?>
			<a href="<?= $videoFile; ?>" style="display:block;width:<?= $embedDetails['width']; ?>px;height:<?= $embedDetails['height']; ?>px"  id="player"></a> 		
		<?php } ?>
		<?php 
			if ($pluginSettings['amazon_s3_video_autobuffer'] == 0) {
				$autoBuffer = 'autoBuffering: false,' . "\r\n";
			} else {
				$autoBuffer = 'autoBuffering: true,' . "\r\n";			
			}
			
			if ($pluginSettings['amazon_s3_video_autoplay'] == 0) {
				$autoPlay = 'autoPlay: false,' . "\r\n";
			} else {
				$autoPlay = 'autoPlay: true,' . "\r\n";			
			}		
		?>
		<script>
			flowplayer("player", '<?= WP_PLUGIN_URL; ?>/s3-video/misc/flowplayer-3.2.11.swf', {
			    clip:  {
			        <?= $autoBuffer; ?>
			        <?= $autoPlay; ?>
			        bufferLength: 5,
			        onStart: function() {
		    			jQuery.getJSON("<?= WP_PLUGIN_URL; ?>/s3-video/includes/video_tracking.php?video=<?= $videoFile; ?>&action=start&time="+$f().getTime()+"&jsoncallback=?");
					},
	      			onResume: function(){
		    			jQuery.getJSON("<?= WP_PLUGIN_URL; ?>/s3-video/includes/video_tracking.php?video=<?= $videoFile; ?>&action=resume&time="+$f().getTime()+"&jsoncallback=?");  				
	      			},					
				    onPause: function () {
		    			jQuery.getJSON("<?= WP_PLUGIN_URL; ?>/s3-video/includes/video_tracking.php?video=<?= $videoFile; ?>&action=paused&time="+$f().getTime()+"&jsoncallback=?");			    	
				    },
				    onFinish: function(){
		    			jQuery.getJSON("<?= WP_PLUGIN_URL; ?>/s3-video/includes/video_tracking.php?video=<?= $videoFile; ?>&action=finish&time="+$f().getTime()+"&jsoncallback=?");		    	
				    }					
			    }			
			});
		</script>
	<?php } else { ?>
			<?php echo substr($videoFile, -3); ?>
    		<script>
    			_V_.options.flash.swf = "<?= WP_PLUGIN_URL; ?>/s3-video/misc/video-js.swf";
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
			?>
			<video id="video_preview" class="video-js vjs-default-skin" controls preload="<?php echo $autoBuffer; ?>" width="<?php echo $playerWidth; ?>" height="<?php echo $playerHeight; ?>" data-setup="{}">
				<?php
				  $fileType = substr($videoFile, -3);
				  if ($fileType == 'flv') {
				 ?>
				  	<source src="<?= $videoFile; ?>" type='video/x-flv'>
				<?php } else { ?>
				  	<source src="<?= $videoFile; ?>" type='video/mp4'>				
				<?php } ?>
			</video>					
			
	<?php } ?>
<?php } else { ?>
		<p>Media not found</p>
<?php } ?>	

