
<html>  
<head>   
    <link rel="stylesheet" href="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/css/' ;?>style.css" type="text/css" /> 
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>   
    <script type="text/javascript" src="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/js/' ;?>jquery.validate.js"></script>
    <script type="text/javascript" src="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/js/' ;?>jquery.placeholders.js"></script>

    <script type="text/javascript">
	$(document).ready(function() {
		$("#pluginSettings").validate({
			errorLabelContainer: $("#validationError"),
			messages: {
				amazon_access_key: {
				    required: 'Please enter an Amazon API access key<br>'
				},			
		
				amazon_secret_access_key: {
				    required: 'Please enter an Amazon API shared key<br>'
				},      	        

				amazon_video_bucket: {
				    required: 'Please enter the bucket your videos are stored in<br>'
				}, 	        	        
		 	}
		});

		$(':input[placeholder]').placeholder();
	
	})
    </script>

</head> 

<body> 

<div class="wrap">
	<h2>Plugin Settings</h2>

	<div id="validationError"></div>

	<form method="POST" id="pluginSettings">	
		<table>
			<tr>
				<td class="heading">
					<em>*</em>
					Access Key ID: 
				</td>

				<td>
					<input type="text" name="amazon_access_key" class="required" maxlength="21" placeholder="Amazon Access Key" value="<?= $pluginSettings['amazon_access_key']; ?>">
				</td>
			</tr>

			<tr>
				<td class="heading">
					<em>*</em>
					Secret Access Key: 
				</td>

				<td>
					<input type="text" name="amazon_secret_access_key" class="required" maxlength="21" placeholder="Secret Access Key" value="<?= $pluginSettings['amazon_secret_access_key']; ?>">
				</td>
			</tr>

			<tr>
				<td class="heading">
					<em>*</em>
					Video Bucket: 
				</td>

				<td>
					<input type="text" name="amazon_video_bucket" class="required" maxlength="50" placeholder="Amazon Video Bucket" value="<?= $pluginSettings['amazon_video_bucket']; ?>">
				</td>
			</tr>

			<tr>
				<td class="heading">
					S3 Host: 
				</td>

				<td>
					<input type="text" name="amazon_url" class="url" placeholder="s3.amazonaws.com"  value="<?= $pluginSettings['amazon_url']; ?>">
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
</div>

      
</body>  
</html>  
