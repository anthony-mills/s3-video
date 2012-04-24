=== S3 Video Plugin ===
Contributors: Anthony.Mills
Plugin URI: https://github.com/anthony-mills/S3-Video
Author URI: http://www.development-cycle.com
Tested up to: 3.3.2
Tags: S3, Video, Embed, Streaming, Playlists

The S3 Video Plugin video allows the embedding and streaming of video files stored on Amazons's S3 storage service. Either as individual videos within pages and posts or playlists created from the plugin.

== Description ==
The S3 Video Plugin allows the embedding of video media stored on Amazon's S3 storage into a Wordpress blog post or page. 

== Installation ==
1. Unzip plugin and upload to the wp-content/plugins directory of your Wordpress install.
2. Make sure your install has a wp-content/uploads directory and that it is writable by the web server user.
3. Check that your PHP installation has the CURL extension active and activate the plugin throught the "plugins" menu found in the Wordpress backend.
4. In order to upload large .flv and .mp4 files to S3 through the backend you will need to increase the PHP upload limit sizes. If you are using Apache as your webserver 
this can be done by creating a .htaccess file if one doesn't already exist in the root of your Wordpress installation and adding the following lines:

php_value upload_max_filesize 200M
php_value post_max_size 200M

You may also need to set the maximum file execution time, if you are experiencing errors when uploading videos. This can be set in your .htaccess by adding the line:

php_value max_execution_time 10000

5. Log into your AWS account and create a new bucket to store your video files.
6. Enter your Amazon access keys and bucket name on the plugin settings page.

== Frequently Asked Questions ==

= Can i change the size of the player in my page? =
Yes, just add height and width tags to your embed shortcode with the dimensions in pixels e.g

To define the player as 640 pixels wide by 380 pixels high
[S3_embed_video file="myVideo.flv" width="640" height="380"]

= Why Amazon S3, Why not just use Youtube? =
Sure Amazon S3 costs money but the ads and youtube branding is often unacceptable and looks unprofessional when using video for commercial purposes. Amazon S3 offers a medium to deliver 
unbranded video content with a low entry cost for projects that may not be able to afford a dedicated solution like Brightcove.

= Why Flowplayer? Why Not JwPlayer, [Insert other player name here]? =
The decision to use Flowplayer basically boiled down to its feature list and its licence. With the GPL 3.0 licence allowing commercial use. 

== Screenshots == 
1. A video playing from S3 embedded into a test page
2. Plugin menu options
3. Drag and drop ordering of videos in a playlist

== Changelog ==

= 0.94 =
Fix for missing files from Wordpress plugin repository

= 0.93 =
The auto buffering and auto play settings controlling the playback of playlists and videos can now be set in the control panel without requiring manual code changes.

= 0.92 =
Purged the Wordpress SVN in hope of retifying issues between GitHub and release code

= 0.9 =
Retagged as 0.9error in first commit didn't update Wordpress plugin repository to 0.8

= 0.8 =
Path case changes through the plugin to accomodate the difference between the Github & Wordpress subversion plugin names

= 0.7 =
Fixed shorttag issues for added comptability on various installs and lots of miscellanous bug fixes

= 0.6 =
Wordpress release

= 0.5 = 
Add playlist functionality, playlists of videos can now be created and embedded into Wordpress pages and posts.

= 0.4 =
Added hook to delete AWS settings on plugin deactivation. Fixed logic bug stopping configuration of plugin.

= 0.3 =
Table displaying existing S3 media is now sortable.
Fixed some spelling mistakes.
Added pagination for larger media listings.
Ability to define the amount of videos to display per page on the settings page.

= 0.2 =
Ability to get standard embed code for a video allowing S3 videos to be embedded in pages outside of wordpress

= 0.1 =
The initial release, alot of polishing still needs to take place but it works.
