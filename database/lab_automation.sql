-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 09:03 AM
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
-- Database: `lab_auto`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` varchar(10) NOT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `product_type` varchar(50) DEFAULT NULL,
  `revision` varchar(5) DEFAULT NULL,
  `serial_no` varchar(5) DEFAULT NULL,
  `manufacture_date` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_type`, `revision`, `serial_no`, `manufacture_date`, `created_by`, `created_at`) VALUES
('CAPA100001', 'Capacitor 50V', 'CAP', 'A1', '00001', '2025-05-20', 2, '2025-05-20 09:58:09'),
('FUSA100001', 'FUSE 10A', 'FUSE', 'A1', '00001', '2025-05-20', 2, '2025-05-20 10:05:40');

-- --------------------------------------------------------

--
-- Table structure for table `reworklog`
--

CREATE TABLE `reworklog` (
  `rework_id` int(11) NOT NULL,
  `product_id` varchar(10) DEFAULT NULL,
  `previous_test_id` varchar(12) DEFAULT NULL,
  `rework_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `name`, `username`, `password`, `role`) VALUES
(1, 'Maaz', 'maaz', '$2y$10$TvLshiwCwgJpx3iIv1ojoe/4h0ZIsxRZSqcGdBLaMD7aiTzQ7/WW2', 'admin'),
(2, 'Mantasha', 'mantasha', '$2y$10$s.ZbwZoAAI2/hExiacmUpOHZS69g8sKQSBuorBv1PY8KbUfgToXHG', 'Lab Manager'),
(5, 'Nabiha', 'nabiha', '$2y$10$w.G7GSuMEWYqtgoF9UuOc.sA/3iEJTYW5kaFcQ4x/cXkFizaAcg4m', 'Tester'),
(6, 'Shaheera', 'shaheera', '$2y$10$4V4OY1M2RfuWDmO/NScB9evLbPOOqEy5LKRQZyiq8tys1HkzaZxD2', 'Tester');

-- --------------------------------------------------------

--
-- Table structure for table `testers`
--

CREATE TABLE `testers` (
  `tester_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `u_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testers`
--

INSERT INTO `testers` (`tester_id`, `name`, `department`, `contact`, `u_id`) VALUES
(1, 'Nabiha', 'Heat Testing', '0314-3115506', 5),
(2, 'Shaheera Nadeem', 'Leakage', '0312-254796', 6);

-- --------------------------------------------------------

--
-- Table structure for table `testflow`
--

CREATE TABLE `testflow` (
  `flow_id` int(11) NOT NULL,
  `product_id` varchar(20) DEFAULT NULL,
  `test_type_id` int(11) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testflow`
--

INSERT INTO `testflow` (`flow_id`, `product_id`, `test_type_id`, `sequence`) VALUES
(1, 'CAPA100001', 1, 1),
(2, 'CAPA100001', 2, 2),
(3, 'FUSA100001', 1, 1),
(4, 'FUSA100001', 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `test_id` varchar(12) NOT NULL,
  `product_id` varchar(10) DEFAULT NULL,
  `test_type_id` int(11) DEFAULT NULL,
  `tester_id` int(11) DEFAULT NULL,
  `test_date` date DEFAULT NULL,
  `result` varchar(20) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`test_id`, `product_id`, `test_type_id`, `tester_id`, `test_date`, `result`, `remarks`, `status`) VALUES
('CAPA10100000', 'CAPA100001', 1, 1, '2025-05-20', 'pass', 'test pass successfully', 'Completed'),
('CAPA10200000', 'CAPA100001', 2, 1, '2025-05-20', 'pass', 'completed', 'Completed'),
('FUSA10100000', 'FUSA100001', 1, 1, '2025-05-21', 'pass', 'test successfull\r\n', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `testtypes`
--

CREATE TABLE `testtypes` (
  `test_type_id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testtypes`
--

INSERT INTO `testtypes` (`test_type_id`, `name`, `description`) VALUES
(1, 'Heat Test', 'In this test we check about the heat resistance'),
(2, 'CPRI', 'Final Quality Test');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `fk_products_user` (`created_by`);

--
-- Indexes for table `reworklog`
--
ALTER TABLE `reworklog`
  ADD PRIMARY KEY (`rework_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `previous_test_id` (`previous_test_id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testers`
--
ALTER TABLE `testers`
  ADD PRIMARY KEY (`tester_id`),
  ADD KEY `fk_testers_user` (`u_id`);

--
-- Indexes for table `testflow`
--
ALTER TABLE `testflow`
  ADD PRIMARY KEY (`flow_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `test_type_id` (`test_type_id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`test_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `test_type_id` (`test_type_id`),
  ADD KEY `tester_id` (`tester_id`);

--
-- Indexes for table `testtypes`
--
ALTER TABLE `testtypes`
  ADD PRIMARY KEY (`test_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reworklog`
--
ALTER TABLE `reworklog`
  MODIFY `rework_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `testers`
--
ALTER TABLE `testers`
  MODIFY `tester_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `testflow`
--
ALTER TABLE `testflow`
  MODIFY `flow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `testtypes`
--
ALTER TABLE `testtypes`
  MODIFY `test_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_user` FOREIGN KEY (`created_by`) REFERENCES `tbl_user` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reworklog`
--
ALTER TABLE `reworklog`
  ADD CONSTRAINT `reworklog_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `reworklog_ibfk_2` FOREIGN KEY (`previous_test_id`) REFERENCES `tests` (`test_id`);

--
-- Constraints for table `testers`
--
ALTER TABLE `testers`
  ADD CONSTRAINT `fk_testers_user` FOREIGN KEY (`u_id`) REFERENCES `tbl_user` (`id`);

--
-- Constraints for table `testflow`
--
ALTER TABLE `testflow`
  ADD CONSTRAINT `testflow_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `testflow_ibfk_2` FOREIGN KEY (`test_type_id`) REFERENCES `testtypes` (`test_type_id`);

--
-- Constraints for table `tests`
--
ALTER TABLE `tests`
  ADD CONSTRAINT `tests_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `tests_ibfk_2` FOREIGN KEY (`test_type_id`) REFERENCES `testtypes` (`test_type_id`),
  ADD CONSTRAINT `tests_ibfk_3` FOREIGN KEY (`tester_id`) REFERENCES `testers` (`tester_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
