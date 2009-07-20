CREATE TABLE IF NOT EXISTS `page_data` (
  `page_id` int(11) NOT NULL,
  `metric_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
