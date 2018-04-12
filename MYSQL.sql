-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `aid2cid`;
CREATE TABLE `aid2cid` (
  `aid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `blacklist`;
CREATE TABLE `blacklist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `value` varchar(255) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `dan`;
CREATE TABLE `dan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `referer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- 2017-08-19 15:30:55
