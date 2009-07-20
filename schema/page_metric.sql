CREATE TABLE IF NOT EXISTS `page_metric` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `page_metric` (`id`, `name`) VALUES
(1, 'pagerank'),
(2, 'inlink_count');
