-- MySQL dump 10.13  Distrib 8.0.16, for Win64 (x86_64)
--
-- Host: localhost    Database: apache_bench
-- ------------------------------------------------------
-- Server version	8.0.16

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8mb4 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `apache_bench`
--
CREATE USER 'test'@'localhost' IDENTIFIED WITH mysql_native_password BY 'testpassword';
GRANT ALL PRIVILEGES ON *.* TO 'test'@'localhost' WITH GRANT OPTION;
CREATE USER 'test'@'%' IDENTIFIED WITH mysql_native_password BY 'testpassword';
GRANT ALL PRIVILEGES ON *.* TO 'test'@'%' WITH GRANT OPTION;

CREATE DATABASE IF NOT EXISTS `apache_bench` COLLATE 'utf8_general_ci' ;
GRANT ALL ON `apache_bench`.* TO 'test'@'%' ;
FLUSH PRIVILEGES ;
USE apache_bench;
SET character_set_client = utf8mb4;
CREATE TABLE `table_test` (`id` int(10) unsigned NOT NULL AUTO_INCREMENT, `data` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
LOCK TABLES `table_test` WRITE;
INSERT INTO `table_test` VALUES (1,'test');
UNLOCK TABLES;
