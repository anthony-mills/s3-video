<h2>Manage Video MetaData</h2>

<form method="POST" enctype="multipart/form-data" id="manageMeta">
	<b>Video Still</b>
	<p>A still is a normal image file that is displayed in the player until the video starts to display.</p>
	
	<?php if (!empty($errorMsg)) { ?>
			<div id="validationError"><?php echo $errorMsg; ?></div>
	<?php } ?>
				
	<?php if (!empty($successMsg)) { ?>
			<div id="successMsg">
				<?php echo $successMsg; ?>
			</div>
	<?php } ?>
	
	<label for="upload_still">
		Upload Still
	</label>
	
	<input type="file" id="upload_still" name="upload_still" class="required" /> ( JPG / PNG / GIF)
	<br />
	<input type="submit" value="Upload" />
</form>
