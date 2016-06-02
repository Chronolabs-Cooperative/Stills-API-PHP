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
-- Table structure for table `packs`
--

DROP TABLE IF EXISTS `packs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `packs` (
  `id` varchar(32) NOT NULL,
  `category-id` varchar(32) DEFAULT '',
  `filename` varchar(255) DEFAULT '',
  `path` varchar(255) DEFAULT '',
  `type-jpg` enum('yes','no') DEFAULT 'no',
  `type-png` enum('yes','no') DEFAULT 'no',
  `type-gif` enum('yes','no') DEFAULT 'no',
  `type-bmp` enum('yes','no') DEFAULT 'no',
  `scape-landscape` enum('yes','no') DEFAULT 'no',
  `scape-footscape` enum('yes','no') DEFAULT 'no',
  `scape-tilescape` enum('yes','no') DEFAULT 'no',
  `nodes` int(8) DEFAULT '0',
  `images-total` int(8) DEFAULT '0',
  `images-jpg` int(8) DEFAULT '0',
  `images-png` int(8) DEFAULT '0',
  `images-gif` int(8) DEFAULT '0',
  `images-bmp` int(8) DEFAULT '0',
  `images-landscape` int(8) DEFAULT '0',
  `images-footscape` int(8) DEFAULT '0',
  `images-tilescape` int(8) DEFAULT '0',
  `bytes-total` int(8) DEFAULT '0',
  `bytes-jpg` int(8) DEFAULT '0',
  `bytes-png` int(8) DEFAULT '0',
  `bytes-gif` int(8) DEFAULT '0',
  `bytes-bmp` int(8) DEFAULT '0',
  `minimal-width` int(8) DEFAULT '0',
  `minimal-height` int(8) DEFAULT '0',
  `maximum-width` int(8) DEFAULT '0',
  `maximum-height` int(8) DEFAULT '0',
  `hits` int(24) DEFAULT '0',
  `created` int(12) DEFAULT '0',
  `accessed` int(12) DEFAULT '0',
  `modified` int(12) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `PINGERING` (`id`(17),`filename`(17))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `packs`
--

LOCK TABLES `packs` WRITE;
/*!40000 ALTER TABLE `packs` DISABLE KEYS */;
/*!40000 ALTER TABLE `packs` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-07-30  7:40:01
