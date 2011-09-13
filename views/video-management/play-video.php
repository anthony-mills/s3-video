<?php if (!empty($videoFile)) { ?>
	<?php if ((empty($embedDetails['width'])) && (empty($embedDetails['height']))) { ?>
		<a href="<?= $videoFile; ?>" style="display:block;width:520px;height:330px"  id="player"></a> 
	<?php } else { ?>
		<a href="<?= $videoFile; ?>" style="display:block;width:<?= $embedDetails['width']; ?>px;height:<?= $embedDetails['height']; ?>px"  id="player"></a> 		
	<?php } ?>
	
	<script>
		flowplayer("player", '<?= WP_PLUGIN_URL; ?>/S3-Video/misc/flowplayer-3.2.7.swf', {
		    clip:  {
		        autoPlay: false,
		        autoBuffering: true,
		        bufferLength: 5,
		        onStart: function() {
	    			jQuery.getJSON("<?= WP_PLUGIN_URL; ?>/S3-Video/includes/video_tracking.php?video=<?= $videoFile; ?>&action=start&time="+$f().getTime()+"&jsoncallback=?");
            		$f().stop();
				},
		        onFinish: function() {
	    			jQuery.getJSON("<?= WP_PLUGIN_URL; ?>/S3-Video/includes/video_tracking.php?video=<?= $videoFile; ?>&action=finish&time="+$f().getTime()+"&jsoncallback=?");
            		$f().stop();
				},				
		    }			
		});
	</script>
<?php } else { ?>
		<p>Media not found</p>
<?php } ?>	

