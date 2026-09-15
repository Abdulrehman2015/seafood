-- MySQL dump 10.13  Distrib 8.4.3, for Win64 (x86_64)
--
-- Host: localhost    Database: oceanfresh
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `customer_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'retail',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_product_id_foreign` (`product_id`),
  KEY `carts_session_id_index` (`session_id`),
  KEY `carts_user_id_index` (`user_id`),
  CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (2,'Iq5m4K8QWEFRO50TKpYPOGztAAhcW0yPp7bELHf8',NULL,1,1,'walkin','2026-09-07 03:41:47','2026-09-07 03:41:47'),(5,NULL,2,1,1,'retail','2026-09-07 04:35:04','2026-09-07 04:35:04'),(6,'Oor8eSalabgcBqC1KwT7VlOKgMoOOD6UjRwtaFeg',NULL,1,1,'retail','2026-09-11 08:05:05','2026-09-11 08:05:05'),(11,NULL,5,1,2,'retail','2026-09-11 08:44:41','2026-09-11 08:50:19'),(12,'xWNLrK0IJAv6ynNCASMBfDo5RmBRf1tieEaHQsUi',NULL,1,1,'retail','2026-09-11 09:12:16','2026-09-11 09:12:16');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Fish','fish',NULL,NULL,NULL,NULL,1,1,1,'2026-09-07 01:23:15','2026-09-09 03:21:14'),(2,'Prawns / Shrimps','prawns-shrimps',NULL,NULL,NULL,NULL,2,1,1,'2026-09-07 01:23:15','2026-09-09 03:21:22'),(3,'Squid','squid',NULL,NULL,NULL,NULL,3,1,1,'2026-09-07 01:23:15','2026-09-09 03:21:23'),(4,'Crab','crab',NULL,NULL,NULL,NULL,4,1,1,'2026-09-07 01:23:15','2026-09-09 03:21:24'),(8,'Shellfish','shellfish',NULL,NULL,NULL,NULL,0,1,1,'2026-09-09 03:03:16','2026-09-09 03:18:53'),(9,'Seafood Products','seafood-products',NULL,NULL,NULL,NULL,5,1,1,'2026-09-09 03:04:45','2026-09-09 03:21:37'),(10,'Fish Fillet','fish-fillet',NULL,NULL,NULL,NULL,6,1,0,'2026-09-09 03:05:12','2026-09-09 03:05:12'),(11,'Other Frozen Seafood','other-frozen-seafood',NULL,NULL,NULL,NULL,7,1,0,'2026-09-09 03:05:24','2026-09-09 03:05:24'),(12,'Steamboat','steamboat',NULL,NULL,NULL,NULL,8,1,0,'2026-09-09 03:05:37','2026-09-09 03:06:28'),(13,'Meat Chicken','meat-chicken',NULL,NULL,NULL,NULL,9,1,0,'2026-09-09 03:06:00','2026-09-09 03:06:00'),(14,'Meat Lamb','meat-lamb',NULL,NULL,NULL,NULL,10,1,0,'2026-09-09 03:06:17','2026-09-09 03:06:17'),(15,'Meat Beef','meat-beef',NULL,NULL,NULL,NULL,11,1,0,'2026-09-09 03:07:01','2026-09-09 03:07:01'),(16,'Meat Duck','meat-duck',NULL,NULL,NULL,NULL,12,1,0,'2026-09-09 03:07:21','2026-09-09 03:07:21'),(17,'Frozen Product Food','frozen-product-food',NULL,NULL,NULL,NULL,13,1,0,'2026-09-09 03:07:42','2026-09-09 03:07:42'),(18,'Dimsum','dimsum',NULL,NULL,NULL,NULL,14,1,0,'2026-09-09 03:08:00','2026-09-09 03:08:00'),(19,'Ready to Eat','ready-to-eat',NULL,NULL,NULL,NULL,15,1,0,'2026-09-09 03:08:16','2026-09-09 03:08:16'),(20,'Snack Food','snack-food',NULL,NULL,NULL,NULL,16,1,0,'2026-09-09 03:08:30','2026-09-09 03:08:30'),(21,'Dessert','dessert',NULL,NULL,NULL,NULL,17,1,0,'2026-09-09 03:08:46','2026-09-09 03:08:46');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

--
-- Table structure for table `contact_messages`
--

DROP TABLE IF EXISTS `contact_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_messages`
--

/*!40000 ALTER TABLE `contact_messages` DISABLE KEYS */;
INSERT INTO `contact_messages` VALUES (1,'Daniel Tan','daniel@example.com','+60123456789','Wholesale Account','Interested in bulk orders',1,'127.0.0.1','2026-09-07 04:13:52','2026-09-07 04:21:44'),(2,'test1','admin@oceanfresh.com','+123456789','General Enquiry','testing',1,'127.0.0.1','2026-09-08 05:16:15','2026-09-08 05:16:57'),(3,'Jane Doe','jane.doe@example.com','+60198765432','Web & Mobile App Development','We would like to inquire about bulk seafood supply and custom wholesale pricing for our chain.',0,'127.0.0.1','2026-09-09 08:46:00','2026-09-09 08:46:00');
/*!40000 ALTER TABLE `contact_messages` ENABLE KEYS */;

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

/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image/webp',
  `size` bigint unsigned NOT NULL,
  `width` int unsigned DEFAULT NULL,
  `height` int unsigned DEFAULT NULL,
  `folder` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'gallery',
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_folder_index` (`folder`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (1,'salmon_fillet.webp','Atlantic Salmon Fillet.jpg','products/salmon_fillet.webp','image/webp',4646,600,600,'products','Atlantic Salmon Fillet','2026-09-07 06:38:16','2026-09-08 04:34:28'),(2,'king_prawns.webp','Fresh King Prawns.png','test-by-ar-updated/king_prawns.webp','image/webp',4244,600,600,'test-by-ar-updated','Fresh King Prawns','2026-09-07 06:38:16','2026-09-08 05:28:20'),(3,'mud_crab.webp','Live Mud Crab.png','test-by-ar-updated/mud_crab.webp','image/webp',4000,600,600,'test-by-ar-updated','Live Mud Crab','2026-09-07 06:38:16','2026-09-08 05:28:20'),(4,'1788783701_eM76aR_chatgpt-image-sep-3-2026-05-53-16-pm.webp','ChatGPT Image Sep 3, 2026, 05_53_16 PM.png','test-by-ar-updated/1788783701_eM76aR_chatgpt-image-sep-3-2026-05-53-16-pm.webp','image/webp',188220,1672,941,'test-by-ar-updated','ChatGPT Image Sep 3, 2026, 05_53_16 PM','2026-09-07 07:21:41','2026-09-08 05:28:20'),(7,'1788935704_4AZjNV_chatgpt-image-sep-3-2026-05-53-16-pm.webp','chatgpt-image-sep-3-2026-05-53-16-pm.png','test-by-ar-updated/1788935704_4AZjNV_chatgpt-image-sep-3-2026-05-53-16-pm.webp','image/webp',179304,1672,941,'test-by-ar-updated','chatgpt-image-sep-3-2026-05-53-16-pm','2026-09-09 01:35:05','2026-09-09 01:35:05'),(8,'1788935705_vLIFDJ_default-image.webp','default_image.webp','test-by-ar-updated/1788935705_vLIFDJ_default-image.webp','image/webp',110228,1378,1155,'test-by-ar-updated','default_image','2026-09-09 01:35:05','2026-09-09 01:35:05'),(9,'1788935705_mETat9_whatsapp-image-2026-08-27-at-20436-pm.webp','whatsapp-image-2026-08-27-at-20436-pm.jpeg','test-by-ar-updated/1788935705_mETat9_whatsapp-image-2026-08-27-at-20436-pm.webp','image/webp',88440,1024,1280,'test-by-ar-updated','whatsapp-image-2026-08-27-at-20436-pm','2026-09-09 01:35:05','2026-09-09 01:35:05'),(10,'1788942707_2M8pW9_mika-logo-fa.pdf','Mika_Logo_FA.pdf','appearance/1788942707_2M8pW9_mika-logo-fa.pdf','application/pdf',1621576,NULL,NULL,'appearance','Mika_Logo_FA','2026-09-09 03:31:47','2026-09-09 03:31:47'),(11,'1788943446_RyJfrC_chatgpt-image-sep-9-2026-01-42-34-pm.webp','ChatGPT Image Sep 9, 2026, 01_42_34 PM.png','appearance/1788943446_RyJfrC_chatgpt-image-sep-9-2026-01-42-34-pm.webp','image/webp',404704,1254,1254,'appearance','ChatGPT Image Sep 9, 2026, 01_42_34 PM','2026-09-09 03:44:06','2026-09-09 03:44:06'),(12,'1789019241_C8tHBL_tigers-prawns-u10.webp','Tigers-prawns-u10.png','products/1789019241_C8tHBL_tigers-prawns-u10.webp','image/webp',98106,800,800,'products','Tigers-prawns-u10','2026-09-10 00:47:22','2026-09-10 00:47:22'),(13,'1789019303_7SewDQ_flowercrab-1-1024x1024.webp','flowercrab_1_1024x1024.jpg','products/1789019303_7SewDQ_flowercrab-1-1024x1024.webp','image/webp',113554,1024,768,'products','flowercrab_1_1024x1024','2026-09-10 00:48:23','2026-09-10 00:48:23'),(14,'1789019355_zhPb0X_seashelfcalamarirings-500gm-2.webp','Seashelfcalamarirings-500gm_2.png','products/1789019355_zhPb0X_seashelfcalamarirings-500gm-2.webp','image/webp',305108,1080,1080,'products','Seashelfcalamarirings-500gm_2','2026-09-10 00:49:15','2026-09-10 00:49:15'),(15,'1789019396_jGi9Uw_b821a0d1-e4a7-4098-a2ce-605068421216.webp','b821a0d1-e4a7-4098-a2ce-605068421216.jpg','products/1789019396_jGi9Uw_b821a0d1-e4a7-4098-a2ce-605068421216.webp','image/webp',50838,760,760,'products','b821a0d1-e4a7-4098-a2ce-605068421216','2026-09-10 00:49:56','2026-09-10 00:49:56');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;

--
-- Table structure for table `media_folders`
--

DROP TABLE IF EXISTS `media_folders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_folders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `media_folders_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_folders`
--

/*!40000 ALTER TABLE `media_folders` DISABLE KEYS */;
INSERT INTO `media_folders` VALUES (1,'General Gallery','gallery',1,'2026-09-07 06:27:46','2026-09-07 06:27:46'),(2,'Products','products',1,'2026-09-07 06:27:46','2026-09-07 06:27:46'),(3,'Categories','categories',1,'2026-09-07 06:27:46','2026-09-07 06:27:46'),(6,'test by ar updated','test-by-ar-updated',0,'2026-09-07 07:22:57','2026-09-08 05:28:20'),(7,'Appearance','appearance',0,'2026-09-09 02:02:45','2026-09-09 02:02:45');
/*!40000 ALTER TABLE `media_folders` ENABLE KEYS */;

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
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2024_01_01_000010_create_categories_table',1),(5,'2024_01_01_000020_create_products_table',1),(6,'2024_01_01_000030_create_carts_table',1),(7,'2024_01_01_000040_create_orders_table',1),(8,'2024_01_01_000050_create_quotations_table',1),(9,'2024_01_01_000060_create_walkin_sessions_table',1),(10,'2024_01_01_000070_add_collection_token_to_orders_table',1),(11,'2024_01_01_000080_create_settings_table',1),(12,'2024_01_01_000081_create_contact_messages_table',1),(13,'2024_01_01_000090_create_media_table',1),(14,'2024_01_01_000091_create_media_folders_table',1),(15,'2024_01_01_000100_create_page_seos_table',1),(16,'2024_01_01_000110_create_reviews_table',1),(17,'2024_01_01_000120_create_newsletter_subscribers_table',1),(18,'2024_01_01_000130_add_custom_url_to_categories_table',1),(19,'2024_01_01_000140_add_is_featured_to_categories_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

--
-- Table structure for table `newsletter_subscribers`
--

DROP TABLE IF EXISTS `newsletter_subscribers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `newsletter_subscribers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletter_subscribers_email_unique` (`email`),
  KEY `newsletter_subscribers_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletter_subscribers`
--

/*!40000 ALTER TABLE `newsletter_subscribers` DISABLE KEYS */;
INSERT INTO `newsletter_subscribers` VALUES (1,'chef.malik@oceanfresh.my','active','127.0.0.1','2026-09-09 02:34:56','2026-09-09 02:34:56');
/*!40000 ALTER TABLE `newsletter_subscribers` ENABLE KEYS */;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `price_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,1,'Atlantic Salmon Fillet (500g)','FISH-SAL-500',1,25.90,25.90,'walkin','2026-09-07 03:56:52','2026-09-07 03:56:52'),(2,1,2,'Tiger Prawns (1kg)','CRUST-TPRAWN-1KG',1,42.00,42.00,'walkin','2026-09-07 03:56:52','2026-09-07 03:56:52'),(3,2,2,'Tiger Prawns (1kg)','CRUST-TPRAWN-1KG',4,42.00,168.00,'walkin','2026-09-07 07:40:36','2026-09-07 07:40:36'),(4,3,1,'Atlantic Salmon Fillet (500g)','FISH-SAL-500',1,28.90,28.90,'retail','2026-09-11 08:22:48','2026-09-11 08:22:48'),(5,4,1,'Atlantic Salmon Fillet (500g)','FISH-SAL-500',1,28.90,28.90,'retail','2026-09-11 08:24:54','2026-09-11 08:24:54'),(6,5,1,'Atlantic Salmon Fillet (500g)','FISH-SAL-500',1,28.90,28.90,'retail','2026-09-11 08:37:50','2026-09-11 08:37:50'),(7,6,1,'Atlantic Salmon Fillet (500g)','FISH-SAL-500',2,28.90,57.80,'retail','2026-09-11 08:42:17','2026-09-11 08:42:17');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `collection_token` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `customer_group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','confirmed','processing','ready','shipped','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_status` enum('unpaid','paid','refunded','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `fulfillment_type` enum('delivery','self_collection') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'delivery',
  `shipping_address` json DEFAULT NULL,
  `collection_date` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stripe_payment_intent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_notes` text COLLATE utf8mb4_unicode_ci,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_collection_token_index` (`collection_token`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-6A9E7C5485B07','W-001',1,'walkin','Marcus Lee','admin@oceanfresh.com','012-3344556','confirmed','paid','test_simulation','pi_test_walkin_7cmismf7jt','2026-09-07 03:56:52','self_collection',NULL,NULL,NULL,70.90,0.00,0.00,0.00,70.90,'pi_test_walkin_7cmismf7jt',NULL,NULL,'2026-09-07 03:56:52','2026-09-07 03:56:52'),(2,'ORD-6A9EB0C400BF7','W-002',1,'walkin','Admin','admin@oceanfresh.com','+60123456789','confirmed','paid','test_simulation','pi_test_walkin_ysh48yy2xc','2026-09-07 07:40:36','self_collection',NULL,NULL,NULL,168.00,0.00,0.00,0.00,168.00,'pi_test_walkin_ysh48yy2xc',NULL,NULL,'2026-09-07 07:40:36','2026-09-07 07:40:36'),(3,'ORD-6AA400A890909',NULL,5,'retail','Abdul Rehman','ss4871836@gmail.com','+923176121524','confirmed','paid','test_simulation','pi_test_7071971d2b2c201be2e2_secret_aa7129af56374be74221','2026-09-11 08:22:48','delivery','{\"city\": \"LAHORE\", \"state\": \"Wilayah Persekutuan\", \"address\": \"9d3, sultan town, lahore\", \"postcode\": \"57450\"}',NULL,NULL,28.90,0.00,0.00,0.00,28.90,'pi_test_7071971d2b2c201be2e2_secret_aa7129af56374be74221',NULL,NULL,'2026-09-11 08:22:48','2026-09-11 08:22:48'),(4,'ORD-6AA4012629023',NULL,5,'retail','Abdul Rehman','ss4871836@gmail.com','+923176121524','confirmed','paid','test_simulation','pi_test_45e07713dccd9c317d5b_secret_7503e9e0761609074ef4','2026-09-11 08:24:54','delivery','{\"city\": \"LAHORE\", \"state\": \"Wilayah Persekutuan\", \"address\": \"9d3, sultan town, lahore\", \"postcode\": \"57450\"}',NULL,NULL,28.90,0.00,0.00,0.00,28.90,'pi_test_45e07713dccd9c317d5b_secret_7503e9e0761609074ef4',NULL,NULL,'2026-09-11 08:24:54','2026-09-11 08:24:54'),(5,'ORD-6AA4042E8157F',NULL,5,'retail','Abdul Rehman','ss4871836@gmail.com','+923176121524','confirmed','paid','test_simulation','mock_stripe_838a774c5fc655f8','2026-09-11 08:37:50','delivery','{\"city\": \"LAHORE\", \"state\": \"Wilayah Persekutuan\", \"address\": \"9d3, sultan town, lahore\", \"postcode\": \"57450\"}',NULL,NULL,28.90,0.00,0.00,0.00,28.90,'mock_stripe_838a774c5fc655f8',NULL,NULL,'2026-09-11 08:37:50','2026-09-11 08:37:50'),(6,'ORD-6AA40539783E2',NULL,5,'retail','Abdul Rehman','ss4871836@gmail.com','+923176121524','confirmed','paid','test_simulation','mock_stripe_01f6da6fbdd2b306','2026-09-11 08:42:17','delivery','{\"city\": \"LAHORE\", \"state\": \"Wilayah Persekutuan\", \"address\": \"9d3, sultan town, lahore\", \"postcode\": \"57450\"}',NULL,NULL,57.80,0.00,0.00,0.00,57.80,'mock_stripe_01f6da6fbdd2b306',NULL,NULL,'2026-09-11 08:42:17','2026-09-11 08:42:17');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;

--
-- Table structure for table `page_seos`
--

DROP TABLE IF EXISTS `page_seos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `page_seos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `page_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `og_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `canonical_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `schema_markup` text COLLATE utf8mb4_unicode_ci,
  `is_system` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_seos_page_slug_unique` (`page_slug`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_seos`
--

/*!40000 ALTER TABLE `page_seos` DISABLE KEYS */;
INSERT INTO `page_seos` VALUES (1,'Home','home','OceanFresh | Premium Fresh & Frozen Seafood Exporter','OceanFresh is a leading supplier and distributor of premium frozen and fresh seafood, catering to retail, wholesale, and bulk trading clients worldwide.','fresh seafood, frozen salmon, king prawns, lobsters, seafood export, b2b seafood',NULL,NULL,NULL,1,'2026-09-07 07:58:05','2026-09-07 07:58:05'),(2,'About Us','about','About OceanFresh | Global Seafood Supply & Sustainability','Learn about OceanFresh, our commitment to sustainable ocean harvesting, international cold-chain quality assurance, and global seafood supply.','about oceanfresh, sustainable seafood, cold chain logistics, seafood wholesale',NULL,NULL,NULL,1,'2026-09-07 07:58:05','2026-09-07 07:58:05'),(3,'Shop / Products','shop','Seafood Catalogue & Shop | OceanFresh Seafood','Explore our extensive catalogue of fresh and frozen seafood including Atlantic Salmon, King Prawns, Mud Crabs, Lobsters, and Whole Fish.','buy seafood online, salmon fillets, fresh tiger prawns, frozen seafood wholesale',NULL,NULL,NULL,1,'2026-09-07 07:58:05','2026-09-07 07:58:05'),(4,'Contact Us','contact','Contact Us & Global Enquiries | OceanFresh Seafood','Get in touch with the OceanFresh team for retail questions, commercial wholesale partnerships, cold-storage logistics, or customer support.','contact seafood supplier, wholesale enquiry, seafood customer service',NULL,NULL,NULL,1,'2026-09-07 07:58:05','2026-09-07 07:58:05'),(5,'Request a Quotation (RFQ)','quotations','Request a Quotation (RFQ) | OceanFresh Commercial Trading','Submit a customized Request for Quotation (RFQ) for bulk container shipments, custom packing, and wholesale commercial seafood pricing.','seafood RFQ, bulk seafood quotation, commercial seafood order',NULL,NULL,NULL,1,'2026-09-07 07:58:06','2026-09-07 07:58:06'),(6,'Walk-In Catalogue','walkin','Walk-In Customer Store & Token Pass | OceanFresh','Browse our in-store digital catalogue, order fresh seafood instantly, and receive a collection token at our physical outlet.','walkin seafood, QR seafood ordering, store collection token',NULL,NULL,NULL,1,'2026-09-07 07:58:06','2026-09-07 07:58:06'),(7,'Shopping Cart','cart','Your Cart | OceanFresh Seafood','Review your selected seafood items, quantities, tiered discounts, and proceed to checkout.','seafood cart, checkout seafood',NULL,NULL,NULL,1,'2026-09-07 07:58:06','2026-09-07 07:58:06'),(8,'Checkout','checkout','Secure Checkout | OceanFresh Seafood','Complete your seafood order securely with credit/debit card, bank transfer, or cash on delivery.','secure checkout, buy fish online',NULL,NULL,NULL,1,'2026-09-07 07:58:06','2026-09-07 07:58:06'),(9,'Privacy Policy','privacy_policy','Privacy Policy | Data Protection & Cookies | OceanFresh','Read how OceanFresh protects customer information, handles cookies, and respects international data privacy standards.','privacy policy, customer data protection, cookies policy',NULL,NULL,NULL,1,'2026-09-07 07:58:06','2026-09-07 07:58:06'),(10,'Terms & Conditions','terms_conditions','Terms & Conditions | OceanFresh Commercial & Retail Sales','Review the official terms of service, delivery policies, return guidelines, and wholesale conditions of OceanFresh.','terms of service, seafood return policy, delivery terms',NULL,NULL,NULL,1,'2026-09-07 07:58:06','2026-09-07 07:58:06'),(11,'Blogs / Thought Leadership','blogs','Thought Leadership | Insights & Market Analysis | OceanFresh','Articles and industry insights on global aquaculture trends, seasonal seafood catch forecasts, and cold-chain innovations.','seafood industry blog, aquaculture insights, fish market news',NULL,NULL,NULL,1,'2026-09-07 07:58:06','2026-09-07 07:58:06'),(12,'Accessibility Policy','accessibility_policy','Accessibility & Inclusion Policy | OceanFresh','Our commitment to ensuring digital accessibility for all users across our online ordering platforms.','accessibility policy, web standards',NULL,NULL,NULL,1,'2026-09-07 07:58:06','2026-09-07 07:58:06'),(13,'AI Use Policy','ai_use_policy','Responsible AI Use Policy | OceanFresh','Guidelines on our responsible deployment of automated inventory forecasting and customer communication AI systems.','ai ethics, responsible technology',NULL,NULL,NULL,1,'2026-09-07 07:58:06','2026-09-07 07:58:06'),(14,'Anti-Fraud & Ethics Policy','anti_fraud_policy','Anti-Fraud, Anti-Corruption & Anti-Bribery | OceanFresh','Corporate integrity standards, anti-fraud compliance, and fair trade practices across all supply chain partners.','anti fraud policy, corporate ethics, compliance',NULL,NULL,NULL,1,'2026-09-07 07:58:06','2026-09-07 07:58:06');
/*!40000 ALTER TABLE `page_seos` ENABLE KEYS */;

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

/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `retail_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `walkin_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `wholesale_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `trading_price` decimal(10,2) DEFAULT NULL,
  `weight` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pcs',
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `storage_temp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specifications` json DEFAULT NULL,
  `images` json DEFAULT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `track_stock` tinyint(1) NOT NULL DEFAULT '1',
  `moq` int NOT NULL DEFAULT '1',
  `moq_wholesale` int NOT NULL DEFAULT '1',
  `moq_trading` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_walkin_available` tinyint(1) NOT NULL DEFAULT '0',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_rfq_only` tinyint(1) NOT NULL DEFAULT '0',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Atlantic Salmon Fillet (500g)','atlantic-salmon-fillet-500g','Sourced directly from cold Norwegian fjords, our Atlantic salmon fillets feature rich omega-3 fatty acids, vibrant marbling, and tender texture. Flash-frozen with Individual Quick Freezing (IQF) technology to preserve oceanic freshness. Ideal for pan-searing, baking with herb butter, or air-frying.','Premium sashimi-grade Norwegian Atlantic salmon fillet, skin-on and pin-bone out.','FISH-SAL-500',1,28.90,25.90,22.00,19.50,'500g','pack','Norway','-18°C','OceanFresh',NULL,NULL,'products/salmon_fillet_premium.jpg',245,1,1,10,50,1,1,1,0,1,'2026-09-07 01:23:15','2026-09-11 08:42:17',NULL),(2,'Tiger Prawns (1kg)','tiger-prawns-1kg','Succulent wild-caught tiger prawns from the South China Sea. Sweet, firm flesh ideal for BBQ, steaming, or curry. 1kg pack contains approximately 15-20 pieces.','Wild-caught jumbo tiger prawns, shell-on, head-on.','CRUST-TPRAWN-1KG',2,45.90,42.00,36.00,NULL,'1kg','kg','Malaysia','-18°C','OceanFresh',NULL,'[]','products/1789019241_C8tHBL_tigers-prawns-u10.webp',175,1,1,5,20,1,1,1,1,2,'2026-09-07 01:23:15','2026-09-10 03:19:51',NULL),(3,'Flower Crab (500g)','flower-crab-500g','Premium flower crabs harvested fresh and individually quick frozen to lock in their natural sweetness. Perfect for steaming with ginger or chilli crab.','Fresh flower crabs, individually quick frozen at peak freshness.','CRUST-FCRAB-500',4,18.90,16.90,13.50,11.00,'500g','pack','Malaysia','-18°C','OceanFresh',NULL,'[]','products/1789019303_7SewDQ_flowercrab-1-1024x1024.webp',320,1,1,20,100,1,1,0,0,3,'2026-09-07 01:23:15','2026-09-10 03:19:51',NULL),(4,'Squid Rings (500g)','squid-rings-500g','Convenient pre-cut squid rings made from fresh squid. Simply thaw and cook. Great for calamari, stir-fry, or hotpot.','Pre-cut squid rings, ready for frying or stir-frying.','CEPH-SQUID-500',3,12.90,11.50,9.00,7.50,'500g','pack','Thailand','-18°C','OceanFresh',NULL,'[]','products/1789019355_zhPb0X_seashelfcalamarirings-500gm-2.webp',400,1,1,20,100,1,1,1,0,4,'2026-09-07 01:23:15','2026-09-10 03:19:52',NULL),(5,'Cockles / Kerang (500g)','cockles-kerang-500g','Locally sourced Malaysian cockles (kerang) that are cleaned and IQF frozen. Ready for noodle dishes, satay kerang, or simply blanched.','Fresh Malaysian cockles, cleaned and individually quick frozen.','SHELL-COCK-500',8,8.90,7.90,6.00,4.80,'500g','pack','Malaysia','-18°C','OceanFresh',NULL,'[]','products/1789019396_jGi9Uw_b821a0d1-e4a7-4098-a2ce-605068421216.webp',500,1,1,50,200,1,1,0,0,5,'2026-09-07 01:23:15','2026-09-10 03:19:52',NULL),(7,'Barramundi / Siakap Whole Cleaned (750g)','barramundi-siakap-whole-750g','Premium live-harvested Barramundi (Ikan Siakap) processed immediately under strict HACCP hygiene standards. Sweet, delicate white flakes with clean ocean flavor. Ready-to-cook for classic Malaysian Teochew steamed fish, Thai sweet and sour, or oven-roasted.','Farm-fresh Asian Seabass (Siakap), de-scaled, gutted and vacuum sealed.','FISH-SIAKAP-750',1,24.50,22.00,18.50,16.00,'750g','fish','Malaysia','-18°C','OceanFresh',NULL,NULL,'products/barramundi_seabass.jpg',140,1,1,15,60,1,1,1,0,2,'2026-09-10 00:42:23','2026-09-10 00:42:23',NULL),(8,'Red Snapper / Ikan Merah Fillet (500g)','red-snapper-ikan-merah-fillet-500g','Prized for its firm, lean meat and subtly sweet, nutty profile, our wild-caught Red Snapper is carefully portioned into 500g packs. Ideal for traditional Malaysian fish head curry, grilling over banana leaf, or fine dining pan-roasting.','Wild-caught Red Snapper fillets, skin-on with sweet firm white meat.','FISH-SNAP-500',1,29.90,27.00,23.50,21.00,'500g','pack','Indonesia','-18°C','OceanFresh',NULL,NULL,'products/red_snapper_fillet.jpg',165,1,1,10,50,1,1,0,0,3,'2026-09-10 00:42:23','2026-09-10 00:42:23',NULL),(9,'Spanish Mackerel / Tenggiri Steak (500g)','spanish-mackerel-tenggiri-steak-500g','Prime center-cut slices of wild Spanish Mackerel (Ikan Tenggiri Batang). Dense, meaty texture with natural oceanic umami. Perfect for classic Malaysian Tenggiri Goreng Kunyit, Assam Pedas, or homemade fish paste.','Center-cut wild Tenggiri fish steaks, rich in flavor and nutrient-dense.','FISH-TENG-500',1,26.00,23.50,20.00,18.00,'500g','pack','Malaysia','-18°C','OceanFresh',NULL,NULL,'products/tenggiri_steak.jpg',120,1,1,10,50,1,1,0,0,4,'2026-09-10 00:42:23','2026-09-10 00:42:23',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

--
-- Table structure for table `quotation_items`
--

DROP TABLE IF EXISTS `quotation_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quotation_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quotation_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity_requested` int NOT NULL,
  `quoted_price` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quotation_items_quotation_id_foreign` (`quotation_id`),
  KEY `quotation_items_product_id_foreign` (`product_id`),
  CONSTRAINT `quotation_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  CONSTRAINT `quotation_items_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotation_items`
--

/*!40000 ALTER TABLE `quotation_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `quotation_items` ENABLE KEYS */;

--
-- Table structure for table `quotations`
--

DROP TABLE IF EXISTS `quotations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quotations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quotation_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `status` enum('pending','quoted','accepted','rejected','expired','converted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `customer_notes` text COLLATE utf8mb4_unicode_ci,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `valid_until` timestamp NULL DEFAULT NULL,
  `total_quoted` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `quotations_quotation_number_unique` (`quotation_number`),
  KEY `quotations_user_id_foreign` (`user_id`),
  CONSTRAINT `quotations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quotations`
--

/*!40000 ALTER TABLE `quotations` DISABLE KEYS */;
/*!40000 ALTER TABLE `quotations` ENABLE KEYS */;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_or_company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` tinyint NOT NULL DEFAULT '5',
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '1',
  `status` enum('approved','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,'Chef Marcus Tan','Executive Head Chef, Marina Seafood Bistro (KL)',5,'OceanFresh has been our primary seafood purveyor for over 3 years. The IQF King Salmon fillets and Tiger Prawns arrive with flawless texture and zero glazing loss. Truly five-star grade consistency for our banquet service.',NULL,1,'approved',1,'2026-09-08 08:26:28','2026-09-08 08:39:27'),(2,'Datin Sarah Al-Haddad','Verified Retail Gourmet Buyer (Ampang)',5,'The cold-truck delivery arrived in under 24 hours strictly frozen at -18°C. The Norwegian Cod steaks were exceptionally sweet and tender. Having restaurant-grade seafood delivered right to our door is unbeatable.',NULL,1,'approved',2,'2026-09-08 08:26:28','2026-09-08 08:26:28'),(3,'Kenji Takahashi','Owner & Head Chef, Omakase Bar Pavilion',5,'Their sashimi-grade Scallops and Yellowfin Tuna meet our stringent standards for raw preparation. Reliable supply chain, prompt RFQ quotes, and pristine cold-chain hygiene throughout.',NULL,1,'approved',3,'2026-09-08 08:26:28','2026-09-08 08:26:28'),(4,'Haji Rahim bin Yusof','Procurement Director, Selera Samudera Catering',5,'We manage corporate catering for up to 2,000 pax weekly. OceanFresh’s wholesale tier and self-pickup counter at Batu Caves save us crucial logistics time. Certified Halal and always dependable.',NULL,1,'approved',4,'2026-09-08 08:26:28','2026-09-08 08:26:28'),(5,'Emily Wong','Home Cook & Culinary Blogger',5,'The Giant Black Tiger Prawns and Squid Tubes made our family reunion dinner unforgettable! Perfectly cleaned, flash-frozen at peak harvest, and completely free of chemical preservatives.',NULL,1,'approved',5,'2026-09-08 08:26:28','2026-09-08 08:26:28'),(6,'Vikram Nair','F&B Manager, Coastal Grill & Bar (Bangsar)',5,'Transparent wholesale pricing, live inventory visibility, and professional cold logistics. OceanFresh eliminates the headaches typically associated with wet market purchasing.',NULL,1,'approved',6,'2026-09-08 08:26:28','2026-09-08 08:26:28');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'store_name','Mika Import and Export SDN Bhd',NULL,'2026-09-10 01:23:44'),(2,'store_phone','013-2800168',NULL,NULL),(3,'store_email','mikatrading15@gmail.com',NULL,NULL),(4,'store_address','7, Jalan SILC 2/18, Kawasan Perindustrian SILC, 79200 Iskandar Puteri, Johor, Malaysia',NULL,NULL),(5,'store_map_url','https://maps.app.goo.gl/jLMaDYCNJ6vfk376A',NULL,NULL),(6,'site_logo','../images/logo.webp','2026-09-09 03:44:31','2026-09-09 03:44:31'),(7,'site_favicon','../images/favicon.webp','2026-09-09 03:44:31','2026-09-09 03:44:31'),(8,'primary_color','#0f766e','2026-09-09 03:44:31','2026-09-09 03:44:31'),(9,'footer_copyright','© 2026 Meijia International Trading Co., Ltd. All rights reserved.','2026-09-09 03:44:31','2026-09-09 03:53:57'),(12,'site_name','Mika Import and Export SDN Bhd.',NULL,'2026-09-10 01:23:44'),(14,'store_phone_2','011-4360109',NULL,NULL),(15,'store_phone_3','011-2710260',NULL,NULL),(17,'store_tagline','Premium Frozen Seafood Trading & Retail in Malaysia','2026-09-09 04:01:22','2026-09-09 04:01:22'),(18,'store_wholesale_email','','2026-09-09 04:01:22','2026-09-09 04:01:22'),(19,'store_hours','Monday – Saturday: 8:00am – 6:00pm (Sunday & Public Holidays: Closed)','2026-09-09 04:01:22','2026-09-09 04:01:22'),(20,'social_whatsapp','https://wa.me/601112710260','2026-09-09 04:01:22','2026-09-09 04:01:22'),(21,'site_description','Leading B2B and B2C seafood distributor, wholesale importer, and walk-in seafood retail market in Malaysia.','2026-09-10 01:23:44','2026-09-10 01:23:44'),(22,'meta_keywords','frozen seafood, salmon fillet, king prawns, mud crabs, wholesale seafood Malaysia, B2B seafood trading, walk-in seafood market','2026-09-10 01:23:44','2026-09-10 01:23:44'),(23,'canonical_url','','2026-09-10 01:23:44','2026-09-10 01:23:44'),(24,'header_tags','','2026-09-10 01:23:44','2026-09-10 01:23:44'),(25,'footer_tags','','2026-09-10 01:23:44','2026-09-10 01:23:44'),(26,'schema_markup','','2026-09-10 01:23:44','2026-09-10 01:23:44'),(27,'mail_mailer','smtp','2026-09-11 07:45:15','2026-09-11 07:45:15'),(28,'mail_host','smtp.gmail.com','2026-09-11 07:45:15','2026-09-11 07:45:15'),(29,'mail_port','465','2026-09-11 07:45:15','2026-09-11 07:45:15'),(30,'mail_encryption','SSL','2026-09-11 07:45:15','2026-09-11 07:45:15'),(31,'mail_username','einnoventionteam@gmail.com','2026-09-11 07:45:15','2026-09-11 07:45:15'),(32,'mail_password','qbvrzdiruepevsgj','2026-09-11 07:45:15','2026-09-11 07:45:15'),(33,'mail_from_address','no-reply@oceanfresh.com','2026-09-11 07:45:15','2026-09-11 07:45:15'),(34,'mail_from_name','OceanFresh Seafood','2026-09-11 07:45:15','2026-09-11 07:45:15'),(35,'mail_contact_email','ss4871836@gmail.com','2026-09-11 07:45:15','2026-09-11 07:46:24'),(36,'mail_secondary_email','','2026-09-11 07:45:15','2026-09-11 07:46:24'),(37,'stripe_enabled','1','2026-09-11 08:21:40','2026-09-11 08:27:59'),(38,'stripe_test_secret','sk_test_51PyYjkDpoXnXuIQ8fgtlA26eW29YFXwrtG8cpzulvuPAwOm3tzIne68QML22U9DuacErbvw5J7t4YawCJwLnEHP200AZqQG9aF','2026-09-11 08:48:14','2026-09-11 08:50:54'),(39,'stripe_mode','test','2026-09-11 08:50:35','2026-09-11 08:51:05'),(40,'stripe_test_key','pk_test_51PyYjkDpoXnXuIQ8DAZZ7WFJb10fP33E5lpQuehU3VeWSuJLBwl2udcz8zVLiiMhwhSeN32BmHrWn2imeFj5e1mS00qlLHDag3','2026-09-11 08:50:35','2026-09-11 08:50:54'),(41,'stripe_live_key','','2026-09-11 08:50:35','2026-09-11 08:50:35'),(42,'stripe_currency','MYR','2026-09-11 08:50:35','2026-09-11 08:50:35'),(43,'stripe_webhook_secret','','2026-09-11 08:50:35','2026-09-11 08:50:35');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;

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
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_group` enum('retail','wholesale','trading','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'retail',
  `approval_status` enum('approved','pending','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_reg_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@oceanfresh.com',NULL,'$2y$12$MK.VwKqJUeCPliRvNWshx./W/4.By6GreJWlLTibIvGk5F.faoc3G','admin','approved','+60198765432',NULL,NULL,NULL,'123 Seafood Street','Kuala Lumpur','Wilayah Persekutuan','50000',NULL,NULL,NULL,'Ek1qzxNHk34CtEpn5jzd8E7Pl3NvNoq5TMF3KLo8JJYItw3pT7afxJRu3ZwK','2026-09-07 01:23:14','2026-09-09 06:32:38'),(2,'Retail Customer','retail@test.com',NULL,'$2y$12$QXtgGozaUzLvU59t2wIQ3OwWwH.SLxxrkluiJzhiaeKFmgJSK/xza','retail','approved','+60111111111',NULL,NULL,NULL,'1 Jalan Test','Kuala Lumpur','Wilayah Persekutuan','50000',NULL,NULL,NULL,NULL,'2026-09-07 01:23:15','2026-09-07 01:23:15'),(3,'Wholesale Trading Sdn Bhd','wholesale@test.com',NULL,'$2y$12$W9qzX/vfmGUxPHPVM1s9EO.7ktKRnCUdAmvYSShkxZ69WIgQ5Zcha','wholesale','approved','+60122222222','Wholesale Trading Sdn Bhd','202300012345','Restaurant & Catering','2 Jalan Wholesale','Petaling Jaya','Selangor','47810',NULL,NULL,NULL,'AszjLfWD0EvIlRqvb26iuTWQOtVeEITMKLZNy3R9X5e3QDVU79J5ARjW6omf','2026-09-07 01:23:15','2026-09-07 01:23:15'),(4,'Trading Corp Sdn Bhd','trading@test.com',NULL,'$2y$12$zAJ9ch95CTBvcWkQxpLjl.ij7H7klfkPQzpmXzVFhsYVuLzyX0oni','trading','approved','+60133333333','Trading Corp Sdn Bhd','202300054321','Seafood Importer','3 Jalan Trading','Shah Alam','Selangor','40150',NULL,NULL,NULL,NULL,'2026-09-07 01:23:15','2026-09-07 01:23:15'),(5,'Abdul Rehman','ss4871836@gmail.com',NULL,'$2y$12$G1Z8MTlPGn5besXMlKD.5.URcq9TQ6ypI0IQFi1Aw9lFGCOi8.pJ2','retail','approved','+923176121524',NULL,NULL,NULL,'9d3, sultan town, lahore','LAHORE','Wilayah Persekutuan','57450',NULL,NULL,NULL,NULL,'2026-09-11 08:06:03','2026-09-11 08:06:03');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

--
-- Table structure for table `walkin_sessions`
--

DROP TABLE IF EXISTS `walkin_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `walkin_sessions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `walkin_sessions_token_unique` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `walkin_sessions`
--

/*!40000 ALTER TABLE `walkin_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `walkin_sessions` ENABLE KEYS */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-11 20:42:54
