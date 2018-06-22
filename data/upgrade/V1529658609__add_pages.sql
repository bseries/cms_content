CREATE TABLE `pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) unsigned NOT NULL,
  `site` varchar(50) DEFAULT NULL,
  `title` varchar(250) DEFAULT '',
  `body` text,
  `is_published` tinyint(1) DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `is_published` (`is_published`)
) ENGINE=InnoDB;
