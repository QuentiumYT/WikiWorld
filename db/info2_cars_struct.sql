SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `info2_cars` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `info2_cars`;

DROP TABLE IF EXISTS `cars`;
CREATE TABLE IF NOT EXISTS `cars` (
  `car_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `car_name` varchar(255) NOT NULL,
  `car_brand_id` bigint(20) UNSIGNED NOT NULL,
  `car_desc` text NOT NULL,
  `car_pic` varchar(255) NOT NULL,
  `car_date_start` year(4) NOT NULL,
  `car_date_end` year(4) NOT NULL,
  `car_motor` varchar(63) NOT NULL,
  `car_transmission` tinyint(4) NOT NULL,
  `car_drivetrain` varchar(3) NOT NULL,
  PRIMARY KEY (`car_id`),
  UNIQUE KEY `car_id` (`car_id`),
  KEY `car_brand_id` (`car_brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `constructors`;
CREATE TABLE IF NOT EXISTS `constructors` (
  `brand_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `brand_name` varchar(127) NOT NULL,
  `brand_desc` text DEFAULT NULL,
  `brand_pic` varchar(255) NOT NULL,
  PRIMARY KEY (`brand_id`),
  UNIQUE KEY `brand_id` (`brand_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `editions`;
CREATE TABLE IF NOT EXISTS `editions` (
  `style_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `style_name` varchar(63) NOT NULL,
  `style_pic` varchar(255) DEFAULT NULL,
  `style_class` varchar(1) NOT NULL,
  `style_overall` int(11) NOT NULL,
  `style_topspeed` int(11) NOT NULL,
  `style_acceleration` int(11) NOT NULL,
  `style_handling` int(11) NOT NULL,
  `style_price` int(11) UNSIGNED DEFAULT NULL,
  `style_price_sb` int(11) UNSIGNED DEFAULT NULL,
  UNIQUE KEY `style_id` (`style_id`),
  KEY `car_id` (`car_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `favorites`;
CREATE TABLE IF NOT EXISTS `favorites` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `stars` tinyint(4) NOT NULL,
  PRIMARY KEY (`user_id`,`car_id`),
  KEY `user_id` (`user_id`),
  KEY `car_id` (`car_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `players`;
CREATE TABLE IF NOT EXISTS `players` (
  `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_ip` varchar(15) DEFAULT NULL,
  `user_city` varchar(63) DEFAULT NULL,
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `user_name` (`user_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`car_brand_id`) REFERENCES `constructors` (`brand_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `editions`
  ADD CONSTRAINT `editions_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `players` (`user_id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`car_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
