-- MySQL dump 10.16  Distrib 10.1.47-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 10.32.6.249    Database: SMS_IMITRA_OTP_CDR
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
-- Table structure for table `CDR202012`
--

DROP TABLE IF EXISTS `CDR202012`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CDR202012` (
  `CDR_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY Identifier for CDR table',
  `MESSAGE_ID` varchar(64) NOT NULL COMMENT 'Unique message Identifier from Queue Node that generated on SMS API.',
  `AGENT_MESSAGE_ID` varchar(64) DEFAULT NULL COMMENT 'Transaction ID obtained from Agent',
  `DESTINATION_MSISDN` varchar(19) NOT NULL COMMENT 'Destination MSISDN for sending messages',
  `ALPHA_SENDER_ID` varchar(20) NOT NULL COMMENT 'The name of the company or person holding a message delivery service and working with an agent.\nThe name has been registered in the data agent and will be used for authentication on the agent application. \nFor example, Firstwap and Imitra Agent.',
  `DELIVERY_STATUS` varchar(16) DEFAULT NULL COMMENT 'Status from Agent that has converted based on status of firstwap',
  `STATUS_CODE` varchar(16) DEFAULT NULL COMMENT 'Delivery Status from agent or Acknowledge Response from Agent that has converted based on status of firstwap',
  `SERVER_NAME` char(10) NOT NULL DEFAULT '' COMMENT 'The ID of server in firstwap from queue node message pop',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'CDR last updated date timestamp',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'CDR created date timestamp',
  PRIMARY KEY (`CDR_ID`) USING BTREE,
  UNIQUE KEY `MESSAGE_ID_UNIQUE` (`MESSAGE_ID`),
  KEY `fk_CDR_DELIVERY_REPORT_idx` (`AGENT_MESSAGE_ID`),
  KEY `fk_CDR_FALLBACK_RESULT_idx` (`MESSAGE_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CDR202012`
--

LOCK TABLES `CDR202012` WRITE;
/*!40000 ALTER TABLE `CDR202012` DISABLE KEYS */;
INSERT INTO `CDR202012` VALUES (1,'5OTP2020-12-13 15:23:37.77.KF4i1',NULL,'628171234567','Firstwap','UNKNOWN','5+0+0+0','PJA','2020-12-16 16:57:55','2020-12-16 16:57:55'),(2,'5OTP2020-12-13 15:23:37.77.KF4i2',NULL,'628171234567','Firstwap','UNKNOWN','5+0+0+0','PJA','2020-12-16 17:00:22','2020-12-16 17:00:22'),(5,'5OTP2020-12-13 15:23:37.77.aaaa4',NULL,'628171234567','dycas','EXPIRED','300+0+0+0','PJA','2020-12-16 22:03:39','2020-12-16 22:03:39'),(6,'5OTP2020-12-13 15:23:37.77.aaaa6',NULL,'628171234567','Firstwap','UNKNOWN','5+0+0+0','PJA','2020-12-16 22:11:16','2020-12-16 22:11:16'),(7,'5OTP2020-12-13 15:23:37.77.12345',NULL,'628171234567','dycas','EXPIRED','300+0+0+0','PJA','2020-12-16 22:20:49','2020-12-16 22:20:49'),(8,'5OTP2020-12-13 15:23:37.77.adada',NULL,'628171234567','dycas','EXPIRED','300+0+0+0','PJA','2020-12-16 22:20:57','2020-12-16 22:20:57'),(9,'5OTP2020-12-13 15:23:37.77.ad12a',NULL,'628171234567','dycas','EXPIRED','300+0+0+0','PJA','2020-12-16 22:23:30','2020-12-16 22:23:30'),(10,'5OTP2020-12-13 15:23:37.77.ad121',NULL,'628171234567','dycas','EXPIRED','300+0+0+0','PJA','2020-12-16 22:25:10','2020-12-16 22:25:10'),(11,'5OTP2020-12-13 15:23:37.77.ad123',NULL,'628171234567','dycas','EXPIRED','300+0+0+0','PJA','2020-12-16 22:35:33','2020-12-16 22:35:33'),(12,'5OTP2020-12-13 15:23:37.77.ad129',NULL,'628171234567','dycas','EXPIRED','300+0+0+0','PJA','2020-12-16 22:35:43','2020-12-16 22:35:43'),(13,'5OTP2020-12-13 15:23:37.77.ad120',NULL,'628171234567','dycas','EXPIRED','300+0+0+0','PJA','2020-12-16 22:35:53','2020-12-16 22:35:53');
/*!40000 ALTER TABLE `CDR202012` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'SMS_IMITRA_OTP_CDR'
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
