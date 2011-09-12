<script type="text/javascript">
	jQuery(function() {
	  var awsBucket = '<?= $pluginSettings['amazon_video_bucket']; ?>';
	  jQuery("#playListTable").tablesorter();
	  jQuery("#playlistListTable").paginateTable({ rowsPerPage: <?= $pluginSettings['s3_video_page_result_limit']; ?>});	  
	});
</script>

<div class="wrap">

<h2>Playlist Management</h2>

<?php if (!empty($successMsg)) { ?>
	<div id="successMsg">
		<?= $successMsg; ?>
	</div>
<?php } ?>

<p><a href="admin.php?page=s3_video_create_playlist">Create new playlist</a></p>

<?php
	if ((!empty($existingPlaylists)) && (count($existingPlaylists) > 0)) {
?>
		<table id="playListTable" class="tablesorter" cellspacing="0" >
			<thead>
				<tr>
					<th>Playlist</th>
					<th>Created</th>				
					<th>Actions</th>								
				</tr>
			</thead>
			
			<tbody>	
				<?php
					foreach($existingPlaylists as $existingPlaylist) {
				?>
					<tr>
						<td>
							<?= $existingPlaylist['playlist_name']; ?>
						</td>
						
						<td>
							<?= date('j/n/Y', $existingPlaylist['created']); ?>
						</td>
											
						<td>
							<a href="admin.php?page=s3_video_show_playlist&delete=<?= $existingPlaylist['id']; ?>">
								Delete
							</a>
							 - 
							<a href="admin.php?page=s3_video_show_playlist&edit=<?= $existingPlaylist['id']; ?>">
								Add / Remove Videos
							</a>	
							 - 
							<a href="admin.php?page=s3_video_show_playlist&reorder=<?= $existingPlaylist['id']; ?>">
								Reorder Playlist Videos
							</a>													
						</td>
					</tr>
				<?php	
					}
				?>
			</tbody>
		</table>
		<?php if (count($existingPlaylists) > $pluginSettings['s3_video_page_result_limit']) { ?>
					<div align="center">
						<div class='pager'>
					        <a href='#' alt='Previous' class='prevPage'>Prev</a> - 
					         Page <span class='currentPage'></span> of <span class='totalPages'></span>
					         - <a href='#' alt='Next' class='nextPage'>Next</a>
					        <br>
					       	<span class='pageNumbers'></span>
				   		</div>
					</div>
		<?php } ?>
		<div style='display:none'>
			<div id='videoInfo' style='padding:10px;'></div>
		</div>
<?php 	
	} else {
?>
		<p>No playlists currently exist.</p>
<?php		
	}
?>

</div>
