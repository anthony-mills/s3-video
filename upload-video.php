<html>  
<head>   
	<link rel="stylesheet" href="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/css/' ;?>style.css" type="text/css" /> 
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>   
	<script type="text/javascript" src="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/js/' ;?>jquery.validate.js"></script>
	<script type="text/javascript" src="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/js/' ;?>jquery.tablesorter.js"></script>
	<script type="text/javascript" src="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/js/' ;?>quickpager.js"></script>
    
	<script type="text/javascript">
	$(document).ready(function() {
	  $('#file_upload').uploadify({
	    'uploader'  : '<?= plugins_url(); ?>/s3-video/misc/uploadify.swf',
	    'script'    : '<?= plugins_url(); ?>/s3-video/misc/uploadify.php',
	    'cancelImg' : '<?= plugins_url(); ?>/images/cancel.png',
	    'folder'    : '<?= WP_CONTENT_DIR . '/uploads/s3_videos/'; ?>/uploads',
	    'auto'      : true
	  });
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
	?>		<p>Upload a file using the form below to S3.</p>
	
			<form method="POST" id="videoUpload" type="file/multipart">
				<table>
					<tr>
						<td class="heading">
							<em>*</em>
							Video File
						</td>

						<td>
							<input type="file" name="video_file" class="required">
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
