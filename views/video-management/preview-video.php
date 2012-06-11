<html>
<head>
	<?php if ((empty($_GET['player'])) || ($_GET['player'] == 'flowplayer')) { ?>
			<?php $player = 'flowplayer'; ?>
    		<script type="text/javascript" src="<?= $_GET['base']; ?>js/flowplayer-3.2.10.js"></script>
    <?php } else { ?>
    		<?php $player = 'videojs'; ?>
    		<link href="<?= $_GET['base']; ?>css/video-js.css" rel="stylesheet">
    		<script type="text/javascript" src="<?= $_GET['base']; ?>js/video.min.js"></script> 
    		<script>
    			_V_.options.flash.swf = "<?= $_GET['base']; ?>/misc/video-js.swf";
  			</script>   
    <?php } ?>
</head> 

<body>
	<div align="center">
		<?php if (!empty($_GET['media'])) { ?>
			<?php if ($player == 'flowplayer') { ?>
						<a href="<?= $_GET['media']; ?>" style="display:block;width:640px;height:360px"  id="player"></a> 
						
						<script>
							flowplayer("player", "<?= $_GET['base']; ?>misc/flowplayer-3.2.11.swf", {
							    clip:  {
							        autoPlay: false,
							        autoBuffering: true,
							        bufferLength: 5
							    }			
							});
						</script>
			<?php } else { ?>
				<video id="video_preview" class="video-js vjs-default-skin" controls preload="auto" width="640" height="380" data-setup="{}">
				  <source src="<?= $_GET['media']; ?>" type='video/mp4'>
				</video>			
			<?php } ?>
		<?php } else { ?>
				<p>Media not found</p>
		<?php } ?>	
	</div>
</body>
</html>
