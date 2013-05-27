<script type="text/javascript">
	jQuery(function() {
	  var awsBucket = '<?php echo $pluginSettings['amazon_video_bucket']; ?>';
	  jQuery("#playListTable").tablesorter();
	  jQuery("#playlistListTable").paginateTable({ rowsPerPage: <?php echo $pluginSettings['s3_video_page_result_limit']; ?>});	
	  jQuery(".chzn-select").chosen();   
	  <?php if (!empty($playlistUpdated)) { ?>
  		 jQuery("#successMsg").fadeTo("slow", 1).animate({opacity: 1.0}, 1000).fadeTo("slow", 0);
	  <?php } ?>	
	});
</script>

<div class="wrap">

<h2>Edit Playlist Contents</h2>

<?php if (!empty($successMsg)) { ?>
	<div id="successMsg">
		<?php echo $successMsg; ?>
	</div>
<?php } ?>

<?php if (!empty($playlistUpdated)) { ?>
	<div id="successMsg">
		Playlist successfully updated
	</div>
<?php } ?>	

<p><a href="admin.php?page=s3_video_show_playlist">Return to playlist management</a></p>

<h3>Edit Contents</h3>
<form id="addVideo" enctype="multipart/form-data" method="POST">
	<table>
		<tr>
			<td valign="top">
				<strong>Video:</strong>
			</td>
			
			<td>
				<select data-placeholder="Videos in playlist" style="width:350px;" multiple class="chzn-select" name="playlist_contents[]" tabindex="8">
				    <option value=""></option>
				   	<?php foreach ($existingVideos as $video) { ?>
						       <option value="<?php echo $video['video_file']; ?>" selected>
						          <?php echo $video['video_file']; ?>
						       </option>
				    <?php } ?>
				    
				   <?php foreach ($s3Videos as $s3Video) { ?>
						       <option value="<?php echo $s3Video['name']; ?>">
						          <?php echo $s3Video['name']; ?>
						       </option>				   		
				   <?php  }	?>				    
				</select>
			</td>
		</tr>
		
		<tr>
			<td></td>
			<td>
				<input type="submit" value="Update playlist">
			</td>
		</tr>
	</table>
</form>

</div>
