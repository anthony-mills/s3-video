<script type="text/javascript">
	jQuery(function() {
		 jQuery("#playlistVideos").tableDnD({
		    onDragClass: "tdDragClass",
		    onDrop: function(table, row) {
             	jQuery.get("<?= WP_PLUGIN_URL; ?>/S3-Video/includes/reorder_playlist.php?playlist=<?= $playlistId; ?>&"+jQuery.tableDnD.serialize(), responseAlert);
		    },
		 });		   
	});
	
	function responseAlert(data) {
		jQuery("#pluginNotification").html(data);
		jQuery(".notice")
		   .fadeIn( function() 
		   {
		      setTimeout( function()
		      {
		         jQuery(".notice").fadeOut("fast");
		      }, 20000);
		});

	}
</script>

<div class="wrap">

	<h2>Reorder Playlist Contents</h2>
	
	<?php if (!empty($successMsg)) { ?>
		<div id="successMsg">
			<?= $successMsg; ?>
		</div>
	<?php } ?>
	
	<div id="pluginNotification" class="notice"></div>
	
	<p><a href="admin.php?page=s3_video_show_playlist">Return to playlist management</a></p>
    <?php if (!empty($playlistVideos)) { ?>
    	<p>Click and drag videos to your desired position in the playlist. With the playlist being played in order from top to bottom.</p><br>
    	
    	<strong>Start of playlist</strong>
    	<div id="reorderPlaylist">
			<table id="playlistVideos">
				<?php foreach($playlistVideos as $playlistVideo) { ?>
				    <tr id="<?= $playlistVideo['id']; ?>">
				    	<td>
				    		<?= $playlistVideo['video_file']; ?>
				    	</td>
				    </tr>
			    <?php } ?>
			</table>
		</div>		
		<strong>End of playlist</strong>
		<p><a href="admin.php?page=s3_video_show_playlist">Return to playlist management</a></p>
	<?php } else { ?>
		<p>This playlist currently contains no videos</p>
	<?php } ?>
</div>
