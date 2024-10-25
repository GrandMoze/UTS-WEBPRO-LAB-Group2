-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 25, 2024 at 12:15 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ujian tengah semester`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(225) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT '''''',
  `list_id` int DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` enum('completed','incompleted') DEFAULT 'incompleted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `user_id`, `title`, `description`, `list_id`, `due_date`, `status`) VALUES
(4, NULL, 'PERKALIAN', 'MENGHITUNG TREE', 5, '2024-10-16', 'completed'),
(5, NULL, 'PENJUMLAHAN', 'MENGHITUNG DENGAN PERLAHAN-LAHAN DENGAN SELAMAT SENTOSA', 5, '2024-11-01', 'completed'),
(6, NULL, 'penjumlahan', '\'\'', 5, NULL, 'incompleted'),
(7, NULL, 'penjumlahan', '\'\'', 5, NULL, 'incompleted'),
(8, NULL, 'penjumlahan', '\'\'', 5, NULL, 'incompleted'),
(9, NULL, 'Derivatif Lanjutan', 'dasdada', 5, NULL, 'incompleted');

-- --------------------------------------------------------

--
-- Table structure for table `todo_lists`
--

CREATE TABLE `todo_lists` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `todo_lists`
--

INSERT INTO `todo_lists` (`id`, `user_id`, `title`, `created_at`) VALUES
(5, 1, 'Matematika', '2024-10-11 08:57:44'),
(6, 1, 'dsadsadas', '2024-10-12 03:22:35'),
(7, 1, 'dsadasdasd', '2024-10-12 03:22:39');

-- --------------------------------------------------------

--
-- Table structure for table `ujianlab`
--

CREATE TABLE `ujianlab` (
  `id` int NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ujianlab`
--

INSERT INTO `ujianlab` (`id`, `username`, `password`, `email`) VALUES
(1, 'ilmankhoir', 'ilman230905', 'agenggamer4@gmail.com'),
(2, 'ayambakar', 'ayam12345', 'ilman@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `todo_lists`
--
ALTER TABLE `todo_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ujianlab`
--
ALTER TABLE `ujianlab`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `todo_lists`
--
ALTER TABLE `todo_lists`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ujianlab`
--
ALTER TABLE `ujianlab`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
