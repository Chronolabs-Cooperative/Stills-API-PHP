CREATE DATABASE  IF NOT EXISTS `stills` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `stills`;
-- MySQL dump 10.13  Distrib 5.6.25, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: stills
-- ------------------------------------------------------
-- Server version	5.6.25-0ubuntu0.15.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `id` varchar(32) NOT NULL,
  `pack_id` varchar(32) DEFAULT '',
  `filename` varchar(255) DEFAULT '',
  `type` enum('jpg','png','gif','bmp','') DEFAULT '',
  `scape` enum('landscape','footscape','tilescape','') DEFAULT '',
  `nodes` int(8) DEFAULT '0',
  `bytes` int(8) DEFAULT '0',
  `width` int(8) DEFAULT '0',
  `height` int(8) DEFAULT '0',
  `fingerprint` varchar(32) DEFAULT '',
  `origin-bytes` int(8) DEFAULT '0',
  `origin-width` int(8) DEFAULT '0',
  `origin-height` int(8) DEFAULT '0',
  `origin-fingerprint` varchar(32) DEFAULT '',
  `origin-upload-fingerprint` varchar(32) DEFAULT '',
  `hits` int(24) DEFAULT '0',
  `caches` int(24) DEFAULT '0',
  `cached` int(12) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `PINGERING` (`pack_id`(17),`type`,`fingerprint`(11),`origin-fingerprint`(11),`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-07-30  7:40:02
