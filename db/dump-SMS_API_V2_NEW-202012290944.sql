-- MySQL dump 10.16  Distrib 10.1.47-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 10.32.6.249    Database: SMS_API_V2_NEW
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
-- Table structure for table `ADMIN`
--

DROP TABLE IF EXISTS `ADMIN`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ADMIN` (
  `ADMIN_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for Admin Table',
  `ADMIN_USERNAME` varchar(16) NOT NULL COMMENT 'Username that will be used by Admin to Login in SMS API ADMIN ',
  `ADMIN_PASSWORD` varchar(200) NOT NULL COMMENT 'Password that will be used by Admin to Login in SMS API ADMIN',
  `ADMIN_DISPLAY_NAME` varchar(32) NOT NULL DEFAULT '' COMMENT 'Name that will be displayed on SMS API ADMIN after Admin login in',
  `LOGIN_ENABLED` bit(1) NOT NULL COMMENT 'Flag that will be used for login permission',
  PRIMARY KEY (`ADMIN_ID`),
  UNIQUE KEY `ADMIN_USERNAME` (`ADMIN_USERNAME`)
) ENGINE=InnoDB AUTO_INCREMENT=1698 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ADMIN`
--

LOCK TABLES `ADMIN` WRITE;
/*!40000 ALTER TABLE `ADMIN` DISABLE KEYS */;
INSERT INTO `ADMIN` VALUES (1,'superadmin','37ed8e042e53a5ffdf526c8797ea3523503eee8dc0612bc5','superadmin','');
/*!40000 ALTER TABLE `ADMIN` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CLIENT`
--

DROP TABLE IF EXISTS `CLIENT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CLIENT` (
  `CLIENT_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for CLIENT Table',
  `ARCHIVED_DATE` date DEFAULT NULL COMMENT 'Flag of deactive Client',
  `COMPANY_NAME` varchar(100) NOT NULL COMMENT 'Name of The Client Company ',
  `COMPANY_URL` varchar(50) NOT NULL DEFAULT '' COMMENT 'URL of the Client',
  `COUNTRY_CODE` varchar(3) NOT NULL COMMENT 'Unique Identifier of CLIENT Table that used for reference in COUNTRY Table  ',
  `CONTACT_NAME` varchar(32) NOT NULL COMMENT 'Name of Contact that represent of The Client Company',
  `CONTACT_EMAIL` varchar(32) NOT NULL DEFAULT '' COMMENT 'Email Client that represent of The Client Company',
  `CONTACT_PHONE` varchar(32) NOT NULL DEFAULT '' COMMENT 'Number Phone that represent of The Client Company',
  `CONTACT_ADDRESS` varchar(200) DEFAULT NULL COMMENT 'Address of Client that represent of The Client Company',
  `CREATED_AT` datetime NOT NULL COMMENT 'The data time when Client Data is created',
  `UPDATED_AT` datetime DEFAULT NULL COMMENT 'The data time when Client Data is updated',
  `CREATED_BY` int(11) NOT NULL COMMENT 'ADMIN ID who created Client Data',
  `UPDATED_BY` int(11) DEFAULT NULL COMMENT 'ADMIN ID who updated Client Data',
  `CUSTOMER_ID` varchar(32) DEFAULT NULL COMMENT 'ID that used by other Dept such as finance',
  PRIMARY KEY (`CLIENT_ID`),
  KEY `CLIENT_COUNTRY_CODE` (`COUNTRY_CODE`),
  KEY `CLIENT_CREATED_BY` (`CREATED_BY`),
  KEY `CLIENT_UPDATED_BY` (`UPDATED_BY`),
  CONSTRAINT `CLIENT_COUNTRY_CODE` FOREIGN KEY (`COUNTRY_CODE`) REFERENCES `COUNTRY` (`COUNTRY_CODE`) ON UPDATE CASCADE,
  CONSTRAINT `CLIENT_CREATED_BY` FOREIGN KEY (`CREATED_BY`) REFERENCES `ADMIN` (`ADMIN_ID`) ON UPDATE CASCADE,
  CONSTRAINT `CLIENT_UPDATED_BY` FOREIGN KEY (`UPDATED_BY`) REFERENCES `ADMIN` (`ADMIN_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=100003 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CLIENT`
--

LOCK TABLES `CLIENT` WRITE;
/*!40000 ALTER TABLE `CLIENT` DISABLE KEYS */;
INSERT INTO `CLIENT` VALUES (1,'2020-11-10','DycasCorp','httt','GLP','dy','dewy@1.com','6285643142','s','2020-11-09 14:18:59','2020-11-12 11:21:52',1,1,'dycas'),(99997,NULL,'Life Wisdom','https://hidupbersahaja.com','IDN','Joko Suwarno','joko@suwarno.com','62929292999','Jalan mangkunegara IV jakarta selatan','2020-11-10 13:28:13',NULL,1,NULL,'99'),(100001,NULL,'1','1','ARG','1','1@1','08129688656666','123','2020-11-17 15:08:12',NULL,1,NULL,'1'),(100002,'2020-12-17','dew','dew.com','IND','ddd','fy@o.com','62834345456','dasdsa','2020-11-21 14:43:28','2020-12-17 14:25:58',1,1,'ddd');
/*!40000 ALTER TABLE `CLIENT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CLIENT_COUNTER`
--

DROP TABLE IF EXISTS `CLIENT_COUNTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CLIENT_COUNTER` (
  `CLIENT_COUNTER_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for Client Counter table',
  `USER_NAME` varchar(32) NOT NULL COMMENT 'The Name of User',
  `COUNTER` int(11) DEFAULT 1 COMMENT 'The indicate number of SMS. This attribute is usually used when updating usage sms for postpaid users',
  `LEVEL` int(11) DEFAULT 0 COMMENT 'To indicate concurrent request of Client',
  `CREATED` datetime DEFAULT NULL COMMENT 'The Date of Counter created',
  `ACCEPTED` datetime DEFAULT NULL COMMENT 'The Date of Counter accepted',
  `RELEASED` datetime DEFAULT NULL COMMENT 'The Date of Counter released',
  PRIMARY KEY (`CLIENT_COUNTER_ID`),
  UNIQUE KEY `USER_NAME` (`USER_NAME`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CLIENT_COUNTER`
--

LOCK TABLES `CLIENT_COUNTER` WRITE;
/*!40000 ALTER TABLE `CLIENT_COUNTER` DISABLE KEYS */;
INSERT INTO `CLIENT_COUNTER` VALUES (1,'dycas',1,100,NULL,NULL,NULL),(2,'1111',1,100,NULL,NULL,NULL),(3,'Dya',1,100,NULL,NULL,NULL),(4,'lifewisdom',1,100,NULL,NULL,NULL),(5,'qwerty1',1,100,NULL,NULL,NULL),(6,'qwerty2',1,100,NULL,NULL,NULL),(7,'wd',1,100,NULL,NULL,NULL),(8,'qwerty3',1,100,NULL,NULL,NULL),(9,'lifewisdom3',35,100,NULL,NULL,NULL);
/*!40000 ALTER TABLE `CLIENT_COUNTER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `COUNTRY`
--

DROP TABLE IF EXISTS `COUNTRY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `COUNTRY` (
  `COUNTRY_CODE` varchar(3) NOT NULL COMMENT 'Unique Identifier for Country Table',
  `COUNTRY_NAME` varchar(30) CHARACTER SET utf8 NOT NULL COMMENT 'Name of Country',
  PRIMARY KEY (`COUNTRY_CODE`),
  UNIQUE KEY `COUNTRY_NAME` (`COUNTRY_NAME`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `COUNTRY`
--

LOCK TABLES `COUNTRY` WRITE;
/*!40000 ALTER TABLE `COUNTRY` DISABLE KEYS */;
INSERT INTO `COUNTRY` VALUES ('AFG','Afghanistan'),('ALB','Albania'),('DZA','Algeria'),('ASM','American Samoa'),('AND','Andorra'),('AGO','AngolaS'),('AIA','Anguilla'),('ATA','Antarctica'),('ATG','Antigua and Barbuda'),('ARG','Argentina'),('ARM','Armenia'),('ABW','Aruba'),('AUS','Australia'),('AUT','Austria'),('AZE','Azerbaijan'),('BHS','Bahamas'),('BHR','Bahrain'),('BGD','Bangladesh'),('BRB','Barbados'),('BLR','Belarus'),('BEL','Belgium'),('BLZ','Belize'),('BEN','Benin'),('BMU','Bermuda'),('BTN','Bhutan'),('BOL','Bolivia'),('BIH','Bosnia and Herzegovina'),('BWA','Botswana'),('BVT','Bouvet Island'),('BRA','Brazil'),('IOT','British Indian Ocean Territory'),('BRN','Brunei Darussalam'),('BGR','Bulgaria'),('BFA','Burkina Faso'),('BDI','Burundi'),('KHM','Cambodia'),('CMR','Cameroon'),('CAN','Canada'),('CPV','Cape Verde'),('CYM','Cayman Islands'),('CAF','Central African Republic'),('TCD','Chad'),('CHL','Chile'),('CHN','China'),('CXR','Christmas Island'),('CCK','Cocos (Keeling) Islands'),('COL','Colombia'),('COM','Comoros'),('COG','Congo'),('COD','Congo, the Democratic Republic'),('COK','Cook Islands'),('CRI','Costa Rica'),('CIV','Cote D\'Ivoire'),('HRV','Croatia'),('CUB','Cuba'),('CYP','Cyprus'),('CZE','Czech Republic'),('DNK','Denmark'),('DJI','Djibouti'),('DMA','Dominica'),('DOM','Dominican Republic'),('ECU','Ecuador'),('EGY','Egypt'),('SLV','El Salvador'),('GNQ','Equatorial Guinea'),('ERI','Eritrea'),('EST','Estonia'),('ETH','Ethiopia'),('FLK','Falkland Islands (Malvinas)'),('FRO','Faroe Islands'),('FJI','Fiji'),('FIN','Finland'),('FRA','France'),('GUF','French Guiana'),('PYF','French Polynesia'),('ATF','French Southern Territories'),('GAB','Gabon'),('GMB','Gambia'),('GEO','Georgia'),('DEU','Germany'),('GHA','Ghana'),('GIB','Gibraltar'),('GRC','Greece'),('GRL','Greenland'),('GRD','Grenada'),('GLP','Guadeloupe'),('GUM','Guam'),('GTM','Guatemala'),('GIN','Guinea'),('GNB','Guinea-Bissau'),('GUY','Guyana'),('HTI','Haiti'),('HMD','Heard Island and Mcdonald Isla'),('VAT','Holy See (Vatican City State)'),('HND','Honduras'),('HKG','Hong Kong'),('HUN','Hungary'),('ISL','Iceland'),('IND','India'),('IDN','Indonesia'),('IRN','Iran, Islamic Republic of'),('IRQ','Iraq'),('IRL','Ireland'),('ISR','Israel'),('ITA','Italy'),('JAM','Jamaica'),('JPN','Japan'),('JOR','Jordan'),('KAZ','Kazakhstan'),('KEN','Kenya'),('KIR','Kiribati'),('PRK','Korea, Democratic People\'s Rep'),('KOR','Korea, Republic of'),('KWT','Kuwait'),('KGZ','Kyrgyzstan'),('LAO','Lao People\'s Democratic Republ'),('LVA','Latvia'),('LBN','Lebanon'),('LSO','Lesotho'),('LBR','Liberia'),('LBY','Libyan Arab Jamahiriya'),('LIE','Liechtenstein'),('LTU','Lithuania'),('LUX','Luxembourg'),('MAC','Macao'),('MKD','Macedonia, the Former Yugoslav'),('MDG','Madagascar'),('MWI','Malawi'),('MYS','Malaysia'),('MDV','Maldives'),('MLI','Mali'),('MLT','Malta'),('MHL','Marshall Islands'),('MTQ','Martinique'),('MRT','Mauritania'),('MUS','Mauritius'),('MYT','Mayotte'),('MEX','Mexico'),('FSM','Micronesia, Federated States o'),('MDA','Moldova, Republic of'),('MCO','Monaco'),('MNG','Mongolia'),('MSR','Montserrat'),('MAR','Morocco'),('MOZ','Mozambique'),('MMR','Myanmar'),('NAM','Namibia'),('NRU','Nauru'),('NPL','Nepal'),('NLD','Netherlands'),('ANT','Netherlands Antilles'),('NCL','New Caledonia'),('NZL','New Zealand'),('NIC','Nicaragua'),('NER','Niger'),('NGA','Nigeria'),('NIU','Niue'),('NFK','Norfolk Island'),('MNP','Northern Mariana Islands'),('NOR','Norway'),('OMN','Oman'),('PAK','Pakistan'),('PLW','Palau'),('PSE','Palestinian Territory, Occupie'),('PAN','Panama'),('PNG','Papua New Guinea'),('PRY','Paraguay'),('PER','Peru'),('PHL','Philippines'),('PCN','Pitcairn'),('POL','Poland'),('PRT','Portugal'),('PRI','Puerto Rico'),('QAT','Qatar'),('REU','Reunion'),('ROM','Romania'),('RUS','Russian Federation'),('RWA','Rwanda'),('SHN','Saint Helena'),('KNA','Saint Kitts and Nevis'),('LCA','Saint Lucia'),('SPM','Saint Pierre and Miquelon'),('VCT','Saint Vincent and the Grenadin'),('WSM','Samoa'),('SMR','San Marino'),('STP','Sao Tome and Principe'),('SAU','Saudi Arabia'),('SEN','Senegal'),('SCG','Serbia and Montenegro'),('SYC','Seychelles'),('SLE','Sierra Leone'),('SGP','Singapore'),('SVK','Slovakia'),('SVN','Slovenia'),('SLB','Solomon Islands'),('SOM','Somalia'),('ZAF','South Africa'),('SGS','South Georgia and the South Sa'),('ESP','Spain'),('LKA','Sri Lanka'),('SDN','Sudan'),('SUR','Suriname'),('SJM','Svalbard and Jan Mayen'),('SWZ','Swaziland'),('SWE','Sweden'),('CHE','Switzerland'),('SYR','Syrian Arab Republic'),('TWN','Taiwan, Province of China'),('TJK','Tajikistan'),('TZA','Tanzania, United Republic of'),('THA','Thailand'),('TLS','Timor-Leste'),('TGO','Togo'),('TKL','Tokelau'),('TON','Tonga'),('TTO','Trinidad and Tobago'),('TUN','Tunisia'),('TUR','Turkey'),('TKM','Turkmenistan'),('TCA','Turks and Caicos Islands'),('TUV','Tuvalu'),('UGA','Uganda'),('UKR','Ukraine'),('ARE','United Arab Emirates'),('GBR','United Kingdom'),('USA','United States'),('UMI','United States Minor Outlying I'),('URY','Uruguay'),('UZB','Uzbekistan'),('VUT','Vanuatu'),('VEN','Venezuela'),('VNM','Viet Nam'),('VGB','Virgin Islands, British'),('VIR','Virgin Islands, U.s.'),('WLF','Wallis and Futuna'),('ESH','Western Sahara'),('YEM','Yemen'),('ZMB','Zambia'),('ZWE','Zimbabwe');
/*!40000 ALTER TABLE `COUNTRY` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CREDIT_TRANSACTION`
--

DROP TABLE IF EXISTS `CREDIT_TRANSACTION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CREDIT_TRANSACTION` (
  `CREDIT_TRANSACTION_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for Credit Transaction',
  `TRANSACTION_REF` varchar(14) NOT NULL COMMENT 'ID Reference number of Transaction',
  `USER_ID` int(11) NOT NULL COMMENT 'Unique Identifier for USER table that used for reference in CREDIT TRANSACTION table',
  `CREDIT_REQUESTER` varchar(30) NOT NULL COMMENT 'Name of Client that have a request of the Transaction',
  `CREDIT_AMOUNT` bigint(20) NOT NULL COMMENT 'Total amount money of Credit user ',
  `CREDIT_PRICE` decimal(16,2) NOT NULL COMMENT 'Total count price of Credit user',
  `CURRENCY_CODE` varchar(3) NOT NULL COMMENT 'Currency of Credit User',
  `CURRENT_BALANCE` int(11) NOT NULL COMMENT 'Status of current balance',
  `PREVIOUS_BALANCE` int(11) NOT NULL COMMENT 'Status of balance before top up Credit',
  `PAYMENT_METHOD` varchar(16) NOT NULL COMMENT 'Methode payment of Transaction',
  `PAYMENT_DATE` date DEFAULT NULL COMMENT 'Timestamp of payment of Transaction',
  `PAYMENT_ACK` bit(1) NOT NULL DEFAULT b'0' COMMENT 'Flag for Acknowledge from Finance Manager',
  `CREATED_BY` int(11) NOT NULL COMMENT 'ADMIN ID who Top Up or Deduct Credit',
  `CREATED_DATE` datetime NOT NULL COMMENT 'Date when Top Up or Deduct Credit',
  `UPDATED_BY` int(11) DEFAULT NULL COMMENT 'ADMIN ID who Top Up or Deduct Credit',
  `UPDATED_DATE` datetime DEFAULT NULL COMMENT 'Date when Top Up or Deduct Credit',
  `TRANSACTION_REMARK` varchar(250) NOT NULL DEFAULT '' COMMENT 'Remark by ADMIN ID if necessary',
  PRIMARY KEY (`CREDIT_TRANSACTION_ID`),
  UNIQUE KEY `TRANSACTION_REF` (`TRANSACTION_REF`),
  KEY `CREDIT_TRANSACTION_ibfk_1` (`USER_ID`),
  KEY `CREDIT_TRANSACTION_ibfk_2` (`CREATED_BY`),
  KEY `CREDIT_TRANSACTION_ibfk_3` (`UPDATED_BY`),
  CONSTRAINT `CREDIT_TRANSACTION_ibfk_1` FOREIGN KEY (`USER_ID`) REFERENCES `USER` (`USER_ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `CREDIT_TRANSACTION_ibfk_2` FOREIGN KEY (`CREATED_BY`) REFERENCES `ADMIN` (`ADMIN_ID`) ON UPDATE CASCADE,
  CONSTRAINT `CREDIT_TRANSACTION_ibfk_3` FOREIGN KEY (`UPDATED_BY`) REFERENCES `ADMIN` (`ADMIN_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=995 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CREDIT_TRANSACTION`
--

LOCK TABLES `CREDIT_TRANSACTION` WRITE;
/*!40000 ALTER TABLE `CREDIT_TRANSACTION` DISABLE KEYS */;
INSERT INTO `CREDIT_TRANSACTION` VALUES (1,'T20201110950lD',2,'farras2',123,123.00,'rp',123,0,'bank','2020-11-03','\0',1,'2020-11-10 10:21:22',1,'2020-11-10 10:22:05','123'),(2,'T20201110A64Zo',2,'-',1,0.00,'rp',122,123,'unspecified',NULL,'\0',1,'2020-11-10 10:21:42',NULL,NULL,''),(3,'T20201110jnnFC',2,'123',10,10.00,'rp',132,122,'bank',NULL,'\0',1,'2020-11-10 10:22:31',NULL,NULL,'123'),(992,'T20201113HixQr',4,'tes',12,12.00,'rp',12,0,'bank',NULL,'\0',1,'2020-11-13 09:00:03',1,'2020-11-13 09:02:00','123'),(993,'T20201203Jz2JD',3,'Life Wisdom',500,1500000.00,'rp',500,0,'bank',NULL,'\0',1,'2020-12-03 11:29:34',NULL,NULL,'Adding Credit'),(994,'T202012037YJV8',99997,'Pak Haji',500,1500000.00,'rp',500,0,'bank',NULL,'\0',1,'2020-12-03 11:44:43',NULL,NULL,'dari pak haji kasih oleh - oleh');
/*!40000 ALTER TABLE `CREDIT_TRANSACTION` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CUSTOMERS`
--

DROP TABLE IF EXISTS `CUSTOMERS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `CUSTOMERS` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FIRST_NAME` varchar(255) NOT NULL,
  `LAST_NAME` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CUSTOMERS`
--

LOCK TABLES `CUSTOMERS` WRITE;
/*!40000 ALTER TABLE `CUSTOMERS` DISABLE KEYS */;
INSERT INTO `CUSTOMERS` VALUES (2,'hantsy@schema','bai');
/*!40000 ALTER TABLE `CUSTOMERS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `INVOICE_BANK`
--

DROP TABLE IF EXISTS `INVOICE_BANK`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `INVOICE_BANK` (
  `INVOICE_BANK_ID` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier ot INVOICE BANK Table',
  `BANK_NAME` varchar(150) NOT NULL COMMENT 'The Name of Bank',
  `ADDRESS` varchar(250) DEFAULT NULL COMMENT 'The address of Bank that created Bank account',
  `ACCOUNT_NAME` varchar(150) NOT NULL COMMENT 'The account name of Bank',
  `ACCOUNT_NUMBER` varchar(30) NOT NULL COMMENT 'The account number of Bank',
  PRIMARY KEY (`INVOICE_BANK_ID`),
  UNIQUE KEY `ACCOUNT_NUMBER_UNIQUE` (`ACCOUNT_NUMBER`)
) ENGINE=InnoDB AUTO_INCREMENT=9999993 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `INVOICE_BANK`
--

LOCK TABLES `INVOICE_BANK` WRITE;
/*!40000 ALTER TABLE `INVOICE_BANK` DISABLE KEYS */;
INSERT INTO `INVOICE_BANK` VALUES (9999992,'BRI','123','Luffy','123123');
/*!40000 ALTER TABLE `INVOICE_BANK` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `INVOICE_HISTORY`
--

DROP TABLE IF EXISTS `INVOICE_HISTORY`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `INVOICE_HISTORY` (
  `INVOICE_HISTORY_ID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for INVOICE_HISTORY Table',
  `INVOICE_PROFILE_ID` int(11) unsigned NOT NULL COMMENT 'Unique Identifier for INVOICE_PROFILE Table as reference for INVOICE_HISTORY Table ',
  `INVOICE_NUMBER` int(10) unsigned NOT NULL COMMENT 'Unique Number for INVOICE',
  `START_DATE` date DEFAULT NULL COMMENT 'Start date for Client to pay the Invoice',
  `DUE_DATE` date DEFAULT NULL COMMENT 'End date for Client to pay the Invoice',
  `REF_NUMBER` varchar(50) DEFAULT NULL COMMENT 'Reference number from marketing department when got deal with Client',
  `STATUS` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Flag for lock or unlock Invoice History. 1 = Locked, 0 = Unlocked',
  `INVOICE_TYPE` varchar(10) NOT NULL DEFAULT 'ORIGINAL' COMMENT 'Type of INVOICE Documentation. Value of this entity ORIGINAL, REVISED, or COPIED',
  `FILE_NAME` varchar(250) DEFAULT NULL COMMENT 'The Name of File',
  `LOCKED_AT` timestamp NULL DEFAULT NULL COMMENT 'Time when ADMIN ID lock the File',
  `CREATED_AT` timestamp NULL DEFAULT current_timestamp() COMMENT 'Time when ADMIN ID create Invoice History',
  `UPDATED_AT` timestamp NULL DEFAULT NULL COMMENT 'Time when ADMIN ID update Invoice History',
  PRIMARY KEY (`INVOICE_HISTORY_ID`),
  KEY `FK_INVOICE_HISTORY` (`INVOICE_PROFILE_ID`),
  CONSTRAINT `FK_INVOICE_HISTORY_` FOREIGN KEY (`INVOICE_PROFILE_ID`) REFERENCES `INVOICE_PROFILE` (`INVOICE_PROFILE_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=10000057 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `INVOICE_HISTORY`
--

LOCK TABLES `INVOICE_HISTORY` WRITE;
/*!40000 ALTER TABLE `INVOICE_HISTORY` DISABLE KEYS */;
INSERT INTO `INVOICE_HISTORY` VALUES (1,2,49,'2020-11-12','2020-11-24',NULL,1,'FINAL','2020/11/49_DycasCorp_November_ORIGINAL_PREVIEW.pdf','2020-11-12 14:07:42','2020-11-12 14:04:25',NULL),(7,2,52,'2020-11-12','2020-11-24',NULL,1,'COPIED','2020/11/52_DycasCorp_November_COPIED_PREVIEW.pdf',NULL,'2020-11-12 14:17:17',NULL),(10000042,2,65,'2020-11-13','2020-11-25',NULL,1,'FINAL','2020/11/65_DycasCorp_November_ORIGINAL_PREVIEW.pdf','2020-11-13 13:45:30','2020-11-13 13:44:56',NULL),(10000043,2,65,'2020-11-13','2020-11-25',NULL,1,'COPIED','2020/11/65_DycasCorp_November_COPIED_PREVIEW.pdf',NULL,'2020-11-13 13:45:51',NULL),(10000044,2,65,'2020-11-13','2020-11-25',NULL,1,'FINAL','2020/11/65_DycasCorp_November_REVISED_PREVIEW.pdf','2020-11-13 13:47:27','2020-11-13 13:46:38',NULL),(10000045,2,66,'2020-10-08','2020-10-20',NULL,0,'ORIGINAL','2020/10/66_DycasCorp_October_ORIGINAL_PREVIEW.pdf',NULL,'2020-11-13 13:48:21',NULL),(10000048,9999992,68,'2020-11-17','2020-11-29',NULL,0,'ORIGINAL','2020/11/68_Life_Wisdom_November_ORIGINAL_PREVIEW.pdf',NULL,'2020-11-17 10:57:18',NULL),(10000049,2,69,'2020-11-17','2020-11-29',NULL,0,'ORIGINAL','2020/11/69_DycasCorp_November_ORIGINAL_PREVIEW.pdf',NULL,'2020-11-17 10:57:38',NULL),(10000050,9999992,70,'2020-11-17','2020-11-29',NULL,1,'FINAL','2020/11/70_Life_Wisdom_November_ORIGINAL_PREVIEW.pdf','2020-11-17 14:38:54','2020-11-17 14:26:26',NULL),(10000051,2,71,'2020-11-21','2020-12-03',NULL,0,'ORIGINAL','2020/11/71_DycasCorp_November_ORIGINAL_PREVIEW.pdf',NULL,'2020-11-21 14:35:31',NULL),(10000052,2,72,'2020-11-21','2020-12-03',NULL,1,'FINAL','2020/11/72_DycasCorp_November_ORIGINAL_PREVIEW.pdf','2020-11-21 14:44:35','2020-11-21 14:35:53',NULL),(10000053,2,73,'2020-11-21','2020-12-04',NULL,0,'ORIGINAL','2020/11/73_DycasCorp_November_ORIGINAL_PREVIEW.pdf',NULL,'2020-11-21 14:57:52',NULL),(10000054,2,74,'2020-11-21','2020-12-03','test',0,'ORIGINAL','2020/11/74_DycasCorp_November_ORIGINAL_PREVIEW.pdf',NULL,'2020-11-21 14:58:21','2020-11-21 14:58:45'),(10000055,9999992,75,'2020-11-21','2020-12-03',NULL,0,'ORIGINAL','2020/11/75_Life_Wisdom_November_ORIGINAL_PREVIEW.pdf',NULL,'2020-11-21 14:59:58',NULL),(10000056,9999993,76,'2021-01-01','2021-01-13','2',0,'ORIGINAL','2021/01/76_1_January_ORIGINAL_PREVIEW.pdf',NULL,'2020-12-02 14:20:29',NULL);
/*!40000 ALTER TABLE `INVOICE_HISTORY` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `INVOICE_PRODUCT`
--

DROP TABLE IF EXISTS `INVOICE_PRODUCT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `INVOICE_PRODUCT` (
  `PRODUCT_ID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier of INVOICE PRODUCT Table',
  `PRODUCT_NAME` varchar(150) NOT NULL COMMENT 'The Name of Product',
  `PERIOD` date DEFAULT NULL COMMENT 'Last Period of Product when generate Invoice History',
  `IS_PERIOD` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Flag to indicate that Invoice Product is using periode and use Billing Report',
  `UNIT_PRICE` decimal(10,2) unsigned DEFAULT 0.00 COMMENT 'The price of product per unit. This column can define to 0 when use Billing Report',
  `QTY` int(11) DEFAULT 0 COMMENT 'The quantity of product billed to Client. This column can define to 0 when use Billing Report',
  `USE_REPORT` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'To indicate Invoice history using Billing Report or Not. 1 = QTY and UNIT_PRICE value will take from Billing Report File, 1 = QTY and UNIT_PRICE value will take from UNIT_PRICE and QTY column that input by Admin',
  `REPORT_NAME` varchar(100) DEFAULT NULL,
  `OPERATOR` varchar(100) DEFAULT NULL,
  `OWNER_TYPE` varchar(10) NOT NULL COMMENT 'If OWNER_TYPE has a value "PROFILE" means the product has a relation with INVOICE_PROFILE table\nelse if OWNER_TYPE has a value "HISTORY" means the product has a relation with INVOICE_HISTORY table',
  `OWNER_ID` int(10) unsigned NOT NULL COMMENT 'This column is a foreign_key from table that describe in OWNER_TYPE column',
  PRIMARY KEY (`PRODUCT_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=100000106 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `INVOICE_PRODUCT`
--

LOCK TABLES `INVOICE_PRODUCT` WRITE;
/*!40000 ALTER TABLE `INVOICE_PRODUCT` DISABLE KEYS */;
INSERT INTO `INVOICE_PRODUCT` VALUES (99999933,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',132),(99999934,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',123),(99999935,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',124),(99999936,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',124),(99999937,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',124),(99999938,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',124),(99999939,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',123),(99999940,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',123),(99999941,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',123),(99999942,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',123),(99999943,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',123),(99999944,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',123),(99999945,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',123),(99999946,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',123),(99999947,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',123),(99999948,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',123),(99999949,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',123),(99999950,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',123),(99999951,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',123),(99999952,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',123),(99999953,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',123),(99999954,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',123),(99999955,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',123),(99999956,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',123),(99999957,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',123),(99999958,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',123),(99999959,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',125),(99999960,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',125),(99999961,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',125),(99999962,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',125),(99999963,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',123),(99999964,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',123),(99999965,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',123),(99999966,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',123),(99999967,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',126),(99999968,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',126),(99999969,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',126),(99999970,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',126),(99999971,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',123),(99999972,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',123),(99999973,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',123),(99999974,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',123),(99999975,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',125),(99999976,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',125),(99999977,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',125),(99999978,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',125),(99999979,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',127),(99999980,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',127),(99999981,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',127),(99999982,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',127),(99999983,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',128),(99999984,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',128),(99999985,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',128),(99999986,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',128),(99999987,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',130),(99999988,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',130),(99999989,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',130),(99999990,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',130),(99999991,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',131),(99999992,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',131),(99999993,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',131),(99999994,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',131),(99999995,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',123),(99999996,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',123),(99999997,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',123),(99999998,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',123),(99999999,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',132),(100000000,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',132),(100000001,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',132),(100000002,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',132),(100000003,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',123),(100000004,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',123),(100000005,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',123),(100000006,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',123),(100000007,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',60),(100000008,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',60),(100000009,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',60),(100000010,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',60),(100000011,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',61),(100000012,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',61),(100000013,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',61),(100000014,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',61),(100000015,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',62),(100000016,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',62),(100000017,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',62),(100000018,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',62),(100000019,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',63),(100000020,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',63),(100000021,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',63),(100000022,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',63),(100000023,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',64),(100000024,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',64),(100000025,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',64),(100000026,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',64),(100000027,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',65),(100000028,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',65),(100000029,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',65),(100000030,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',65),(100000031,'M1','2020-11-12',1,0.00,0,1,'Dya','DEFAULT','HISTORY',66),(100000032,'M2','2020-11-12',1,2.00,1,1,'lifewisdom','DEFAULT','HISTORY',66),(100000033,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',66),(100000034,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',66),(100000037,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',69),(100000039,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',71),(100000040,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',71),(100000046,'','2020-11-17',0,0.00,0,0,NULL,'DEFAULT','HISTORY',69),(100000047,'9','2020-11-17',0,9.00,9,0,NULL,'DEFAULT','HISTORY',69),(100000048,'4','2020-11-17',0,4.00,4,0,NULL,'DEFAULT','HISTORY',69),(100000049,'5','2020-11-17',0,5.00,5,0,NULL,'DEFAULT','HISTORY',69),(100000050,'6','2020-11-17',0,6.00,6,0,NULL,'DEFAULT','HISTORY',69),(100000051,'7','2020-11-17',0,7.00,7,0,NULL,'DEFAULT','HISTORY',69),(100000052,'8','2020-11-17',0,8.00,8,0,NULL,'DEFAULT','HISTORY',69),(100000053,'9','2020-11-17',0,9.00,9,0,NULL,'DEFAULT','HISTORY',69),(100000057,'10','2020-11-17',0,10.00,10,0,NULL,'DEFAULT','HISTORY',69),(100000058,'1','2020-11-17',0,1.00,1,0,NULL,'DEFAULT','HISTORY',69),(100000059,'3','2020-11-17',0,3.00,3,0,NULL,'DEFAULT','HISTORY',52),(100000060,'2','2020-11-17',0,2.00,2,0,NULL,'DEFAULT','HISTORY',52),(100000061,'3','2020-11-17',0,3.00,3,0,NULL,'DEFAULT','HISTORY',52),(100000063,'4','2020-11-17',0,4.00,4,0,NULL,'DEFAULT','HISTORY',52),(100000064,'5','2020-11-17',0,5.00,5,0,NULL,'DEFAULT','HISTORY',52),(100000065,'6','2020-11-17',0,6.00,6,0,NULL,'DEFAULT','HISTORY',52),(100000066,'7','2020-11-17',0,7.00,7,0,NULL,'DEFAULT','HISTORY',52),(100000069,'asdad','2020-11-17',0,1.00,1,0,NULL,'DEFAULT','HISTORY',52),(100000070,'123213','2020-11-17',0,2.00,2,0,NULL,'DEFAULT','HISTORY',52),(100000071,'123213','2020-11-17',0,1.00,1,0,NULL,'DEFAULT','HISTORY',52),(100000074,'1233333333333333333 1233333333333333333','2020-11-17',0,7.00,7,0,NULL,'DEFAULT','HISTORY',52),(100000075,'1233333333333333333','2020-11-17',0,2.00,2,0,NULL,'DEFAULT','HISTORY',52),(100000076,'1','2020-11-17',0,2.00,2,0,NULL,'DEFAULT','HISTORY',52),(100000077,'1','2020-11-17',0,5.00,5,0,NULL,'DEFAULT','HISTORY',52),(100000078,'5','2020-11-17',0,5.00,5,0,NULL,'DEFAULT','HISTORY',52),(100000079,'2','2020-11-17',0,2.00,2,0,NULL,'DEFAULT','HISTORY',52),(100000082,'1233333333333333333 1233333333333333333 1233333333333333333 1233333333333333333','2020-11-17',0,2.00,2,0,NULL,'DEFAULT','HISTORY',52),(100000083,'1233333333333333333 1233333333333333333 1233333333333333333 1233333333333333333','2020-11-17',0,4.00,4,0,NULL,'DEFAULT','HISTORY',52),(100000084,'1','2020-11-17',0,1.00,1,0,NULL,'DEFAULT','HISTORY',52),(100000085,'2','2020-11-17',0,2.00,2,0,NULL,'DEFAULT','HISTORY',62),(100000086,'1','2020-11-17',0,1.00,1,0,NULL,'DEFAULT','HISTORY',62),(100000087,'7','2020-11-17',0,7.00,7,0,NULL,'DEFAULT','HISTORY',62),(100000093,'1','2020-11-17',0,1.00,1,0,NULL,'DEFAULT','HISTORY',40),(100000094,'2','2020-11-17',0,2.00,2,0,NULL,'DEFAULT','HISTORY',40),(100000095,'3','2020-11-17',0,3.00,3,0,NULL,'DEFAULT','HISTORY',40),(100000096,'4','2020-11-17',0,4.00,4,0,NULL,'DEFAULT','HISTORY',40),(100000097,'5','2020-11-17',0,5.00,5,0,NULL,'DEFAULT','HISTORY',40),(100000098,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',72),(100000099,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',72),(100000100,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',73),(100000101,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',73),(100000102,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','HISTORY',74),(100000103,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','HISTORY',74),(100000104,'M3','2020-11-12',1,4.00,3,0,NULL,'DEFAULT','PROFILE',2),(100000105,'M4','2020-11-12',0,6.00,5,0,NULL,'DEFAULT','PROFILE',2);
/*!40000 ALTER TABLE `INVOICE_PRODUCT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `INVOICE_PROFILE`
--

DROP TABLE IF EXISTS `INVOICE_PROFILE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `INVOICE_PROFILE` (
  `INVOICE_PROFILE_ID` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identifier of Invoice Profile Table',
  `CLIENT_ID` int(11) NOT NULL COMMENT 'Unique Identifier of Client Table that used as reference on Inovice Profile Table',
  `INVOICE_BANK_ID` int(10) unsigned NOT NULL COMMENT 'Uniques Identifier of Invoice Bank table that used as reference on Inovice Profile Table',
  `AUTO_GENERATE` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Flag to indicate auto generated or not by System',
  `CREATED_AT` timestamp NULL DEFAULT current_timestamp() COMMENT 'The Date when Admin ID create Invoice Profile',
  `APPROVED_NAME` varchar(45) DEFAULT NULL COMMENT 'The Name of Approver',
  `APPROVED_POSITION` varchar(45) DEFAULT NULL COMMENT 'The position of Approver',
  `UPDATED_AT` timestamp NULL DEFAULT NULL COMMENT 'The Date when Admin ID update Invoice Profile',
  PRIMARY KEY (`INVOICE_PROFILE_ID`),
  UNIQUE KEY `CLIENT_ID_UNIQUE` (`CLIENT_ID`),
  KEY `FK_INVOICE_PROFILE` (`INVOICE_BANK_ID`),
  CONSTRAINT `FK_INVOICE_PROFILE` FOREIGN KEY (`INVOICE_BANK_ID`) REFERENCES `INVOICE_BANK` (`INVOICE_BANK_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9999995 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `INVOICE_PROFILE`
--

LOCK TABLES `INVOICE_PROFILE` WRITE;
/*!40000 ALTER TABLE `INVOICE_PROFILE` DISABLE KEYS */;
INSERT INTO `INVOICE_PROFILE` VALUES (2,1,9999992,1,'2020-11-12 14:04:12','7','7','2020-11-17 15:07:46'),(9999992,99997,9999992,0,'2020-11-13 14:24:51','','','2020-11-17 14:27:53'),(9999993,100001,9999992,0,'2020-11-17 15:08:26','1','3434','2020-11-17 15:09:00'),(9999994,100002,9999992,0,'2020-11-21 14:43:42',NULL,NULL,NULL);
/*!40000 ALTER TABLE `INVOICE_PROFILE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `INVOICE_SETTING`
--

DROP TABLE IF EXISTS `INVOICE_SETTING`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `INVOICE_SETTING` (
  `SETTING_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `PAYMENT_PERIOD` int(11) NOT NULL DEFAULT 3 COMMENT 'Payment period in days',
  `AUTHORIZED_NAME` varchar(45) DEFAULT NULL,
  `AUTHORIZED_POSITION` varchar(45) DEFAULT NULL,
  `NOTE_MESSAGE` text DEFAULT NULL,
  `INVOICE_NUMBER_PREFIX` varchar(45) NOT NULL,
  `LAST_INVOICE_NUMBER` int(10) unsigned NOT NULL,
  PRIMARY KEY (`SETTING_ID`),
  UNIQUE KEY `SETTING_ID_UNIQUE` (`SETTING_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `INVOICE_SETTING`
--

LOCK TABLES `INVOICE_SETTING` WRITE;
/*!40000 ALTER TABLE `INVOICE_SETTING` DISABLE KEYS */;
INSERT INTO `INVOICE_SETTING` VALUES (1,12,'12','12','1272','1rstwap -',77);
/*!40000 ALTER TABLE `INVOICE_SETTING` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PUSH_STATUS_FLAG`
--

DROP TABLE IF EXISTS `PUSH_STATUS_FLAG`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PUSH_STATUS_FLAG` (
  `PROCEED_TIMESTAMP` datetime DEFAULT NULL COMMENT 'The Time when message on User Message Status were pushed to URL Client',
  `USER_MESSAGE_STATUS_ID` int(11) NOT NULL COMMENT 'Uniques Identifier from User Message Status Table that used as reference for Push Status Flag',
  PRIMARY KEY (`USER_MESSAGE_STATUS_ID`),
  CONSTRAINT `FK_PUSHSTATUS_FLAG` FOREIGN KEY (`USER_MESSAGE_STATUS_ID`) REFERENCES `USER_MESSAGE_STATUS` (`USER_MESSAGE_STATUS_ID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PUSH_STATUS_FLAG`
--

LOCK TABLES `PUSH_STATUS_FLAG` WRITE;
/*!40000 ALTER TABLE `PUSH_STATUS_FLAG` DISABLE KEYS */;
/*!40000 ALTER TABLE `PUSH_STATUS_FLAG` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `REPLY_BLACKLIST`
--

DROP TABLE IF EXISTS `REPLY_BLACKLIST`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `REPLY_BLACKLIST` (
  `REPLY_BLACKLIST_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for Reply Blacklist Table',
  `USER_ID` int(11) NOT NULL COMMENT 'Unique Identifier from User Table that used as reference on Reply Blacklist Table',
  `MSISDN` varchar(16) NOT NULL COMMENT 'The Number Uniquely identifying a subscription in GSM or a UMTS mobile Network',
  PRIMARY KEY (`REPLY_BLACKLIST_ID`),
  KEY `REPLY_USER_FK` (`USER_ID`),
  CONSTRAINT `REPLY_USER_FK` FOREIGN KEY (`USER_ID`) REFERENCES `USER` (`USER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `REPLY_BLACKLIST`
--

LOCK TABLES `REPLY_BLACKLIST` WRITE;
/*!40000 ALTER TABLE `REPLY_BLACKLIST` DISABLE KEYS */;
/*!40000 ALTER TABLE `REPLY_BLACKLIST` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `REPLY_SMS`
--

DROP TABLE IF EXISTS `REPLY_SMS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `REPLY_SMS` (
  `REPLY_SMS_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for Reply SMS Table',
  `VIRTUAL_NUMBER_ID` int(11) NOT NULL COMMENT 'Unique Identifier from Virtual Number Table that used as reference in Reply SMS Table',
  `SENDER` varchar(16) NOT NULL COMMENT 'The User Name based on USER ID NUMBER',
  `DELIVERED` bit(1) NOT NULL COMMENT 'To indicate status of Reply SMS is deliver or not',
  `ENCODING` int(11) NOT NULL COMMENT 'The message type of message sent bt Modem',
  `RECEIVED_TIMESTAMP` datetime NOT NULL COMMENT 'The date of Message that received',
  `MESSAGE` longtext NOT NULL COMMENT 'Content of Message that  has been sent by User',
  `BLACKLISTED` bit(1) NOT NULL DEFAULT b'0' COMMENT 'To indicate Reply SMS use blacklist or not',
  PRIMARY KEY (`REPLY_SMS_ID`),
  KEY `VIRTUAL_REPLY_SMS_FK` (`VIRTUAL_NUMBER_ID`),
  CONSTRAINT `VIRTUAL_REPLY_SMS_FK` FOREIGN KEY (`VIRTUAL_NUMBER_ID`) REFERENCES `VIRTUAL_NUMBER` (`VIRTUAL_NUMBER_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `REPLY_SMS`
--

LOCK TABLES `REPLY_SMS` WRITE;
/*!40000 ALTER TABLE `REPLY_SMS` DISABLE KEYS */;
/*!40000 ALTER TABLE `REPLY_SMS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `REPLY_SMS_STATUS`
--

DROP TABLE IF EXISTS `REPLY_SMS_STATUS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `REPLY_SMS_STATUS` (
  `REPLY_SMS_STATUS_ID` int(11) NOT NULL COMMENT 'Unique Identifier for Reply SMS Status Table',
  `FLAG` bit(1) NOT NULL DEFAULT b'0' COMMENT 'To indicate SMS Reply Status has been pushed or not',
  PRIMARY KEY (`REPLY_SMS_STATUS_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `REPLY_SMS_STATUS`
--

LOCK TABLES `REPLY_SMS_STATUS` WRITE;
/*!40000 ALTER TABLE `REPLY_SMS_STATUS` DISABLE KEYS */;
/*!40000 ALTER TABLE `REPLY_SMS_STATUS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SENDER`
--

DROP TABLE IF EXISTS `SENDER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SENDER` (
  `SENDER_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for Sender Table',
  `SENDER_ENABLED` bit(1) NOT NULL COMMENT 'To indicate status of Sender is active or not',
  `USER_ID` int(11) NOT NULL COMMENT 'Unique Identifier of User table that used as reference on Sender Table',
  `SENDER_NAME` varchar(20) NOT NULL COMMENT 'The Name of Sender',
  `COBRANDER_ID` varchar(16) DEFAULT NULL COMMENT 'COBRANDER ID is used for routing message based on Agent and Operator',
  PRIMARY KEY (`SENDER_ID`),
  KEY `fk_SENDER` (`USER_ID`),
  CONSTRAINT `fk_SENDER` FOREIGN KEY (`USER_ID`) REFERENCES `USER` (`USER_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1009 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SENDER`
--

LOCK TABLES `SENDER` WRITE;
/*!40000 ALTER TABLE `SENDER` DISABLE KEYS */;
INSERT INTO `SENDER` VALUES (1,'',1,'Firstwap','dycas'),(2,'',3,'1rstWAP SMSC','lifewisdom'),(3,'\0',4,'1111','dycas'),(4,'',5,'DycasCorp','dycas'),(993,'',4,'Firstwap','1111'),(994,'',2,'Firstwap','Dya'),(995,'',3,'Firstwap','lifewisdom'),(996,'\0',99993,'Firstwap',''),(997,'\0',99994,'Firstwap','qwerty2'),(998,'\0',5,'Firstwap','lifewisdom'),(999,'',3,'Firstwap','lifewisdom'),(1000,'',99993,'Firstwap',''),(1001,'',99995,'Firstwap','qwerty3'),(1002,'',5,'Firstwap','wd'),(1003,'',4,'fsdfdaf','dycas'),(1004,'',1,'Test','dycas'),(1005,'',4,'Test','dycas'),(1006,'',99995,'Firstwap','dycas'),(1007,'',99993,'Firstwap',''),(1008,'',99997,'Test','lifewisdom3');
/*!40000 ALTER TABLE `SENDER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SMS_DISPATCHER_SERVER`
--

DROP TABLE IF EXISTS `SMS_DISPATCHER_SERVER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SMS_DISPATCHER_SERVER` (
  `SMS_DISPATCHER_SERVER_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Uniques Identifier for SMS Dispatcher Server Table',
  `USER_MESSAGE_STATUS_ID` int(11) NOT NULL COMMENT 'Unique identifier for User Message Status Table that used as reference om SMS Dispatcher Table',
  `SMS_DISPATCHER_CLIENT_ID` int(11) NOT NULL COMMENT 'Versatile ID that sended by Users or Clients',
  PRIMARY KEY (`SMS_DISPATCHER_SERVER_ID`),
  UNIQUE KEY `USER_MESSAGE_STATUS_ID` (`USER_MESSAGE_STATUS_ID`),
  CONSTRAINT `FKB2B46E3ED85A1E3D54` FOREIGN KEY (`USER_MESSAGE_STATUS_ID`) REFERENCES `USER_MESSAGE_STATUS` (`USER_MESSAGE_STATUS_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SMS_DISPATCHER_SERVER`
--

LOCK TABLES `SMS_DISPATCHER_SERVER` WRITE;
/*!40000 ALTER TABLE `SMS_DISPATCHER_SERVER` DISABLE KEYS */;
INSERT INTO `SMS_DISPATCHER_SERVER` VALUES (1,1,1),(2,2,2),(3,3,1),(4,4,2),(5,5,2),(6,6,1),(7,7,2),(8,8,1),(9,9,1),(10,10,2),(11,11,2),(12,12,1),(13,13,1),(14,14,2),(15,1412,423432),(16,1413,423433),(17,1414,10000),(18,1415,1313421),(19,1416,3234234),(20,1417,32342341),(21,1418,3234232),(22,1419,55684645),(23,1420,556846451),(24,1421,5568464),(25,1422,556846423),(26,1424,55684),(27,1425,5568423),(28,1426,55684231),(29,1427,12121212),(30,1428,121212121),(31,1429,12121215),(32,1430,12121218),(33,1431,12121219),(34,1432,1216),(35,1433,1218),(36,1434,1219),(37,1435,1213),(38,1436,120001),(39,1437,12002),(40,1438,12004),(41,1439,12005),(42,1440,12006),(43,1441,12007),(44,1442,12008),(45,1443,1209),(46,1444,1309),(47,1445,1409),(48,1446,1509),(49,1447,1609),(50,1448,1709),(51,1449,1909),(52,1450,1901),(53,1451,1903),(54,1452,1906),(55,1453,19010),(56,1454,19011),(57,1455,19012),(58,1456,19014),(59,1457,19017),(60,1458,19019),(61,1459,19112),(62,1460,19116),(63,1461,19117),(64,1462,19118),(65,1463,19119),(66,1464,19171),(67,1465,19172),(68,1466,19178),(70,1468,1917922),(71,1469,1917926),(73,1471,1917920);
/*!40000 ALTER TABLE `SMS_DISPATCHER_SERVER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USER`
--

DROP TABLE IF EXISTS `USER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USER` (
  `USER_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for User Table',
  `version` int(11) NOT NULL COMMENT 'Version of SMS API',
  `USER_NAME` varchar(32) NOT NULL COMMENT 'The Name of User',
  `PASSWORD` varchar(32) NOT NULL COMMENT 'The Password of User',
  `ACTIVE` bit(1) NOT NULL COMMENT 'To indicate active or not of User Account',
  `COUNTER` int(11) DEFAULT 0 COMMENT 'To indicate number of SMS. This attribute is usually used for Postpaid Users',
  `CREDIT` bigint(20) NOT NULL COMMENT 'To indicate credit od User. This attribute is usually used for Prepaid Users and Validation data ',
  `LAST_ACCESS` datetime DEFAULT NULL COMMENT 'The Last Date of User send message',
  `CREATED_DATE` datetime NOT NULL COMMENT 'The Date time when User created by Admin ID',
  `UPDATED_DATE` datetime DEFAULT NULL COMMENT 'The Date time when User updated by Admin ID',
  `CREATED_BY` int(11) NOT NULL COMMENT 'The Admin ID who create User Account ',
  `UPDATED_BY` int(11) DEFAULT NULL COMMENT 'The Admin ID who update User Account',
  `COBRANDER_ID` varchar(32) DEFAULT NULL COMMENT 'COBRANDER ID is used for routing message based on Agent and Operator',
  `CLIENT_ID` int(11) NOT NULL COMMENT 'Unique Identifier of Client Table that used as reference on User Table',
  `DELIVERY_STATUS_URL` varchar(255) DEFAULT NULL COMMENT 'URL Client that used to push Delivery Status',
  `URL_INVALID_COUNT` int(11) DEFAULT NULL COMMENT 'Number of Trial of process access DELIVERY STATUS URL',
  `URL_ACTIVE` bit(1) DEFAULT NULL COMMENT 'The Status URL is active or not',
  `URL_LAST_RETRY` datetime DEFAULT NULL COMMENT 'The Last Date of access DELIVERY STATUS URL',
  `USE_BLACKLIST` bit(1) NOT NULL DEFAULT b'0' COMMENT 'To indicate User using blacklist or not',
  `IS_POSTPAID` bit(1) NOT NULL DEFAULT b'0' COMMENT 'To indicate User type. 1 = Postpaid User, 0 = Prepaid User',
  `TRY_COUNT` int(11) DEFAULT 0 COMMENT 'The Number of loop check validation when authentication on SMS API',
  `INACTIVE_REASON` varchar(35) DEFAULT NULL COMMENT 'The Reason Account User is inactive',
  `DATETIME_TRY` datetime DEFAULT NULL COMMENT 'The Last date of User try send message',
  `BILLING_PROFILE_ID` int(4) DEFAULT NULL COMMENT 'Unique Identifier of Billing Profile Table on BILL_PRICELIST Database that used as reference on User Table',
  `BILLING_REPORT_GROUP_ID` int(4) DEFAULT NULL COMMENT 'Unique Identifier of Billing Report Table on BILL_PRICELIST Database that used as reference on User Table.',
  `BILLING_TIERING_GROUP_ID` int(4) DEFAULT NULL COMMENT 'Unique Identifier of Billing Tiering Group table on BILL_PRICELIST Database that used as reference on User Table.',
  `IS_OJK` bit(1) DEFAULT b'0' COMMENT 'To indicate User is being regulated by OJK Regulation',
  PRIMARY KEY (`USER_ID`),
  UNIQUE KEY `USER_NAME` (`USER_NAME`),
  KEY `ADMIN_USER_fk_1` (`CREATED_BY`),
  KEY `ADMIN_USER_fk_2` (`UPDATED_BY`),
  KEY `CLIENT_USER_fk_1` (`CLIENT_ID`),
  CONSTRAINT `ADMIN_USER_fk_1` FOREIGN KEY (`CREATED_BY`) REFERENCES `ADMIN` (`ADMIN_ID`) ON UPDATE CASCADE,
  CONSTRAINT `ADMIN_USER_fk_2` FOREIGN KEY (`UPDATED_BY`) REFERENCES `ADMIN` (`ADMIN_ID`) ON UPDATE CASCADE,
  CONSTRAINT `CLIENT_USER_fk_1` FOREIGN KEY (`CLIENT_ID`) REFERENCES `CLIENT` (`CLIENT_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=99999 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USER`
--

LOCK TABLES `USER` WRITE;
/*!40000 ALTER TABLE `USER` DISABLE KEYS */;
INSERT INTO `USER` VALUES (1,0,'dycas','12345','\0',0,0,NULL,'2020-11-09 14:19:22','2020-11-16 12:05:00',1,1,'j',1,NULL,NULL,'\0',NULL,'\0','\0',0,NULL,NULL,99997,NULL,NULL,'\0'),(2,0,'Dya','12345','',0,-68,NULL,'2020-11-10 10:19:35','2020-11-12 11:53:11',1,1,'Dya',1,NULL,NULL,'\0',NULL,'\0','',0,NULL,NULL,NULL,NULL,99994,'\0'),(3,0,'lifewisdom','12345','',0,500,NULL,'2020-11-10 13:29:20','2020-12-03 11:29:34',1,1,'lifewisdom',99997,'https://someurl.com/somewhere',NULL,'',NULL,'\0','',0,NULL,NULL,NULL,NULL,NULL,'\0'),(4,0,'1111','12345','\0',0,12,NULL,'2020-11-10 19:21:38','2020-11-25 10:51:17',1,1,'1111',1,NULL,NULL,'\0',NULL,'\0','',0,NULL,NULL,NULL,99993,NULL,'\0'),(5,0,'wd','12345','',0,0,NULL,'2020-11-10 19:34:28','2020-11-12 11:55:50',1,1,'wd',1,NULL,NULL,'\0',NULL,'\0','',0,NULL,NULL,NULL,NULL,NULL,'\0'),(99993,0,'qwerty1','12345','',23,9999,'2020-12-11 16:07:58','2020-11-15 02:45:28','2020-12-11 22:02:34',1,1,'qwerty1',1,NULL,0,'\0',NULL,'\0','\0',0,'','2020-12-11 16:07:58',4,NULL,NULL,''),(99994,0,'qwerty2','12345','',0,0,NULL,'2020-11-12 11:45:42',NULL,1,NULL,'qwerty2',1,NULL,NULL,'\0',NULL,'\0','',0,NULL,NULL,1,NULL,99996,'\0'),(99995,0,'qwerty3','12345','',2,0,'2020-12-03 19:24:03','2020-11-12 11:45:52','2020-12-03 19:24:03',1,1,'dycas',1,NULL,NULL,'\0',NULL,'\0','',0,'','2020-12-03 19:24:03',NULL,NULL,NULL,'\0'),(99996,0,'lifewisdom2','123456','',0,0,NULL,'2020-12-03 11:33:39',NULL,1,NULL,'lifewisdom2',99997,'https://somewhereibelong.com',NULL,'',NULL,'\0','\0',0,NULL,NULL,100000,NULL,NULL,''),(99997,0,'lifewisdom3','123456','',0,41400,NULL,'2020-12-03 11:44:03','2020-12-03 11:44:43',1,1,'qwerty1',99997,'https://somewhere.com',NULL,'',NULL,'\0','\0',0,NULL,NULL,7,NULL,NULL,''),(99998,0,'213213','2323','',0,0,NULL,'2020-12-22 09:24:18',NULL,1,NULL,'213213',1,NULL,NULL,'\0',NULL,'\0','\0',0,NULL,NULL,NULL,NULL,NULL,'\0');
/*!40000 ALTER TABLE `USER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USER_IP`
--

DROP TABLE IF EXISTS `USER_IP`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USER_IP` (
  `USER_IP_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for User IP Table',
  `USER_ID` int(11) NOT NULL COMMENT 'Unique Identifier for User table that used as reference on User IP table',
  `IP_ADDRESS` varchar(18) NOT NULL COMMENT 'IP Address of User Id that registeref by Marketing Dept to access SMS Service Firstwap',
  PRIMARY KEY (`USER_IP_ID`),
  KEY `USER_IP_fk_1` (`USER_ID`),
  CONSTRAINT `USER_IP_fk_1` FOREIGN KEY (`USER_ID`) REFERENCES `USER` (`USER_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1004 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USER_IP`
--

LOCK TABLES `USER_IP` WRITE;
/*!40000 ALTER TABLE `USER_IP` DISABLE KEYS */;
INSERT INTO `USER_IP` VALUES (1,1,'10.32.6.5'),(995,2,'10.32.6.5'),(996,3,'10.32.6.5'),(1001,99995,'10.32.6.5'),(1002,99993,'10.32.6.5'),(1003,99997,'10.32.6.5');
/*!40000 ALTER TABLE `USER_IP` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USER_MESSAGE_STATUS`
--

DROP TABLE IF EXISTS `USER_MESSAGE_STATUS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `USER_MESSAGE_STATUS` (
  `USER_MESSAGE_STATUS_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier of User Message Status Table',
  `MESSAGE_ID` varchar(64) NOT NULL COMMENT 'Unique Identifier of Message',
  `DESTINATION` varchar(20) NOT NULL COMMENT 'Destination Number of Message',
  `OP_COUNTRY_CODE` char(3) DEFAULT NULL COMMENT 'Country based on Prefix of Desination Number of Message',
  `OP_ID` varchar(16) DEFAULT NULL COMMENT 'The Provider or Operator of Destination Number on Message',
  `MESSAGE_CONTENT` longtext NOT NULL COMMENT 'Content of Message that  has been sent by User',
  `MESSAGE_TYPE` int(11) NOT NULL COMMENT 'Message Type that has been sent by User',
  `MESSAGE_STATUS` varchar(16) DEFAULT NULL COMMENT 'Delivery Status of Message that has been sent by User',
  `SEND_DATETIME` datetime DEFAULT NULL COMMENT 'The Date of message that was sent by User',
  `STATUS_DATETIME` datetime DEFAULT NULL COMMENT 'The Date of Delivery Status of message updated by System',
  `SENDER_ID` int(11) NOT NULL COMMENT 'Unique Identifier of Sender Table that used as reference on User Message Status Table',
  `USER_ID_NUMBER` int(11) NOT NULL COMMENT 'Unique Identifier of User Table that used as reference on User Message Status Table',
  `ACKNOWLEDGED` bit(1) DEFAULT NULL COMMENT 'To indicate acknowledgement of Message data that has been fetch or push by User or System',
  `BROADCAST_SMS_ID` int(11) DEFAULT NULL COMMENT 'Unique Identifier for Broadcast SMS Table that used as reference on User Message Status Table',
  `SENDER` varchar(20) NOT NULL COMMENT 'The Sender Name based on SENDER_ID',
  `USER_ID` varchar(16) NOT NULL COMMENT 'The User Name based on USER ID NUMBER',
  PRIMARY KEY (`USER_MESSAGE_STATUS_ID`),
  UNIQUE KEY `MESSAGE_ID` (`MESSAGE_ID`),
  KEY `FKB2B46E3ED85A1E2D2` (`USER_ID_NUMBER`),
  KEY `FKB2B46E3ED85A1E2D3` (`SENDER_ID`),
  CONSTRAINT `FKB2B46E3ED85A1E2D2` FOREIGN KEY (`USER_ID_NUMBER`) REFERENCES `USER` (`USER_ID`),
  CONSTRAINT `FKB2B46E3ED85A1E2D3` FOREIGN KEY (`SENDER_ID`) REFERENCES `SENDER` (`SENDER_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=1472 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USER_MESSAGE_STATUS`
--

LOCK TABLES `USER_MESSAGE_STATUS` WRITE;
/*!40000 ALTER TABLE `USER_MESSAGE_STATUS` DISABLE KEYS */;
INSERT INTO `USER_MESSAGE_STATUS` VALUES (1,'5OTP2020-10-09 16:16:25.626.6vEgN','628171234567','IDN','EXCELCOM','Type sms here',0,' ',NULL,NULL,1,1,NULL,NULL,'Firstwap','dycas'),(2,'5OTP2020-10-09 16:16:33.819.mYp7A','628171234567','IDN','EXCELCOM','Type sms here',0,' ',NULL,NULL,1,1,NULL,NULL,'Firstwap','dycas'),(3,'5OTP2020-10-12 11:33:21.856.AdqjU','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,993,4,NULL,NULL,'Firstwap','1111'),(4,'5OTP2020-10-12 11:33:28.822.ShauA','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,993,4,NULL,NULL,'Firstwap','1111'),(5,'5OTP2020-10-12 11:37:14.190.A6U6H','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,994,2,NULL,NULL,'Firstwap','Dya'),(6,'5OTP2020-10-12 11:37:19.49.T0tAq','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,994,2,NULL,NULL,'Firstwap','Dya'),(7,'5OTP2020-10-12 11:37:42.245.xOCKL','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,995,3,NULL,NULL,'Firstwap','lifewisdom'),(8,'5OTP2020-10-12 11:37:45.744.q3c1m','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,995,3,NULL,NULL,'Firstwap','lifewisdom'),(9,'5OTP2020-10-12 11:38:21.811.MBI7C','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,996,99993,NULL,NULL,'Firstwap','qwerty1'),(10,'5OTP2020-10-12 11:38:27.755.xgRQ4','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,996,99993,NULL,NULL,'Firstwap','qwerty1'),(11,'5OTP2020-10-12 11:39:01.179.YhyoF','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,997,99994,NULL,NULL,'Firstwap','qwerty2'),(12,'5OTP2020-10-12 11:39:04.262.yOSlR','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,997,99994,NULL,NULL,'Firstwap','qwerty2'),(13,'5OTP2020-10-12 11:40:53.956.L0i2P','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,998,5,NULL,NULL,'Firstwap','wd'),(14,'5OTP2020-10-12 11:40:59.275.0tXVL','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,998,5,NULL,NULL,'Firstwap','wd'),(111,'5OTP2020-11-09 16:16:25.626.6vEgN','628171234567','IDN','EXCELCOM','Type sms here',0,' ',NULL,NULL,1,1,NULL,NULL,'Firstwap','dycas'),(211,'5OTP2020-11-09 16:16:33.819.mYp7A','628171234567','IDN','EXCELCOM','Type sms here',0,' ',NULL,NULL,1,1,NULL,NULL,'Firstwap','dycas'),(311,'5OTP2020-11-12 11:33:21.856.AdqjU','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,993,4,NULL,NULL,'Firstwap','1111'),(411,'5OTP2020-11-12 11:33:28.822.ShauA','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,993,4,NULL,NULL,'Firstwap','1111'),(511,'5OTP2020-11-12 11:37:14.190.A6U6H','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,994,2,NULL,NULL,'Firstwap','Dya'),(611,'5OTP2020-11-12 11:37:19.49.T0tAq','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,994,2,NULL,NULL,'Firstwap','Dya'),(711,'5OTP2020-11-12 11:37:42.245.xOCKL','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,995,3,NULL,NULL,'Firstwap','lifewisdom'),(811,'5OTP2020-11-12 11:37:45.744.q3c1m','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,995,3,NULL,NULL,'Firstwap','lifewisdom'),(911,'5OTP2020-11-12 11:38:21.811.MBI7C','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,996,99993,NULL,NULL,'Firstwap','qwerty1'),(1011,'5OTP2020-11-12 11:38:27.755.xgRQ4','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,996,99993,NULL,NULL,'Firstwap','qwerty1'),(1111,'5OTP2020-11-12 11:39:01.179.YhyoF','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,997,99994,NULL,NULL,'Firstwap','qwerty2'),(1211,'5OTP2020-11-12 11:39:04.262.yOSlR','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,997,99994,NULL,NULL,'Firstwap','qwerty2'),(1311,'5OTP2020-11-12 11:40:53.956.L0i2P','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,998,5,NULL,NULL,'Firstwap','wd'),(1411,'5OTP2020-11-12 11:40:59.275.0tXVL','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,998,5,NULL,NULL,'Firstwap','wd'),(1412,'5OTP2020-12-03 19:06:46.842.izYPZ','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,1006,99995,NULL,NULL,'Firstwap','qwerty3'),(1413,'5OTP2020-12-03 19:07:40.607.Im5ia','628171234567','IDN','EXCELCOM','Type sms here',0,'Pending',NULL,NULL,1006,99995,NULL,NULL,'Firstwap','qwerty3'),(1414,'5OTP2020-12-08 14:40:43.703.PyNoK','628171234567','IDN','EXCELCOM','Type sms here',0,'Pending',NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1415,'5OTP2020-12-08 14:59:23.631.P8r0q','628171234567','IDN','EXCELCOM','Type sms here',0,'Pending',NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1416,'5OTP2020-12-10 16:26:09.472.NFd5z','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1417,'5OTP2020-12-10 16:29:31.821.doj1A','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1418,'5OTP2020-12-10 16:30:01.99.T1bKM','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1419,'5OTP2020-12-11 08:47:48.685.JvEI7','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1420,'5OTP2020-12-11 08:49:26.388.BLgfu','628171234567','IDN','EXCELCOM','Type sms here',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1421,'5OTP2020-12-11 09:28:15.338.7esPj','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1422,'5OTP2020-12-11 09:33:38.224.7tIaH','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1424,'5OTP2020-12-11 09:34:17.415.PnM3Y','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1425,'5OTP2020-12-11 14:04:05.494.fHdMO','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1426,'5OTP2020-12-11 14:04:43.287.sUy0T','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1427,'5OTP2020-12-11 14:23:22.941.DGu7o','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1428,'5OTP2020-12-11 14:32:12.601.BK5ob','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1429,'5OTP2020-12-11 15:13:12.480.YeOz5','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1430,'5OTP2020-12-11 15:51:08.687.4XDDx','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1431,'5OTP2020-12-11 15:51:18.950.MXiF7','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1432,'5OTP2020-12-11 18:25:30.205.rmvcW','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1433,'5OTP2020-12-11 18:27:39.50.ppMOu','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1434,'5OTP2020-12-11 18:28:24.15.nDDg8','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1435,'5OTP2020-12-11 18:30:33.114.ChJfb','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1436,'5OTP2020-12-11 18:47:58.43.tkDtd','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1437,'5OTP2020-12-11 18:48:40.977.9msU8','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1438,'5OTP2020-12-11 18:50:38.149.nhx9n','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1439,'5OTP2020-12-11 18:54:26.235.L9qHd','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1440,'5OTP2020-12-11 18:54:56.283.elX5y','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1441,'5OTP2020-12-11 18:55:42.70.QLkS3','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1442,'5OTP2020-12-11 18:57:37.737.HRkbq','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1443,'5OTP2020-12-11 19:04:06.711.biIGP','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1444,'5OTP2020-12-11 19:31:01.630.YFAEy','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1445,'5OTP2020-12-11 19:33:07.984.YzFbY','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1446,'5OTP2020-12-11 19:33:56.141.iyBuZ','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1447,'5OTP2020-12-11 19:34:42.383.uZycM','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1448,'5OTP2020-12-11 19:35:02.533.2MgJp','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1449,'5OTP2020-12-11 19:36:13.466.7ySNw','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1450,'5OTP2020-12-11 19:37:39.706.okaWS','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1451,'5OTP2020-12-11 19:57:28.205.rYMqu','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1452,'5OTP2020-12-11 19:58:16.95.0GFCn','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1453,'5OTP2020-12-11 20:05:22.702.RnqjO','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1454,'5OTP2020-12-11 20:06:00.998.CnWfy','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1455,'5OTP2020-12-11 20:17:14.342.5TPTO','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1456,'5OTP2020-12-11 20:17:47.248.dRCGc','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1457,'5OTP2020-12-11 20:18:31.662.xUmAL','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1458,'5OTP2020-12-11 20:19:26.676.rAyRX','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1459,'5OTP2020-12-11 20:20:03.126.HKNPi','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1460,'5OTP2020-12-11 20:28:05.36.utClN','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1461,'5OTP2020-12-11 20:43:56.273.CcnlG','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1462,'5OTP2020-12-11 20:56:21.903.XZnjM','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1463,'5OTP2020-12-11 20:56:58.760.KxGzi','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1464,'5OTP2020-12-11 20:57:12.392.cKCrh','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1465,'5OTP2020-12-11 20:57:49.697.pI61o','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1466,'5OTP2020-12-11 20:59:53.991.c6YWl','628171234567','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1468,'5OTP2020-12-11 21:05:38.249.mG7iU','62817123451','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1469,'5OTP2020-12-11 21:08:27.698.GhPfV','62817123451','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1'),(1471,'5OTP2020-12-11 21:45:53.662.3O9tv','62817123451','IDN','EXCELCOM','sebd',0,NULL,NULL,NULL,1007,99993,NULL,NULL,'Firstwap','qwerty1');
/*!40000 ALTER TABLE `USER_MESSAGE_STATUS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VIRTUAL_NUMBER`
--

DROP TABLE IF EXISTS `VIRTUAL_NUMBER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VIRTUAL_NUMBER` (
  `VIRTUAL_NUMBER_ID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique Identifier for Virtual Number Table',
  `USER_ID` int(11) NOT NULL COMMENT 'Unique Identifier for User Table that used as reference in Virtual Number Table',
  `DESTINATION` varchar(16) NOT NULL COMMENT 'Destination number in sending messages',
  `FORWARD_URL` varchar(255) NOT NULL COMMENT 'URL to Forward',
  `URL_INVALID_COUNT` int(11) DEFAULT NULL COMMENT 'The Number of trial access Forward URL',
  `URL_ACTIVE` bit(1) DEFAULT NULL COMMENT 'To indicate Forward URL is active or not',
  `URL_LAST_RETRY` datetime DEFAULT NULL COMMENT 'The Date of last retry Forward URL  ',
  PRIMARY KEY (`VIRTUAL_NUMBER_ID`),
  KEY `USER_VIRTUAL_NUMBER_fk_1` (`USER_ID`),
  CONSTRAINT `USER_VIRTUAL_NUMBER_fk_1` FOREIGN KEY (`USER_ID`) REFERENCES `USER` (`USER_ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=995 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VIRTUAL_NUMBER`
--

LOCK TABLES `VIRTUAL_NUMBER` WRITE;
/*!40000 ALTER TABLE `VIRTUAL_NUMBER` DISABLE KEYS */;
INSERT INTO `VIRTUAL_NUMBER` VALUES (993,1,'123123213213','-',NULL,'\0',NULL),(994,4,'0888888888','123213',NULL,'',NULL);
/*!40000 ALTER TABLE `VIRTUAL_NUMBER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'SMS_API_V2_NEW'
--
/*!50003 DROP PROCEDURE IF EXISTS `substract_prepaid_credit_proc` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'IGNORE_SPACE,STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
CREATE DEFINER=`devsms`@`10.32.%` PROCEDURE `substract_prepaid_credit_proc`(
			user_id_param INT,
			credit_count INT,
			minimum_credit INT,
			operator_name VARCHAR(20),
			OUT result VARCHAR(20),
			OUT sum_credit_deduct INT)
BEGIN
				DECLARE var_credit INT(11);
				DECLARE billing_id INT(11);
				DECLARE admin_id_param INT;
				DECLARE type_billing VARCHAR(20);
				DECLARE sms_count INT(11);
				DECLARE sms_price INT(11);
				DECLARE sms_price_exist INT(11);

				DECLARE countsms INT(11);
				SET admin_id_param = 1;

				START TRANSACTION;

				SELECT  BILLING_PROFILE_ID, CREDIT, COUNTER INTO billing_id, var_credit, countsms FROM USER WHERE user_id = user_id_param;

				IF (var_credit - credit_count) < minimum_credit AND var_credit < minimum_credit THEN

					SELECT 'insufficient' INTO result;

				ELSE

					SELECT BILLING_TYPE INTO type_billing FROM BILL_PRICELIST_NEW.BILLING_PROFILE where BILLING_PROFILE_ID = billing_id;
					SET sms_count = countsms + credit_count;

					IF type_billing ='tieringoperator' THEN

						SELECT price.PER_SMS_PRICE, count(price.PER_SMS_PRICE) INTO sms_price, sms_price_exist FROM BILL_PRICELIST_NEW.BILLING_PROFILE_TIERING_OPERATOR operator
						INNER JOIN BILL_PRICELIST_NEW.BILLING_PROFILE_OPERATOR_PRICE price
							ON price.BILLING_PROFILE_TIERING_OPERATOR_ID = operator.BILLING_PROFILE_TIERING_OPERATOR_ID
						WHERE
							operator.BILLING_PROFILE_ID = billing_id
						AND
							sms_count BETWEEN operator.SMS_COUNT_FROM  and operator.SMS_COUNT_UP_TO
						AND
							price.OP_ID = operator_name;
						
						IF sms_price_exist = 0 THEN
							
							SELECT price.PER_SMS_PRICE INTO sms_price FROM BILL_PRICELIST_NEW.BILLING_PROFILE_TIERING_OPERATOR operator
							INNER JOIN BILL_PRICELIST_NEW.BILLING_PROFILE_OPERATOR_PRICE price
								ON price.BILLING_PROFILE_TIERING_OPERATOR_ID = operator.BILLING_PROFILE_TIERING_OPERATOR_ID
							WHERE
								operator.BILLING_PROFILE_ID = billing_id
							AND
								sms_count BETWEEN operator.SMS_COUNT_FROM  and operator.SMS_COUNT_UP_TO
							AND
								price.OP_ID = 'DEFAULT';
						
						END IF;

					ELSEIF type_billing ='tiering' THEN

						SELECT PER_SMS_PRICE INTO sms_price from BILL_PRICELIST_NEW.BILLING_PROFILE_TIERING
						where BILLING_PROFILE_ID = billing_id
						and sms_count BETWEEN SMS_COUNT_FROM and SMS_COUNT_UP_TO;

					ELSE

						SELECT PER_SMS_PRICE, COUNT(PER_SMS_PRICE) INTO sms_price, sms_price_exist from BILL_PRICELIST_NEW.BILLING_PROFILE_OPERATOR where BILLING_PROFILE_OPERATOR.BILLING_PROFILE_ID = billing_id and OP_ID = operator_name;
						
						IF sms_price_exist = 0 THEN
							
							SELECT PER_SMS_PRICE INTO sms_price from BILL_PRICELIST_NEW.BILLING_PROFILE_OPERATOR where BILLING_PROFILE_OPERATOR.BILLING_PROFILE_ID = billing_id and OP_ID = 'DEFAULT';
						
						END IF;
					
					END IF;

					SET var_credit = sms_price * credit_count;
					UPDATE USER  SET credit = credit - var_credit, COUNTER = sms_count, UPDATED_BY = admin_id_param, UPDATED_DATE = now() WHERE user_id = user_id_param ;
					SELECT 'sufficient' INTO result;
					SELECT sms_price into sum_credit_deduct;

				END IF ;

				COMMIT;
			END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-12-29  9:44:27
