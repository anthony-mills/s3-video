<script type="text/javascript">
	jQuery(function() {
		jQuery("#createPlaylist").validate({
			errorLabelContainer: jQuery("#validationError"),
			messages: {
				playlist_name: {
					required: 'Please enter a name for the playlist<br>'
				}
			}
		});	
		jQuery(':input[placeholder]').placeholder();
		jQuery(".chzn-select").chosen(); 
	});
</script>

<div class="wrap">

<h2>Create New Playlist</h2>

<div id="#validationError"></div>

<?php if (!empty($successMsg)) { ?>
	<div id="successMsg">
		<?php echo $successMsg; ?>
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
?>	 
	<form method="POST" id="createPlaylist">
		<table>
			<tr>
				<td>
					Playlist Name:
				</td>
				
				<td>
					<input type="text" name="playlist_name" placeholder="My playlist name" class="required" maxlength="100">					
				</td>
			<tr>
				
			</tr>
				<td valign="top">	
					Playlist Contents
				</td>
				
				<td>
				        <select data-placeholder="Videos in playlist" style="width:350px;" multiple class="chzn-select" name="playlist_contents[]" tabindex="8">
				          <option value=""></option>
						  <?php
							$videoExtensions = array('mp4', 'mov', 'avi', 'flv', 'mpeg', 'mpg', 'wmv', '3gp', 'ogm', 'mkv');

							foreach ($existingVideos as $existingVideo) { 

								$fileExtension = strtolower(pathinfo($existingVideo['name'], PATHINFO_EXTENSION));

								if (in_array($fileExtension, $videoExtensions)) {
				          			echo '<option value="' . $existingVideo['name'] . '">'.$existingVideo['name'].'</option>';
								}

							} 
						  ?>
				        </select>
		       </td>
	        </tr>
	    </table>
      		
		<div class="clear"></div>
    		
		<input type="submit" value="Save Playlist">
	
	</form>
</div>
