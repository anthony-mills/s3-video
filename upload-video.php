<html>  
<head>   
	<link rel="stylesheet" href="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/css/' ;?>style.css" type="text/css" /> 
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>   
	<script type="text/javascript" src="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/js/' ;?>jquery.validate.js"></script>
	<script type="text/javascript" src="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/js/' ;?>jquery.placeholders.js"></script>
    
	<script type="text/javascript">
	$(document).ready(function() {
	  $('#video_file').uploadify({
	    'uploader'  : '<?= plugins_url(); ?>/s3-video/misc/uploadify.swf',
	    'script'    : '<?= plugins_url(); ?>/s3-video/misc/uploadify.php',
	    'cancelImg' : '<?= plugins_url(); ?>/images/cancel.png',
	    'folder'    : '<?= WP_CONTENT_DIR . '/uploads/s3_videos/'; ?>/uploads',
	    'auto'      : true
	  });

	  $("#videoUpload").validate({
		errorLabelContainer: $("#validationError"),
		messages: {
			amazon_access_key: {
				required: 'Please enter an Amazon API access key<br>'
			}
		}			
	  });

	  $(':input[placeholder]').placeholder();

	});
	</script>

</head> 

<body> 

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

      
</body>  
</html>  
