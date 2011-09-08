<script type="text/javascript">
	jQuery(function() {
		jQuery("#pluginSettings").validate({
			errorLabelContainer: jQuery("#validationError"),
			messages: {
				playlist_name: {
					required: 'Please enter an Amazon API access key<br>'
				}
			}
		});	
		jQuery(':input[placeholder]').placeholder();
		jQuery(".chzn-select").chosen(); 
	});
</script>

<div class="wrap">

<h2>Create New Playlist</h2>

<?php if (!empty($successMsg)) { ?>
	<div id="successMsg">
		<?= $successMsg; ?>
	</div>
<?php } ?>
<br>

<?php 
// Check there is videos to add to a playlist
if (!$existingVideos) { 
?>
	<p>You need to upload some videos before you can create a playlist.</p>
</div>
<?php 
	return;
} 

print_r($existingVideos);
?>	 
	<form method="POST">
		<table>
			<tr>
				<td>
					Playlist Name:
				</td>
				
				<td>
					<input type="text" name="playlist_name" placeholder="My playlist name">					
				</td>
			<tr>
				
			</tr>
				<td valign="top">	
					Playlist Contents
				</td>
				
				<td>
				        <select data-placeholder="videos in playlist" style="width:350px;" multiple class="chzn-select" name="playlist_contents" tabindex="8">
				          <option value=""></option>
				          <?php foreach ($existingVideo as $video) { ?>
				          			<option value="<?= $video['filename']; ?>">
				          				<?= $video['name']; ?>
				          			</option>
				          <?php } ?>
				        </select>
		       </td>
	        </tr>
	    </table>
      		
		<div class="clear"></div>
    		
		<div align="center">
			<input type="submit" value="Save">
		</div>	
	
	</form>
</div>
