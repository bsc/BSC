CREATE TABLE IF NOT EXISTS `#__mycomponent` (
`id` int(11) unsigned NOT NULL auto_increment,
`catid` int(11) unsigned NOT NULL default '0',
`sid` int(11) unsigned NOT NULL default '0',
`title` varchar(255) NOT NULL default '',
`alias` varchar(255) NOT NULL default '',
`text` varchar(255) NOT NULL default '',
`picture` varchar(255) NOT NULL default '',
`date` varchar(255) NOT NULL default '',
`created` datetime NOT NULL default '0000-00-00 00:00:00',
`created_by` int(11) unsigned NOT NULL default '0',
`created_by_alias` varchar(255) NOT NULL default '',
`modified` datetime NOT NULL default '0000-00-00 00:00:00',
`modified_by` int(11) unsigned NOT NULL default '0',
`checked_out` int(11) unsigned NOT NULL default '0',
`checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
`published` tinyint(1)  NOT NULL default '0',
`ordering` int(11) NOT NULL default '0',
`params` text NOT NULL,
`hits` int(11) unsigned NOT NULL default '0',
PRIMARY KEY (`id`)
);