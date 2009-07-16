CREATE TABLE IF NOT EXISTS `option` (
  name varchar(255) collate utf8_unicode_ci NOT NULL,
  value text collate utf8_unicode_ci,
  PRIMARY KEY (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
