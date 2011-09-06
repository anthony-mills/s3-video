<html>
<head>
    <script type="text/javascript" src="<?= $_GET['base']; ?>js/flowplayer-3.2.6.js"></script>
</head> 

<body>
<?php if (!empty($_GET['media'])) { ?>
	<a href="<?= $_GET['media']; ?>" style="display:block;width:640px;height:380px"  id="player"></a> 
	
	<script>
		flowplayer("player", "<?= $_GET['base']; ?>misc/flowplayer-3.2.7.swf", {
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
</body>
</html>
