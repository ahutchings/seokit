CREATE TABLE IF NOT EXISTS `search_engine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

INSERT INTO `search_engine` (`id`, `name`) VALUES
(1, 'Google'),
(2, 'Yahoo'),
(3, 'Bing');
