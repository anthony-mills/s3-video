<?php 
$baseDir = str_replace('views/video-management', '', dirname($_SERVER['PHP_SELF']));

$videoPlayer = filter_input(INPUT_GET, 'player');
$mediaFile = filter_input(INPUT_GET, 'media');
?> 
<html>
<head>
	<?php if ((empty($videoPlayer) || ($videoPlayer == 'flowplayer')) { ?>
			<?php $player = 'flowplayer'; ?>
    		<script type="text/javascript" src="<?php echo $baseDir; ?>js/flowplayer-3.2.12.js"></script>
	<?php } else { ?>
    		<?php $player = 'videojs'; ?>
    		<link href="<?php echo $baseDir; ?>css/video-js.css" rel="stylesheet">
    		<script type="text/javascript" src="<?php echo $baseDir; ?>js/video.min.js"></script> 
    		<script>
    			_V_.options.flash.swf = "<?php echo $baseDir; ?>misc/video-js.swf";
  		</script>   
	<?php } ?>
</head> 

<body>
	<div align="center" id="videoElement">
		<?php if (!empty($mediaFile)) { ?>
			<?php if ($player == 'flowplayer') { ?>
						<a href="<?php echo $mediaFile ?>" style="display:block;width:640px;height:360px"  id="player"></a> 
						
						<script>
							flowplayer("player", "<?php echo $baseDir; ?>misc/flowplayer-3.2.16.swf", {
							    clip:  {
							        autoPlay: false,
							        autoBuffering: true,
							        bufferLength: 5
							    }			
							});
						</script>
			<?php } else { ?>
				<video id="video_preview" class="video-js vjs-default-skin" controls preload="auto" width="640" height="380" data-setup="{}">
				  <source src="<?php echo $mediaFile; ?>" type='video/mp4'>
				</video>			
			<?php } ?>
		<?php } else { ?>
				<p>Media not found</p>
		<?php } ?>	
	</div>
</body>
</html>