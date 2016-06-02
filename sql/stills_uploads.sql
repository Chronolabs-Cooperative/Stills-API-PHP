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
-- Table structure for table `uploads`
--

DROP TABLE IF EXISTS `uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `uploads` (
  `id` varchar(32) NOT NULL,
  `uploaded_file` varchar(255) DEFAULT '',
  `uploaded_path` varchar(255) DEFAULT '',
  `sorted_path` varchar(255) DEFAULT '',
  `key` varchar(44) DEFAULT '',
  `email` varchar(198) DEFAULT '',
  `scope` enum('to','cc','bcc','all') DEFAULT 'to',
  `cc` tinytext,
  `bcc` tinytext,
  `pack_id` varchar(32) DEFAULT '',
  `category_id` varchar(32) DEFAULT '',
  `category_method` enum('create-root','create-child','assign-to-only') DEFAULT 'assign-to-only',
  `category_name` varchar(128) DEFAULT '',
  `referee_uri` varchar(350) DEFAULT '',
  `default_name` varchar(128) DEFAULT '',
  `reminder` int(12) DEFAULT '0',
  `uploaded` int(12) DEFAULT '0',
  `sorted` int(12) DEFAULT '0',
  `starts` int(12) DEFAULT '0',
  `expire` int(12) DEFAULT '0',
  `packed` int(12) DEFAULT '0',
  `cleaned` int(12) DEFAULT '0',
  `notified` int(12) DEFAULT '0',
  `bytes` int(8) DEFAULT '0',
  `batch-size` int(12) DEFAULT '0',
  `archived` int(8) DEFAULT '0',
  `data` longtext,
  PRIMARY KEY (`id`),
  KEY `PINGERING` (`id`(10),`key`(14),`email`(13),`pack_id`(11),`category_method`,`archived`,`batch-size`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uploads`
--

LOCK TABLES `uploads` WRITE;
/*!40000 ALTER TABLE `uploads` DISABLE KEYS */;
/*!40000 ALTER TABLE `uploads` ENABLE KEYS */;
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
