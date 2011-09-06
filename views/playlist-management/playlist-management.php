<script type="text/javascript">
	jQuery(function() {
	  var awsBucket = '<?= $pluginSettings['amazon_video_bucket']; ?>';
	  jQuery("#playListTable").tablesorter();
	  jQuery("#playlistListTable").paginateTable({ rowsPerPage: <?= $pluginSettings['s3_video_page_result_limit']; ?>});	  
	  jQuery(".colorBox").colorbox();
	  	  
	  jQuery("a#getShortLink").click(function() {
		var videoFile = jQuery(this).attr("title"); 
		var linkText = '<h2>Wordpress Shortcode</h2><p>Copy and paste the following shortcode into the page or post where you would like to embed your video: </p><br>';
		var shortLink = '<p>[S3_embed_video file=\"' + videoFile + '\"]</p>';
		jQuery("#videoInfo").html(linkText + shortLink + '<br>');
		jQuery().colorbox({width:"50%", inline:true, href:"#videoInfo"});
	  });
	    
	});
</script>

<div class="wrap">

<h2>Playlist Management</h2>

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
							 -
							<a href="#" title="<?= $existingVideo['name']; ?>" id="getShortLink">
								Get Shortlink
							</a>
							 -
							<a href="#" title="<?= $existingVideo['name']; ?>" id="getEmbedCode">
								Get Embed Code
							</a>							
						</td>
					</tr>
				<?php	
					}
				?>
			</tbody>
		</table>
		
		<div align="center">
			<div class='pager'>
		        <a href='#' alt='Previous' class='prevPage'>Prev</a> - 
		         Page <span class='currentPage'></span> of <span class='totalPages'></span>
		         - <a href='#' alt='Next' class='nextPage'>Next</a>
		        <br>
		       	<span class='pageNumbers'></span>
	   		</div>
		</div>
		
		<div style='display:none'>
			<div id='videoInfo' style='padding:10px;'></div>
		</div>
<?php 	
	} else {
?>
		<p>No media found in this bucket.</p>
<?php		
	}
?>

</div>
