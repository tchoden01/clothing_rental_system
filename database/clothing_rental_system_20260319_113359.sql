-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: clothing_rental_system
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

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
-- Current Database: `clothing_rental_system`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `clothing_rental_system` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `clothing_rental_system`;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `rental_start_date` date DEFAULT NULL,
  `rental_end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`),
  KEY `carts_product_id_foreign` (`product_id`),
  CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `seller_id` bigint(20) unsigned DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_seller_id_foreign` (`seller_id`),
  CONSTRAINT `categories_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Traditional Gho','Traditional Bhutanese men\'s clothing',NULL,1,'2026-03-17 00:18:38','2026-03-17 00:18:38'),(2,'Traditional Kira','Traditional Bhutanese women\'s clothing',NULL,1,'2026-03-17 00:18:38','2026-03-17 00:18:38'),(3,'Ceremonial Wear','Special occasion traditional wear',NULL,1,'2026-03-17 00:18:38','2026-03-17 00:18:38'),(4,'Wedding Attire','Traditional wedding clothing',NULL,1,'2026-03-17 00:18:38','2026-03-17 00:18:38'),(5,'Festival Wear','Clothing for festivals and celebrations',NULL,1,'2026-03-17 00:18:38','2026-03-17 00:18:38'),(6,'Others','includes prom dresses or suits',1,1,'2026-03-17 02:30:28','2026-03-17 02:47:50');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `damage_reports`
--

DROP TABLE IF EXISTS `damage_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `damage_reports` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `damage_type` enum('minor_tear','major_tear','stain','missing_accessory','other') DEFAULT NULL,
  `damage_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `reported_by` bigint(20) unsigned NOT NULL,
  `status` enum('pending','approved','disputed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `damage_reports_order_id_foreign` (`order_id`),
  KEY `damage_reports_product_id_foreign` (`product_id`),
  KEY `damage_reports_reported_by_foreign` (`reported_by`),
  CONSTRAINT `damage_reports_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `damage_reports_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `damage_reports_reported_by_foreign` FOREIGN KEY (`reported_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `damage_reports`
--

LOCK TABLES `damage_reports` WRITE;
/*!40000 ALTER TABLE `damage_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `damage_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2026_03_09_092613_create_sellers_table',1),(6,'2026_03_09_092621_create_categories_table',1),(7,'2026_03_09_092622_create_products_table',1),(8,'2026_03_09_092623_create_carts_table',1),(9,'2026_03_09_092623_create_orders_table',1),(10,'2026_03_09_092624_create_damage_reports_table',1),(11,'2026_03_09_092624_create_order_items_table',1),(12,'2026_03_09_092624_create_payments_table',1),(13,'2026_03_09_092624_create_platform_settings_table',1),(14,'2026_03_11_000001_create_subscription_plans_table',1),(15,'2026_03_11_000002_create_user_subscriptions_table',1),(16,'2026_03_13_120000_add_return_status_to_order_items_table',1),(17,'2026_03_16_000002_add_is_suspended_to_users_table',1),(18,'2026_03_17_120000_add_approval_fields_to_categories_table',2),(19,'2026_03_17_130000_create_notifications_table',3),(20,'2026_03_17_140000_expand_product_status_workflow',4),(21,'2026_03_18_000001_add_material_to_products_table',5),(22,'2026_03_18_010000_create_pickups_table',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('046fe9ec-8ea7-4136-ba70-3da8ffb91b95','App\\Notifications\\AdminDecisionNotification','App\\Models\\User',2,'{\"title\":\"Order cancelled by customer\",\"message\":\"An upcoming order was cancelled. Your item is available for rental again.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/seller\\/orders\",\"action_label\":\"View seller orders\"}','2026-03-17 04:00:00','2026-03-17 03:54:25','2026-03-17 04:00:00'),('303b346e-714e-4841-9dc0-1578a52b45dd','App\\Notifications\\AdminDecisionNotification','App\\Models\\User',2,'{\"title\":\"Product needs updates\",\"message\":\"Your product \\\"Kira set\\\" is not approved right now. Please update details and resubmit if needed.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/seller\\/products\\/4\\/edit\",\"action_label\":\"Edit product\"}','2026-03-17 04:00:00','2026-03-17 03:19:21','2026-03-17 04:00:00'),('35fdd43c-46e6-43d6-9cb6-b898af551713','App\\Notifications\\AdminDecisionNotification','App\\Models\\User',2,'{\"title\":\"Product approved\",\"message\":\"Your product \\\"Prom Dress\\\" has been approved and is now visible to customers.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/seller\\/products\",\"action_label\":\"View my products\"}','2026-03-17 03:18:49','2026-03-17 03:09:23','2026-03-17 03:18:49'),('371d2bd5-a011-4a25-ab67-a73fcf50d8ee','App\\Notifications\\AdminDecisionNotification','App\\Models\\User',2,'{\"title\":\"Product approved\",\"message\":\"Your product \\\"Kishuthara\\\" has been approved and is now visible to customers.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/seller\\/products\",\"action_label\":\"View my products\"}','2026-03-17 03:18:49','2026-03-17 03:09:25','2026-03-17 03:18:49'),('3a20313f-a448-48f0-ae99-ca4995f92c33','App\\Notifications\\AdminDecisionNotification','App\\Models\\User',2,'{\"title\":\"Product needs updates\",\"message\":\"Your product \\\"Kira set\\\" is not approved right now. Please update details and resubmit if needed.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/seller\\/products\\/4\\/edit\",\"action_label\":\"Edit product\"}','2026-03-17 04:14:16','2026-03-17 04:07:49','2026-03-17 04:14:16'),('5f04a90e-8294-4705-a395-995659ca32cd','App\\Notifications\\AdminDecisionNotification','App\\Models\\User',2,'{\"title\":\"Product needs updates\",\"message\":\"Your product \\\"Kira set\\\" is not approved right now. Please update details and resubmit if needed.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/seller\\/products\\/4\\/edit\",\"action_label\":\"Edit product\"}','2026-03-17 04:14:16','2026-03-17 04:08:02','2026-03-17 04:14:16'),('68ba2d3d-2117-4842-ad8c-e91a47974dcf','App\\Notifications\\AdminDecisionNotification','App\\Models\\User',2,'{\"title\":\"Product needs updates\",\"message\":\"Your product \\\"Kira set\\\" is not approved right now. Please update details and resubmit if needed.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/seller\\/products\\/4\\/edit\",\"action_label\":\"Edit product\"}','2026-03-17 03:18:49','2026-03-17 03:18:06','2026-03-17 03:18:49'),('988e6019-e1ce-4fd5-b0ae-e856c3e981cd','App\\Notifications\\AdminDecisionNotification','App\\Models\\User',2,'{\"title\":\"Product needs updates\",\"message\":\"Your product \\\"Kira set\\\" is not approved right now. Please update details and resubmit if needed.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/seller\\/products\\/4\\/edit\",\"action_label\":\"Edit product\"}','2026-03-17 03:18:49','2026-03-17 03:17:47','2026-03-17 03:18:49'),('a1c3f571-463e-4d80-b9b0-ac976be74800','App\\Notifications\\AdminDecisionNotification','App\\Models\\User',2,'{\"title\":\"Order cancelled by customer\",\"message\":\"An upcoming order was cancelled. Your item is available for rental again.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/seller\\/orders\",\"action_label\":\"View seller orders\"}','2026-03-17 04:00:00','2026-03-17 03:40:06','2026-03-17 04:00:00'),('a1d0b698-ba54-47c3-a340-43cdca867124','App\\Notifications\\AdminDecisionNotification','App\\Models\\User',2,'{\"title\":\"Product approved\",\"message\":\"Your product \\\"Gho\\\" has been approved and is now visible to customers.\",\"action_url\":\"http:\\/\\/127.0.0.1:8000\\/seller\\/products\",\"action_label\":\"View my products\"}','2026-03-17 23:02:22','2026-03-17 23:02:04','2026-03-17 23:02:22');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `seller_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL,
  `rental_price` decimal(10,2) NOT NULL,
  `seller_earnings` decimal(10,2) NOT NULL,
  `rental_start_date` date NOT NULL,
  `rental_end_date` date NOT NULL,
  `return_status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  KEY `order_items_seller_id_foreign` (`seller_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,2,1,1,250.00,212.50,'2026-03-18','2026-03-26','pending','2026-03-17 03:26:16','2026-03-17 03:26:16'),(2,2,3,1,1,300.00,255.00,'2026-03-18','2026-03-19','pending','2026-03-17 03:54:05','2026-03-17 03:54:05'),(3,3,1,1,1,200.00,1360.00,'2026-03-17','2026-03-25','returned','2026-03-17 03:55:02','2026-03-17 04:00:25'),(4,4,2,1,1,250.00,1062.50,'2026-03-18','2026-03-23','returned','2026-03-18 05:37:28','2026-03-18 05:58:33');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `order_number` varchar(255) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `platform_commission` decimal(10,2) NOT NULL DEFAULT 0.00,
  `delivery_method` enum('pickup','home_delivery') NOT NULL,
  `delivery_address` text DEFAULT NULL,
  `rental_start_date` date NOT NULL,
  `rental_end_date` date NOT NULL,
  `status` enum('pending','confirmed','collected_from_seller','picked_up_by_customer','in_use','returned_by_customer','returned_to_seller','completed','cancelled') NOT NULL DEFAULT 'pending',
  `payment_status` enum('pending','paid','refunded') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,3,'ORD-20260317092616-8032',250.00,37.50,'pickup','Motithang','2026-03-18','2026-03-26','cancelled','refunded','2026-03-17 03:26:16','2026-03-17 03:40:06'),(2,3,'ORD-20260317095405-8541',300.00,45.00,'pickup','Motithang','2026-03-18','2026-03-19','cancelled','refunded','2026-03-17 03:54:05','2026-03-17 03:54:25'),(3,3,'ORD-20260317095502-5622',1600.00,240.00,'pickup','Motithang','2026-03-17','2026-03-25','confirmed','paid','2026-03-17 03:55:02','2026-03-17 03:55:02'),(4,3,'ORD-20260318113728-6555',1250.00,187.50,'pickup','Motithang','2026-03-18','2026-03-23','confirmed','paid','2026-03-18 05:37:28','2026-03-18 06:12:27');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `payment_method` enum('digital','cash_on_delivery') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_order_id_foreign` (`order_id`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,'digital',250.00,'refunded','TXN-69b91e3803b5e','2026-03-17 03:26:16','2026-03-17 03:40:06'),(2,2,'digital',300.00,'refunded','TXN-69b924bd3d8ed','2026-03-17 03:54:05','2026-03-17 03:54:25'),(3,3,'digital',1600.00,'completed','TXN-69b924f64cd42','2026-03-17 03:55:02','2026-03-17 03:55:02'),(4,4,'digital',1250.00,'completed','TXN-69ba8e7845e0b','2026-03-18 05:37:28','2026-03-18 05:37:28');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
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
-- Table structure for table `pickups`
--

DROP TABLE IF EXISTS `pickups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pickups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `order_item_id` bigint(20) unsigned NOT NULL,
  `seller_id` bigint(20) unsigned DEFAULT NULL,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `pickup_date` date NOT NULL,
  `return_date` date NOT NULL,
  `pickup_status` varchar(255) NOT NULL DEFAULT 'pending_pickup',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pickups_order_id_order_item_id_unique` (`order_id`,`order_item_id`),
  KEY `pickups_order_item_id_foreign` (`order_item_id`),
  KEY `pickups_seller_id_foreign` (`seller_id`),
  KEY `pickups_customer_id_foreign` (`customer_id`),
  KEY `pickups_pickup_status_index` (`pickup_status`),
  KEY `pickups_pickup_date_index` (`pickup_date`),
  KEY `pickups_return_date_index` (`return_date`),
  CONSTRAINT `pickups_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `pickups_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pickups_order_item_id_foreign` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pickups_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pickups`
--

LOCK TABLES `pickups` WRITE;
/*!40000 ALTER TABLE `pickups` DISABLE KEYS */;
INSERT INTO `pickups` VALUES (1,3,3,1,3,'2026-03-17','2026-03-25','pending_pickup','2026-03-18 06:23:50','2026-03-18 06:23:50'),(2,4,4,1,3,'2026-03-18','2026-03-23','pending_pickup','2026-03-18 06:23:50','2026-03-18 06:23:50');
/*!40000 ALTER TABLE `pickups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `platform_settings`
--

DROP TABLE IF EXISTS `platform_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `platform_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `platform_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `platform_settings`
--

LOCK TABLES `platform_settings` WRITE;
/*!40000 ALTER TABLE `platform_settings` DISABLE KEYS */;
INSERT INTO `platform_settings` VALUES (1,'commission_rate','20','Platform commission rate in percentage','2026-03-17 00:18:38','2026-03-18 06:05:12');
/*!40000 ALTER TABLE `platform_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `seller_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `size` varchar(255) DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `color` varchar(255) DEFAULT NULL,
  `condition` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `rental_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('pending','approved','available','rented','returned','rejected','unavailable') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_seller_id_foreign` (`seller_id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_seller_id_foreign` FOREIGN KEY (`seller_id`) REFERENCES `sellers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,3,'Gho','Free Size',NULL,'colourful','Good','Thimphu','This gho fabric features colorful traditional Bhutanese woven patterns. It has a mix of red, orange, green, white, and blue stripes with detailed geometric designs. The fabric looks thick and neatly woven, making it suitable for making a traditional Bhutanese gho for formal or cultural occasions.',200.00,1,'[\"products\\/WpPpMC3YpXYDoplsulmm5nSuJL7wKRsqGreKYIb0.jpg\"]',1,'available','2026-03-17 02:15:30','2026-03-17 04:00:25'),(2,1,6,'Prom Dress','L',NULL,'Baby Pink','New','Thimphu','Elegant blush pink prom dress featuring a beautifully embroidered lace bodice and a smooth satin skirt. Designed with delicate crisscross back straps, flattering waist ruching, and a stylish high slit for a graceful and modern look. Perfect for prom, formal events, and special occasions.',250.00,1,'[\"products\\/xDtfTB2Jx6L3B2oi5LSv0pzY2nfUSkc2PIvy8N3X.jpg\"]',1,'available','2026-03-17 02:52:38','2026-03-18 05:58:33'),(3,1,3,'Kishuthara','Free Size',NULL,'White','Good','Thimphu','Beautiful traditional Kishuthara for women featuring colorful woven patterns and detailed designs. Made with elegant Bhutanese textile, it adds a vibrant and graceful look when worn. Perfect for festivals, special occasions, and cultural events.',300.00,1,'[\"products\\/R0FLWRy6Q2xXhP0Etm0Ci54REugRpNEcH3qmIpqH.jpg\"]',1,'available','2026-03-17 02:55:48','2026-03-17 03:54:25'),(4,1,4,'Kira set','Free Size',NULL,'Green, white','Fair','Thimphu','Ready-made set',250.00,1,'[\"products\\/LfNHmHIkr1qMjsuh4YykI2Q7yNl3kf7y4LicwK5k.jpg\"]',0,'rejected','2026-03-17 02:57:16','2026-03-17 04:07:49'),(5,1,5,'Gho','Free Size','Cotton, Natural & artificial dyes','Mix','Good','Thimphu','Traditional Bhutanese Gho for men featuring vibrant orange tones with detailed striped patterns. Perfect for festivals, formal events, and traditional occasions.',250.00,1,'[\"products\\/xQTprSQcBcLoWpTvIAynKgFwabuH9Dg1RYYo1hG4.png\",\"products\\/sgekgUdIwJ2TAnrngqU01UxdJJCAlye61cSELY7Y.png\",\"products\\/cIIyTeHJf3ezAdfUdhqSCZysk8Xb8BmZqZjUD6Or.png\",\"products\\/zmTk07aAGhMNmwpTCgfmcjUe76YzxO6AuddOD78E.png\"]',1,'approved','2026-03-17 23:01:04','2026-03-17 23:02:03');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sellers`
--

DROP TABLE IF EXISTS `sellers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sellers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sellers_user_id_foreign` (`user_id`),
  CONSTRAINT `sellers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sellers`
--

LOCK TABLES `sellers` WRITE;
/*!40000 ALTER TABLE `sellers` DISABLE KEYS */;
INSERT INTO `sellers` VALUES (1,2,'Diva','77889955','Main Traffic',1,'2026-03-17 00:19:35','2026-03-17 00:19:51');
/*!40000 ALTER TABLE `sellers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_plans`
--

DROP TABLE IF EXISTS `subscription_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscription_plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `item_limit` int(11) NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `first_month_price` decimal(8,2) DEFAULT NULL,
  `swap_days` int(11) NOT NULL DEFAULT 30,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_plans_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_plans`
--

LOCK TABLES `subscription_plans` WRITE;
/*!40000 ALTER TABLE `subscription_plans` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscription_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_subscriptions`
--

DROP TABLE IF EXISTS `user_subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `subscription_plan_id` bigint(20) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `next_billing_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','cancelled','expired') NOT NULL DEFAULT 'active',
  `items_currently_rented` int(11) NOT NULL DEFAULT 0,
  `is_first_month` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_subscriptions_user_id_foreign` (`user_id`),
  KEY `user_subscriptions_subscription_plan_id_foreign` (`subscription_plan_id`),
  CONSTRAINT `user_subscriptions_subscription_plan_id_foreign` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_subscriptions`
--

LOCK TABLES `user_subscriptions` WRITE;
/*!40000 ALTER TABLE `user_subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','seller','admin') NOT NULL DEFAULT 'customer',
  `is_suspended` tinyint(1) NOT NULL DEFAULT 0,
  `contact_number` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin User','admin@clothing.com',NULL,'$2y$12$N7AgN9ULSA3WSDrnKnxAnOj8wiRzc4eC5R1o4/0UMdfSYBLd0bnv6','admin',0,'1234567890','Admin Office',NULL,'2026-03-17 00:18:38','2026-03-17 00:18:38'),(2,'Dina Shree Ghalley','dinashree@gmail.com',NULL,'$2y$12$dL9fTVSNXVv.BHj02eYHkuMYMEjgBakvLHlwXZ681WmOTWemZbczi','seller',0,'77889955','Main Traffic',NULL,'2026-03-17 00:19:35','2026-03-17 00:19:35'),(3,'Tshewang Choden','tshewangchoden113@gmail.com',NULL,'$2y$12$piWG2qtOkjMgewCB1R/hXOQxrZhWnKGvnl6a6PnEdC.373Jjcdavy','customer',0,'77755723','Motithang',NULL,'2026-03-17 03:25:33','2026-03-17 03:25:33');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'clothing_rental_system'
--

--
-- Dumping routines for database 'clothing_rental_system'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-19 11:34:00
