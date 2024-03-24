-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 24, 2022 at 08:40 AM
-- Server version: 5.7.21
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel_sm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `ad_no` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` text NOT NULL,
  `role` varchar(20) DEFAULT NULL,
  `emp_no` int(11) DEFAULT NULL,
  PRIMARY KEY (`ad_no`),
  KEY `admins_ibfk_1` (`emp_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`ad_no`, `username`, `password`, `role`, `emp_no`) VALUES
(1, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'admin', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
CREATE TABLE IF NOT EXISTS `books` (
  `bo_no` int(11) NOT NULL AUTO_INCREMENT,
  `gu_state` int(11) DEFAULT NULL,
  `bo_payed_amount` int(11) DEFAULT NULL,
  `bo_date` date NOT NULL,
  `bo_arrive_date` date NOT NULL,
  `bo_people_no` int(11) NOT NULL,
  `rm_no` int(11) DEFAULT NULL,
  `gu_no` int(11) DEFAULT NULL,
  PRIMARY KEY (`bo_no`),
  KEY `gu_no` (`gu_no`),
  KEY `rm_no` (`rm_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`bo_no`, `gu_state`, `bo_payed_amount`, `bo_date`, `bo_arrive_date`, `bo_people_no`, `rm_no`, `gu_no`) VALUES
(1, 0, 10000, '2022-08-06', '2022-08-27', 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

DROP TABLE IF EXISTS `department`;
CREATE TABLE IF NOT EXISTS `department` (
  `dept_no` int(11) NOT NULL AUTO_INCREMENT,
  `dept_name` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`dept_no`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`dept_no`, `dept_name`) VALUES
(1, 'IT'),
(2, 'Management'),
(3, 'Hotel Management'),
(4, 'Reception'),
(5, 'Carrier'),
(6, 'Security');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;
CREATE TABLE IF NOT EXISTS `employees` (
  `emp_no` int(11) NOT NULL AUTO_INCREMENT,
  `emp_name` varchar(50) NOT NULL,
  `emp_id_card` int(11) NOT NULL,
  `emp_email` varchar(100) NOT NULL,
  `gender` varchar(8) DEFAULT NULL,
  `emp_br_date` date DEFAULT NULL,
  `emp_age` int(11) DEFAULT NULL,
  `emp_hr_date` date DEFAULT NULL,
  `emp_phone` int(11) DEFAULT NULL,
  `emp_address` varchar(100) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `emp_hours_work` int(11) DEFAULT NULL,
  `dept_no` int(11) DEFAULT NULL,
  PRIMARY KEY (`emp_no`),
  UNIQUE KEY `emp_id_card` (`emp_id_card`),
  UNIQUE KEY `emp_email` (`emp_email`),
  KEY `dept_no` (`dept_no`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`emp_no`, `emp_name`, `emp_id_card`, `emp_email`, `gender`, `emp_br_date`, `emp_age`, `emp_hr_date`, `emp_phone`, `emp_address`, `salary`, `emp_hours_work`, `dept_no`) VALUES
(1, 'Emp1', 123456789, 'emp1@gmail.com', 'Male', '1999-06-23', 23, '2022-08-23', 777777777, 'Sana\'a', 150000, 12, 1),
(2, 'Emp2', 987654321, 'emp2@gmail.com', 'Female', '1998-07-23', 24, '2022-08-23', 777888888, 'Sana\'a', 150000, 12, 2),
(3, 'Emp3', 147258369, 'emp3@gmail.com', 'Male', '1997-07-23', 25, '2022-08-23', 777999999, 'Sana\'a', 150000, 12, 3),
(4, 'Emp4', 852369741, 'emp4@gmail.com', 'Male', '2002-02-27', 20, '2022-08-23', 777444444, 'Sana\'a', 30000, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

DROP TABLE IF EXISTS `guests`;
CREATE TABLE IF NOT EXISTS `guests` (
  `gu_no` int(11) NOT NULL AUTO_INCREMENT,
  `gu_name` varchar(50) DEFAULT NULL,
  `gender` varchar(8) NOT NULL,
  `gu_phone` int(11) DEFAULT NULL,
  `gu_email` varchar(100) DEFAULT NULL,
  `gu_address` varchar(100) DEFAULT NULL,
  `gu_code_type` varchar(12) NOT NULL,
  `gu_code` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`gu_no`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`gu_no`, `gu_name`, `gender`, `gu_phone`, `gu_email`, `gu_address`, `gu_code_type`, `gu_code`) VALUES
(1, 'mo', 'Male', 777, 'mo@gmail.com', 'gh', 'Annajm', '7879887');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `or_no` int(11) NOT NULL AUTO_INCREMENT,
  `or_time` datetime DEFAULT NULL,
  `or_mount` int(11) DEFAULT NULL,
  `or_type` varchar(20) DEFAULT NULL,
  `or_details` text,
  `or_money` int(11) DEFAULT NULL,
  `or_state` int(11) DEFAULT NULL,
  `gu_no` int(11) DEFAULT NULL,
  `rm_no` int(11) DEFAULT NULL,
  `floor_no` int(11) DEFAULT NULL,
  `emp_no` int(11) DEFAULT NULL,
  PRIMARY KEY (`or_no`),
  KEY `gu_no` (`gu_no`),
  KEY `rm_no` (`rm_no`),
  KEY `emp_no` (`emp_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

DROP TABLE IF EXISTS `rooms`;
CREATE TABLE IF NOT EXISTS `rooms` (
  `rm_no` int(11) NOT NULL AUTO_INCREMENT,
  `floor_no` int(11) DEFAULT NULL,
  `rm_state` int(11) DEFAULT NULL,
  `rm_people_capacity` int(11) DEFAULT NULL,
  `rm_type_no` int(11) DEFAULT NULL,
  `rm_cost` varchar(10) DEFAULT NULL,
  `rm_details` text,
  `rm_rate` int(11) DEFAULT NULL,
  `rm_img` text,
  PRIMARY KEY (`rm_no`),
  KEY `rm_type_no` (`rm_type_no`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`rm_no`, `floor_no`, `rm_state`, `rm_people_capacity`, `rm_type_no`, `rm_cost`, `rm_details`, `rm_rate`, `rm_img`) VALUES
(2, 3, 0, 4, 1, '10000', 'These rooms are so special have all perfect services to all vip people its location near to beach', 5, '7183340352_3ca157aa0ae0fc439870a6216197c1ef.jpg'),
(3, 1, 0, 2, 6, '3000', 'These rooms are simple for few people and has good services', 3, '4239109540_7b0c7895e034a0562913ed28d5a36493.jpg'),
(4, 5, 0, 2, 5, '8000', 'These rooms are so perfect to spend the time of honey moon in our hotel', 4, '6709876856_9e8176e0d6531a3a5f50daaa29c2bd45.jpg'),
(5, 6, 0, 4, 2, '5000', 'These rooms are so good for families who want to spend funny time with our hotel', 4, '9744137027_69e798faf596cabd9fb8710136202711.jpg'),
(6, 4, 0, 2, 3, '4000', 'These rooms are very good to take a comfortable after a big time of hard working', 3, '738318912_91a011255930b0709cf698971739173c.jpg'),
(7, 7, 0, 2, 6, '3000', 'These rooms are simple for few people and has good services', 3, '176893250_886aa937b88fdfeae28e0b67fc88c106.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `rooms_type`
--

DROP TABLE IF EXISTS `rooms_type`;
CREATE TABLE IF NOT EXISTS `rooms_type` (
  `rm_type_no` int(11) NOT NULL AUTO_INCREMENT,
  `rm_type_name` varchar(30) NOT NULL,
  PRIMARY KEY (`rm_type_no`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rooms_type`
--

INSERT INTO `rooms_type` (`rm_type_no`, `rm_type_name`) VALUES
(1, 'VIP'),
(2, 'Big Room'),
(3, 'Room'),
(4, 'Suite'),
(5, 'Royal'),
(6, 'Simple Room');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_ibfk_1` FOREIGN KEY (`emp_no`) REFERENCES `employees` (`emp_no`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`gu_no`) REFERENCES `guests` (`gu_no`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `books_ibfk_2` FOREIGN KEY (`rm_no`) REFERENCES `rooms` (`rm_no`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`dept_no`) REFERENCES `department` (`dept_no`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`gu_no`) REFERENCES `guests` (`gu_no`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`rm_no`) REFERENCES `rooms` (`rm_no`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`emp_no`) REFERENCES `employees` (`emp_no`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`rm_type_no`) REFERENCES `rooms_type` (`rm_type_no`) ON DELETE SET NULL ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
