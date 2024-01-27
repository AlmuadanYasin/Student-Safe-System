-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2024 at 12:55 PM
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
-- Database: `sims`
--

-- --------------------------------------------------------

--
-- Table structure for table `cashier_table`
--

CREATE TABLE `cashier_table` (
  `employee_id` int(11) NOT NULL,
  `f_name` varchar(50) NOT NULL,
  `m_name` varchar(5) NOT NULL,
  `l_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cashier_table`
--

INSERT INTO `cashier_table` (`employee_id`, `f_name`, `m_name`, `l_name`, `email`, `password`, `user_type`) VALUES
(5, 'Raymond', 'Limbo', 'Clarin', 'raymondclarin@gmail.com', 'edaf6ef136e32cbc4f3c8885a49497c4', 'cashier');

-- --------------------------------------------------------

--
-- Table structure for table `registrar_table`
--

CREATE TABLE `registrar_table` (
  `employee_id` int(11) NOT NULL,
  `f_name` varchar(50) NOT NULL,
  `m_name` varchar(5) NOT NULL,
  `l_name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `user_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrar_table`
--

INSERT INTO `registrar_table` (`employee_id`, `f_name`, `m_name`, `l_name`, `email`, `password`, `user_type`) VALUES
(4, 'Jason', 'Limbo', 'Baring', 'jasonbarin@gmail.com', '30bb1f756d1dfc4965b48fcbc190c11d', 'registrar');

-- --------------------------------------------------------

--
-- Table structure for table `student_table`
--

CREATE TABLE `student_table` (
  `student_id` int(11) NOT NULL,
  `f_name` varchar(100) NOT NULL,
  `m_name` varchar(100) DEFAULT NULL,
  `l_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `tuition` decimal(10,2) DEFAULT 0.00
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_table`
--

INSERT INTO `student_table` (`student_id`, `f_name`, `m_name`, `l_name`, `email`, `password`, `user_type`, `tuition`) VALUES
(5, 'Almuadan', 'Limbo', 'Yasin', 'almuadanyasin@gmail.com', '6c2528eac36ef7200b91dae6af8adbc7', 'student', 24000.00);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `sub_code` varchar(25) NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `sub_name` varchar(255) DEFAULT NULL,
  `sub_sched` varchar(255) DEFAULT NULL,
  `sub_instructor` varchar(255) DEFAULT NULL,
  `grades` decimal(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`sub_code`, `student_id`, `sub_name`, `sub_sched`, `sub_instructor`, `grades`) VALUES
('IT-ELEC 101', 5, 'IT Elective II (Web and Application Development)', 'Mon-Wed-Fri', 'Ebrahim Diangca', 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cashier_table`
--
ALTER TABLE `cashier_table`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `registrar_table`
--
ALTER TABLE `registrar_table`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `student_table`
--
ALTER TABLE `student_table`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`sub_code`),
  ADD KEY `fk_student` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cashier_table`
--
ALTER TABLE `cashier_table`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `registrar_table`
--
ALTER TABLE `registrar_table`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `student_table`
--
ALTER TABLE `student_table`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
