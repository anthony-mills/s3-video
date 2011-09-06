CREATE TABLE IF NOT EXISTS `s3_video_playlists` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `playlist_name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

{table}

CREATE TABLE IF NOT EXISTS `s3_video_playlist_videos` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `video_file` varchar(200) NOT NULL,
  `video_playlist` int(11) NOT NULL,
  `video_weight` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

