<?php
// Sort out auto play and autobeffer settings for flash embedded videos
$flashVars = '"autoPlay":'.$pluginSettings['amazon_s3_video_autoplay'].',"autoBuffering":'. $pluginSettings['amazon_s3_video_autobuffer'];
?>
<script type="text/javascript">
	jQuery(function() {
	  var awsBucket = '<?php echo $pluginSettings['amazon_video_bucket']; ?>';
	  jQuery("#videoListTable").tablesorter();
	  jQuery("#videoListTable").paginateTable({ rowsPerPage: <?echo $pluginSettings['s3_video_page_result_limit']; ?>});	  
	  jQuery(".colorBox").colorbox({width:"700", height:"480"});
	  	  
	  jQuery("a#getShortLink").click(function() {
		var videoFile = jQuery(this).attr("title"); 
		var linkText = '<h2>Wordpress Shortcode</h2><p>Copy and paste the following shortcode into the page or post where you would like to embed your video: </p><br>';
		var shortLink = '<p><input type=\"text\" readonly=\"readonly\" name=\"shortlink\" value=\"[S3_embed_video file=\'' + videoFile + '\']\" style=\"width: 450px;\"></p>';
		jQuery("#videoInfo").html(linkText + shortLink + '<br>');
		jQuery().colorbox({width:"50%", inline:true, href:"#videoInfo"});
	  });
	  
	  jQuery("a#getEmbedCode").click(function() {
		var videoFile = jQuery(this).attr("title"); 
		var linkText = '<h2>Video Embed Code</h2><p>Copy and paste the following code to embed the video in pages outside of wordpress: </p><br>';
		var embedCode = '<object width="640" height="380" id="s3EmbedVideo" name="s3EmbedVideo" data="http://releases.flowplayer.org/swf/flowplayer-3.2.11.swf" type="application/x-shockwave-flash">' +
						'<param name="movie" value="http://releases.flowplayer.org/swf/flowplayer-3.2.11.swf" />' +
						'<param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="always" />' +
						'<param name="flashvars" value=\'config={"clip":{"url":"http://' + awsBucket + '.s3.amazonaws.com/' + videoFile + '", <?php echo $flashVars; ?>},"canvas":{"backgroundColor":"#112233"}}}\' />' +
						'</object>';
		var copyEmbedCode = '<p><textarea style="width: 600px; height: 300px;" name="embedCode" readonly="readonly">' + embedCode + '</textarea></p>' +
							'<p><strong>PLEASE NOTE:</strong> The code from here is intended for use outside of Wordpress i.e other sites and test pages. It is best to use a Wordpress shortlink when embedding a video into your pages and posts.</p>';
		jQuery("#videoInfo").html(linkText + copyEmbedCode + '<br>');
		jQuery().colorbox({width:"50%", inline:true, href:"#videoInfo"});
	  });
	  	  
	  if (jQuery('#successMsg').not(':empty')){
		jQuery('#successMsg').show();
		jQuery('#successMsg').fadeOut(5000);
	  }	  
	});
</script>

<div class="wrap">

<h2>Existing S3 Videos</h2>

<?php if (!empty($successMsg)) { ?>
	<div id="successMsg"><?php echo $successMsg; ?></div>
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
						$fileExtension = strtolower(pathinfo($existingVideo['name'], PATHINFO_EXTENSION));
						$videoExtensions = array('mp4', 'mov', 'avi', 'flv', 'mpeg', 'mpg', 'wmv', '3gp', 'ogm', 'mkv');
						if (in_array($fileExtension, $videoExtensions)) {
				?>
							<tr>
								<td>
									<?php echo $existingVideo['name']; ?> 
								</td>
								
								<td>
									<?php echo s3_humanReadableBytes($existingVideo['size']); ?>
								</td>
								
								<td>
									<?php echo date('j/n/Y', $existingVideo['time']); ?>
								</td>
													
								<td>
									<a title="<?php echo $existingVideo['name']; ?>" href="<?php echo WP_PLUGIN_URL; ?>/s3-video/views/video-management/preview_video.php?base=<?php echo WP_PLUGIN_URL; ?>/s3-video/&player=<?php echo $pluginSettings['amazon_s3_video_player']; ?>&media=<?php echo 'http://' . $pluginSettings['amazon_video_bucket'] .'.'.$pluginSettings['amazon_url'] . '/' .urlencode($existingVideo['name']); ?>" class="colorBox">
										Preview
									</a>
									 - 
									<a href="admin.php?page=s3-video&delete=<?php echo $existingVideo['name']; ?>">
										Delete
									</a>	
									 -
									<a href="#" title="<?php echo $existingVideo['name']; ?>" id="getShortLink">
										Get Shortlink
									</a>
									 -
									<a href="#" title="<?php echo $existingVideo['name']; ?>" id="getEmbedCode">
										Get Embed Code
									</a>	
									 -
									<a href="admin.php?page=s3_video_meta_data&video=<?php echo $existingVideo['name']; ?>" title="<?php echo $existingVideo['name']; ?>">
										Create / Edit Meta Data
									</a>													
								</td>
							</tr>
				<?php	
						}
					}
				?>
			</tbody>
		</table>
		<?php if (count($existingVideos) > $pluginSettings['s3_video_page_result_limit']) { ?>
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
		<p>No media found in this bucket.</p>
<?php		
	}
?>

</div>
