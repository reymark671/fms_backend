-- MySQL dump 10.13  Distrib 8.0.37, for Linux (x86_64)
--
-- Host: localhost    Database: fms_db
-- ------------------------------------------------------
-- Server version	8.0.37-0ubuntu0.22.04.3

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `client_file_uploads`
--

DROP TABLE IF EXISTS `client_file_uploads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_file_uploads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_file_uploads`
--

LOCK TABLES `client_file_uploads` WRITE;
/*!40000 ALTER TABLE `client_file_uploads` DISABLE KEYS */;
INSERT INTO `client_file_uploads` VALUES (1,'test resource','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/client_files/1719919264_client_spending_plan_1%20%282%29.pdf',6,'2024-07-02 11:21:04','2024-07-02 11:21:04',NULL),(2,'test resource','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/client_files/1719919776_client_spending_plan_6%20%2811%29.pdf',6,'2024-07-02 11:29:36','2024-07-02 11:29:36',NULL),(3,'For W4 Form filled up already','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/client_files/1719983673_Form%20w4%20%281%29.pdf',6,'2024-07-03 05:14:33','2024-07-03 05:14:33',NULL),(4,'vendor guide payment','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/client_files/1720247059_Vendor%20Schedule%20payment-0k.pdf',12,'2024-07-05 23:24:19','2024-07-05 23:24:19',NULL);
/*!40000 ALTER TABLE `client_file_uploads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_spending_plan_items`
--

DROP TABLE IF EXISTS `client_spending_plan_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_spending_plan_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_spending_plan_id` bigint unsigned NOT NULL,
  `service_code_id` bigint unsigned NOT NULL,
  `allocated_budget` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_spending_plan_items_client_spending_plan_id_foreign` (`client_spending_plan_id`),
  KEY `client_spending_plan_items_service_code_id_foreign` (`service_code_id`),
  CONSTRAINT `client_spending_plan_items_client_spending_plan_id_foreign` FOREIGN KEY (`client_spending_plan_id`) REFERENCES `client_spending_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `client_spending_plan_items_service_code_id_foreign` FOREIGN KEY (`service_code_id`) REFERENCES `service_codes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_spending_plan_items`
--

LOCK TABLES `client_spending_plan_items` WRITE;
/*!40000 ALTER TABLE `client_spending_plan_items` DISABLE KEYS */;
INSERT INTO `client_spending_plan_items` VALUES (1,1,1,4000.00,'2024-07-03 04:49:58','2024-07-03 04:49:58'),(2,1,2,22500.00,'2024-07-03 04:49:58','2024-07-03 04:49:58'),(3,1,3,660.00,'2024-07-03 04:49:58','2024-07-03 04:49:58'),(4,1,4,600.00,'2024-07-03 04:49:58','2024-07-03 04:49:58'),(5,1,5,6000.00,'2024-07-03 04:49:58','2024-07-03 04:49:58'),(6,1,6,6240.00,'2024-07-03 04:49:58','2024-07-03 04:49:58'),(7,2,1,4000.00,'2024-07-05 23:43:02','2024-07-05 23:43:02');
/*!40000 ALTER TABLE `client_spending_plan_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `client_spending_plans`
--

DROP TABLE IF EXISTS `client_spending_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `client_spending_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `total_budget` decimal(15,2) NOT NULL,
  `from` date NOT NULL,
  `to` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_spending_plans_client_id_foreign` (`client_id`),
  CONSTRAINT `client_spending_plans_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `client_spending_plans`
--

LOCK TABLES `client_spending_plans` WRITE;
/*!40000 ALTER TABLE `client_spending_plans` DISABLE KEYS */;
INSERT INTO `client_spending_plans` VALUES (1,6,40000.00,'2024-01-01','2024-12-31','2024-07-03 04:49:58','2024-07-03 04:49:58'),(2,12,40000.00,'2024-07-04','2024-12-31','2024-07-05 23:43:02','2024-07-05 23:43:02');
/*!40000 ALTER TABLE `client_spending_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ss_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `api_token` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_ss_number_unique` (`ss_number`),
  UNIQUE KEY `clients_email_unique` (`email`),
  KEY `clients_full_index` (`first_name`,`last_name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (6,'rey mark','egot','999999999','test address','test address','test address','14443','1231231231','poknaitz@gmail.com','729357',NULL,'$2y$12$x0oHVWrq4NN5jJTezxnP9eyAIF.ehAIHp/a3GInlHYd5jykcjZ8he','2024-06-24 04:45:59','2024-07-15 04:25:58',1,'w6aZ3q43Yx54ZaXVFs6VCreOxLZqP1K6oyZh62hiNHEnGYvnNneUWmqXBfb0'),(7,'Jennifer','Lee','608400032','1815 1/2 S Gramercy Pl','Los Angeles','CA','90019','2132734411','jencsun0505@gmail.com','975676',NULL,'$2y$12$QErzYu7FjHyhg/rpSimzIu.0Bnat/de9JjlUtWArENJu2X.4c/1Qe','2024-06-24 05:16:53','2024-07-22 02:16:24',1,'fHcH87AjdEVBsbz11Vxpt8Tn4PZwFSPUsowDJhQuZrf4C9WhsdEjX8pWS2oe'),(8,'Ligaya','Woo','123456789','269 S Western Ave','Los Angeles','California','90004','3104323933','aikasee@gmail.com',NULL,NULL,'$2y$12$mlWRTcCOZaeGQC1wnbkyDe1DsYTigyVNTDnE9noWalGEeGR2ND6Yu','2024-06-25 06:02:08','2024-07-14 22:28:09',0,NULL),(9,'test','test client','111111111','qeq','qwe','qweqwe','123','123123123','test@gmail.com',NULL,NULL,'$2y$12$pbIXawrAxSVhEF4zjVRxiuBQICHGS6NY4dORdF44fwwsyiWJuuBo.','2024-07-05 22:39:12','2024-07-05 22:39:12',0,NULL),(10,'qweqwe','qweqwe','qw1231445','qewqwe','qweqe','123123','1231231','qweqeqwe','test@io.ca',NULL,NULL,'$2y$12$0aZhjResEUzicIvxsPjsWOU.4xXnz4fFUSBrDvVcAZPYtL626.Gi6','2024-07-05 23:12:01','2024-07-05 23:12:01',0,NULL),(11,'tetsing','data','qqwwwee','qweqweq','qweqwe','qweqeq','22222','123123123','testio@gmail.com',NULL,NULL,'$2y$12$Jz70hR60VqiRTkC2egt.VOCyQBXkMUUxb80HLpvOdXifA4YlUqiM2','2024-07-05 23:12:39','2024-07-05 23:12:39',0,NULL),(12,'Ross','Macys','0123456','8601 Wilshire Blvd Apt 511','Beverly Hills','CA','90211','3234701947','ligayawoo@yahoo.com','631932',NULL,'$2y$12$NfTg86uwM/WA1VvvE2gRLulQnjAMOwKyJIqY7Bu0JmBew2BD6dQBe','2024-07-05 23:17:38','2024-07-14 22:42:16',1,'7STJUvalhuNEobWjiyOJT3fAS6hQLmY6sUbr3Qr3oeUfNIjXnSc8LbDj57eN');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coordinator_assignments`
--

DROP TABLE IF EXISTS `coordinator_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coordinator_assignments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `coordinator_id` bigint unsigned NOT NULL,
  `client_id` bigint unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coordinator_assignments`
--

LOCK TABLES `coordinator_assignments` WRITE;
/*!40000 ALTER TABLE `coordinator_assignments` DISABLE KEYS */;
INSERT INTO `coordinator_assignments` VALUES (1,2,12,1,'2024-07-05 23:46:54','2024-07-05 23:46:54',NULL);
/*!40000 ALTER TABLE `coordinator_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coordinators`
--

DROP TABLE IF EXISTS `coordinators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coordinators` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region_center` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coordinators`
--

LOCK TABLES `coordinators` WRITE;
/*!40000 ALTER TABLE `coordinators` DISABLE KEYS */;
INSERT INTO `coordinators` VALUES (1,'test','account','poknaitz@gmail.com','Lanterman','1231321231','1321231','1231qweqe','qweqw','qwe','qweq','2231231','$2y$12$dGPnIVV/./GRYcBa8VCpw.RgH6TLu8Xfv24f7pXya070vfuIe8wgm','877244','1','2024-07-05 04:42:08','2024-07-15 02:26:51',NULL),(2,'rey mark','egot','poknaitz@gmail.com','Lanterman','12345678','12345678','test location',NULL,'Los Angeles','CA','90034','$2y$12$qAgM7z7ICNu1nN3hOtptOO4zLiUcmxyeEPKyKAzSW/39P3Kv8Iuoy',NULL,'1','2024-07-05 23:45:12','2024-07-05 23:46:35',NULL);
/*!40000 ALTER TABLE `coordinators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `SP_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `OTP` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_dir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `Username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pw` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Status` enum('-1','0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `employees_client_id_foreign` (`client_id`),
  CONSTRAINT `employees_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;
/*!40000 ALTER TABLE `employees` DISABLE KEYS */;
INSERT INTO `employees` VALUES (6,'john','smith','SPN_24062466',NULL,'032',NULL,6,'2024-06-24 05:00:26','2024-06-24 05:13:45','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/employees/1719205226_datasheet.pdf','SPN_24062466','rey.egot@megaxcess.com','0912000000','$2y$12$nURlY39XMjTyMolGCGuLaOAUgC19lq5KePwHFte4CwJHW1xOvYv/O','npQcCXDk','0'),(7,'john','smith','SPN_24062467','zL1yVk3X6TH9merNTg9TgHVHIFszWBrdJ10YSm72UAVgNPNjcgZ33SWI39WA','032','363285',6,'2024-06-24 05:01:40','2024-07-04 06:51:29','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/employees/1719205299_datasheet.pdf','SPN_24062467','poknaitz@gmail.com','0912000000','$2y$12$tqWSFy5AujEDkmgsVvlAwux18bI4NmUL4b4IgDj/lebbJXgeWfMp6','oGTuRbAr','0'),(8,'Jennifer','Lee','SPN_24062478','7RdKwUMswv0pJA5LlwPcyFJg3i8YSdGgE144E53ybZYf1Vxw2YF9zK9oM6Zd','0323','428995',7,'2024-06-24 05:25:44','2024-06-25 06:02:56','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/employees/1719206743_Jennifer%20Injin%20Lee.pdf','SPN_24062478','jencsun0505@gmail.com','2135311132','$2y$12$q5peUutm/ADjSsymcGapzOGiRZKTFwtC5ussbuxuqMFhNZkQOMsy.','Y5AJGyrX','0'),(9,'Joy','White','SPN_240705129','hAAjxkSQpVdkG9V2yuyUxPemEEpg0Xa1LOwSzRllqZuYnGrCnPVnb4CuGAXE',NULL,'660582',12,'2024-07-05 23:27:52','2024-07-14 23:08:38','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/employees/1720247271_FMS%20SHIFT%20DETAILS.pdf','SPN_240705129','aikasee@gmail.com','3234701947','$2y$12$oLu8B.qe4.WPOiY.PkHYKeG/TzSAWQmk/MEgBgQJY8w9nFEq0V6ky','B3vKsYRz','0');
/*!40000 ALTER TABLE `employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hired_employees`
--

DROP TABLE IF EXISTS `hired_employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hired_employees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hired_date` date NOT NULL,
  `separation_date` date DEFAULT NULL,
  `status` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hired_employees`
--

LOCK TABLES `hired_employees` WRITE;
/*!40000 ALTER TABLE `hired_employees` DISABLE KEYS */;
INSERT INTO `hired_employees` VALUES (1,6,8,'Physical Therapy','2024-07-04','2024-07-04','1','2024-07-04 05:40:53','2024-07-04 05:41:00',NULL),(2,6,7,'software developer','2024-07-04',NULL,'1','2024-07-04 06:54:54','2024-07-04 06:54:54',NULL),(3,12,9,'Caregiver','2024-07-05',NULL,'1','2024-07-05 23:30:48','2024-07-05 23:30:48',NULL);
/*!40000 ALTER TABLE `hired_employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail_sender`
--

DROP TABLE IF EXISTS `mail_sender`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mail_sender` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `recipient` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sender` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail_sender`
--

LOCK TABLES `mail_sender` WRITE;
/*!40000 ALTER TABLE `mail_sender` DISABLE KEYS */;
INSERT INTO `mail_sender` VALUES (1,'request for employees update','Please update my employee rates for employee zyx','jezreel@blessedfms.com','poknaitz@gmail.com','2024-06-24 06:19:24','2024-06-24 06:19:24'),(2,'test concern','sample concern','customerservice@blessedfms.com','poknaitz@gmail.com','2024-06-24 06:38:20','2024-06-24 06:38:20'),(3,'test concern','sample issue','customerservice@blessedfms.com','poknaitz@gmail.com','2024-06-24 06:39:15','2024-06-24 06:39:15'),(4,'test concern','sample issue','jezreel@blessedfms.com','poknaitz@gmail.com','2024-06-24 06:44:13','2024-06-24 06:44:13');
/*!40000 ALTER TABLE `mail_sender` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2014_10_12_100000_create_password_resets_table',1),(4,'2019_08_19_000000_create_failed_jobs_table',1),(5,'2019_12_14_000001_create_personal_access_tokens_table',1),(6,'2024_01_16_143601_create_clients',1),(7,'2024_01_17_142321_add_column_name_to_clients',1),(8,'2024_01_18_124126_create_employees_table',1),(9,'2024_01_18_124711_add_column_to_employees',1),(10,'2024_01_18_130725_remove_email_column_from_employees_table',1),(11,'2024_01_20_052736_create_table_payables_upload',1),(12,'2024_01_20_112003_add_response_file_to_table_payables_upload',1),(13,'2024_01_21_092227_create_payroll_table',1),(14,'2024_01_27_065346_add_api_token_in_clients_table',1),(15,'2024_02_06_033401_modify_employee_id_column_nullable_in_table_payables_upload',1),(16,'2024_02_06_060206_add_new_column_to_table_payables_upload',1),(17,'2024_02_06_060509_rename_table_payables_upload_to_payables',1),(18,'2024_02_10_004604_add_columns_to_employees_table',1),(19,'2024_02_10_011156_add_columns_to_employees_table',1),(20,'2024_02_17_062506_add_employee_id_column',1),(21,'2024_02_24_043817_add_column_employees_table',1),(22,'2024_02_24_055611_modify_employees_table',1),(23,'2024_02_24_073209_add_column_employees_table',1),(24,'2024_02_24_073346_add_column_employees_table',1),(25,'2024_02_24_091944_create_timesheet_table',1),(26,'2024_02_25_131237_create_mails_table',1),(27,'2024_02_27_132307_add_column_in_employees_table',1),(28,'2024_02_28_132331_add_otp_column_in_clients',1),(29,'2024_03_05_143926_add_otp_column_in_users_table',1),(30,'2024_03_13_125432_create_new_table',2),(31,'2024_04_22_050550_add_service_code_column',2),(32,'2024_04_24_030447_create_vendors_table',2),(33,'2024_04_24_121111_create_vendors_invoices_table',2),(34,'2024_05_01_004605_add_column_invoice_reciept_file',2),(35,'2024_05_01_122614_create_coordinators_table',3),(36,'2024_05_02_083734_create_reports_table',3),(37,'2024_07_01_044617_create_service_code_categories_table',4),(38,'2024_07_01_093458_create_service_codes_table',4),(39,'2024_07_01_120455_create_client_spending_plans_table',4),(40,'2024_07_01_143125_create_client_spending_plan_items_table',4),(41,'2024_07_02_052617_create_client_file_uploads_table',4),(42,'2024_07_03_090239_create_hired_employees_table',5),(43,'2024_07_04_050957_change_ss_number_to_string_in_clients_table',5),(44,'2024_07_04_101040_add_service_in_timesheets_table',6),(45,'2024_07_05_002125_create_coordinator_assignments_table',7),(46,'2024_07_15_002250_add_new_column_to_reports_table',8);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('admin@blessedfms.com','$2y$12$NUTo9nfXPRm5nmsWqrkJuu.w0rSSadJVp8GTeQ/oHaQfsftA2s36.','2024-03-08 02:53:43');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payables`
--

DROP TABLE IF EXISTS `payables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `file_dir` text COLLATE utf8mb4_unicode_ci,
  `client_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `response_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `table_payables_upload_client_id_foreign` (`client_id`),
  KEY `table_payables_upload_employee_id_foreign` (`employee_id`),
  CONSTRAINT `table_payables_upload_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `table_payables_upload_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payables`
--

LOCK TABLES `payables` WRITE;
/*!40000 ALTER TABLE `payables` DISABLE KEYS */;
INSERT INTO `payables` VALUES (2,'https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/payables/1719204797_datasheet.pdf',6,NULL,'2024-06-24 04:53:17','2024-06-24 04:53:17',NULL,'Payment for school'),(3,'https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/payables/1719209641_datasheet.pdf',6,NULL,'2024-06-24 06:14:01','2024-06-24 06:14:26','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/employees/response/1719209666_download.jpg','bookstore purchase');
/*!40000 ALTER TABLE `payables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payroll`
--

DROP TABLE IF EXISTS `payroll`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payroll` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint unsigned NOT NULL,
  `payroll_start` date NOT NULL,
  `payroll_end` date NOT NULL,
  `payroll_file` text COLLATE utf8mb4_unicode_ci,
  `time_sheet_file` text COLLATE utf8mb4_unicode_ci,
  `status` enum('-1','0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '-1 cancelled, 0 pending, 1 completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `employee_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `payroll_client_id_foreign` (`client_id`),
  KEY `payroll_employee_id_foreign` (`employee_id`),
  CONSTRAINT `payroll_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payroll_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payroll`
--

LOCK TABLES `payroll` WRITE;
/*!40000 ALTER TABLE `payroll` DISABLE KEYS */;
INSERT INTO `payroll` VALUES (3,6,'2024-06-24','2024-06-30','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/payroll/1719209167_datasheet.pdf',NULL,'0','2024-06-24 06:06:07','2024-06-24 06:06:07',6),(4,6,'2024-06-23','2024-06-24','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/payroll/1719209226_datasheet.pdf',NULL,'0','2024-06-24 06:07:06','2024-06-24 06:07:06',7),(5,12,'2024-07-05','2024-07-31','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/payroll/1720248499_FMS%20Vendor%20Monthly%20Report.pdf',NULL,'0','2024-07-05 23:48:19','2024-07-05 23:48:19',9),(6,12,'2024-07-01','2024-07-31','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/payroll/1721022585_FMS-%20Shift%20Details%20of%20the%20month.pdf',NULL,'0','2024-07-14 22:49:45','2024-07-14 22:49:45',9);
/*!40000 ALTER TABLE `payroll` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_destination_account_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_destination_type` enum('1','2','3') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1' COMMENT '1 for coordinator, 2 for employees, 3 for clients',
  `report_date` date DEFAULT NULL,
  `report_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reports`
--

LOCK TABLES `reports` WRITE;
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
INSERT INTO `reports` VALUES (1,'Monthly Report','TEst Report',NULL,'1','2024-07-05','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/reports/1720248677_Best%20Buy%20confirmation%20ZERO%20Balance.jpg',NULL,'2024-07-05 23:51:17','2024-07-05 23:51:17',NULL),(2,'Spending Plan Monthly','Monthly Summary',NULL,'1','2024-01-31','https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/reports/1721025098_Spending%20Plan%20Summary.pdf',NULL,'2024-07-14 23:31:38','2024-07-14 23:31:38',NULL);
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resources`
--

DROP TABLE IF EXISTS `resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resources` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resources`
--

LOCK TABLES `resources` WRITE;
/*!40000 ALTER TABLE `resources` DISABLE KEYS */;
INSERT INTO `resources` VALUES (2,'https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/resources/1719800417_Form%20w4.pdf','Form w4','Payroll Enrollment doc.','2024-07-01 02:20:18','2024-07-01 02:20:18',NULL),(3,'https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/resources/1720246973_Vendor%20Schedule%20payment-0k.pdf','Vendor Schedule payment','','2024-07-05 23:22:53','2024-07-05 23:22:53',NULL),(4,'https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/resources/1721021826_Resources.%20Invoice%20Submission%20Guidelines.pdf','Submission of Invoice Guidelines','','2024-07-14 22:37:07','2024-07-14 22:37:07',NULL),(5,'https://blessedfms.s3.ca-central-1.amazonaws.com/uploads/resources/1721021861_resources%20Form%208821%20%28Rev.%20January%202021%29.pdf','Form 8821','','2024-07-14 22:37:41','2024-07-14 22:37:41',NULL);
/*!40000 ALTER TABLE `resources` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_code_categories`
--

DROP TABLE IF EXISTS `service_code_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_code_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_code_categories`
--

LOCK TABLES `service_code_categories` WRITE;
/*!40000 ALTER TABLE `service_code_categories` DISABLE KEYS */;
INSERT INTO `service_code_categories` VALUES (1,'Living Arrangements','2024-07-03 04:36:07','2024-07-03 04:36:07',NULL),(2,'Employment & Community Participation','2024-07-03 04:36:32','2024-07-03 04:36:32',NULL),(3,'Health & Safety','2024-07-03 04:36:44','2024-07-03 04:36:44',NULL);
/*!40000 ALTER TABLE `service_code_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_codes`
--

DROP TABLE IF EXISTS `service_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_code_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_code_category_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_codes_service_code_category_id_foreign` (`service_code_category_id`),
  CONSTRAINT `service_codes_service_code_category_id_foreign` FOREIGN KEY (`service_code_category_id`) REFERENCES `service_code_categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_codes`
--

LOCK TABLES `service_codes` WRITE;
/*!40000 ALTER TABLE `service_codes` DISABLE KEYS */;
INSERT INTO `service_codes` VALUES (1,'310','Respite (Individual and Agency) In- home',1,'2024-07-03 04:38:44','2024-07-03 04:38:44',NULL),(2,'331','Community Integration Supports',2,'2024-07-03 04:39:02','2024-07-03 04:39:02',NULL),(3,'333','Participant-Directed Goods and Services',2,'2024-07-03 04:39:17','2024-07-03 04:39:17',NULL),(4,'358','Non-Medical Transportation',2,'2024-07-03 04:39:34','2024-07-03 04:39:34',NULL),(5,'315','FMS Fiscal Agent',2,'2024-07-03 04:39:49','2024-07-03 04:39:49',NULL),(6,'316','FMS Co-Employer',2,'2024-07-03 04:44:43','2024-07-03 04:44:43',NULL);
/*!40000 ALTER TABLE `service_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timesheets`
--

DROP TABLE IF EXISTS `timesheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `timesheets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `start_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `total_hours` decimal(8,2) DEFAULT NULL,
  `specification` text COLLATE utf8mb4_unicode_ci,
  `service_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('0','1','-1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `client_id` bigint unsigned NOT NULL,
  `employee_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `timesheets_client_id_foreign` (`client_id`),
  KEY `timesheets_employee_id_foreign` (`employee_id`),
  CONSTRAINT `timesheets_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `timesheets_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timesheets`
--

LOCK TABLES `timesheets` WRITE;
/*!40000 ALTER TABLE `timesheets` DISABLE KEYS */;
INSERT INTO `timesheets` VALUES (1,'2024-06-24','13:08:00','2024-06-24','15:08:00',2.00,'i provided virtual support',NULL,'-1',6,7,'2024-06-24 05:14:00','2024-06-24 05:56:32'),(2,'2024-06-24','17:50:00','2024-06-24','19:50:00',2.00,'test',NULL,'1',6,7,'2024-06-24 05:50:47','2024-06-24 05:56:36'),(3,'2024-06-24','15:03:00','2024-06-24','18:03:00',3.00,'test123',NULL,'0',6,7,'2024-06-24 06:04:02','2024-06-24 06:04:02'),(4,'2024-05-01','12:04:00','2024-05-01','15:07:00',3.05,'personal assistant',NULL,'1',7,8,'2024-06-25 06:05:41','2024-06-25 06:20:48'),(5,'2024-05-03','11:06:00','2024-05-03','14:09:00',3.05,'went to a park',NULL,'1',7,8,'2024-06-25 06:07:01','2024-06-25 06:20:53'),(6,'2024-05-06','12:07:00','2024-05-06','15:07:00',3.00,'helping cleaning his room',NULL,'1',7,8,'2024-06-25 06:08:10','2024-06-25 06:20:57'),(7,'2024-05-12','12:08:00','2024-05-12','15:08:00',3.00,'ate lunch together and read storybooks',NULL,'1',7,8,'2024-06-25 06:09:43','2024-06-25 06:21:01'),(8,'2024-05-14','12:10:00','2024-05-14','15:10:00',3.00,'playtime with his younger brother Joon',NULL,'1',7,8,'2024-06-25 06:10:55','2024-06-25 06:21:04'),(9,'2024-05-16','12:11:00','2024-05-16','15:11:00',3.00,'making sandwitches',NULL,'1',7,8,'2024-06-25 06:11:59','2024-06-25 06:21:08'),(10,'2024-05-25','12:12:00','2024-05-25','15:12:00',3.00,'play hospital.',NULL,'1',7,8,'2024-06-25 06:12:58','2024-06-25 06:21:13'),(11,'2024-07-04','06:55:13','2024-07-04','06:55:22',0.00,NULL,'331','0',6,7,'2024-07-04 06:55:13','2024-07-04 06:55:22'),(12,'2024-07-05','23:31:42','2024-07-05','23:31:52',0.00,NULL,'331','1',12,9,'2024-07-05 23:31:42','2024-07-05 23:32:23'),(13,'2024-07-14','23:09:07','2024-07-14','23:09:35',0.01,NULL,'331','0',12,9,'2024-07-14 23:09:07','2024-07-14 23:09:35'),(14,'2024-07-14','23:12:30','2024-07-14','23:12:40',0.00,NULL,'331','0',12,9,'2024-07-14 23:12:30','2024-07-14 23:12:40'),(15,'2024-01-15','12:00:00','2024-01-15','16:00:00',4.00,'for demo only','331','1',12,9,'2024-07-14 23:16:34','2024-07-14 23:17:47');
/*!40000 ALTER TABLE `timesheets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Super Admin','aikasee@gmail.com','151558','2024-03-07 13:52:02','$2y$12$ep13kpiUeryZSgS6okSsseOLuGDR.iYZiGZiUDOFBSfQHpWS5MP5K','YngWUFTXxekeuaaJNnjVr03xNmqfvblrYYOswetbHGsddAS05HNzhOxDw5ms','2024-03-07 13:52:02','2024-07-14 22:34:38'),(2,'Ariel','admin@blessedfms.com','899658',NULL,'$2y$12$UG.mPYMzOxK/sBry3xxjI.CYGNLUHGhG/gsDijg95eRSnCZa./moy',NULL,'2024-03-11 20:01:06','2024-04-07 05:27:18'),(3,'Admin','poknaitz@gmail.com','862473',NULL,'$2y$12$1Kd7asbtgGM9TVTP36jzuuFoXYSD6kQgDGfLsztK56Mv4JzgGQylO','D5Qb3CGrcuXosA1GfXoDzGxvj0XWoeLUfS3dM3JOPWi8H0sGSK0suOqkpnRH','2024-03-07 13:52:02','2024-07-15 04:20:05'),(5,'Ariel','wu_wnwwoo@yahoo.com','436086',NULL,'$2y$12$gvcDmPo1pSzvVfmKWzgg5OylL8yW54fO22b3EYgeFTDCbMwB/5g7S',NULL,'2024-04-07 09:20:36','2024-04-07 09:21:00'),(6,'Ariel','wu_wnwoo@yahoo.com','509422',NULL,'$2y$12$Z3P/.8U7bFeyicDYmGEMHO.KAtcukKQeQcJvgIBpY/88Ffup45NBy',NULL,'2024-04-07 09:24:59','2024-04-07 09:25:25'),(7,'Jennifer Lee','jencsun0505@gmail.com','207074',NULL,'$2y$12$SbCYdxjCd0jhMLgfSNBSjOV993MaYvFxrm4LgKq6f3qfZ.Mgm5rfG',NULL,'2024-04-07 23:39:29','2024-04-07 23:49:38'),(8,'Jennefer Lee','j.lee@blessedfms.com',NULL,NULL,'$2y$12$BXVBGg5gC.gaOpFMSkEzYul8hKi1WM08tBm5ZrprD9o252CBe2nzS',NULL,'2024-06-24 06:24:16','2024-06-24 06:24:16');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zipcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors`
--

LOCK TABLES `vendors` WRITE;
/*!40000 ALTER TABLE `vendors` DISABLE KEYS */;
INSERT INTO `vendors` VALUES (1,'Ligaya','Woo','aikasee@gmail.com','Sunny Art Store','3234701947','3234701947','1234 S Western Ave','8601 Wilshire Blvd Apt 511','Los Angeles','California','90034',NULL,'$2y$12$huTpCoEYafXS11B603HCuuVPe85nyNPSDfl4thVcqFILVMljRTXHu','094968','1','2024-07-05 23:39:24','2024-07-05 23:40:31',NULL);
/*!40000 ALTER TABLE `vendors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendors_invoices`
--

DROP TABLE IF EXISTS `vendors_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendors_invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `date_purchased` date DEFAULT NULL,
  `client_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reciept_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_complete` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vendors_invoices_vendor_id_foreign` (`vendor_id`),
  CONSTRAINT `vendors_invoices_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors_invoices`
--

LOCK TABLES `vendors_invoices` WRITE;
/*!40000 ALTER TABLE `vendors_invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `vendors_invoices` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-07-26  8:25:18
