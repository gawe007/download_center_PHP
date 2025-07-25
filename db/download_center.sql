-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2025 at 10:41 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `download_center`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_log`
--

CREATE TABLE `access_log` (
  `id` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `action` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '[Default Display Name]]',
  `extension` varchar(10) NOT NULL,
  `file_name` text NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `sha256` varchar(64) DEFAULT NULL,
  `categories` text NOT NULL,
  `need_clearance` tinyint(1) NOT NULL DEFAULT 0,
  `clearance_level` tinyint(1) NOT NULL DEFAULT 0,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `operating_system` varchar(100) NOT NULL,
  `version` varchar(10) NOT NULL,
  `publisher` varchar(100) NOT NULL,
  `publisher_link` text NOT NULL,
  `information` varchar(300) NOT NULL,
  `architecture` varchar(10) NOT NULL,
  `downloaded_count` int(20) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `id_user`, `name`, `extension`, `file_name`, `file_type`, `file_size`, `sha256`, `categories`, `need_clearance`, `clearance_level`, `deleted`, `timestamp`, `operating_system`, `version`, `publisher`, `publisher_link`, `information`, `architecture`, `downloaded_count`) VALUES
(8, 1, 'Demo Public File', 'txt', 'file-688330e86f80d.txt', 'undefined', 20, 'bcc98465792a6349c44c8a9f596cd2b313394bb0c2e8995e02ff2ef0703413c5', 'demo,file,public', 0, 0, 0, '2025-07-25 07:23:21', 'Other', '1.0', 'Admin', '', 'This is a demo file for public download. This file should be able to be downloaded without login.', 'Other', 1),
(9, 1, 'Demo Private File', 'txt', 'file-6883316539d48.txt', 'undefined', 20, '11566f3d4782a38daa6ec6bf1583578a2568155e9b59f52f0ea668d34ce65db8', 'demo,file,private', 1, 1, 0, '2025-07-25 07:25:26', 'Other', '1.0', 'Admin', '', 'This is demo for private file. This file can be downloaded only if a user has logged in.', 'Other', 1),
(10, 1, 'Demo Admin File', 'txt', 'file-688331b4d19a3.txt', 'undefined', 18, '6242091de8dac89f76551e68ba5cf92eadf023db5abfc836a6b3505444f655b7', 'demo,file,admin', 1, 2, 0, '2025-07-25 07:26:45', 'Other', '1.0', 'Admin', '', 'This is demo for admin file. This can only be downloaded if logged user has admin/system clearance.', 'Other', 2);

-- --------------------------------------------------------

--
-- Table structure for table `session`
--

CREATE TABLE `session` (
  `id` int(10) NOT NULL,
  `auth_token` text NOT NULL,
  `id_user` int(10) NOT NULL,
  `created` int(12) NOT NULL,
  `life` int(10) NOT NULL,
  `keep_alive` tinyint(1) NOT NULL DEFAULT 1,
  `mysql_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` text NOT NULL,
  `name` varchar(30) NOT NULL,
  `level` tinyint(1) NOT NULL DEFAULT 1,
  `timestamp` timestamp NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `name`, `level`, `timestamp`) VALUES
(1, 'admin1@localhost.com', '$2y$10$n9U8TGdHfKptjv9BkrmSmeXwERHLB9u9ZGMAsKelljcA/BmASOhWW', 'Administrator 1', 3, '2025-07-15 02:41:25'),
(3, 'user1@localhost.com', '$2y$10$WEOf8F.u8sut0nHgIdVXPuyR9axm0UP2D5hvC1vceq2U5z982GEgq', 'Normal User', 1, '2025-07-15 15:02:22'),
(4, 'user2@localhost.com', '$2y$10$E7vy5rI.VA0Bw.EejeZuZOG/PAgtdEi8bNenPEDLM3qY/gauhr6bS', 'Normal Admin', 2, '2025-07-15 15:09:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_log`
--
ALTER TABLE `access_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `auth_token` (`auth_token`) USING HASH;

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `access_log`
--
ALTER TABLE `access_log`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `session`
--
ALTER TABLE `session`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
