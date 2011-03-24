#DROP TABLE IF EXISTS `#__reviews`;
CREATE TABLE IF NOT EXISTS `#__reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `reservations` varchar(31) NOT NULL,
  `quicktake` text NOT NULL,
  `review` text NOT NULL,
  `notes` text NOT NULL,
  `smoking` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `credit_cards` varchar(255) NOT NULL,
  `cuisine` varchar(31) NOT NULL,
  `avg_dinner_price` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `review_date` datetime NOT NULL,
  `published` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8

