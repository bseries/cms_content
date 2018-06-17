CREATE TABLE `content_blocks` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) unsigned NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT '',
  `region` varchar(100) NOT NULL DEFAULT '',
  `value_text` text,
  `value_number` int(20) DEFAULT NULL,
  `value_money` int(20) DEFAULT NULL,
  `value_media_id` int(11) unsigned DEFAULT NULL,
  `is_published` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
