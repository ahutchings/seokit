CREATE TABLE IF NOT EXISTS `crontab` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `callback` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_run` datetime NOT NULL,
  `next_run` datetime NOT NULL,
  `increment` int(10) unsigned NOT NULL,
  `result` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

INSERT INTO `crontab` (`id`, `name`, `callback`, `last_run`, `next_run`, `increment`, `result`, `description`) VALUES
(1, 'refresh_pages', 'a:2:{i:0;s:5:"Sites";i:1;s:13:"refresh_pages";} ', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 604800, '', 'Refreshes all site pages.'),
(2, 'update_page_statistics', 'a:2:{i:0;s:5:"Sites";i:1;s:22:"update_page_statistics";} ', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 604800, '', 'Updates all page statistics.'),
(3, 'update_keyword_rankings', 'a:2:{i:0;s:5:"Sites";i:1;s:23:"update_keyword_rankings";} ', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 604800, '', 'Updates all site keyword rankings.');
