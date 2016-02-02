-- MySQL dump 10.13  Distrib 5.5.47, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: yii2basic
-- ------------------------------------------------------
-- Server version	5.5.47-0ubuntu0.14.04.1

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
-- Table structure for table `basic_indicators`
--

DROP TABLE IF EXISTS `basic_indicators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `basic_indicators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_polish_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `basic_indicators`
--

LOCK TABLES `basic_indicators` WRITE;
/*!40000 ALTER TABLE `basic_indicators` DISABLE KEYS */;
INSERT INTO `basic_indicators` VALUES (11,'roi','Stopa zwrotu'),(12,'roi_benchmark','Stopa zwrotu benchmark'),(13,'income','Przychody'),(14,'income_benchmark','Przychody benchmark'),(15,'cost','Koszty'),(16,'cost_benchmark','Koszty benchmark');
/*!40000 ALTER TABLE `basic_indicators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(45) COLLATE utf8_polish_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (3,'finance','Wskaźniki finansowe'),(4,'occupancy','Obłożenie'),(5,'stay','Pobyt');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hospitals`
--

DROP TABLE IF EXISTS `hospitals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hospitals` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_polish_ci DEFAULT NULL,
  `roi` decimal(12,2) DEFAULT NULL,
  `roi_benchmark` decimal(12,2) DEFAULT NULL,
  `income` decimal(12,2) DEFAULT NULL,
  `income_benchmark` decimal(12,2) DEFAULT NULL,
  `cost` decimal(12,2) DEFAULT NULL,
  `cost_benchmark` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hospitals`
--

LOCK TABLES `hospitals` WRITE;
/*!40000 ALTER TABLE `hospitals` DISABLE KEYS */;
INSERT INTO `hospitals` VALUES (14,'Szpital Przykładowy',2.03,1.95,24233475.00,34572979.00,34957987.00,9324579.00);
/*!40000 ALTER TABLE `hospitals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `indicator_math`
--

DROP TABLE IF EXISTS `indicator_math`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `indicator_math` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indicator` varchar(45) COLLATE utf8_polish_ci NOT NULL,
  `id_hospital` int(11) DEFAULT NULL,
  `id_division` int(11) DEFAULT NULL,
  `minus2` decimal(5,2) DEFAULT NULL,
  `minus1` decimal(5,2) DEFAULT NULL,
  `plus1` decimal(5,2) DEFAULT NULL,
  `plus2` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_indicator_math_1_idx` (`indicator`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `indicator_math`
--

LOCK TABLES `indicator_math` WRITE;
/*!40000 ALTER TABLE `indicator_math` DISABLE KEYS */;
INSERT INTO `indicator_math` VALUES (2,'roi2benchmark',NULL,NULL,0.20,0.10,0.10,0.20),(3,'income2benchmark',NULL,NULL,0.50,0.05,0.05,0.10),(4,'costs2benchmark',NULL,NULL,0.50,0.05,0.05,0.10);
/*!40000 ALTER TABLE `indicator_math` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `indicator_names`
--

DROP TABLE IF EXISTS `indicator_names`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `indicator_names` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indicator` varchar(45) COLLATE utf8_polish_ci NOT NULL,
  `lang` varchar(2) COLLATE utf8_polish_ci NOT NULL DEFAULT 'pl',
  `name` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `numerator` int(11) DEFAULT NULL,
  `denominator` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `indicator_names`
--

LOCK TABLES `indicator_names` WRITE;
/*!40000 ALTER TABLE `indicator_names` DISABLE KEYS */;
INSERT INTO `indicator_names` VALUES (1,'roi2benchmark','pl','Stopa zwrotu względem benchmarku',11,12),(3,'income2benchmark','pl','Przychód względem benchmarku',13,14),(4,'costs2benchmark','pl','Koszt względem benchmarku',15,16);
/*!40000 ALTER TABLE `indicator_names` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `insights_content`
--

DROP TABLE IF EXISTS `insights_content`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `insights_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_polish_ci NOT NULL,
  `lang` varchar(2) COLLATE utf8_polish_ci NOT NULL DEFAULT 'pl',
  `content` text COLLATE utf8_polish_ci NOT NULL,
  `usage_counter` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insights_content`
--

LOCK TABLES `insights_content` WRITE;
/*!40000 ALTER TABLE `insights_content` DISABLE KEYS */;
INSERT INTO `insights_content` VALUES (1,'sdfs','pl','Oj bida Panie straszna',0),(3,'asa','pl','Oj jako tako',0);
/*!40000 ALTER TABLE `insights_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `insights_def`
--

DROP TABLE IF EXISTS `insights_def`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `insights_def` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8_polish_ci NOT NULL,
  `id_category` int(11) NOT NULL,
  `priority` tinyint(4) NOT NULL DEFAULT '0',
  `roi2benchmark` tinyint(4) DEFAULT NULL,
  `income2benchmark` tinyint(4) DEFAULT NULL,
  `costs2benchmark` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `insights_def`
--

LOCK TABLES `insights_def` WRITE;
/*!40000 ALTER TABLE `insights_def` DISABLE KEYS */;
INSERT INTO `insights_def` VALUES (6,'sdfs',3,0,0,-2,-2),(7,'asa',3,0,0,NULL,NULL),(8,'wniosek',4,0,0,2,-1);
/*!40000 ALTER TABLE `insights_def` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-02  3:40:00
