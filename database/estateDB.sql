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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estateInfo`
--

LOCK TABLES `estateInfo` WRITE;
/*!40000 ALTER TABLE `estateInfo` DISABLE KEYS */;
INSERT INTO `estateInfo` VALUES (1,1,'22 Le Duan, Ben Nghe, District 1, Ho Chi Minh City',250000.00,'Apartment','Available','2024-08-12 16:53:04'),(2,2,'123 Tran Hung Dao, Hoan Kiem, Hanoi',300000.00,'House','Sold','2024-08-12 16:53:04'),(3,3,'12 Nguyen Hue, Ben Thanh, District 1, Ho Chi Minh City',275000.00,'Land','Pending','2024-08-12 16:53:04'),(4,4,'98 Hang Buom, Hoan Kiem, Hanoi',150000.00,'Apartment','Available','2024-08-12 16:53:04'),(5,5,'45 Ly Tu Trong, Ben Nghe, District 1, Ho Chi Minh City',500000.00,'House','Sold','2024-08-12 16:53:04');
/*!40000 ALTER TABLE `estateInfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'test1','securepass1','Nguyen Van A','2024-08-12 16:52:55'),(2,'test2','securepass2','Tran Thi B','2024-08-12 16:52:55'),(3,'test3','securepass3','Le Van C','2024-08-12 16:52:55'),(4,'test4','securepass4','Pham Thi D','2024-08-12 16:52:55'),(5,'test5','securepass5','Hoang Van E','2024-08-12 16:52:55');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-08-13  0:31:27
