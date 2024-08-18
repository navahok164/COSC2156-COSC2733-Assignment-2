-- MySQL dump 10.13  Distrib 8.0.39, for Linux (x86_64)
--
-- Host: localhost    Database: estateDB
-- ------------------------------------------------------
-- Server version	8.0.39-0ubuntu0.22.04.1

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
-- Table structure for table `estateInfo`
--

DROP TABLE IF EXISTS `estateInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estateInfo` (
  `estate_id` int NOT NULL AUTO_INCREMENT,
  `owner_id` int NOT NULL,
  `address` varchar(255) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `description` text,
  `status` enum('Available','Sold','Pending') NOT NULL DEFAULT 'Available',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`estate_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estateInfo`
--

LOCK TABLES `estateInfo` WRITE;
/*!40000 ALTER TABLE `estateInfo` DISABLE KEYS */;
INSERT INTO `estateInfo` VALUES (1,1,'22 Le Duan, Ben Nghe, District 1, Ho Chi Minh City',250000.00,'Apartment','Available','2024-08-12 16:53:04'),(2,2,'123 Tran Hung Dao, Hoan Kiem, Hanoi',300000.00,'House','Sold','2024-08-12 16:53:04'),(3,3,'12 Nguyen Hue, Ben Thanh, District 1, Ho Chi Minh City',275000.00,'Land','Pending','2024-08-12 16:53:04'),(4,4,'98 Hang Buom, Hoan Kiem, Hanoi',150000.00,'Apartment','Available','2024-08-12 16:53:04'),(5,5,'45 Ly Tu Trong, Ben Nghe, District 1, Ho Chi Minh City',500000.00,'House','Sold','2024-08-12 16:53:04');
/*!40000 ALTER TABLE `estateInfo` ENABLE KEYS */;
UNLOCK TABLES;
