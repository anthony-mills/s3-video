<html>
<head>
    <script type="text/javascript" src="<?= $_GET['base']; ?>js/flowplayer-3.2.6.js"></script>
</head> 

<body>
<?php if (!empty($_GET['media'])) { ?>
	<a href="<?= $_GET['media']; ?>" style="display:block;width:520px;height:330px"  id="player"></a> 
	
	<script>
		flowplayer("player", "<?= $_GET['base']; ?>misc/flowplayer-3.2.7.swf");
	</script>
<?php } else { ?>
		<p>Media not found</p>
<?php } ?>	
</body>
</html>
