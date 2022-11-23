-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 07, 2022 at 06:55 AM
-- Server version: 8.0.29
-- PHP Version: 7.4.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wordpress`
--

-- --------------------------------------------------------

--
-- Table structure for table `md5_hash`
--

CREATE TABLE `md5_hash` (
  `id` int NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `mobile` varchar(15) NOT NULL,
  `contact_person` varchar(50) DEFAULT NULL,
  `role_id` int NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `md5_hash`
--

INSERT INTO `md5_hash` (`id`, `user_name`, `password`, `mobile`, `contact_person`, `role_id`, `add_date`, `status`) VALUES
(1700, 'noida@spa.com', 'f65df1a40392818c97a5a49269a9d52d', '9999999991', 'B H SHRAVAN KUMAR', 1, '2013-12-01 23:41:24', 1),
(1701, 'prayagraj@spa.com', 'e0cd3a6041c47b8cba4c48010954466a', '9999999992', 'B H KIRAN DEVI', 2, '2013-12-02 06:11:27', 1),
(1702, 'agra@spa.com', '1b7f4afdc7017c367f38859b2a5b27a9', '9999999993', 'B M VISHNU SHANKER', 2, '2013-12-16 04:29:00', 1),
(1703, 'lucknow@spa.com', '6769d1c4c0bd077e7f16c3595e1c7096', '9999999994', 'B H RAHUL KUMAR', 2, '2013-12-19 06:46:26', 1),
(1704, 'varanasi@spa.com', '92a0f83102a4cb6ecbca81a3708f3180', '9999999995', 'B H ANUSHREE SHRIVASTAVA', 3, '2014-06-18 22:19:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `plain_password`
--

CREATE TABLE `plain_password` (
  `id` int NOT NULL,
  `user_login` varchar(50) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_status` tinyint NOT NULL DEFAULT '1',
  `role_id` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `plain_password`
--

INSERT INTO `plain_password` (`id`, `user_login`, `user_email`, `user_pass`, `user_status`, `role_id`, `created_at`) VALUES
(3, 'akash', 'akash@gmail.com', 'Akash@123456', 1, 'authenticated', '2022-02-18 19:45:29'),
(100, 'sanjeev', 'sanjeev@gmail.com', 'sanjeev@12345', 1, 'authenticated', '2022-02-18 17:41:45'),
(101, 'mahendra', 'mahendra@gmail.com', 'mahendra@12345', 1, 'authenticated', '2022-02-18 17:41:45');

-- --------------------------------------------------------

--
-- Table structure for table `sha512_users`
--

CREATE TABLE `sha512_users` (
  `id` int NOT NULL,
  `user_login` varchar(50) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_status` tinyint NOT NULL DEFAULT '1',
  `role_id` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sha512_users`
--

INSERT INTO `sha512_users` (`id`, `user_login`, `user_email`, `user_pass`, `user_status`, `role_id`, `created_at`) VALUES
(20, 'pankaj', 'pankaj@gmail.com', '4c50e63069f54952bf2c3156853566dbf8f765a2bf2f9eb36ebb2b56af2429197dbf795d2445b5cf6aced67b647c1424ce44908d0e0b81c6b107da909598214a', 1, 'authenticated', '2022-08-07 06:50:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `md5_hash`
--
ALTER TABLE `md5_hash`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_name` (`user_name`);

--
-- Indexes for table `plain_password`
--
ALTER TABLE `plain_password`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_login` (`user_login`,`user_email`) USING BTREE;

--
-- Indexes for table `sha512_users`
--
ALTER TABLE `sha512_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_login` (`user_login`,`user_email`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `md5_hash`
--
ALTER TABLE `md5_hash`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1705;

--
-- AUTO_INCREMENT for table `plain_password`
--
ALTER TABLE `plain_password`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
