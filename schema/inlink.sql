CREATE TABLE IF NOT EXISTS `inlink` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_url` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `pagerank` int(2) DEFAULT NULL,
  `inlink_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
