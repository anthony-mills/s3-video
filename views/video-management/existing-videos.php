<script type="text/javascript">
	jQuery(function() {
	  var awsBucket = '<?= $pluginSettings['amazon_video_bucket']; ?>';
	  jQuery("#videoListTable").tablesorter();
	  jQuery("#videoListTable").paginateTable({ rowsPerPage: <?= $pluginSettings['s3_video_page_result_limit']; ?>});	  
	  jQuery(".colorBox").colorbox();
	  	  
	  jQuery("a#getShortLink").click(function() {
		var videoFile = jQuery(this).attr("title"); 
		var linkText = '<h2>Wordpress Shortcode</h2><p>Copy and paste the following shortcode into the page or post where you would like to embed your video: </p><br>';
		var shortLink = '<p>[S3_embed_video file=\"' + videoFile + '\"]</p>';
		jQuery("#videoInfo").html(linkText + shortLink + '<br>');
		jQuery().colorbox({width:"50%", inline:true, href:"#videoInfo"});
	  });
	  
	  jQuery("a#getEmbedCode").click(function() {
		var videoFile = jQuery(this).attr("title"); 
		var linkText = '<h2>Video Embed Code</h2><p>Copy and paste the following code to embed the video in pages outside of wordpress: </p><br>';
		var embedCode = '<object width="640" height="380" id="s3EmbedVideo" name="s3EmbedVideo" data="http://releases.flowplayer.org/swf/flowplayer-3.2.7.swf" type="application/x-shockwave-flash">' +
						'<param name="movie" value="http://releases.flowplayer.org/swf/flowplayer-3.2.7.swf" />' +
						'<param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" />' +
						'<param name="flashvars" value=\'config={"clip":{"url":"http://' + awsBucket + '.s3.amazonaws.com/' + videoFile + '"},"canvas":{"backgroundColor":"#112233"}}}\' />' +
						'</object>';
		var copyEmbedCode = '<p><textarea style="width: 600px; height: 300px;" name="embedCode">' + embedCode + '</textarea></p>';
		jQuery("#videoInfo").html(linkText + copyEmbedCode + '<br>');
		jQuery().colorbox({width:"50%", inline:true, href:"#videoInfo"});
	  });	  
	});
</script>

<div class="wrap">

<h2>Existing S3 Videos</h2>

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
