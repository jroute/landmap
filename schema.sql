/*
SQLyog Community v9.20 
MySQL - 5.5.24-0ubuntu0.12.04.1 : Database - landmap_db
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`landmap_db` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `landmap_db`;

/*Table structure for table `access_counter` */

DROP TABLE IF EXISTS `access_counter`;

CREATE TABLE `access_counter` (
  `date` date NOT NULL DEFAULT '0000-00-00' COMMENT '날짜',
  `count` int(10) unsigned DEFAULT '0' COMMENT '접속카운트',
  PRIMARY KEY (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `calculate_counter` */

DROP TABLE IF EXISTS `calculate_counter`;

CREATE TABLE `calculate_counter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `date` date DEFAULT NULL COMMENT '날짜',
  `address` varchar(255) DEFAULT NULL COMMENT '주소',
  `count` int(10) unsigned DEFAULT '0' COMMENT '산정카운트',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `reduction` */

DROP TABLE IF EXISTS `reduction`;

CREATE TABLE `reduction` (
  `rid` int(11) NOT NULL AUTO_INCREMENT COMMENT '일련번호',
  `item` varchar(100) DEFAULT NULL COMMENT '감면항목',
  `content` varchar(255) DEFAULT NULL COMMENT '감면내용',
  `rate` varchar(5) DEFAULT '0' COMMENT '이율',
  `created` datetime NOT NULL COMMENT '등록일',
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
