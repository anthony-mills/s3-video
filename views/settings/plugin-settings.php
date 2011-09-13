<script type="text/javascript">
	jQuery(function() {
			jQuery("#pluginSettings").validate({
				errorLabelContainer: jQuery("#validationError"),
				messages: {
					amazon_access_key: {
					    required: 'Please enter an Amazon API access key<br>'
					},			
			
					amazon_secret_access_key: {
					    required: 'Please enter an Amazon API shared key<br>'
					},      	        
	
					amazon_video_bucket: {
					    required: 'Please enter the name of the bucket your videos are stored in<br>'
					}, 	        	        
			 	}
			});
	
			jQuery(':input[placeholder]').placeholder();
		
	});	
</script>

<div class="wrap">
	<h2>Plugin Settings</h2>

	<div id="validationError"></div>
	
	<?php if (!empty($successMsg)) { ?>
			<div id="successMsg">
				<?php echo $successMsg; ?>
			</div>
	<?php } ?>
	<form method="POST" id="pluginSettings">	
		<table>
			<tr>
				<td>
					<p>
						<strong>AWS Details:</strong>
					</p>
				</td>

				<td>
				</td>
			</tr>
						
			<tr>
				<td class="heading">
					<em>*</em>
					Access Key ID: 
				</td>

				<td>
					<input type="text" name="amazon_access_key" class="required" maxlength="21" placeholder="Amazon Access Key" value="<?php echo $pluginSettings['amazon_access_key']; ?>">
				</td>
			</tr>

			<tr>
				<td class="heading">
					<em>*</em>
					Secret Access Key: 
				</td>

				<td>
					<input type="text" name="amazon_secret_access_key" class="required" maxlength="50" placeholder="Secret Access Key" value="<?php echo $pluginSettings['amazon_secret_access_key']; ?>">
				</td>
			</tr>

			<tr>
				<td class="heading">
					<em>*</em>
					Video Bucket: 
				</td>

				<td>
					<input type="text" name="amazon_video_bucket" class="required" maxlength="50" placeholder="Amazon Video Bucket" value="<?php echo $pluginSettings['amazon_video_bucket']; ?>">
				</td>
			</tr>

			<tr>
				<td class="heading">
					S3 Host: 
				</td>

				<td>
					<input type="text" name="amazon_url" placeholder="s3.amazonaws.com"  value="<?php echo $pluginSettings['amazon_url']; ?>">
				</td>
			</tr>

			<tr>
				<td>
					<br>
					<p>
						<strong>General Settings:</strong>
					</p>
				</td>

				<td>
				</td>
			</tr>
			
			<tr>
				<td class="heading">
					# Of Video Results Per Page: 
				</td>

				<td>
					<input type="text" name="page_result_limit" placeholder="15"  value="<?php echo $pluginSettings['s3_video_page_result_limit']; ?>">					
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
