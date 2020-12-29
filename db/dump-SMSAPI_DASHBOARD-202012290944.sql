-- MySQL dump 10.16  Distrib 10.1.47-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: 10.32.6.249    Database: SMSAPI_DASHBOARD
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
-- Table structure for table `AD_API_USER_REPORT`
--

DROP TABLE IF EXISTS `AD_API_USER_REPORT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AD_API_USER_REPORT` (
  `report_id` int(10) unsigned NOT NULL,
  `api_user_id` int(11) NOT NULL,
  KEY `ad_api_user_report_report_id_foreign` (`report_id`),
  KEY `ad_api_user_report_api_user_id_foreign` (`api_user_id`),
  CONSTRAINT `ad_api_user_report_report_id_foreign` FOREIGN KEY (`report_id`) REFERENCES `AD_REPORT` (`report_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AD_API_USER_REPORT`
--

LOCK TABLES `AD_API_USER_REPORT` WRITE;
/*!40000 ALTER TABLE `AD_API_USER_REPORT` DISABLE KEYS */;
INSERT INTO `AD_API_USER_REPORT` VALUES (159,4),(159,2),(159,1),(159,5),(160,4),(160,2),(160,1),(160,5),(181,4),(181,2),(181,1),(181,5);
/*!40000 ALTER TABLE `AD_API_USER_REPORT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AD_PRIVILEGES`
--

DROP TABLE IF EXISTS `AD_PRIVILEGES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AD_PRIVILEGES` (
  `privilege_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `privilege_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`privilege_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AD_PRIVILEGES`
--

LOCK TABLES `AD_PRIVILEGES` WRITE;
/*!40000 ALTER TABLE `AD_PRIVILEGES` DISABLE KEYS */;
INSERT INTO `AD_PRIVILEGES` VALUES (1,'user.acc.system'),(2,'user.acc.company'),(3,'user.page.read'),(4,'user.page.write'),(5,'user.page.delete'),(6,'report.acc.system'),(7,'apiuser.acc.system'),(8,'user.acc.company'),(9,'user.page.read'),(10,'user.page.write'),(11,'user.page.delete'),(12,'apiuser.acc.company'),(13,'report.acc.company'),(14,'report.acc.own'),(15,'apiuser.acc.own'),(16,'report.page.read'),(17,'report.page.download'),(18,'report.page.generate'),(19,'report.page.delete');
/*!40000 ALTER TABLE `AD_PRIVILEGES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AD_PRIVILEGE_ROLE`
--

DROP TABLE IF EXISTS `AD_PRIVILEGE_ROLE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AD_PRIVILEGE_ROLE` (
  `privilege_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  KEY `ad_privilege_role_privilege_id_foreign` (`privilege_id`),
  KEY `ad_privilege_role_role_id_foreign` (`role_id`),
  CONSTRAINT `ad_privilege_role_privilege_id_foreign` FOREIGN KEY (`privilege_id`) REFERENCES `AD_PRIVILEGES` (`privilege_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ad_privilege_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `AD_ROLES` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AD_PRIVILEGE_ROLE`
--

LOCK TABLES `AD_PRIVILEGE_ROLE` WRITE;
/*!40000 ALTER TABLE `AD_PRIVILEGE_ROLE` DISABLE KEYS */;
INSERT INTO `AD_PRIVILEGE_ROLE` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,2),(10,2),(11,2),(12,2),(13,2),(14,3),(15,3),(16,3),(17,3),(18,3),(19,3),(2,2);
/*!40000 ALTER TABLE `AD_PRIVILEGE_ROLE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AD_QUEUE_FAILED_JOB`
--

DROP TABLE IF EXISTS `AD_QUEUE_FAILED_JOB`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AD_QUEUE_FAILED_JOB` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AD_QUEUE_FAILED_JOB`
--

LOCK TABLES `AD_QUEUE_FAILED_JOB` WRITE;
/*!40000 ALTER TABLE `AD_QUEUE_FAILED_JOB` DISABLE KEYS */;
/*!40000 ALTER TABLE `AD_QUEUE_FAILED_JOB` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AD_QUEUE_JOB`
--

DROP TABLE IF EXISTS `AD_QUEUE_JOB`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AD_QUEUE_JOB` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ad_queue_job_queue_index` (`queue`(191))
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AD_QUEUE_JOB`
--

LOCK TABLES `AD_QUEUE_JOB` WRITE;
/*!40000 ALTER TABLE `AD_QUEUE_JOB` DISABLE KEYS */;
INSERT INTO `AD_QUEUE_JOB` VALUES (6,'default','{\"displayName\":\"Firstwap\\\\SmsApiDashboard\\\\Jobs\\\\GenerateReport\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"timeout\":null,\"timeoutAt\":null,\"data\":{\"commandName\":\"Firstwap\\\\SmsApiDashboard\\\\Jobs\\\\GenerateReport\",\"command\":\"O:44:\\\"Firstwap\\\\SmsApiDashboard\\\\Jobs\\\\GenerateReport\\\":8:{s:9:\\\"generator\\\";O:58:\\\"Firstwap\\\\SmsApiDashboard\\\\Libraries\\\\Report\\\\WriterReportFile\\\":7:{s:9:\\\"\\u0000*\\u0000writer\\\";N;s:8:\\\"\\u0000*\\u0000model\\\";O:40:\\\"Firstwap\\\\SmsApiDashboard\\\\Entities\\\\Report\\\":26:{s:13:\\\"\\u0000*\\u0000connection\\\";s:13:\\\"api_dashboard\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:9:\\\"AD_REPORT\\\";s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:9:\\\"report_id\\\";s:11:\\\"\\u0000*\\u0000fillable\\\";a:9:{i:0;s:11:\\\"report_name\\\";i:1;s:10:\\\"start_date\\\";i:2;s:8:\\\"end_date\\\";i:3;s:14:\\\"message_status\\\";i:4;s:9:\\\"file_type\\\";i:5;s:10:\\\"created_by\\\";i:6;s:15:\\\"generate_status\\\";i:7;s:10:\\\"percentage\\\";i:8;s:3:\\\"pid\\\";}s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:13:\\\"\\u0000*\\u0000attributes\\\";a:11:{s:11:\\\"report_name\\\";s:26:\\\"Report_API_11112020_212352\\\";s:9:\\\"file_type\\\";s:4:\\\"xlsx\\\";s:10:\\\"start_date\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-08-12 17:00:00.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:8:\\\"end_date\\\";O:25:\\\"Illuminate\\\\Support\\\\Carbon\\\":3:{s:4:\\\"date\\\";s:26:\\\"2020-11-11 16:59:59.000000\\\";s:13:\\\"timezone_type\\\";i:3;s:8:\\\"timezone\\\";s:3:\\\"UTC\\\";}s:14:\\\"message_status\\\";s:35:\\\"sent,delivered,undelivered,rejected\\\";s:10:\\\"created_by\\\";i:72;s:10:\\\"updated_at\\\";s:19:\\\"2020-11-11 14:23:52\\\";s:10:\\\"created_at\\\";s:19:\\\"2020-11-11 14:23:52\\\";s:9:\\\"report_id\\\";i:163;s:15:\\\"generate_status\\\";i:0;s:10:\\\"percentage\\\";i:0;}s:11:\\\"\\u0000*\\u0000original\\\";a:11:{s:11:\\\"report_name\\\";s:26:\\\"Report_API_11112020_212352\\\";s:9:\\\"file_type\\\";s:4:\\\"xlsx\\\";s:10:\\\"start_date\\\";r:28;s:8:\\\"end_date\\\";r:32;s:14:\\\"message_status\\\";s:35:\\\"sent,delivered,undelivered,rejected\\\";s:10:\\\"created_by\\\";i:72;s:10:\\\"updated_at\\\";s:19:\\\"2020-11-11 14:23:52\\\";s:10:\\\"created_at\\\";s:19:\\\"2020-11-11 14:23:52\\\";s:9:\\\"report_id\\\";i:163;s:15:\\\"generate_status\\\";i:0;s:10:\\\"percentage\\\";i:0;}s:10:\\\"\\u0000*\\u0000changes\\\";a:2:{s:15:\\\"generate_status\\\";i:0;s:10:\\\"percentage\\\";i:0;}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:1:{s:8:\\\"apiUsers\\\";O:39:\\\"Illuminate\\\\Database\\\\Eloquent\\\\Collection\\\":1:{s:8:\\\"\\u0000*\\u0000items\\\";a:4:{i:0;O:41:\\\"Firstwap\\\\SmsApiDashboard\\\\Entities\\\\ApiUser\\\":26:{s:13:\\\"\\u0000*\\u0000connection\\\";s:13:\\\"api_dashboard\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:4:\\\"USER\\\";s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:7:\\\"user_id\\\";s:10:\\\"timestamps\\\";b:0;s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:27:{s:7:\\\"user_id\\\";i:1;s:7:\\\"version\\\";i:0;s:9:\\\"user_name\\\";s:5:\\\"dycas\\\";s:8:\\\"password\\\";s:7:\\\"1rstwap\\\";s:6:\\\"active\\\";i:1;s:7:\\\"counter\\\";i:0;s:6:\\\"credit\\\";i:0;s:11:\\\"last_access\\\";N;s:12:\\\"created_date\\\";s:19:\\\"2020-11-09 14:19:22\\\";s:12:\\\"updated_date\\\";s:19:\\\"2020-11-10 10:26:05\\\";s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_by\\\";i:1;s:12:\\\"cobrander_id\\\";s:5:\\\"dycas\\\";s:9:\\\"client_id\\\";i:1;s:19:\\\"delivery_status_url\\\";N;s:17:\\\"url_invalid_count\\\";N;s:10:\\\"url_active\\\";i:0;s:14:\\\"url_last_retry\\\";N;s:13:\\\"use_blacklist\\\";i:0;s:11:\\\"is_postpaid\\\";i:0;s:9:\\\"try_count\\\";i:0;s:15:\\\"inactive_reason\\\";N;s:12:\\\"datetime_try\\\";N;s:18:\\\"billing_profile_id\\\";i:7;s:23:\\\"billing_report_group_id\\\";N;s:24:\\\"billing_tiering_group_id\\\";N;s:6:\\\"is_ojk\\\";i:0;}s:11:\\\"\\u0000*\\u0000original\\\";a:29:{s:7:\\\"user_id\\\";i:1;s:7:\\\"version\\\";i:0;s:9:\\\"user_name\\\";s:5:\\\"dycas\\\";s:8:\\\"password\\\";s:7:\\\"1rstwap\\\";s:6:\\\"active\\\";i:1;s:7:\\\"counter\\\";i:0;s:6:\\\"credit\\\";i:0;s:11:\\\"last_access\\\";N;s:12:\\\"created_date\\\";s:19:\\\"2020-11-09 14:19:22\\\";s:12:\\\"updated_date\\\";s:19:\\\"2020-11-10 10:26:05\\\";s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_by\\\";i:1;s:12:\\\"cobrander_id\\\";s:5:\\\"dycas\\\";s:9:\\\"client_id\\\";i:1;s:19:\\\"delivery_status_url\\\";N;s:17:\\\"url_invalid_count\\\";N;s:10:\\\"url_active\\\";i:0;s:14:\\\"url_last_retry\\\";N;s:13:\\\"use_blacklist\\\";i:0;s:11:\\\"is_postpaid\\\";i:0;s:9:\\\"try_count\\\";i:0;s:15:\\\"inactive_reason\\\";N;s:12:\\\"datetime_try\\\";N;s:18:\\\"billing_profile_id\\\";i:7;s:23:\\\"billing_report_group_id\\\";N;s:24:\\\"billing_tiering_group_id\\\";N;s:6:\\\"is_ojk\\\";i:0;s:15:\\\"pivot_report_id\\\";i:163;s:17:\\\"pivot_api_user_id\\\";i:1;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:1:{s:5:\\\"pivot\\\";O:44:\\\"Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\Pivot\\\":29:{s:11:\\\"pivotParent\\\";r:4;s:13:\\\"\\u0000*\\u0000foreignKey\\\";s:9:\\\"report_id\\\";s:13:\\\"\\u0000*\\u0000relatedKey\\\";s:11:\\\"api_user_id\\\";s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}s:13:\\\"\\u0000*\\u0000connection\\\";s:13:\\\"api_dashboard\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:35:\\\"SMSAPI_DASHBOARD.AD_API_USER_REPORT\\\";s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:2:{s:9:\\\"report_id\\\";i:163;s:11:\\\"api_user_id\\\";i:1;}s:11:\\\"\\u0000*\\u0000original\\\";a:2:{s:9:\\\"report_id\\\";i:163;s:11:\\\"api_user_id\\\";i:1;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:0;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}}}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}s:10:\\\"\\u0000*\\u0000guarded\\\";a:1:{i:0;s:1:\\\"*\\\";}}i:1;O:41:\\\"Firstwap\\\\SmsApiDashboard\\\\Entities\\\\ApiUser\\\":26:{s:13:\\\"\\u0000*\\u0000connection\\\";s:13:\\\"api_dashboard\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:4:\\\"USER\\\";s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:7:\\\"user_id\\\";s:10:\\\"timestamps\\\";b:0;s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:27:{s:7:\\\"user_id\\\";i:2;s:7:\\\"version\\\";i:0;s:9:\\\"user_name\\\";s:3:\\\"Dya\\\";s:8:\\\"password\\\";s:3:\\\"dya\\\";s:6:\\\"active\\\";i:0;s:7:\\\"counter\\\";i:0;s:6:\\\"credit\\\";i:132;s:11:\\\"last_access\\\";N;s:12:\\\"created_date\\\";s:19:\\\"2020-11-10 10:19:35\\\";s:12:\\\"updated_date\\\";s:19:\\\"2020-11-10 10:24:06\\\";s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_by\\\";i:1;s:12:\\\"cobrander_id\\\";s:3:\\\"Dya\\\";s:9:\\\"client_id\\\";i:1;s:19:\\\"delivery_status_url\\\";N;s:17:\\\"url_invalid_count\\\";N;s:10:\\\"url_active\\\";i:0;s:14:\\\"url_last_retry\\\";N;s:13:\\\"use_blacklist\\\";i:0;s:11:\\\"is_postpaid\\\";i:0;s:9:\\\"try_count\\\";i:0;s:15:\\\"inactive_reason\\\";N;s:12:\\\"datetime_try\\\";N;s:18:\\\"billing_profile_id\\\";i:6;s:23:\\\"billing_report_group_id\\\";N;s:24:\\\"billing_tiering_group_id\\\";N;s:6:\\\"is_ojk\\\";i:0;}s:11:\\\"\\u0000*\\u0000original\\\";a:29:{s:7:\\\"user_id\\\";i:2;s:7:\\\"version\\\";i:0;s:9:\\\"user_name\\\";s:3:\\\"Dya\\\";s:8:\\\"password\\\";s:3:\\\"dya\\\";s:6:\\\"active\\\";i:0;s:7:\\\"counter\\\";i:0;s:6:\\\"credit\\\";i:132;s:11:\\\"last_access\\\";N;s:12:\\\"created_date\\\";s:19:\\\"2020-11-10 10:19:35\\\";s:12:\\\"updated_date\\\";s:19:\\\"2020-11-10 10:24:06\\\";s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_by\\\";i:1;s:12:\\\"cobrander_id\\\";s:3:\\\"Dya\\\";s:9:\\\"client_id\\\";i:1;s:19:\\\"delivery_status_url\\\";N;s:17:\\\"url_invalid_count\\\";N;s:10:\\\"url_active\\\";i:0;s:14:\\\"url_last_retry\\\";N;s:13:\\\"use_blacklist\\\";i:0;s:11:\\\"is_postpaid\\\";i:0;s:9:\\\"try_count\\\";i:0;s:15:\\\"inactive_reason\\\";N;s:12:\\\"datetime_try\\\";N;s:18:\\\"billing_profile_id\\\";i:6;s:23:\\\"billing_report_group_id\\\";N;s:24:\\\"billing_tiering_group_id\\\";N;s:6:\\\"is_ojk\\\";i:0;s:15:\\\"pivot_report_id\\\";i:163;s:17:\\\"pivot_api_user_id\\\";i:2;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:1:{s:5:\\\"pivot\\\";O:44:\\\"Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\Pivot\\\":29:{s:11:\\\"pivotParent\\\";r:4;s:13:\\\"\\u0000*\\u0000foreignKey\\\";s:9:\\\"report_id\\\";s:13:\\\"\\u0000*\\u0000relatedKey\\\";s:11:\\\"api_user_id\\\";s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}s:13:\\\"\\u0000*\\u0000connection\\\";s:13:\\\"api_dashboard\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:35:\\\"SMSAPI_DASHBOARD.AD_API_USER_REPORT\\\";s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:2:{s:9:\\\"report_id\\\";i:163;s:11:\\\"api_user_id\\\";i:2;}s:11:\\\"\\u0000*\\u0000original\\\";a:2:{s:9:\\\"report_id\\\";i:163;s:11:\\\"api_user_id\\\";i:2;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:0;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}}}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}s:10:\\\"\\u0000*\\u0000guarded\\\";a:1:{i:0;s:1:\\\"*\\\";}}i:2;O:41:\\\"Firstwap\\\\SmsApiDashboard\\\\Entities\\\\ApiUser\\\":26:{s:13:\\\"\\u0000*\\u0000connection\\\";s:13:\\\"api_dashboard\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:4:\\\"USER\\\";s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:7:\\\"user_id\\\";s:10:\\\"timestamps\\\";b:0;s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:27:{s:7:\\\"user_id\\\";i:4;s:7:\\\"version\\\";i:0;s:9:\\\"user_name\\\";s:4:\\\"1111\\\";s:8:\\\"password\\\";s:3:\\\"111\\\";s:6:\\\"active\\\";i:1;s:7:\\\"counter\\\";i:0;s:6:\\\"credit\\\";i:0;s:11:\\\"last_access\\\";N;s:12:\\\"created_date\\\";s:19:\\\"2020-11-10 19:21:38\\\";s:12:\\\"updated_date\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_by\\\";N;s:12:\\\"cobrander_id\\\";s:4:\\\"1111\\\";s:9:\\\"client_id\\\";i:1;s:19:\\\"delivery_status_url\\\";N;s:17:\\\"url_invalid_count\\\";N;s:10:\\\"url_active\\\";i:0;s:14:\\\"url_last_retry\\\";N;s:13:\\\"use_blacklist\\\";i:0;s:11:\\\"is_postpaid\\\";i:0;s:9:\\\"try_count\\\";i:0;s:15:\\\"inactive_reason\\\";N;s:12:\\\"datetime_try\\\";N;s:18:\\\"billing_profile_id\\\";N;s:23:\\\"billing_report_group_id\\\";N;s:24:\\\"billing_tiering_group_id\\\";N;s:6:\\\"is_ojk\\\";i:0;}s:11:\\\"\\u0000*\\u0000original\\\";a:29:{s:7:\\\"user_id\\\";i:4;s:7:\\\"version\\\";i:0;s:9:\\\"user_name\\\";s:4:\\\"1111\\\";s:8:\\\"password\\\";s:3:\\\"111\\\";s:6:\\\"active\\\";i:1;s:7:\\\"counter\\\";i:0;s:6:\\\"credit\\\";i:0;s:11:\\\"last_access\\\";N;s:12:\\\"created_date\\\";s:19:\\\"2020-11-10 19:21:38\\\";s:12:\\\"updated_date\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_by\\\";N;s:12:\\\"cobrander_id\\\";s:4:\\\"1111\\\";s:9:\\\"client_id\\\";i:1;s:19:\\\"delivery_status_url\\\";N;s:17:\\\"url_invalid_count\\\";N;s:10:\\\"url_active\\\";i:0;s:14:\\\"url_last_retry\\\";N;s:13:\\\"use_blacklist\\\";i:0;s:11:\\\"is_postpaid\\\";i:0;s:9:\\\"try_count\\\";i:0;s:15:\\\"inactive_reason\\\";N;s:12:\\\"datetime_try\\\";N;s:18:\\\"billing_profile_id\\\";N;s:23:\\\"billing_report_group_id\\\";N;s:24:\\\"billing_tiering_group_id\\\";N;s:6:\\\"is_ojk\\\";i:0;s:15:\\\"pivot_report_id\\\";i:163;s:17:\\\"pivot_api_user_id\\\";i:4;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:1:{s:5:\\\"pivot\\\";O:44:\\\"Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\Pivot\\\":29:{s:11:\\\"pivotParent\\\";r:4;s:13:\\\"\\u0000*\\u0000foreignKey\\\";s:9:\\\"report_id\\\";s:13:\\\"\\u0000*\\u0000relatedKey\\\";s:11:\\\"api_user_id\\\";s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}s:13:\\\"\\u0000*\\u0000connection\\\";s:13:\\\"api_dashboard\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:35:\\\"SMSAPI_DASHBOARD.AD_API_USER_REPORT\\\";s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:2:{s:9:\\\"report_id\\\";i:163;s:11:\\\"api_user_id\\\";i:4;}s:11:\\\"\\u0000*\\u0000original\\\";a:2:{s:9:\\\"report_id\\\";i:163;s:11:\\\"api_user_id\\\";i:4;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:0;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}}}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}s:10:\\\"\\u0000*\\u0000guarded\\\";a:1:{i:0;s:1:\\\"*\\\";}}i:3;O:41:\\\"Firstwap\\\\SmsApiDashboard\\\\Entities\\\\ApiUser\\\":26:{s:13:\\\"\\u0000*\\u0000connection\\\";s:13:\\\"api_dashboard\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:4:\\\"USER\\\";s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:7:\\\"user_id\\\";s:10:\\\"timestamps\\\";b:0;s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:27:{s:7:\\\"user_id\\\";i:5;s:7:\\\"version\\\";i:0;s:9:\\\"user_name\\\";s:2:\\\"wd\\\";s:8:\\\"password\\\";s:6:\\\"dasdsa\\\";s:6:\\\"active\\\";i:1;s:7:\\\"counter\\\";i:0;s:6:\\\"credit\\\";i:0;s:11:\\\"last_access\\\";N;s:12:\\\"created_date\\\";s:19:\\\"2020-11-10 19:34:28\\\";s:12:\\\"updated_date\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_by\\\";N;s:12:\\\"cobrander_id\\\";s:2:\\\"wd\\\";s:9:\\\"client_id\\\";i:1;s:19:\\\"delivery_status_url\\\";N;s:17:\\\"url_invalid_count\\\";N;s:10:\\\"url_active\\\";i:0;s:14:\\\"url_last_retry\\\";N;s:13:\\\"use_blacklist\\\";i:0;s:11:\\\"is_postpaid\\\";i:0;s:9:\\\"try_count\\\";i:0;s:15:\\\"inactive_reason\\\";N;s:12:\\\"datetime_try\\\";N;s:18:\\\"billing_profile_id\\\";N;s:23:\\\"billing_report_group_id\\\";N;s:24:\\\"billing_tiering_group_id\\\";N;s:6:\\\"is_ojk\\\";i:0;}s:11:\\\"\\u0000*\\u0000original\\\";a:29:{s:7:\\\"user_id\\\";i:5;s:7:\\\"version\\\";i:0;s:9:\\\"user_name\\\";s:2:\\\"wd\\\";s:8:\\\"password\\\";s:6:\\\"dasdsa\\\";s:6:\\\"active\\\";i:1;s:7:\\\"counter\\\";i:0;s:6:\\\"credit\\\";i:0;s:11:\\\"last_access\\\";N;s:12:\\\"created_date\\\";s:19:\\\"2020-11-10 19:34:28\\\";s:12:\\\"updated_date\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_by\\\";N;s:12:\\\"cobrander_id\\\";s:2:\\\"wd\\\";s:9:\\\"client_id\\\";i:1;s:19:\\\"delivery_status_url\\\";N;s:17:\\\"url_invalid_count\\\";N;s:10:\\\"url_active\\\";i:0;s:14:\\\"url_last_retry\\\";N;s:13:\\\"use_blacklist\\\";i:0;s:11:\\\"is_postpaid\\\";i:0;s:9:\\\"try_count\\\";i:0;s:15:\\\"inactive_reason\\\";N;s:12:\\\"datetime_try\\\";N;s:18:\\\"billing_profile_id\\\";N;s:23:\\\"billing_report_group_id\\\";N;s:24:\\\"billing_tiering_group_id\\\";N;s:6:\\\"is_ojk\\\";i:0;s:15:\\\"pivot_report_id\\\";i:163;s:17:\\\"pivot_api_user_id\\\";i:5;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:1:{s:5:\\\"pivot\\\";O:44:\\\"Illuminate\\\\Database\\\\Eloquent\\\\Relations\\\\Pivot\\\":29:{s:11:\\\"pivotParent\\\";r:4;s:13:\\\"\\u0000*\\u0000foreignKey\\\";s:9:\\\"report_id\\\";s:13:\\\"\\u0000*\\u0000relatedKey\\\";s:11:\\\"api_user_id\\\";s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}s:13:\\\"\\u0000*\\u0000connection\\\";s:13:\\\"api_dashboard\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:35:\\\"SMSAPI_DASHBOARD.AD_API_USER_REPORT\\\";s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:2:{s:9:\\\"report_id\\\";i:163;s:11:\\\"api_user_id\\\";i:5;}s:11:\\\"\\u0000*\\u0000original\\\";a:2:{s:9:\\\"report_id\\\";i:163;s:11:\\\"api_user_id\\\";i:5;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:0;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}}}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}s:10:\\\"\\u0000*\\u0000guarded\\\";a:1:{i:0;s:1:\\\"*\\\";}}}}}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:1;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:10:\\\"\\u0000*\\u0000guarded\\\";a:1:{i:0;s:1:\\\"*\\\";}}s:17:\\\"\\u0000*\\u0000messageBuilder\\\";N;s:7:\\\"\\u0000*\\u0000keys\\\";N;s:11:\\\"\\u0000*\\u0000timezone\\\";s:2:\\\"+7\\\";s:11:\\\"\\u0000*\\u0000fileName\\\";s:32:\\\"22f65bb2932416166884dfad5f40d293\\\";s:14:\\\"\\u0000*\\u0000reportFiles\\\";a:0:{}}s:6:\\\"\\u0000*\\u0000job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:5:\\\"delay\\\";N;s:7:\\\"chained\\\";a:0:{}}\"}}',1,1605104632,1605104632,1605104632);
/*!40000 ALTER TABLE `AD_QUEUE_JOB` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AD_REPORT`
--

DROP TABLE IF EXISTS `AD_REPORT`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AD_REPORT` (
  `report_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `report_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `generated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) unsigned NOT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `message_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generate_status` int(11) NOT NULL DEFAULT 0,
  `pid` int(11) NOT NULL DEFAULT 0,
  `file_type` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'csv',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `percentage` decimal(3,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`report_id`),
  KEY `ad_report_created_by_index` (`created_by`)
) ENGINE=InnoDB AUTO_INCREMENT=182 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AD_REPORT`
--

LOCK TABLES `AD_REPORT` WRITE;
/*!40000 ALTER TABLE `AD_REPORT` DISABLE KEYS */;
INSERT INTO `AD_REPORT` VALUES (159,'tes123','2020-11-11 13:37:25','2020-11-11 13:37:25','2020-11-11 20:37:25',69,'2020-08-12 17:00:00','2020-11-11 16:59:59','sent,delivered,undelivered,rejected',2,0,'xlsx','4bc0d619062362119801d345f61b5dca.zip',0.00),(160,'tes23','2020-11-11 13:53:19','2020-11-11 13:53:20','2020-11-11 20:53:20',72,'2020-08-12 17:00:00','2020-11-11 16:59:59','sent,delivered,undelivered,rejected',2,0,'xlsx','3fa565412d9c4e78a93f3eede7f93e12.zip',0.00),(181,'Report_API_12112020_163550','2020-11-12 09:35:50','2020-11-12 09:35:52','2020-11-12 16:35:52',1,'2020-08-13 17:00:00','2020-11-12 16:59:59','sent,delivered,undelivered,rejected',2,0,'xlsx','a1684c3fd74661d9ae3517b8035f17e4.zip',0.00);
/*!40000 ALTER TABLE `AD_REPORT` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AD_ROLES`
--

DROP TABLE IF EXISTS `AD_ROLES`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AD_ROLES` (
  `role_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AD_ROLES`
--

LOCK TABLES `AD_ROLES` WRITE;
/*!40000 ALTER TABLE `AD_ROLES` DISABLE KEYS */;
INSERT INTO `AD_ROLES` VALUES (1,'Super Admin'),(2,'Admin'),(3,'Report');
/*!40000 ALTER TABLE `AD_ROLES` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AD_ROLE_USER`
--

DROP TABLE IF EXISTS `AD_ROLE_USER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AD_ROLE_USER` (
  `role_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  KEY `ad_role_user_role_id_foreign` (`role_id`),
  KEY `ad_role_user_user_id_foreign` (`user_id`),
  CONSTRAINT `ad_role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `AD_ROLES` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ad_role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `AD_USER` (`ad_user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AD_ROLE_USER`
--

LOCK TABLES `AD_ROLE_USER` WRITE;
/*!40000 ALTER TABLE `AD_ROLE_USER` DISABLE KEYS */;
INSERT INTO `AD_ROLE_USER` VALUES (1,1),(3,1),(3,69),(2,70),(3,71),(2,72),(3,72),(2,73),(3,73),(1,74),(2,75),(1,76),(2,77),(1,78),(1,79),(2,80),(1,80),(1,81),(1,82);
/*!40000 ALTER TABLE `AD_ROLE_USER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AD_USER`
--

DROP TABLE IF EXISTS `AD_USER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AD_USER` (
  `ad_user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `password` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expired_token` timestamp NULL DEFAULT NULL,
  `forget_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`ad_user_id`),
  UNIQUE KEY `ad_user_email_unique` (`email`),
  KEY `ad_user_client_id_foreign` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=83 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AD_USER`
--

LOCK TABLES `AD_USER` WRITE;
/*!40000 ALTER TABLE `AD_USER` DISABLE KEYS */;
INSERT INTO `AD_USER` VALUES (1,'Super Admin',NULL,'super.admin@1rstwap.com',1,1,'$2y$10$vPRF.qnQHw32frC1wdO6oOxViS/uNtxESRN.FSppAvTubkruINol2','2020-12-08 11:47:12','2020-07-02 15:46:39','$2y$10$ldhOmAQnfkBZE39wv/kRjeC8/nEpUxq3FcnS1zPYfy3unEAHfgSVu','i0jOCagF5Prypkk33ioVKXy7KFIssHArUgV9n68Kh9nZrp4kbbQ2zSJdrRXH','2017-12-11 23:56:49','2020-11-11 11:55:47',1,NULL),(69,'report1','avatars/cRvi7vnLIdL1fSHGUCW8BGCwrkNz4sSzhGUULAYO.png','report1@1rstwap.com',1,1,'$2y$10$P3rei2G7aOFwZUOU2qDrWe99kSzlkegTyGpSrtSvFYBnPcbPzZNHG','2020-12-08 11:36:03',NULL,NULL,'FqYGjfS1qYETDjRRAHWEaG7IP9MK9fOzlLR37F0KJGn9arEydqaBqjbPbotH','2020-11-11 11:55:21','2020-12-08 11:36:03',1,'2020-12-08 11:36:03'),(70,'admin',NULL,'admin@gmail.com',99992,1,'$2y$10$IKmfjAcx0ZKOicVodhecROeqn2R9CZkftBt7xZQCYiVjzkEC38oeG','2020-12-08 11:32:38',NULL,NULL,'j0cy09bhuDFWxuFhJKZQPGgboHHiOOT27PRSV9VhotRX35f3AEiLIMiT7kjS','2020-11-11 13:40:26','2020-12-08 11:32:38',1,'2020-12-08 11:32:38'),(71,'admin2',NULL,'admin2@gmail.com',99992,70,'$2y$10$MzmxvDiRQIouRIvbAsewNuZ99DA9p3U6u5VFRYIBWfTU2UOg1u0LG','2020-12-08 11:17:05',NULL,NULL,NULL,'2020-11-11 13:42:16','2020-12-08 11:17:05',1,'2020-12-08 11:17:05'),(72,'adminreport',NULL,'adminreport@gmail.com',1,1,'$2y$10$v5sL5/ZwSn1/F3nAQoZtF.fgLypuKKTU5MtfJ/tmHccnYtwB8DBDW','2020-12-08 11:16:54',NULL,NULL,'QvKbqXkdbT5OtHZMkEtdiJNx9DpAuNWjP4DHM7tFqPxHA2f8gF35jykhFA6X','2020-11-11 13:43:55','2020-12-08 11:16:54',1,'2020-12-08 11:16:54'),(73,'farras',NULL,'report12@1rstwap.com',1,1,'$2y$10$sgauuDqzgrzcSVIDgiluRurWEduzij.edkHMplH7dZaT3ZtzSnkdq','2020-12-08 11:32:04',NULL,NULL,NULL,'2020-12-08 11:17:39','2020-12-08 11:32:04',1,'2020-12-08 11:32:04'),(74,'tes',NULL,'report21@1rstwap.com',1,1,'$2y$10$ERPk1B6Vf9U0T6TjBS4x9OXOgiapLnnsCENdufk6YmTudZeGM5nta','2020-12-08 11:44:48',NULL,NULL,NULL,'2020-12-08 11:38:08','2020-12-08 11:44:48',1,'2020-12-08 11:44:48'),(75,'User baru',NULL,'baru@1rstwap.com',1,1,'$2y$10$3iGsInrOtQkxK0lt2VKBwehEPi3U9663.qAeenRgxwsuXU5miB6py','2020-12-08 12:13:07',NULL,NULL,NULL,'2020-12-08 11:52:17','2020-12-08 12:13:07',0,'2020-12-08 12:13:07'),(76,'farras',NULL,'report1223@1rstwap.com',1,1,'$2y$10$5oOVBfK/PIZEAlWp.eLjZuAZK7FXiI6Se6Uc5M2Q93ylZLh5MjaNS','2020-12-08 12:14:39',NULL,NULL,NULL,'2020-12-08 12:14:32','2020-12-08 12:14:39',0,'2020-12-08 12:14:39'),(77,'s213',NULL,'report1123213@1rstwap.com',1,1,'$2y$10$nR169fUNc1ZZeB0PwIca9.Z1cfJIAtdi8Ub9feg42urcGXSNvFQqO','2020-12-08 13:24:11',NULL,NULL,NULL,'2020-12-08 13:23:57','2020-12-08 13:24:11',1,'2020-12-08 13:24:11'),(78,'farras',NULL,'23232@1rstwap.com',99997,1,'$2y$10$JuV5AGorADdY2H4D894RQONjdZtDDlJ1ZOO1W3TI5O3FLe2eFc0TG','2020-12-08 13:25:47',NULL,NULL,NULL,'2020-12-08 13:25:42','2020-12-08 13:25:47',1,'2020-12-08 13:25:47'),(79,'aaaaa',NULL,'aaa@gmail.com',99997,1,'$2y$10$DDCr1uOtmxnWHBXS19u28uLz8Y2357mldSg2HI0eSxEEDb6n72MdW','2020-12-08 13:26:56',NULL,NULL,NULL,'2020-12-08 13:26:50','2020-12-08 13:26:56',0,'2020-12-08 13:26:56'),(80,'farras',NULL,'report112223213@1rstwap.com',1,1,'$2y$10$TCYHlsTEDUetqjeIVyEvROymvpDvSobTpwbce2CGwsLz.MBqimXh6','2020-12-08 13:27:55',NULL,NULL,NULL,'2020-12-08 13:27:51','2020-12-08 13:27:55',0,'2020-12-08 13:27:55'),(81,'name_test2',NULL,'report1tes@1rstwap.com',1,1,'$2y$10$5q0Phx1WZAfZIS9hdcA1h.LAWQH0XpxLVJgif7GaIzl.N5PHKywUK','2020-12-08 13:56:49',NULL,NULL,NULL,'2020-12-08 13:55:57','2020-12-08 13:56:49',0,'2020-12-08 13:56:49'),(82,'yryr',NULL,'yryr@1rstwap.com',99997,1,'$2y$10$8.x4DT3JquibiDRcRWFQpe5m7yw63/qe0y7nrmLqK44f6ihO3zYmO','2020-12-08 13:56:52',NULL,NULL,NULL,'2020-12-08 13:56:44','2020-12-08 13:56:52',1,'2020-12-08 13:56:52');
/*!40000 ALTER TABLE `AD_USER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `AD_USER_APIUSER`
--

DROP TABLE IF EXISTS `AD_USER_APIUSER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AD_USER_APIUSER` (
  `ad_user_id` int(10) unsigned NOT NULL,
  `api_user_id` int(11) NOT NULL,
  KEY `ad_user_apiuser_ad_user_id_foreign` (`ad_user_id`),
  KEY `ad_user_apiuser_api_user_id_foreign` (`api_user_id`),
  CONSTRAINT `ad_user_apiuser_ad_user_id_foreign` FOREIGN KEY (`ad_user_id`) REFERENCES `AD_USER` (`ad_user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AD_USER_APIUSER`
--

LOCK TABLES `AD_USER_APIUSER` WRITE;
/*!40000 ALTER TABLE `AD_USER_APIUSER` DISABLE KEYS */;
INSERT INTO `AD_USER_APIUSER` VALUES (1,1),(69,4),(69,2),(69,1),(69,5),(70,3),(71,3),(72,4),(72,2),(72,1),(72,5),(73,4),(73,2),(74,4),(74,2),(74,1),(75,4),(75,2),(75,1),(76,2),(76,1),(77,4),(77,2),(78,3),(78,99996),(78,99997),(79,3),(79,99996),(80,4),(80,2),(80,1),(81,4),(81,2),(82,99996);
/*!40000 ALTER TABLE `AD_USER_APIUSER` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'SMSAPI_DASHBOARD'
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
