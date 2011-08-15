<html>  
<head>   
    <link rel="stylesheet" href="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/css/' ;?>style.css" type="text/css" /> 
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>   
    <script type="text/javascript" src="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/js/' ;?>jquery.validate.js"></script>
    <style>
    	.heading {
		text-align: right;
		font-weight: bold;
	}

	em {
		color: red;
	}
    </style>

</head> 

<body> 

<div class="wrap">

	<h2>Plugin Settings</h2>

	<form method="POST" id="pluginSettings">
		
		<table>
			<tr>
				<td class="heading">
					<em>*</em>
					Access Key ID: 
				</td>

				<td>
					<input type="text" name="access_key" class="required">
				</td>
			</tr>

			<tr>
				<td class="heading">
					<em>*</em>
					Secret Access Key: 
				</td>

				<td>
					<input type="text" name="secret_access_key" class="required">
				</td>
			</tr>

			<tr>
				<td class="heading">
					<em>*</em>
					Video Bucket: 
				</td>

				<td>
					<input type="text" name="amazon_url" class="required">
				</td>
			</tr>

			<tr>
				<td class="heading">
					Amazon URL: 
				</td>

				<td>
					<input type="text" name="amazon_url" class="required">
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
