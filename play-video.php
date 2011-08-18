<?php if (!empty($videoFile)) { ?>
	<a href="<?= $videoFile; ?>" style="display:block;width:520px;height:330px"  id="player"></a> 
	
	<script>
		flowplayer("player", "misc/flowplayer-3.2.7.swf");
	</script>
<?php } ?>	

