-- phpMyAdmin SQL Dump
-- version 4.8.0-dev
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 01, 2018 at 03:12 PM
-- Server version: 5.7.23-0ubuntu0.16.04.1
-- PHP Version: 7.2.9-1+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_base`
--

-- --------------------------------------------------------

--
-- Table structure for table `action`
--

CREATE TABLE `action` (
  `action_id` int(11) NOT NULL COMMENT 'Actions',
  `controller_id` int(11) NOT NULL COMMENT 'Controller',
  `action_name` char(100) CHARACTER SET utf8 NOT NULL COMMENT 'Name',
  `action_description` char(80) CHARACTER SET utf8 NOT NULL COMMENT 'Description',
  `active` tinyint(1) NOT NULL COMMENT 'Active',
  `created_at` datetime DEFAULT NULL COMMENT 'date created',
  `updated_at` datetime DEFAULT NULL COMMENT 'date updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Actions';

--
-- Dumping data for table `action`
--

INSERT INTO `action` (`action_id`, `controller_id`, `action_name`, `action_description`, `active`, `created_at`, `updated_at`) VALUES
(1, 28, 'index', 'not verified', 1, '2018-10-01 15:12:16', '2018-10-01 15:12:16'),
(2, 29, 'index', 'not verified', 1, '2018-10-01 15:12:17', '2018-10-01 15:12:17'),
(3, 24, 'index', 'not verified', 1, '2018-10-01 15:12:25', '2018-10-01 15:12:25');

-- --------------------------------------------------------

--
-- Table structure for table `blocked`
--

CREATE TABLE `blocked` (
  `id` int(11) NOT NULL COMMENT 'id',
  `ipv4_address` char(20) CHARACTER SET utf8 NOT NULL COMMENT 'IPV4 address',
  `ipv4_address_int` bigint(20) NOT NULL COMMENT 'IPV4 address integer',
  `date` datetime NOT NULL COMMENT 'date time',
  `status_id` int(11) NOT NULL COMMENT 'Status',
  `created_at` datetime NOT NULL COMMENT 'created at',
  `updated_at` datetime NOT NULL COMMENT 'updated at'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Ipv4 Blocked ';

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `company_id` int(11) NOT NULL COMMENT 'Company',
  `company_name` char(60) NOT NULL COMMENT 'Name',
  `address` char(100) NOT NULL COMMENT 'Address',
  `contact_person` char(80) NOT NULL COMMENT 'Contact person',
  `contact_phone_1` char(20) DEFAULT NULL COMMENT 'Contact phone',
  `contact_phone_2` char(20) DEFAULT NULL COMMENT 'Phone additional',
  `contact_phone_3` char(20) DEFAULT NULL COMMENT 'Phone additional',
  `contact_email` char(254) DEFAULT NULL COMMENT 'Contact email',
  `webpage` char(254) DEFAULT NULL COMMENT 'URL Webpage',
  `created_at` datetime NOT NULL COMMENT 'Created at',
  `updated_at` datetime NOT NULL COMMENT 'Updated at',
  `active` tinyint(1) NOT NULL COMMENT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Company';

-- --------------------------------------------------------

--
-- Table structure for table `controllers`
--

CREATE TABLE `controllers` (
  `controller_id` int(11) NOT NULL COMMENT 'Controller',
  `controller_name` char(100) CHARACTER SET utf8 NOT NULL COMMENT 'Name',
  `controller_description` char(80) CHARACTER SET utf8 NOT NULL COMMENT 'Description',
  `menu_boolean_private` tinyint(1) NOT NULL COMMENT 'Menu is private',
  `menu_boolean_visible` tinyint(1) NOT NULL COMMENT 'Menu is visible',
  `active` tinyint(1) NOT NULL COMMENT 'Active',
  `created_at` datetime DEFAULT NULL COMMENT 'date created',
  `updated_at` datetime DEFAULT NULL COMMENT 'date updated'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Controllers';

--
-- Dumping data for table `controllers`
--

INSERT INTO `controllers` (`controller_id`, `controller_name`, `controller_description`, `menu_boolean_private`, `menu_boolean_visible`, `active`, `created_at`, `updated_at`) VALUES
(20, 'user', 'not verified', 1, 0, 1, '2018-08-14 21:02:01', '2018-08-14 21:02:01'),
(21, 'status', 'not verified', 1, 0, 1, '2018-08-14 21:02:04', '2018-08-14 21:02:04'),
(22, 'profile', 'not verified', 1, 0, 1, '2018-08-14 21:02:08', '2018-08-14 21:02:08'),
(23, 'permission', 'not verified', 1, 0, 1, '2018-08-14 21:02:11', '2018-08-14 21:02:11'),
(24, 'logs', 'not verified', 1, 0, 1, '2018-08-14 21:02:14', '2018-08-14 21:02:14'),
(25, 'controllers', 'not verified', 1, 0, 1, '2018-08-14 21:02:18', '2018-08-14 21:02:18'),
(26, 'blocked', 'not verified', 1, 0, 1, '2018-08-14 21:02:21', '2018-08-14 21:02:21'),
(27, 'action', 'not verified', 1, 0, 1, '2018-08-14 21:02:23', '2018-08-14 21:02:23'),
(28, 'site', 'not verified', 1, 0, 1, '2018-08-15 14:41:33', '2018-08-15 14:41:33'),
(29, 'login', 'not verified', 1, 0, 1, '2018-08-15 14:41:35', '2018-08-15 14:41:35'),
(30, 'singup', 'not verified', 1, 0, 1, '2018-08-17 14:53:37', '2018-08-17 14:53:37'),
(31, 'brand', 'not verified', 1, 0, 1, '2018-08-22 12:16:21', '2018-08-22 12:16:21'),
(32, 'categories', 'not verified', 1, 0, 1, '2018-08-22 12:16:36', '2018-08-22 12:16:36'),
(33, 'comments', 'not verified', 1, 0, 1, '2018-08-22 12:16:46', '2018-08-22 12:16:46'),
(34, 'company', 'not verified', 1, 0, 1, '2018-08-22 12:16:55', '2018-08-22 12:16:55'),
(35, 'operatingsystem', 'not verified', 1, 0, 1, '2018-08-22 12:17:00', '2018-08-22 12:17:00'),
(36, 'severity', 'not verified', 1, 0, 1, '2018-08-22 12:17:28', '2018-08-22 12:17:28'),
(37, 'subcategories', 'not verified', 1, 0, 1, '2018-08-22 12:17:40', '2018-08-22 12:17:40'),
(38, 'tickets', 'not verified', 1, 0, 1, '2018-08-22 12:17:45', '2018-08-22 12:17:45'),
(39, 'unit', 'not verified', 1, 0, 1, '2018-08-22 12:17:49', '2018-08-22 12:17:49'),
(40, 'product', 'not verified', 1, 0, 1, '2018-08-24 14:32:48', '2018-08-24 14:32:48'),
(41, 'contact', 'not verified', 1, 0, 1, '2018-09-03 14:51:40', '2018-09-03 14:51:40'),
(42, 'account', 'not verified', 1, 0, 1, '2018-09-16 23:10:20', '2018-09-16 23:10:20');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `logs_id` int(11) NOT NULL COMMENT 'Logs',
  `date` datetime NOT NULL COMMENT 'date',
  `status_id` int(11) NOT NULL COMMENT 'Status',
  `controller_id` int(11) NOT NULL COMMENT 'Controller',
  `action_id` int(11) NOT NULL COMMENT 'Action',
  `event` char(80) CHARACTER SET utf8 NOT NULL COMMENT 'Activity / Event',
  `user_agent` char(250) CHARACTER SET utf8 NOT NULL COMMENT 'user agent browser',
  `ipv4_address` char(20) CHARACTER SET utf8 NOT NULL COMMENT 'ipv4_address',
  `ipv4_address_int` bigint(20) NOT NULL COMMENT 'ipv4_address integer',
  `confirmed` tinyint(1) NOT NULL COMMENT 'ipv4 address confirmed',
  `user_id` int(11) NOT NULL COMMENT 'User'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Logs (user bitacora)';

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`logs_id`, `date`, `status_id`, `controller_id`, `action_id`, `event`, `user_agent`, `ipv4_address`, `ipv4_address_int`, `confirmed`, `user_id`) VALUES
(1, '2018-10-01 15:12:23', 10, 28, 1, 'Mostrando la vista', 'Mozilla/5.0 (X11; Linux x86_64; rv:63.0) Gecko/20100101 Firefox/63.0', '127.0.0.1', 2130706433, 0, 20),
(2, '2018-10-01 15:12:25', 10, 24, 3, 'Mostrando la vista', 'Mozilla/5.0 (X11; Linux x86_64; rv:63.0) Gecko/20100101 Firefox/63.0', '127.0.0.1', 2130706433, 0, 20);

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `permission_id` int(11) NOT NULL COMMENT 'Permission',
  `profile_id` int(11) NOT NULL COMMENT 'Profile',
  `controller_id` int(11) NOT NULL COMMENT 'Controller',
  `action_id` int(11) NOT NULL COMMENT 'Action Name',
  `action_permission` tinyint(1) NOT NULL COMMENT 'Action permission',
  `created_at` datetime NOT NULL COMMENT 'Created at',
  `updated_at` datetime NOT NULL COMMENT 'Updated at'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Permission';

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `profile_id` int(11) NOT NULL COMMENT 'Profile',
  `profile_name` char(80) CHARACTER SET utf8 NOT NULL COMMENT 'Name',
  `created_at` datetime NOT NULL COMMENT 'Date created',
  `updated_at` datetime NOT NULL COMMENT 'date updated',
  `active` tinyint(1) NOT NULL COMMENT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Profiles';

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`profile_id`, `profile_name`, `created_at`, `updated_at`, `active`) VALUES
(10, 'Invited', '2018-07-17 00:00:00', '2018-07-17 00:00:00', 1),
(20, 'user', '2018-07-17 00:00:00', '2018-07-17 00:00:00', 1),
(99, 'Administrator', '2018-07-18 00:00:00', '2018-07-18 00:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `id` char(40) CHARACTER SET utf8 NOT NULL COMMENT 'id',
  `expire` int(11) DEFAULT NULL COMMENT 'date time expire session',
  `data` blob COMMENT 'data token'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Sessions of this web application';

--
-- Dumping data for table `session`
--

INSERT INTO `session` (`id`, `expire`, `data`) VALUES
('6a6v9315gt0a4kve2rvakgetot', 1538418985, 0x5f5f666c6173687c613a303a7b7d5f5f69647c693a32303b7061676553697a657c733a313a2235223b);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `status_id` int(11) NOT NULL COMMENT 'Status',
  `status_name` char(80) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Status message',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created at',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Updated at',
  `active` tinyint(1) DEFAULT NULL COMMENT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Informative status of events in all the platform';

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`status_id`, `status_name`, `created_at`, `updated_at`, `active`) VALUES
(10, 'info', '2018-08-09 11:25:33', '2018-08-09 14:34:19', 1),
(20, 'success', '2018-08-09 11:25:40', '2018-08-09 11:25:40', 1),
(30, 'warning', '2018-08-09 11:25:33', '2018-08-09 11:25:33', 1),
(40, 'error', '2018-08-09 11:25:33', '2018-08-09 11:25:33', 1),
(50, 'Security issue', '2018-09-26 18:29:22', '2018-09-26 18:29:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL COMMENT 'User',
  `username` char(20) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'User account',
  `auth_key` char(32) CHARACTER SET utf8 NOT NULL COMMENT 'key auth',
  `password_hash` char(255) CHARACTER SET utf8 NOT NULL COMMENT 'password',
  `password_reset_token` char(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'password reset token',
  `password_reset_token_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'password reset token date creation',
  `email_confirmation_token` char(255) CHARACTER SET utf8 NOT NULL COMMENT 'Email token of confirmation ',
  `firstName` char(80) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT 'User name',
  `lastName` char(80) COLLATE utf8_bin NOT NULL COMMENT 'Last name',
  `email` char(254) CHARACTER SET utf8 NOT NULL COMMENT 'Email',
  `email_is_verified` tinyint(1) NOT NULL COMMENT 'Boolean is email verified ',
  `telephone` char(15) CHARACTER SET utf8 DEFAULT NULL COMMENT 'Phone number 12 digits',
  `profile_id` int(11) NOT NULL COMMENT 'Profile',
  `company_id` int(11) NOT NULL COMMENT 'Company',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date created',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'date updated',
  `active` tinyint(1) NOT NULL COMMENT 'Active',
  `ipv4_address_last_login` char(20) COLLATE utf8_bin NOT NULL COMMENT 'Last ipv4 address used'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users';

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `auth_key`, `password_hash`, `password_reset_token`, `password_reset_token_date`, `email_confirmation_token`, `firstName`, `lastName`, `email`, `email_is_verified`, `telephone`, `profile_id`, `company_id`, `created_at`, `updated_at`, `active`, `ipv4_address_last_login`) VALUES
(20, 'admin', 'eHCuQ7yHQ13Xsxwy9djir0k5FCbuYKcc', '$2y$13$jL2vB0tP3RGc1r483ETKaea3IZfEbcME.pM8A.xFuAgOp2A3e9X3a', '', '2018-07-17 23:18:18', '5hpbjIKd5FARfDsIsiVI31Vi9huaad7H_1538056684', 'Administrador', 'administrador', 'pro@dev-master.local', 1, '', 99, 4, '2018-07-17 23:18:18', '2018-10-01 15:11:59', 1, '127');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action`
--
ALTER TABLE `action`
  ADD PRIMARY KEY (`action_id`);

--
-- Indexes for table `blocked`
--
ALTER TABLE `blocked`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_blocked_status1_idx` (`status_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `controllers`
--
ALTER TABLE `controllers`
  ADD PRIMARY KEY (`controller_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`logs_id`),
  ADD KEY `fk_logs_controllers1_idx` (`controller_id`),
  ADD KEY `fk_logs_user1_idx` (`user_id`),
  ADD KEY `fk_logs_status1_idx` (`status_id`),
  ADD KEY `fk_logs_action1_idx` (`action_id`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`permission_id`),
  ADD KEY `fk_permission_controllers1_idx` (`controller_id`),
  ADD KEY `fk_permission_profile1` (`profile_id`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`profile_id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `idx_usu_login` (`username`),
  ADD KEY `fk_user_profile_idx` (`profile_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `action`
--
ALTER TABLE `action`
  MODIFY `action_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Actions', AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `blocked`
--
ALTER TABLE `blocked`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id';

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Company';

--
-- AUTO_INCREMENT for table `controllers`
--
ALTER TABLE `controllers`
  MODIFY `controller_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Controller', AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `logs_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Logs', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Permission';

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Profile', AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Status', AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'User', AUTO_INCREMENT=51;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blocked`
--
ALTER TABLE `blocked`
  ADD CONSTRAINT `fk_blocked_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `fk_logs_controllers1` FOREIGN KEY (`controller_id`) REFERENCES `controllers` (`controller_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_logs_status1` FOREIGN KEY (`status_id`) REFERENCES `status` (`status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_logs_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `permission`
--
ALTER TABLE `permission`
  ADD CONSTRAINT `fk_permission_controllers1` FOREIGN KEY (`controller_id`) REFERENCES `controllers` (`controller_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_permission_profile1` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`profile_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_profile` FOREIGN KEY (`profile_id`) REFERENCES `profile` (`profile_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
