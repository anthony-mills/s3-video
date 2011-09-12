<script type="text/javascript">
	jQuery(function() {
		 jQuery("#playlistVideos").tableDnD({
		    onDragClass: "tdDragClass",
		    onDrop: function(table, row) {
	            var rows = table.tBodies[0].rows;
	            var playlistVids=new Array()
	            for (var i=0; i<rows.length; i++) {
	                playlistVids[] = rows[i].id;
	            }
		        alert(playlistVids);
				jQuery.post('admin.php?page=s3_video_show_playlist', function(playlistVids) {
				  jQuery('#successMsg').html(debugStr);
				});

		    },
		 });		   
	});
</script>

<div class="wrap">

	<h2>Reorder Playlist Contents</h2>
	
	
	<div id="successMsg">
		<?php if (!empty($successMsg)) { ?>
			<?= $successMsg; ?>
		<?php } ?>
	</div>
	
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
	<?php } else { ?>
		<p>This playlist currently contains no videos</p>
	<?php } ?>
</div>
