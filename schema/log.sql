CREATE TABLE IF NOT EXISTS log (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  level bigint(20) DEFAULT NULL,
  file text COLLATE utf8_unicode_ci,
  line bigint(20) DEFAULT NULL,
  message text COLLATE utf8_unicode_ci,
  context text COLLATE utf8_unicode_ci,
  created_at datetime DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
