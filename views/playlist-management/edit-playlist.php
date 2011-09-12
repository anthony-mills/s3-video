<script type="text/javascript">
	jQuery(function() {
	  var awsBucket = '<?= $pluginSettings['amazon_video_bucket']; ?>';
	  jQuery("#playListTable").tablesorter();
	  jQuery("#playlistListTable").paginateTable({ rowsPerPage: <?= $pluginSettings['s3_video_page_result_limit']; ?>});	  
	});
</script>

<div class="wrap">

<h2>Add Video To Playlist</h2>

<?php if (!empty($successMsg)) { ?>
	<div id="successMsg">
		<?= $successMsg; ?>
	</div>
<?php } ?>

<p><a href="admin.php?page=s3_video_show_playlist">Return to playlist management</a></p>

<h3>From A File</h3>
<form id="addVideo" enctype="multipart/form-data" method="POST">
	<table>
		<tr>
			<td>Video:</td>
			
			<td>
				<input type="file" name="addVideo"	
			</td>
		</tr>
	</table>
</form>

<h3>Existing Video From S3</h3>
<form id="addVideo" enctype="multipart/form-data" method="POST">
	<table>
		<tr>
			<td>Video:</td>
			
			<td>
				<input type="file" name="addVideo"	
			</td>
		</tr>
	</table>
</form>

</div>
