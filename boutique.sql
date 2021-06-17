-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2021 at 08:41 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `boutique`
--
CREATE DATABASE IF NOT EXISTS `boutique` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `boutique`;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--
-- Creation: Jun 12, 2021 at 11:06 AM
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `commandes`
--
-- Creation: Jun 17, 2021 at 03:37 PM
-- Last update: Jun 17, 2021 at 06:07 PM
--

CREATE TABLE IF NOT EXISTS `commandes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `UserCommands` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `lignecommande`
--
-- Creation: Jun 17, 2021 at 03:39 PM
-- Last update: Jun 17, 2021 at 06:07 PM
--

CREATE TABLE IF NOT EXISTS `lignecommande` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `commandeid` int(11) NOT NULL,
  `productid` bigint(20) NOT NULL,
  `Qte` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `LC_Commande` (`commandeid`),
  KEY `LC_product` (`productid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--
-- Creation: Jun 11, 2021 at 10:26 PM
--

CREATE TABLE IF NOT EXISTS `products` (
  `sku` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(70) NOT NULL,
  `price` float NOT NULL,
  `upc` varchar(255) NOT NULL,
  `shipping` float NOT NULL,
  `description` text DEFAULT NULL,
  `manufacturer` varchar(100) DEFAULT NULL,
  `model` varchar(255) DEFAULT NULL,
  `url` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--
-- Creation: Jun 12, 2021 at 11:08 AM
--

CREATE TABLE IF NOT EXISTS `product_categories` (
  `product_id` bigint(20) NOT NULL,
  `category_id` varchar(100) NOT NULL,
  PRIMARY KEY (`product_id`,`category_id`),
  KEY `ManyCategories` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--
-- Creation: Jun 17, 2021 at 12:58 PM
-- Last update: Jun 17, 2021 at 02:22 PM
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commandes`
--
ALTER TABLE `commandes`
  ADD CONSTRAINT `UserCommands` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lignecommande`
--
ALTER TABLE `lignecommande`
  ADD CONSTRAINT `LC_Commande` FOREIGN KEY (`commandeid`) REFERENCES `commandes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `LC_product` FOREIGN KEY (`productid`) REFERENCES `products` (`sku`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `product_categories`
--
ALTER TABLE `product_categories`
  ADD CONSTRAINT `ManyCategories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `OneProduct` FOREIGN KEY (`product_id`) REFERENCES `products` (`sku`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
