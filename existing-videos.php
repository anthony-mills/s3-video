<script type="text/javascript">
	jQuery(function() {
	  //jQuery('#myTable').paginateTable({ rowsPerPage: 10 });
	  jQuery(".colorBox").colorbox();
	});
</script>

<div class="wrap">

<h2>S3 Videos</h2>

<?php if (!empty($successMsg)) { ?>
	<div id="successMsg">
		<?= $successMsg; ?>
	</div>
<?php } ?>

<?php
	if ((!empty($existingVideos)) && (count($existingVideos) > 0)) {
?>
		<table id="videoListTable" class="tablesorter" cellspacing="0" >
			<thead>
				<tr>
					<th>File Name</th>
					<th>File Size</th>
					<th>Created</th>				
					<th>Actions</th>								
				</tr>
			</thead>
			
			<tbody>	
				<?php
					foreach($existingVideos as $existingVideo) {
				?>
					<tr>
						<td>
							<?= $existingVideo['name']; ?>
						</td>
						
						<td>
							<?= humanReadableBytes($existingVideo['size']); ?>
						</td>
						
						<td>
							<?= date('j/n/Y', $existingVideo['time']); ?>
						</td>
											
						<td>
							<a title="<?= $existingVideo['name']; ?>" href="<?= WP_PLUGIN_URL ?>/S3-Video/preview-video.php?base=<?= WP_PLUGIN_URL ?>/S3-Video/&media=<?= 'http://' . $pluginSettings['amazon_video_bucket'] .'.'.$pluginSettings['amazon_url'] . '/' .urlencode($existingVideo['name']); ?>" class="colorBox">
								Preview
							</a>
							 - 
							<a href="admin.php?page=s3-video&delete=<?= $existingVideo['name']; ?>">
								Delete
							</a>							
						</td>
					</tr>
				<?php	
					}
				?>
			</tbody>
		</table>
<?php 	
	} else {
?>
		<p>No media found in this bucket.</p>
<?php		
	}
?>
</div>
