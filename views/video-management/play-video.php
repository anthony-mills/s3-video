<?php if (!empty($videoFile)) { ?>
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
		<p>Media not found</p>
<?php } ?>	

