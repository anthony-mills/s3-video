<link rel="stylesheet" href="<?php echo get_bloginfo('url'); ?>/wp-content/plugins/s3-video/css/style.css?ver=3.5.1" type="text/css" media="all" />

<script type="text/javascript" src="<?php echo get_bloginfo('url'); ?>/wp-admin/load-scripts.php?c=0&amp;load%5B%5D=jquery,utils&amp;ver=3.5.1"></script>
<script type='text/javascript' src="<?php echo get_bloginfo('url'); ?>/wp-content/plugins/s3-video/js/jquery.tablesorter.js?ver=1.0"></script>
<script type='text/javascript' src="<?php echo get_bloginfo('url'); ?>/wp-content/plugins/s3-video/js/jquery.paginator.js?ver=1.0"></script>

<script type="text/javascript">
	jQuery(function() {
	  var awsBucket = '<?php echo $pluginSettings['amazon_video_bucket']; ?>';
	  jQuery("#playListTable").tablesorter();
	  jQuery("#playlistListTable").paginateTable({ rowsPerPage: <?php echo  $pluginSettings['s3_video_page_result_limit']; ?>});	  
	  
	  jQuery(".insertPlaylist").click(function() {
			var videoName = jQuery(this).attr("title");
			jQuery("#insertPlaylistId").val(videoName);
			jQuery("#insertPlaylistForm").submit();
	  });	  
	});
</script>

<div class="wrap">
	
	<strong>Insert Playlist</strong>

	<?php
		if ((!empty($existingPlaylists)) && (count($existingPlaylists) > 0)) {
	?>
			<table id="playListTable" class="tablesorter" cellspacing="0" >
				<thead>
					<tr>
						<th>Playlist Name</th>
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
								<?php echo  $existingPlaylist['playlist_name']; ?>
							</td>
							
							<td>
								<?php echo  date('j/n/Y', $existingPlaylist['created']); ?>
							</td>
												
							<td>
								<a href="#" title="<?php echo  $existingPlaylist['id']; ?>" class="insertPlaylist">
									Insert
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
	<?php 	
		} else {
	?>
			<p>No playlists currently exist.</p>
	<?php		
		}
	?>
	<form method="POST" id="insertPlaylistForm">
		<input type="hidden" name="insertPlaylistId" id="insertPlaylistId" value="" />
	</form>
</div>
