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
				jQuery('#currentStill').html('<div id="successMsg">Video still successfully deleted.</div>');
				jQuery('#currentStill').fadeOut(7000);
			}
		});
	  });
	});
</script>

<h2>Manage Video MetaData</h2>

<form method="POST" enctype="multipart/form-data" id="manageMeta">
	<b>Video Still</b>
	<p>
		A video still is a normal image file that is displayed in the player until the video starts to display. 
		Video stills only show before when a video embedded into a page using a single shortcode embed.
	</p>
	
	<?php if (!empty($errorMsg)) { ?>
			<div id="validationError"><?php echo $errorMsg; ?></div>
	<?php } ?>
				
	<?php if (!empty($successMsg)) { ?>
			<div id="successMsg">
				<?php echo $successMsg; ?>
			</div>
	<?php } ?>
	
	<?php 
	if (!empty($videoStill)) {
		?>
			<div id="currentStill"><a href="<?php echo $videoStill; ?>" class="colorBox">Current video still</a> - <a href="#" class="deleteStill">Delete</a></div>
		<?php
	} else {
		// Only show the upload form if there is no still in the database
	?>
	
	<label for="upload_still">
		Upload Still
	</label>
	
	<input type="file" id="upload_still" name="upload_still" class="required" /> ( JPG / PNG / GIF)
	<br />
	<input type="submit" value="Upload" />
	<?php
	}
	?>
</form>
