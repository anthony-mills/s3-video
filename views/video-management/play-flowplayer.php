<a href="{videoFile}" style="display:block;width:{videoWidth}px;height:{videoHeight}px"  id="player{playerId}"></a> 		
<script>
	flowplayer("player{playerId}", '{flowplayerLocation}', {
		clip:  {
			autoBuffering: {videoAutoBuffer},
			autoPlay: {videoAutoPlay},
			bufferLength: 5,				
		},
		{videoPlaylist}
	});
</script>
