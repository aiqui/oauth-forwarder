CREATE TABLE `oauth_redirect_log` (
  `log_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ip_address` varchar(40) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `redirect` varchar(255) DEFAULT NULL,
  `success` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`log_id`),
  KEY `creation` (`creation`),
  KEY `ip_address` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Records oAuth forwarding';
