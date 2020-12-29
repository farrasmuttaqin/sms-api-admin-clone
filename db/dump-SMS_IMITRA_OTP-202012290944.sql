-- MySQL dump 10.16  Distrib 10.1.47-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 10.32.6.249    Database: SMS_IMITRA_OTP
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
-- Table structure for table `DELIVERY_REPORT`
--

DROP TABLE IF EXISTS `DELIVERY_REPORT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DELIVERY_REPORT` (
  `DELIVERY_REPORT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `AGENT_MESSAGE_ID` varchar(64) NOT NULL COMMENT 'Identifier for message agent from queue node message pop (Transaction ID)',
  `DESTINATION_MSISDN` varchar(19) DEFAULT NULL COMMENT 'Destination MSISDN for sending messages',
  `STATUS_CODE` varchar(20) NOT NULL COMMENT 'Error/Status code from agent delivery response ',
  `EXECUTED` tinyint(1) DEFAULT NULL,
  `TRY_COUNT` int(2) NOT NULL DEFAULT 0 COMMENT 'Number of update try delivery status',
  `CREATED_AT` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'The timestamp when delivery status is received',
  `UPDATED_AT` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`DELIVERY_REPORT_ID`),
  UNIQUE KEY `transaction_id_UNIQUE` (`AGENT_MESSAGE_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table contains data of SMS that success to be sent in Agent Sending 3rd Party ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DELIVERY_REPORT`
--

LOCK TABLES `DELIVERY_REPORT` WRITE;
/*!40000 ALTER TABLE `DELIVERY_REPORT` DISABLE KEYS */;
/*!40000 ALTER TABLE `DELIVERY_REPORT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PENDING_TASK`
--

DROP TABLE IF EXISTS `PENDING_TASK`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PENDING_TASK` (
  `PENDING_TASK_ID` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY Identifier for Pending Task table\n \n',
  `MESSAGE_ID` varchar(64) NOT NULL COMMENT 'Unique message Identifier from Queue Node that generated on SMS API.',
  `ALPHA_SENDER_ID` varchar(20) NOT NULL COMMENT 'The name of the company or person holding a message delivery service and working with an agent.\nThe name has been registered in the data agent and will be used for authentication on the agent application. \nFor example, Firstwap and Imitra Agent.',
  `DESTINATION_MSISDN` varchar(19) NOT NULL COMMENT 'Destination MSISDN for sending messages',
  `MESSAGE_CONTENT` blob NOT NULL COMMENT 'Message content sent by user',
  `MESSAGE_TYPE` varchar(2) NOT NULL COMMENT 'The type of message sent by user',
  `EXECUTED` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Pending task re-send execution status',
  `TRY_COUNT` int(2) NOT NULL DEFAULT 1 COMMENT 'Number of trial in the Pending Task Process',
  PRIMARY KEY (`PENDING_TASK_ID`),
  UNIQUE KEY `MESSAGE_ID_UNIQUE` (`MESSAGE_ID`),
  UNIQUE KEY `fk_PENDING_TASK_1` (`PENDING_TASK_ID`),
  KEY `fk_PENDING_TASK_1_idx` (`ALPHA_SENDER_ID`),
  CONSTRAINT `PENDING_TASK_ibfk_1` FOREIGN KEY (`ALPHA_SENDER_ID`) REFERENCES `WHITE_LIST_ALPHA` (`ALPHA_SENDER_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4853 DEFAULT CHARSET=utf8mb4 COMMENT='Table contains pending task of SMS Process ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PENDING_TASK`
--

LOCK TABLES `PENDING_TASK` WRITE;
/*!40000 ALTER TABLE `PENDING_TASK` DISABLE KEYS */;
INSERT INTO `PENDING_TASK` VALUES (5,'5OTP2020-12-13 15:23:37.77.KF4iG','dycas','628171234567','Type sms here','1',-1,2),(6,'5OTP2020-12-13 15:23:37.77.aaaa4','dycas','628171234567','Type sms here','1',1,2),(2873,'5OTP2020-12-13 15:23:37.77.12345','dycas','628171234567','Type sms here','1',1,2),(2874,'5OTP2020-12-13 15:23:37.77.adada','dycas','628171234567','Type sms here','1',1,2),(4568,'5OTP2020-12-13 15:23:37.77.ad12a','dycas','628171234567','Type sms here','1',1,2),(4569,'5OTP2020-12-13 15:23:37.77.ad121','dycas','628171234567','Type sms here','1',1,2),(4570,'5OTP2020-12-13 15:23:37.77.ad123','dycas','628171234567','Type sms here','1',1,2),(4571,'5OTP2020-12-13 15:23:37.77.ad129','dycas','628171234567','Type sms here','1',1,2),(4572,'5OTP2020-12-13 15:23:37.77.ad120','dycas','628171234567','Type sms here','1',1,2);
/*!40000 ALTER TABLE `PENDING_TASK` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `WHITE_LIST_ALPHA`
--

DROP TABLE IF EXISTS `WHITE_LIST_ALPHA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `WHITE_LIST_ALPHA` (
  `WHITE_LIST_ALPHA_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'PRIMARY identifier for White list alpha table \n',
  `ALPHA_SENDER_ID` varchar(20) NOT NULL COMMENT 'The name of the company or person holding a message delivery service and working with an agent.\nThe name has been registered in the data agent and will be used for authentication on the agent application. \nFor example, Firstwap and Imitra Agent.',
  `USER_NAME` varchar(16) NOT NULL COMMENT 'Username authentification registered with database agent',
  `PASSWORD` varchar(16) NOT NULL COMMENT 'Password authentication registered with database agent \n',
  `SENDER_ID` varchar(16) DEFAULT NULL COMMENT 'The name of sender who sent the message that will appear in message when delivered to handset. Sender alias ID (optional)',
  PRIMARY KEY (`WHITE_LIST_ALPHA_ID`),
  UNIQUE KEY `ALPHA_SENDER_UNIQUE` (`ALPHA_SENDER_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='Table contains list of registered sender id ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `WHITE_LIST_ALPHA`
--

LOCK TABLES `WHITE_LIST_ALPHA` WRITE;
/*!40000 ALTER TABLE `WHITE_LIST_ALPHA` DISABLE KEYS */;
INSERT INTO `WHITE_LIST_ALPHA` VALUES (1,'dycas','dycas','dycas',NULL);
/*!40000 ALTER TABLE `WHITE_LIST_ALPHA` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'SMS_IMITRA_OTP'
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
