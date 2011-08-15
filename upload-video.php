<script type="text/javascript">
	jQuery(function() {
	  jQuery('#video_file').uploadify({
	    'uploader'  : '<?= WP_PLUGIN_URL; ?>/S3-Video/misc/uploadify.swf',
	    'script'    : '<?= WP_PLUGIN_URL; ?>/S3-Video/misc/uploadify.php',
	    'cancelImg' : '<?= WP_PLUGIN_URL; ?>/images/cancel.png',
	    'folder'    : '<?= WP_CONTENT_DIR . '/uploads/s3_videos/'; ?>/uploads',
	    'auto'      : true
	  });

	  jQuery("#videoUpload").validate({
		errorLabelContainer: jQuery("#validationError"),
		messages: {
			amazon_access_key: {
				required: 'Please enter an Amazon API access key<br>'
			}
		}			
	  });

	  jQuery(':input[placeholder]').placeholder();

	});
</script>

<div class="wrap">

	<h2>Upload Video</h2>
	<?php 
		if (!$tmpDirectory) {
	?>
			<p>The wp-content/uploads directory does not appear to be writable, please change the permissions and try again.</p>
	<?php
		} else {
	?>		<p>Upload a file using the form below to your S3 bucket.</p>
	
			<form method="POST" id="videoUpload" type="file/multipart">
				<table>
					<tr>
						<td class="heading">
							<em>*</em>
							Video File
						</td>

						<td>
							<input type="file" id ="video_file" name="video_file" class="required" placeholder="Path to file">
						</td>
					</tr>	

					<tr>
						<td>
						</td>

						<td>
							<div align="center">
								<input type="submit" value="Save">
							</div>							
						</td>
					</tr>		
				</table>
			</form>
	<?php
		}
	?>

</div> 
