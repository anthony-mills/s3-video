<html>  
<head>   
    <link rel="stylesheet" href="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/css/' ;?>style.css" type="text/css" /> 
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>   
    <script type="text/javascript" src="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/js/' ;?>jquery.tablesorter.js"></script>
    <script type="text/javascript" src="<?php echo get_option('siteurl').'/wp-content/plugins/s3-video/js/' ;?>quickpager.js"></script>
    
    
    <style>
    
		
    
    </style>

</head> 

<body> 

<div class="wrap">



<?php   

if($tokenRead == 'Token Read Goes Here' || $tokenWrite == 'Token Read Goes Here' ){
		$numVideos = 0;
		echo '<div id="warning"><span>You need to change your <a href="admin.php?page=bc-settings" ><strong>settings</strong></a>.</span></div>';
	}else{
		$showTable = true;
	}


?>

 <h2>S3 Videos</h2>


</div> <!-- End Wrap -->

      
</body>  
</html>  
