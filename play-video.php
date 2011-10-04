<?php if (!empty($videoFile)) { ?>
	<?php if ((empty($embedDetails['width'])) && (empty($embedDetails['height']))) { ?>
		<a href="<?= $videoFile; ?>" style="display:block;width:520px;height:330px"  id="player"></a> 
	<?php } else { ?>
		<a href="<?= $videoFile; ?>" style="display:block;width:<?= $embedDetails['width']; ?>px;height:<?= $embedDetails['height']; ?>px"  id="player"></a> 		
	<?php } ?>
	
	<script>
		flowplayer("player", '<?= WP_PLUGIN_URL; ?>/s3-video/misc/flowplayer-3.2.7.swf', {
		    clip:  {
		        autoPlay: false,
		        autoBuffering: true,
		        bufferLength: 5
		    }			
		});
	</script>
<?php } else { ?>
		<p>Media not found</p>
<?php } ?>	

