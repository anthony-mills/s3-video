<script type="text/javascript">
	jQuery(function() {	  
	  jQuery(".colorBox").colorbox({width:"700", height:"480"});
	  
	  jQuery(".deleteStill").click(function() {
		jQuery.ajax({
			url: '<?php echo  get_option('siteurl') . '/wp-admin/admin-ajax.php'; ?>',
			type: 'POST',
			data: { 
				'action': "remove_video_still", 
				'cookie': encodeURIComponent(document.cookie), 
				'video_name': '<?php echo $videoName; ?>',
				'image_name': '<?php echo $stillFile; ?>', 
				dataType: 'json',
				cache:  'false'
			},
			success: function(data) {
				jQuery('#successMsg').html('Video still successfully deleted.');
				jQuery('#successMsg').fadeOut(7000);
			}
		});
	  });
	  
	  <?php if (!empty($successMsg)) { ?>
	  			jQuery('#successMsg').html('<?php echo $successMsg; ?>');
				jQuery('#successMsg').fadeOut(7000);
	  <?php } ?>
	});
</script>

<h2>Manage Video MetaData</h2>

<hr />

	<form method="POST" enctype="multipart/form-data" id="manageMeta">
		<b>Video Still</b>
		<p>
			A video still is a normal image file that is displayed in the player until the video starts to display. 
			Video stills only show before when a video embedded into a page using a single shortcode embed.
		</p>
		
		<p>
			<b>Note:</b> 
			<em>Uploading a video still when one already exists will replace the current one.</em>
		</p>
		
		<?php if (!empty($errorMsg)) { ?>
				<div id="validationError"><?php echo $errorMsg; ?></div>
		<?php } ?>
					
		<?php if (!empty($successMsg)) { ?>
			<div id="successMsg"></div>
		<?php } ?>
		
		<?php if (!empty($videoStill)) { ?>
			<div id="currentStill"><a href="<?php echo $videoStill; ?>" class="colorBox">Current video still</a> - <a class="deleteStill">Delete</a></div>
			<br />
		<?php } ?>
		
		<label for="upload_still">
			Upload Still
		</label>
		
		<input type="file" id="upload_still" name="upload_still" class="required" /> ( JPG / PNG / GIF)
		<br />
		<input type="submit" value="Upload" />
	</form>

	<br />
	
<hr />

<p>Return to <a href="admin.php?page=s3-video">page existing S3 videos</a>.</p>