CREATE TABLE IF NOT EXISTS `{table_prefix}acls` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `role` varchar(30) NOT NULL,
  `expression` varchar(100) NOT NULL,
  `action` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
