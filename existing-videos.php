<div class="wrap">

<h2>S3 Videos</h2>

<?php
	if ((!empty($existingVideos)) && (count($existingVideos) > 0)) {
?>
		<table>
			<tr>
				<th>File Name</th>
				<th>File Size</th>
				<th>Created</th>				
				<th>Actions</th>								
			</tr>
			<?php
				foreach($existingVideos as $existingVideo) {
			?>
				<tr>
					<td>
						<?= $existingVideo['name']; ?>
					</td>
					
					<td>
						<?= humanReadableBytes($existingVideo['size']); ?>
					</td>
					
					<td>
						<?= date('j/n/Y', $existingVideo['time']); ?>
					</td>
										
					<td>
						Preview
					</td>
				</tr>
			<?php	
				}
			?>
		</table>
<?php 	
	}
?>
</div>
