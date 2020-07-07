-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 07, 2020 at 04:31 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.2.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `approve`
--

CREATE TABLE `approve` (
  `ap_id` int(2) NOT NULL,
  `ap_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `ap_descriptions` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `approve`
--

INSERT INTO `approve` (`ap_id`, `ap_name`, `ap_descriptions`) VALUES
(1, 'Approve', '-'),
(2, 'Disapproves', '-');

-- --------------------------------------------------------

--
-- Table structure for table `bk_car`
--

CREATE TABLE `bk_car` (
  `bkcar_id` int(5) NOT NULL,
  `bk_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `bk_idcode` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `bk_tel` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `bk_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `bk_position` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `bk_dept` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `bktime_start` datetime NOT NULL,
  `bktime_end` datetime NOT NULL,
  `bk_destination` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `bk_purpose` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `car_id` int(5) DEFAULT NULL,
  `bk_factory` int(2) NOT NULL,
  `bkemp_id` int(5) DEFAULT NULL,
  `bk_mgr_ap` int(2) DEFAULT NULL,
  `bk_hr_ap` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ROW_FORMAT=COMPRESSED;

--
-- Dumping data for table `bk_car`
--

INSERT INTO `bk_car` (`bkcar_id`, `bk_name`, `bk_idcode`, `bk_tel`, `bk_email`, `bk_position`, `bk_dept`, `bktime_start`, `bktime_end`, `bk_destination`, `bk_purpose`, `car_id`, `bk_factory`, `bkemp_id`, `bk_mgr_ap`, `bk_hr_ap`) VALUES
(24, 'w', 'w', '1252', 'w@w', 'GM', 'w', '2017-08-23 11:00:00', '2017-08-23 16:00:00', 'w', 'w', NULL, 1, 2, NULL, NULL),
(25, 'w', 'w', '1252', 'w@w', 'Staff', 'w', '2017-08-24 08:00:00', '2017-08-24 09:00:00', 'w', 'w', NULL, 1, 2, NULL, NULL),
(26, 'w', 'w', '1252', 'w@w', 'Staff', 'w', '2017-08-25 08:00:00', '2017-08-25 08:30:00', 'w', 'w', NULL, 1, 2, NULL, NULL),
(27, 'w', 'w', '1252', 'w@w', 'Staff', 'w', '2017-08-28 08:00:00', '2017-08-28 08:30:00', 'w', 'w', 7, 1, 2, 1, 1),
(28, 'w', 'w', '1252', 'w@w', 'Staff', 'w', '2017-08-28 08:00:00', '2017-08-28 08:30:00', 'w', 'w', NULL, 1, 3, 2, 2),
(29, 'w', 'w', '1252', 'w@w', 'Staff', 'w', '2017-08-29 11:00:00', '2017-08-29 12:00:00', 'w', 'w', 7, 1, 3, 1, 1),
(30, '1', '1', '1', '1@1', 'Staff', '1', '2017-08-29 11:00:00', '2017-08-29 14:30:00', '1', '1', 8, 1, 3, 1, 1),
(31, 'สมชาย ใจดี', '99999', '1252', 'is-support@tgas.co.th', 'Staff', 'TEST', '2017-08-31 08:00:00', '2017-08-31 15:00:00', 'tgas / kittipong', 'test system', 7, 1, 8, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `bk_interpreter`
--

CREATE TABLE `bk_interpreter` (
  `bkitp_id` int(5) NOT NULL,
  `bk_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `bk_dept` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `bk_tel` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `bk_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `bktime_start` datetime NOT NULL,
  `bktime_end` datetime NOT NULL,
  `itp_id` int(5) DEFAULT NULL,
  `bk_factory` int(2) NOT NULL,
  `bkemp_id` int(5) NOT NULL,
  `bk_mgr_ap` int(2) DEFAULT NULL,
  `bk_hr_ap` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bk_interpreter`
--

INSERT INTO `bk_interpreter` (`bkitp_id`, `bk_name`, `bk_dept`, `bk_tel`, `bk_email`, `bktime_start`, `bktime_end`, `itp_id`, `bk_factory`, `bkemp_id`, `bk_mgr_ap`, `bk_hr_ap`) VALUES
(9, 'w', 'w', '1252', 'w@w', '2017-08-23 10:00:00', '2017-08-23 12:30:00', NULL, 1, 2, 1, NULL),
(10, 'w', 'w', '1252', 'w@w', '2017-08-24 10:00:00', '2017-08-24 14:00:00', NULL, 1, 2, 1, NULL),
(11, 'w', 'w', '1252', 'w@w', '2017-08-25 08:00:00', '2017-08-25 08:30:00', NULL, 1, 2, 1, NULL),
(12, 'w', 'w', '1252', 'w@w', '2017-08-28 08:00:00', '2017-08-28 08:30:00', NULL, 1, 2, 1, NULL),
(13, 'w', 'w', '1252', 'w@w', '2017-08-28 08:00:00', '2017-08-28 09:00:00', 1, 1, 3, 1, 1),
(14, 'w', 'w', '1252', 'w@w', '2017-08-29 11:00:00', '2017-08-29 14:00:00', 1, 1, 3, 1, 1),
(15, '1', '1', '1', '1@1', '2017-08-29 11:00:00', '2017-08-29 14:00:00', NULL, 1, 3, NULL, NULL),
(16, '2', '2', '2', '2@2', '2017-08-29 13:00:00', '2017-08-29 16:00:00', NULL, 1, 2, NULL, NULL),
(17, '3', '3', '3', '3@3', '2017-08-29 13:30:00', '2017-08-29 15:30:00', 2, 1, 2, 1, 1),
(18, 'สมชาย ใจดี', 'TEST', '1252', 'is-support@tgas.co.th', '2017-08-31 08:00:00', '2017-08-31 09:30:00', NULL, 1, 7, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `bk_mtr`
--

CREATE TABLE `bk_mtr` (
  `bkmtr_id` int(5) NOT NULL,
  `bk_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `bk_dept` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `bk_tel` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `bk_email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `bktime_start` datetime NOT NULL,
  `bktime_end` datetime NOT NULL,
  `mtr_id` int(5) NOT NULL,
  `bk_item_request` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `bk_objective` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bk_factory` int(2) NOT NULL,
  `bkemp_id` int(5) DEFAULT NULL,
  `bk_mgr_ap` int(2) DEFAULT NULL,
  `bk_hr_ap` int(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `bk_mtr`
--

INSERT INTO `bk_mtr` (`bkmtr_id`, `bk_name`, `bk_dept`, `bk_tel`, `bk_email`, `bktime_start`, `bktime_end`, `mtr_id`, `bk_item_request`, `bk_objective`, `bk_factory`, `bkemp_id`, `bk_mgr_ap`, `bk_hr_ap`) VALUES
(27, 'w', 'w', '1252', 'w@w', '2017-08-23 08:00:00', '2017-08-23 09:00:00', 3, 'Refreshing Towel - , ', 'w', 1, 2, NULL, NULL),
(28, 'w', 'w', '12345', 'w@w', '2017-08-23 12:00:00', '2017-08-23 14:00:00', 3, '', '', 1, 2, NULL, NULL),
(29, 'w', 'w', '213123', 'w@w', '2017-08-23 14:00:00', '2017-08-23 16:00:00', 3, 'Coffee - , Refreshing Towel - , ', 'w', 1, 2, NULL, NULL),
(33, 'w', 'w', '1252', 'w@w', '2017-08-24 11:00:00', '2017-08-24 15:30:00', 3, '', '', 1, 2, NULL, NULL),
(34, 'w', 'w', '12', 'w@w', '2017-08-25 09:00:00', '2017-08-25 13:30:00', 3, 'Coffee - 5, ', '5', 1, 2, 2, 2),
(35, 'w', 'w', '1252', 'w@w', '2017-08-25 08:00:00', '2017-08-25 08:30:00', 3, '', '', 1, 2, 2, 2),
(36, 'w', 'w', '1252', 'w@w', '2017-08-25 08:00:00', '2017-08-25 08:30:00', 3, '', '', 1, 2, 2, 2),
(38, 'w', 'w', '1252', 'w@w', '2017-08-25 08:00:00', '2017-08-25 08:30:00', 1, '', '', 1, 2, NULL, NULL),
(39, 'w', 'w', '1252', 'w@w', '2017-08-28 08:00:00', '2017-08-28 09:00:00', 2, '', '', 1, 2, 1, 1),
(40, 'w', 'w', '1252', 'w@w', '2017-08-29 11:00:00', '2017-08-29 12:00:00', 1, 'Coffee - 5, ', 'w', 1, 3, 1, 1),
(41, '1', '1', '1', '1@1', '2017-08-29 11:00:00', '2017-08-29 13:00:00', 4, 'Coffee - 1, Tea - 1, Drinking Water - 1, Refreshing Towel - 1, Projector - 1, ', '1', 1, 3, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `car`
--

CREATE TABLE `car` (
  `car_id` int(5) NOT NULL,
  `license_plate` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `driver_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `driver_tel` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `car_brand` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `car_color` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `car_options` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `car_factory` int(2) NOT NULL,
  `car_status` int(2) NOT NULL,
  `car_descriptions` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `car`
--

INSERT INTO `car` (`car_id`, `license_plate`, `driver_name`, `driver_tel`, `car_brand`, `car_color`, `car_options`, `car_factory`, `car_status`, `car_descriptions`) VALUES
(7, '5กล-7261', '1', '1', 'Revo', '#000000', '597164b35364f5.48281428.png', 1, 1, ''),
(8, '4กษ-2291', '2', '2', 'City', '#ffffff', '597164c3761aa5.60900383.png', 1, 1, ''),
(9, '5กร-9343', '3', '3', 'Mazda 2', '#00ffff', '597164d06ea7d4.62117341.png', 1, 1, ''),
(10, '5กจ-4301', '1', '1', 'Atrage', '#1cabe1', '597164db103462.22989698.png', 1, 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `emp_id` int(5) NOT NULL,
  `emp_fname` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `emp_lname` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `emp_tel` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `emp_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `emp_position` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `emp_factory` int(2) NOT NULL,
  `emp_dept` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `emp_user` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `emp_pass` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `emp_status` int(2) NOT NULL,
  `emp_descriptions` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`emp_id`, `emp_fname`, `emp_lname`, `emp_tel`, `emp_email`, `emp_position`, `emp_factory`, `emp_dept`, `emp_user`, `emp_pass`, `emp_status`, `emp_descriptions`) VALUES
(1, 'Chusak', 'Chatsopon', '1215', 'webmaster@tgas.co.th', 'HR Approval', 1, 'HR', '11HR00', 'HR00', 1, NULL),
(2, 'Sawinee', 'Somkliang', '1251', 'sawinees@tgas.co.th', 'Manager', 1, 'HR', '11HR01', 'HR01', 1, NULL),
(3, 'Siriporn', 'Yai-in', '1252', 'siriporny@tgas.co.th', 'User', 1, 'HR', '11HR02', 'HR02', 1, NULL),
(4, 'TESTER', 'Admin', '1251', 'yanaworavat@outlook.com', 'Administrator', 1, 'IS', 'admin', 'admin', 1, NULL),
(5, 'Koson', 'Ratana', '1215', 'kosonr@tgas.co.th', 'Manager', 1, 'IS', '11IS01', 'IS01', 1, NULL),
(6, 'IS', 'Support', '1252', 'is-support@tgas.co.th', 'User', 1, 'IS', '11IS02', 'IS02', 1, NULL),
(7, 'TESTER', 'IS', '1252', 'is-support@tgas.co.th', 'Manager', 1, 'TEST', 'TESTER', '01', 1, NULL),
(8, 'TESTER', 'IS', '1252', 'is-support@tgas.co.th', 'User', 1, 'TEST', 'TESTER', '02', 1, NULL),
(10, 'TESTER', 'IS', NULL, 'webmaster@tgas.co.th', 'HR Approval', 1, 'TEST', 'TESTER', '00', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `factory`
--

CREATE TABLE `factory` (
  `ft_id` int(2) NOT NULL,
  `ft_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ft_descriptions` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `factory`
--

INSERT INTO `factory` (`ft_id`, `ft_name`, `ft_descriptions`) VALUES
(1, 'Toyoda Gosei Asia Co,. Ltd. (TGAS)', '-'),
(2, 'Toyoda Gosei (Thailand) Co,. Ltd. (TGT)', '-'),
(3, 'Toyoda Gosei Rubber (Thailand) Co,. Ltd. (TGRT)', '-');

-- --------------------------------------------------------

--
-- Table structure for table `interpreter`
--

CREATE TABLE `interpreter` (
  `itp_id` int(5) NOT NULL,
  `itp_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `itp_tel` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `itp_options` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `itp_factory` int(2) NOT NULL,
  `itp_status` int(2) NOT NULL,
  `itp_descriptions` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `interpreter`
--

INSERT INTO `interpreter` (`itp_id`, `itp_name`, `itp_tel`, `itp_options`, `itp_factory`, `itp_status`, `itp_descriptions`) VALUES
(1, 'Karen Reed', '0872301893', '5971afd78b2cc0.60760062.png', 1, 1, NULL),
(2, 'Rebecca Jones', '0661506948', '5971afd78b2cc0.60760062.png', 1, 1, NULL),
(3, 'Martha Meyer', '0616956329', '5971afd78b2cc0.60760062.png', 1, 1, NULL),
(4, 'Lori Nichols', '0837814195', '5971afd78b2cc0.60760062.png', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `meeting_room`
--

CREATE TABLE `meeting_room` (
  `mtr_id` int(5) NOT NULL,
  `mtr_number` varchar(5) COLLATE utf8_unicode_ci NOT NULL,
  `mtr_options` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mtr_factory` int(2) NOT NULL,
  `mtr_status` int(2) NOT NULL,
  `mtr_descriptions` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `meeting_room`
--

INSERT INTO `meeting_room` (`mtr_id`, `mtr_number`, `mtr_options`, `mtr_factory`, `mtr_status`, `mtr_descriptions`) VALUES
(1, '201', '5971aff6885af6.67057574.png', 1, 1, NULL),
(2, '202', '59715e26104263.48348778.png', 1, 1, NULL),
(3, '203', '59715e330795f3.52080498.png', 1, 1, NULL),
(4, '204', '59715e3d958374.25417369.png', 1, 1, NULL),
(5, '301', '59715e567ceba1.24042523.png', 1, 1, NULL),
(6, '302', '59715e993d73e7.32072803.png', 1, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `st_id` int(2) NOT NULL,
  `st_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `st_descriptions` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`st_id`, `st_name`, `st_descriptions`) VALUES
(1, 'Enable', '-'),
(2, 'Disable', '-');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `approve`
--
ALTER TABLE `approve`
  ADD PRIMARY KEY (`ap_id`);

--
-- Indexes for table `bk_car`
--
ALTER TABLE `bk_car`
  ADD PRIMARY KEY (`bkcar_id`),
  ADD KEY `car_id` (`car_id`),
  ADD KEY `bk_factory` (`bk_factory`),
  ADD KEY `bkemp_id` (`bkemp_id`),
  ADD KEY `bk_mgr_ap` (`bk_mgr_ap`),
  ADD KEY `bk_hr_ap` (`bk_hr_ap`);

--
-- Indexes for table `bk_interpreter`
--
ALTER TABLE `bk_interpreter`
  ADD PRIMARY KEY (`bkitp_id`),
  ADD KEY `itp_id` (`itp_id`),
  ADD KEY `bk_factory` (`bk_factory`),
  ADD KEY `bkemp_id` (`bkemp_id`),
  ADD KEY `bk_mgr_ap` (`bk_mgr_ap`),
  ADD KEY `bk_hr_ap` (`bk_hr_ap`);

--
-- Indexes for table `bk_mtr`
--
ALTER TABLE `bk_mtr`
  ADD PRIMARY KEY (`bkmtr_id`),
  ADD KEY `mtr_id` (`mtr_id`),
  ADD KEY `bk_factory` (`bk_factory`),
  ADD KEY `bkemp_id` (`bkemp_id`),
  ADD KEY `bk_mgr_ap` (`bk_mgr_ap`),
  ADD KEY `bk_hr_ap` (`bk_hr_ap`);

--
-- Indexes for table `car`
--
ALTER TABLE `car`
  ADD PRIMARY KEY (`car_id`),
  ADD UNIQUE KEY `license_plate` (`license_plate`),
  ADD KEY `car_factory` (`car_factory`),
  ADD KEY `car_status` (`car_status`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`emp_id`),
  ADD KEY `emp_factory` (`emp_factory`),
  ADD KEY `emp_status` (`emp_status`);

--
-- Indexes for table `factory`
--
ALTER TABLE `factory`
  ADD PRIMARY KEY (`ft_id`);

--
-- Indexes for table `interpreter`
--
ALTER TABLE `interpreter`
  ADD PRIMARY KEY (`itp_id`),
  ADD KEY `itp_factory` (`itp_factory`),
  ADD KEY `itp_status` (`itp_status`);

--
-- Indexes for table `meeting_room`
--
ALTER TABLE `meeting_room`
  ADD PRIMARY KEY (`mtr_id`),
  ADD KEY `mtr_factory` (`mtr_factory`),
  ADD KEY `mtr_status` (`mtr_status`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`st_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `approve`
--
ALTER TABLE `approve`
  MODIFY `ap_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bk_car`
--
ALTER TABLE `bk_car`
  MODIFY `bkcar_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `bk_interpreter`
--
ALTER TABLE `bk_interpreter`
  MODIFY `bkitp_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `bk_mtr`
--
ALTER TABLE `bk_mtr`
  MODIFY `bkmtr_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `car`
--
ALTER TABLE `car`
  MODIFY `car_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `emp_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `interpreter`
--
ALTER TABLE `interpreter`
  MODIFY `itp_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `meeting_room`
--
ALTER TABLE `meeting_room`
  MODIFY `mtr_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `st_id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bk_car`
--
ALTER TABLE `bk_car`
  ADD CONSTRAINT `bk_car_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `car` (`car_id`),
  ADD CONSTRAINT `bk_car_ibfk_2` FOREIGN KEY (`bk_factory`) REFERENCES `factory` (`ft_id`),
  ADD CONSTRAINT `bk_car_ibfk_3` FOREIGN KEY (`bkemp_id`) REFERENCES `employee` (`emp_id`),
  ADD CONSTRAINT `bk_car_ibfk_4` FOREIGN KEY (`bk_mgr_ap`) REFERENCES `approve` (`ap_id`),
  ADD CONSTRAINT `bk_car_ibfk_5` FOREIGN KEY (`bk_hr_ap`) REFERENCES `approve` (`ap_id`);

--
-- Constraints for table `bk_interpreter`
--
ALTER TABLE `bk_interpreter`
  ADD CONSTRAINT `bk_interpreter_ibfk_1` FOREIGN KEY (`itp_id`) REFERENCES `interpreter` (`itp_id`),
  ADD CONSTRAINT `bk_interpreter_ibfk_2` FOREIGN KEY (`bk_factory`) REFERENCES `factory` (`ft_id`),
  ADD CONSTRAINT `bk_interpreter_ibfk_3` FOREIGN KEY (`bkemp_id`) REFERENCES `employee` (`emp_id`),
  ADD CONSTRAINT `bk_interpreter_ibfk_4` FOREIGN KEY (`bk_mgr_ap`) REFERENCES `approve` (`ap_id`),
  ADD CONSTRAINT `bk_interpreter_ibfk_5` FOREIGN KEY (`bk_hr_ap`) REFERENCES `approve` (`ap_id`);

--
-- Constraints for table `bk_mtr`
--
ALTER TABLE `bk_mtr`
  ADD CONSTRAINT `bk_mtr_ibfk_1` FOREIGN KEY (`mtr_id`) REFERENCES `meeting_room` (`mtr_id`),
  ADD CONSTRAINT `bk_mtr_ibfk_2` FOREIGN KEY (`bk_factory`) REFERENCES `factory` (`ft_id`),
  ADD CONSTRAINT `bk_mtr_ibfk_3` FOREIGN KEY (`bkemp_id`) REFERENCES `employee` (`emp_id`),
  ADD CONSTRAINT `bk_mtr_ibfk_4` FOREIGN KEY (`bk_mgr_ap`) REFERENCES `approve` (`ap_id`),
  ADD CONSTRAINT `bk_mtr_ibfk_5` FOREIGN KEY (`bk_hr_ap`) REFERENCES `approve` (`ap_id`);

--
-- Constraints for table `car`
--
ALTER TABLE `car`
  ADD CONSTRAINT `car_ibfk_1` FOREIGN KEY (`car_factory`) REFERENCES `factory` (`ft_id`),
  ADD CONSTRAINT `car_ibfk_2` FOREIGN KEY (`car_status`) REFERENCES `status` (`st_id`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`emp_factory`) REFERENCES `factory` (`ft_id`),
  ADD CONSTRAINT `employee_ibfk_2` FOREIGN KEY (`emp_status`) REFERENCES `status` (`st_id`);

--
-- Constraints for table `interpreter`
--
ALTER TABLE `interpreter`
  ADD CONSTRAINT `interpreter_ibfk_1` FOREIGN KEY (`itp_factory`) REFERENCES `factory` (`ft_id`),
  ADD CONSTRAINT `interpreter_ibfk_2` FOREIGN KEY (`itp_status`) REFERENCES `status` (`st_id`);

--
-- Constraints for table `meeting_room`
--
ALTER TABLE `meeting_room`
  ADD CONSTRAINT `meeting_room_ibfk_1` FOREIGN KEY (`mtr_factory`) REFERENCES `factory` (`ft_id`),
  ADD CONSTRAINT `meeting_room_ibfk_2` FOREIGN KEY (`mtr_status`) REFERENCES `status` (`st_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
