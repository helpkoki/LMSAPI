-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2022 at 07:07 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `leave_db`
--

-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `department` varchar(100) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varbinary(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usertype` varchar(100) NOT NULL DEFAULT 'user',
  `uploads` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_id`, `fname`, `lname`, `department`, `designation`, `email`, `password`, `date`, `usertype`, `uploads`, `mobile`) VALUES
(12, 2867, 'Telly', 'Sello', 'IT', 'Developer', 'sellotelly@gmail.com', 0x3832376363623065656138613730366334633334613136383931663834653762, '2022-11-17 09:23:32', 'user', '', ''),
(23, 8, 'Mega', 'Mahlatji', 'BA', 'Intern', 'mega@gmail.com', 0x3831646339626462353264303464633230303336646264383331336564303535, '2022-11-14 08:58:25', 'user', '', ''),
(24, 0, 'Kgopelo', 'Mathebula', 'IT', 'Intern', 'kgopelo@gmail.com', 0x3831646339626462353264303464633230303336646264383331336564303535, '2022-11-01 08:34:44', 'user', '', ''),
(26, 7, 'Tshidiso', 'Moso', 'IT', 'Developer', 'tshidiso@gmail.com', 0x3831646339626462353264303464633230303336646264383331336564303535, '2022-11-23 07:36:53', 'manager', '', '0824689456'),
(27, 56, 'Maria', 'Machika', 'BA', 'Multimedia', 'maria@gmail.com', 0x3832376363623065656138613730366334633334613136383931663834653762, '2022-11-18 07:21:32', 'manager', '', ''),
(28, 195, 'Matau', 'Ramapuputla', 'CEO', 'Director', 'matau@gmail.com', 0x3831646339626462353264303464633230303336646264383331336564303535, '2022-11-23 07:36:16', 'director', '', '0721258956'),
(29, 558, 'Promise', 'Mashishi', 'BA', 'Multimedia', 'promise@gmail.com', 0x3831646339626462353264303464633230303336646264383331336564303535, '2022-11-15 07:38:38', 'user', '', ''),
(30, 46661, 'Scott', 'sello', 'IT', 'Developer', 'scott@gmail.com', 0x31323334, '2022-11-17 08:34:45', 'user', '', ''),
(31, 202, 'Thabiso', 'Sello', 'IT', 'Multimedia', 'thabiso@gmail.com', 0x3831646339626462353264303464633230303336646264383331336564303535, '2022-11-17 08:38:36', 'user', '', ''),
(32, 5, 'Promise', 'Mashishi', 'BA', 'project management', 'p.mashishi@moepipublishing.co.za', 0x3664623936326166613738326566316466326530306432646535663235663833, '2022-11-18 08:51:28', 'user', '', ''),
(33, 82370, 'scott', 'Sello', 'IT', 'Developer', 'scott1@gmail.com', 0x3831646339626462353264303464633230303336646264383331336564303535, '2022-11-23 07:37:35', 'user', '', '0725896464');

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_name` (`email`),
  ADD KEY `date` (`date`);

ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

