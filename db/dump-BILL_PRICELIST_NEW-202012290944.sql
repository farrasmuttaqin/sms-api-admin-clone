-- MySQL dump 10.16  Distrib 10.1.47-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 10.32.6.249    Database: BILL_PRICELIST_NEW
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
-- Table structure for table `BILLING_PROFILE`
--

DROP TABLE IF EXISTS `BILLING_PROFILE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BILLING_PROFILE` (
  `BILLING_PROFILE_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for Billing Profile Table',
  `BILLING_TYPE` varchar(45) DEFAULT NULL COMMENT 'Type of Biling_Profile,\nCurrent supported profile type was "TIERING" and "OPERATOR",\nvalue of "PER_SMS_PRICE" will be search by "BILLING_TYPE" on "BILING_PROFILE_($TYPE)" Table,\nFor example:\n- BILLING_TYPE is "OPERATOR", then get "PER_SMS_PRICE" from "BILLING_PROFILE_OPERATOR"\n- BILLING_TYPE is "TIERING", then get "PER_SMS_PRICE" from "BILLING_PROFILE_TIERING',
  `NAME` varchar(50) DEFAULT NULL COMMENT 'The name of billing profile',
  `DESCRIPTION` varchar(1053) DEFAULT NULL COMMENT 'The Description of Billing Profile',
  `CREATED_AT` datetime DEFAULT NULL COMMENT 'The Date time when billing profile is created',
  `UPDATED_AT` datetime DEFAULT NULL COMMENT 'The Date time when billing profile is updated',
  PRIMARY KEY (`BILLING_PROFILE_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=100005 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BILLING_PROFILE`
--

LOCK TABLES `BILLING_PROFILE` WRITE;
/*!40000 ALTER TABLE `BILLING_PROFILE` DISABLE KEYS */;
INSERT INTO `BILLING_PROFILE` VALUES (1,'tiering','test',NULL,'2020-11-09 14:19:45','2020-11-12 13:22:13'),(4,'operator','tes','123','2020-11-10 13:28:33','2020-12-11 19:47:20'),(7,'tiering-operator','dsd',NULL,'2020-11-10 21:09:01','2020-11-12 13:21:59'),(99996,'operator','eqewq',NULL,'2020-11-25 10:38:25','2020-11-25 10:38:54'),(99997,'operator','Test11111',NULL,'2020-11-25 10:40:05',NULL),(99998,'tiering','test2222',NULL,'2020-11-25 10:40:28',NULL),(100000,'tiering','Billing for LifeWisdom 2','Billing for LifeWisdom 2','2020-12-03 11:28:08','2020-12-03 12:13:20'),(100003,'operator','Test Billing LifeWisdom3','Test Billing LifeWisdom3',NULL,NULL),(100004,'tiering','Test tiering LifeWisdom3','Test tiering LifeWisdom3',NULL,NULL);
/*!40000 ALTER TABLE `BILLING_PROFILE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BILLING_PROFILE_OPERATOR`
--

DROP TABLE IF EXISTS `BILLING_PROFILE_OPERATOR`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BILLING_PROFILE_OPERATOR` (
  `BILLING_PROFILE_OPERATOR_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for Billing Profile Operator',
  `BILLING_PROFILE_ID` int(11) NOT NULL COMMENT 'Unique Identifier for Billing Profile table that used for reference in Biliing Profile Operator',
  `OP_ID` varchar(45) DEFAULT NULL COMMENT 'To indicate operator for every destination',
  `PER_SMS_PRICE` decimal(8,2) unsigned DEFAULT NULL COMMENT 'Price Per SMS for specific operator and spesific billing profile',
  PRIMARY KEY (`BILLING_PROFILE_OPERATOR_ID`),
  KEY `fk_BILLING_PROFILE_OPERATOR_1_idx` (`BILLING_PROFILE_ID`),
  KEY `fk_BILLING_PROFILE_OPERATOR_2_idx` (`OP_ID`),
  CONSTRAINT `fk_BILLING_PROFILE_OPERATOR_1` FOREIGN KEY (`BILLING_PROFILE_ID`) REFERENCES `BILLING_PROFILE` (`BILLING_PROFILE_ID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BILLING_PROFILE_OPERATOR`
--

LOCK TABLES `BILLING_PROFILE_OPERATOR` WRITE;
/*!40000 ALTER TABLE `BILLING_PROFILE_OPERATOR` DISABLE KEYS */;
INSERT INTO `BILLING_PROFILE_OPERATOR` VALUES (27,99996,'DEFAULT',100.22),(28,99996,'1RSTWAP_LI',100.22),(29,99997,'DEFAULT',10.22),(41,4,'DEFAULT',1.00),(42,4,'3_RIVERS_PCS',2.00),(43,100003,'DEFAULT',100.00),(44,100003,'EXCELCOM',150.20);
/*!40000 ALTER TABLE `BILLING_PROFILE_OPERATOR` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BILLING_PROFILE_OPERATOR_PRICE`
--

DROP TABLE IF EXISTS `BILLING_PROFILE_OPERATOR_PRICE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BILLING_PROFILE_OPERATOR_PRICE` (
  `BILLING_PROFILE_OPERATOR_PRICE_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for BILLING PROFILE OPERATOR PRICE Table',
  `BILLING_PROFILE_TIERING_OPERATOR_ID` int(11) NOT NULL COMMENT 'Unique identifier for BILLING PROFILE TIERING OPERATOR Table used as reference BILLING PROFILE OPERATOR PRICE Table',
  `OP_ID` varchar(45) DEFAULT NULL COMMENT 'To indicate Name Provider based on Message Destination',
  `PER_SMS_PRICE` decimal(8,2) unsigned DEFAULT NULL COMMENT 'The Price of One Message based on OP ID COLUMN',
  PRIMARY KEY (`BILLING_PROFILE_OPERATOR_PRICE_ID`),
  KEY `fk_BILLING_PROFILE_OPERATOR_PRICE_1` (`BILLING_PROFILE_TIERING_OPERATOR_ID`),
  CONSTRAINT `fk_BILLING_PROFILE_OPERATOR_PRICE_1` FOREIGN KEY (`BILLING_PROFILE_TIERING_OPERATOR_ID`) REFERENCES `BILLING_PROFILE_TIERING_OPERATOR` (`BILLING_PROFILE_TIERING_OPERATOR_ID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BILLING_PROFILE_OPERATOR_PRICE`
--

LOCK TABLES `BILLING_PROFILE_OPERATOR_PRICE` WRITE;
/*!40000 ALTER TABLE `BILLING_PROFILE_OPERATOR_PRICE` DISABLE KEYS */;
INSERT INTO `BILLING_PROFILE_OPERATOR_PRICE` VALUES (3,3,'DEFAULT',100.00),(4,4,'DEFAULT',200.00);
/*!40000 ALTER TABLE `BILLING_PROFILE_OPERATOR_PRICE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BILLING_PROFILE_TIERING`
--

DROP TABLE IF EXISTS `BILLING_PROFILE_TIERING`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BILLING_PROFILE_TIERING` (
  `BILLING_PROFILE_TIERING_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for Billing Profile Tiering Table',
  `BILLING_PROFILE_ID` int(11) NOT NULL COMMENT 'Unique Identifier for Billing Profile table that used for reference in Biliing Profile Operator',
  `SMS_COUNT_FROM` int(11) NOT NULL COMMENT 'MINIMUM amount of SMS Count for using this PER_SMS_PRICE,\nThis field using VARCHAR instead of  BIGINT to reduce amount of storage usage\nPlease fill with an Integer value without dot(.) or coma(,). Any non Integer Value would be read as zero(0).\nPlease fil',
  `SMS_COUNT_UP_TO` int(11) NOT NULL COMMENT 'MAXIMUM amount of SMS Count for using this PER_SMS_PRICE,\nThis field using VARCHAR instead of  BIGINT to reduce amount of storage usage\nPlease fill with an Integer value without dot(.) or coma(,). Any non Integer Value would be read as zero(0).\nPlease fil',
  `PER_SMS_PRICE` decimal(8,2) unsigned DEFAULT NULL COMMENT 'Price Per SMS for spesific Tiering and spesific Billing Profile',
  PRIMARY KEY (`BILLING_PROFILE_TIERING_ID`),
  KEY `fk_BILLING_PROFILE_TIERING_2_idx` (`BILLING_PROFILE_ID`),
  CONSTRAINT `fk_BILLING_PROFILE_TIERING_3` FOREIGN KEY (`BILLING_PROFILE_ID`) REFERENCES `BILLING_PROFILE` (`BILLING_PROFILE_ID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BILLING_PROFILE_TIERING`
--

LOCK TABLES `BILLING_PROFILE_TIERING` WRITE;
/*!40000 ALTER TABLE `BILLING_PROFILE_TIERING` DISABLE KEYS */;
INSERT INTO `BILLING_PROFILE_TIERING` VALUES (10,1,0,1,3000.00),(11,1,2,2147483647,3.00),(17,99998,0,2147483647,12.22),(22,100000,0,1499,250.00),(23,100000,1500,2147483647,150.00),(24,100004,0,2,200.00),(25,100004,3,500,150.00);
/*!40000 ALTER TABLE `BILLING_PROFILE_TIERING` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BILLING_PROFILE_TIERING_OPERATOR`
--

DROP TABLE IF EXISTS `BILLING_PROFILE_TIERING_OPERATOR`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BILLING_PROFILE_TIERING_OPERATOR` (
  `BILLING_PROFILE_TIERING_OPERATOR_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier for BILLING PROFILE TIERING OPERATOR',
  `BILLING_PROFILE_ID` int(11) NOT NULL COMMENT 'Unique identifier for BILLING PROFILE ID Table used as reference BILLING PROFILE ID',
  `SMS_COUNT_FROM` int(11) NOT NULL COMMENT 'MINIMUM amount of SMS Count for using this PER_SMS_PRICE,\nThis field using VARCHAR instead of  BIGINT to reduce amount of storage usage\nPlease fill with an Integer value without dot(.) or coma(,). Any non Integer Value would be read as zero(0).\nPlease fill with ''MAX'' for Infinity number\nMaximum number can be handle was 10E+32',
  `SMS_COUNT_UP_TO` int(11) NOT NULL COMMENT 'MAXIMUM amount of SMS Count for using this PER_SMS_PRICE,\nThis field using VARCHAR instead of  BIGINT to reduce amount of storage usage\nPlease fill with an Integer value without dot(.) or coma(,). Any non Integer Value would be read as zero(0).\nPlease fill with ''MAX'' for Infinity number\nMaximum number can be handle was 10E+32',
  PRIMARY KEY (`BILLING_PROFILE_TIERING_OPERATOR_ID`),
  KEY `fk_BILLING_PROFILE_OPERATOR_5` (`BILLING_PROFILE_ID`),
  CONSTRAINT `fk_BILLING_PROFILE_OPERATOR_6` FOREIGN KEY (`BILLING_PROFILE_ID`) REFERENCES `BILLING_PROFILE` (`BILLING_PROFILE_ID`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BILLING_PROFILE_TIERING_OPERATOR`
--

LOCK TABLES `BILLING_PROFILE_TIERING_OPERATOR` WRITE;
/*!40000 ALTER TABLE `BILLING_PROFILE_TIERING_OPERATOR` DISABLE KEYS */;
INSERT INTO `BILLING_PROFILE_TIERING_OPERATOR` VALUES (3,7,0,2),(4,7,3,2147483647);
/*!40000 ALTER TABLE `BILLING_PROFILE_TIERING_OPERATOR` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BILLING_REPORT_GROUP`
--

DROP TABLE IF EXISTS `BILLING_REPORT_GROUP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BILLING_REPORT_GROUP` (
  `BILLING_REPORT_GROUP_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for Billing Report Group Table',
  `NAME` varchar(100) DEFAULT NULL COMMENT 'Group Name for Billing Report which grouped and will used for filename in invoice when report categorized as report group',
  `DESCRIPTION` varchar(1053) DEFAULT NULL COMMENT 'Details about Group in billing report',
  `CREATED_AT` datetime DEFAULT NULL COMMENT 'The Date time when billing report group is created',
  `UPDATED_AT` datetime DEFAULT NULL COMMENT 'The Date time when billing report group is updated',
  PRIMARY KEY (`BILLING_REPORT_GROUP_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=99994 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BILLING_REPORT_GROUP`
--

LOCK TABLES `BILLING_REPORT_GROUP` WRITE;
/*!40000 ALTER TABLE `BILLING_REPORT_GROUP` DISABLE KEYS */;
INSERT INTO `BILLING_REPORT_GROUP` VALUES (99993,'123','123','2020-11-12 13:27:48','2020-11-12 15:36:56');
/*!40000 ALTER TABLE `BILLING_REPORT_GROUP` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `BILLING_TIERING_GROUP`
--

DROP TABLE IF EXISTS `BILLING_TIERING_GROUP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `BILLING_TIERING_GROUP` (
  `BILLING_TIERING_GROUP_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for Billing Tiering Group table',
  `NAME` varchar(100) DEFAULT NULL COMMENT 'Group Name for Billing Report which grouped by tiering and will used for filename in invoice when report categorized as report group',
  `DESCRIPTION` varchar(1053) DEFAULT NULL COMMENT 'Details about Tiering Group in billing report',
  `CREATED_AT` varchar(45) DEFAULT NULL COMMENT 'The Date time when billing tiering group is created',
  `UPDATED_AT` varchar(45) DEFAULT NULL COMMENT 'The Date time when billing tiering group is updated',
  PRIMARY KEY (`BILLING_TIERING_GROUP_ID`),
  UNIQUE KEY `UPDATED_AT_UNIQUE` (`UPDATED_AT`)
) ENGINE=InnoDB AUTO_INCREMENT=99997 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `BILLING_TIERING_GROUP`
--

LOCK TABLES `BILLING_TIERING_GROUP` WRITE;
/*!40000 ALTER TABLE `BILLING_TIERING_GROUP` DISABLE KEYS */;
INSERT INTO `BILLING_TIERING_GROUP` VALUES (99993,'tes','tes','2020-11-12 13:27:34',NULL),(99994,'Tess',NULL,'2020-11-13 09:32:59',NULL),(99995,'aa','aa','2020-11-16 15:12:53',NULL),(99996,'Tiering Groi',NULL,'2020-11-21 14:29:57','2020-11-21 14:30:25');
/*!40000 ALTER TABLE `BILLING_TIERING_GROUP` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'BILL_PRICELIST_NEW'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-12-29  9:44:27
