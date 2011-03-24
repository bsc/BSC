#<?php die('Direct Access To Log Files Not Permitted'); ?>
#Version: 1.0
#Date: 2011-03-20 21:36:36
#Fields: date	time	user_id	comment
#Software: Joomla! 1.5.22 Stable [ senu takaa ama woi ] 04-November-2010 18:00 GMT
2011-03-20	21:36:36	62	ArtofUser 1.1.1  upgraded with schema changes.
2011-03-20	21:36:36	62	Pass: CREATE TABLE IF NOT EXISTS `jos_artofuser_notes`(
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`user_id` int(10) unsigned NOT NULL DEFAULT '0',
`catid` int(10) unsigned NOT NULL DEFAULT '0',
`subject` varchar(100) NOT NULL DEFAULT '',
`body` text NOT NULL,
`published` tinyint(3) NOT NULL DEFAULT '0',
`checked_out` int(10) unsigned NOT NULL DEFAULT '0',
`checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
`created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`modified_user_id` int(10) unsigned NOT NULL,
`modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
`review_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
PRIMARY KEY  (`id`),
KEY `idx_user_id` (`user_id`),
KEY `idx_category_id` (`catid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
2011-03-20	21:36:36	62	Pass: CREATE TABLE IF NOT EXISTS `jos_artofuser_blocked`(
`user_id` int(11) unsigned NOT NULL DEFAULT '0',
`catid` int(11) unsigned NOT NULL DEFAULT '0',
`checked_out` tinyint(3) unsigned NOT NULL DEFAULT '0',
PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8