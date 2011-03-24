/* Installation section */
/* Version: fc8 - adds showsearch column to _easytables */
/* Version: fc9 - removes showsearch column, and correctly uses Joomla params instead */
CREATE TABLE IF NOT EXISTS `#__easytables` (
  `id` int(11) NOT NULL auto_increment,
  `easytablename` varchar(255) NOT NULL,
  `easytablealias` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `published` tinyint(1) unsigned NOT NULL default '0',
  `defaultimagedir` text NOT NULL,
  `linkedtable` int(11) NOT NULL,
  `created_` datetime NOT NULL,
  `modified_` datetime NOT NULL,
  `modifiedby_` tinyint(3) NOT NULL,
  `checked_out` tinyint(1) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `hits` int(11) unsigned NOT NULL default '0',
  `datatablename` varchar(255) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY  (`id`)
) COMMENT='1.0fc10';

CREATE TABLE IF NOT EXISTS `#__easytables_table_meta` (
  `id` int(11) NOT NULL auto_increment,
  `easytable_id` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `type` tinyint(1) NOT NULL,
  `list_view` tinyint(1) NOT NULL,
  `detail_link` tinyint(1) NOT NULL,
  `detail_view` tinyint(1) NOT NULL,
  `fieldalias` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `easytable_id` (`easytable_id`,`position`,`label`)
) COMMENT='1.0fc10';
