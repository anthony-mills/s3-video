<?php 
if (!$playlistVideos) {
	echo '<div align="center">Playlist not found</center>';
} else {
		if ($pluginSettings['amazon_s3_playlist_autobuffer'] == 0) {
			$autoBuffer = 'autoBuffering: false,' . "\r\n";
		} else {
			$autoBuffer = 'autoBuffering: true,' . "\r\n";			
		}
		
		if ($pluginSettings['amazon_s3_playlist_autoplay'] == 0) {
			$autoPlay = 'autoPlay: false,' . "\r\n";
		} else {
			$autoPlay = 'autoPlay: true,' . "\r\n";			
		}		
?>
	<script>
	jQuery(function() {
		
		// setup player normally
		$f("player", "<?= WP_PLUGIN_URL;?>/s3-video/misc/flowplayer-3.2.7.swf", {
		
			// clip properties common to all playlist entries
			clip: {
				autoPlay: true,
		        <?= $autoBuffer; ?>
		        <?= $autoPlay; ?>				
				autoBuffering: true,
				bufferLength: 5,
			 	baseUrl: '<?= $baseUrl;?>'
			},
			
			// our playlist
			playlist: [
				<?php foreach($playlistVideos as $video) { ?>
					{
						url: '<?= $video['video_file'];?>',
						title: '<?= $video['video_file'];?>'
					},
				<?php }?>
			],
			
			// show playlist buttons in controlbar
			plugins: {
				controls: {
					playlist: true
				}
			}
		});
		
		
	});	
	</script>
	
	<?php if ((empty($embedDetails['width'])) && (empty($embedDetails['height']))) { ?>
		<a style="display:block;width:520px;height:330px"  id="player"></a> 
	<?php } else { ?>
		<a style="display:block;width:<?= $embedDetails['width']; ?>px;height:<?= $embedDetails['height']; ?>px"  id="player"></a> 		
	<?php } ?>
	<br clear="all"/>
<?php } ?>