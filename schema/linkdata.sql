CREATE TABLE IF NOT EXISTS `linkanalysis_linkdata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `linking_page` varchar(255) DEFAULT NULL,
  `linking_page_title` varchar(255) DEFAULT NULL,
  `linking_page_pr` int(2) DEFAULT NULL,
  `linking_page_inlinks` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;
