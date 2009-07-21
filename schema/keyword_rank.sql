CREATE TABLE IF NOT EXISTS `keyword_rank` (
  `site_id` int(11) NOT NULL,
  `search_engine_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `rank` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
