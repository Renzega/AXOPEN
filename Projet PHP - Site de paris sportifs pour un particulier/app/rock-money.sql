-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 26 fév. 2019 à 12:05
-- Version du serveur :  5.7.19
-- Version de PHP :  7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `rock-money`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(256) NOT NULL,
  `phone_number` varchar(50) NOT NULL,
  `member_rank` int(2) NOT NULL,
  `subscription_type` int(1) NOT NULL,
  `subscription_date_end` varchar(255) DEFAULT 'NULL',
  `registration_date` varchar(100) NOT NULL,
  `newpassword_key` varchar(255) DEFAULT 'NULL',
  `payer_id` varchar(255) DEFAULT 'NULL',
  `profile_id` varchar(255) DEFAULT 'NULL',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `codes_promo`;
CREATE TABLE IF NOT EXISTS `codes_promo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `secret_code` varchar(255) NOT NULL,
  `promo_value` int(3) NOT NULL,
  `subscription_type` int(2) NOT NULL,
  `for_all` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `invoices`;
CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `client_email` varchar(255) NOT NULL,
  `invoice_date` varchar(255) NOT NULL,
  `invoice_title` varchar(255) NOT NULL,
  `invoice_amount` float(10) NOT NULL,
  `payment_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `paypal_checkouts`;
CREATE TABLE IF NOT EXISTS `paypal_checkouts` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_id` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `payer_id` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pronostics_categories`;
CREATE TABLE IF NOT EXISTS `pronostics_categories` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `category_note` varchar(255) NOT NULL,
  `subscription_type` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pronostics_classes`;
CREATE TABLE IF NOT EXISTS `pronostics_classes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category_id` int(10) NOT NULL,
  `pronostic_title` varchar(255) NOT NULL,
  `pronostic_type` int(1) NOT NULL,
  `publication_date` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pronostics`;
CREATE TABLE IF NOT EXISTS `pronostics` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `class_id` int(10) NOT NULL,
  `sport` varchar(255) NOT NULL,
  `meet` varchar(255) NOT NULL,
  `cote` float(10) NOT NULL,
  `pronostic` varchar(255) NOT NULL,
  `probability` float(10) NOT NULL,
  `analysis` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
