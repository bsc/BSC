-- country
-- Copyright © 2011 - All rights reserved.
-- License: GNU/GPL
--
-- country table(s) definition
--
--
CREATE TABLE IF NOT EXISTS #__table_name (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM;