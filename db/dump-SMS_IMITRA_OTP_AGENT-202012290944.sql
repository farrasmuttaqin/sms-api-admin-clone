-- MySQL dump 10.16  Distrib 10.1.47-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 10.32.6.249    Database: SMS_IMITRA_OTP_AGENT
-- ------------------------------------------------------
-- Server version	10.5.7-MariaDB-1:10.5.7+maria~focal

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `DELIVERY_STATUS`
--

DROP TABLE IF EXISTS `DELIVERY_STATUS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DELIVERY_STATUS` (
  `DELIVERY_STATUS_ID` int(11) NOT NULL AUTO_INCREMENT,
  `AGENT_MESSAGE_ID` varchar(45) DEFAULT NULL,
  `USERNAME` varchar(45) NOT NULL,
  `PASSWORD` varchar(45) NOT NULL,
  `MSISDN` varchar(19) DEFAULT NULL,
  `MSISDN_SENDER` varchar(45) DEFAULT NULL,
  `DELIVERY_STATUS_URL` varchar(45) DEFAULT NULL,
  `DELIVERY_STATUS` varchar(45) DEFAULT NULL,
  `EXECUTED` varchar(45) DEFAULT NULL,
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'CDR last updated date timestamp',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'CDR created date timestamp',
  PRIMARY KEY (`DELIVERY_STATUS_ID`),
  UNIQUE KEY `AGENT_MESSAGE_ID_UNIQUE` (`AGENT_MESSAGE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DELIVERY_STATUS`
--

LOCK TABLES `DELIVERY_STATUS` WRITE;
/*!40000 ALTER TABLE `DELIVERY_STATUS` DISABLE KEYS */;
/*!40000 ALTER TABLE `DELIVERY_STATUS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'SMS_IMITRA_OTP_AGENT'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-12-29  9:44:28
