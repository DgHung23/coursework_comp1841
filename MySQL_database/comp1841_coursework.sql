-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2026 at 07:44 PM
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
-- Database: `comp1841_coursework`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `hashed_password` varchar(255) NOT NULL,
  `role` enum('USER','ADMIN') DEFAULT 'USER',
  `display_name` varchar(100) DEFAULT NULL,
  `bio` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `email`, `hashed_password`, `role`, `display_name`, `bio`, `created_at`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$XzTm/IISBpHCxeaBur308OQ7Gh6LTDOYpIfBQ7HYJ73UHM9m.o6s6', 'ADMIN', 'Admin User', 'Administrator account for managing users and modules.', '2026-06-26 04:22:04'),
(4, 'dghung', 'hungdangmcn@gmail.com', '$2y$10$586gLeITbhlVpSGuD7.w1.kT3wGkNYIktRJwH76ztGV7YrWkShMKa', 'USER', 'Dg Hung', 'Coursework owner and demo student account.', '2026-06-26 05:08:22'),
(6, 'an_nguyen', 'an.nguyen@studentqa.local', '$2y$10$iQ7aUMY1QJicwCLbvxkDzONfx7PgudMQ9c7Zt8Jgwqn/uB0wuQTQK', 'USER', 'An Nguyen', 'First year computing student.', '2026-07-30 23:37:42'),
(7, 'linh_tran', 'linh.tran@studentqa.local', '$2y$10$bY2E4UYeHlFrbOMVXtlYG.t/MqQTIbcVvzh9yamHiAjH9yUMz/qSG', 'USER', 'Linh Tran', 'Interested in databases and clean UI design.', '2026-07-30 23:37:42'),
(8, 'minh_pham', 'minh.pham@studentqa.local', '$2y$10$BxZlHfdDwB2.vzzadwx4xOh9/HdgWZkb/zRfMHULYgqKB1mzEzRCq', 'USER', 'Minh Pham', 'Practising PHP, MySQL, and coursework documentation.', '2026-07-30 23:37:42');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `description`) VALUES
(11, 'COMP1841', 'Web Programming 1 - PHP and MySQL'),
(12, 'COMP1842', 'Software Development Principles'),
(13, 'COMP1843', 'Database Systems'),
(14, 'COMP1755', 'Programming Fundamentals'),
(15, 'COMP1640', 'Enterprise Web Software Development'),
(16, 'COMP1770', 'Professional Practice in IT');

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `id` int(20) NOT NULL,
  `author_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` varchar(5000) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`id`, `author_id`, `title`, `content`, `image`, `created_at`, `updated_at`) VALUES
(21, 4, 'How should I structure PHP includes for this coursework?', 'I have separate files for the layout, database connection, and helper functions. Is this a good structure for a small PHP coursework project, or should I split it further?', NULL, '2026-07-21 16:15:00', '2026-07-21 16:15:00'),
(22, 7, 'PDO prepared statement returns no rows even though data exists', 'The SQL works in phpMyAdmin, but my PHP page returns an empty array. I am binding a category id from the query string. What should I check first?', NULL, '2026-07-21 21:40:00', '2026-07-21 21:40:00'),
(23, 6, 'When should I use a composite primary key?', 'For the post_category table, I see that post_id and category_id are used together as the primary key. Why is this better than adding a separate id column?', NULL, '2026-07-22 17:20:00', '2026-07-22 17:20:00'),
(24, 8, 'How do I normalize forum data without overcomplicating the schema?', 'I want to explain normalization in my report using accounts, posts, categories, and the post_category bridge table. What is a simple way to describe it?', NULL, '2026-07-22 23:05:00', '2026-07-22 23:05:00'),
(25, 4, 'Best way to break a coursework task into smaller functions', 'My post page is getting longer as I add filtering and CRUD features. Which parts should be moved into reusable functions?', NULL, '2026-07-23 18:30:00', '2026-07-23 18:30:00'),
(26, 6, 'How do I plan MVC-style pages in plain PHP?', 'This project is not using a framework, but I still want the code to be clear. Is it okay to keep controller logic in PHP entry files and views in templates?', NULL, '2026-07-24 16:10:00', '2026-07-24 16:10:00'),
(27, 7, 'What should a short coursework reflection include?', 'I need to write about what went well, what was difficult, and what could be improved. How much technical detail should be included?', NULL, '2026-07-24 22:35:00', '2026-07-24 22:35:00'),
(28, 8, 'How can I test role-based access control?', 'The admin area should only be available to ADMIN users. What manual tests should I include as evidence in my report?', NULL, '2026-07-25 19:00:00', '2026-07-25 19:00:00'),
(29, 4, 'Loop through an array of modules and show selected checkboxes', 'On the edit post page, I want modules that are already assigned to a post to be checked automatically. What is the cleanest approach?', NULL, '2026-07-26 17:45:00', '2026-07-26 17:45:00'),
(30, 1, 'How should security decisions be described in the final report?', 'The project uses password_hash, password_verify, PDO prepared statements, and role checks. Which parts should be highlighted for marking?', NULL, '2026-07-27 00:20:00', '2026-07-27 00:20:00');

-- --------------------------------------------------------

--
-- Table structure for table `post_category`
--

CREATE TABLE `post_category` (
  `post_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post_category`
--

INSERT INTO `post_category` (`post_id`, `category_id`) VALUES
(21, 11),
(22, 11),
(22, 13),
(23, 13),
(24, 12),
(24, 13),
(25, 12),
(25, 14),
(26, 11),
(26, 12),
(26, 15),
(27, 16),
(28, 12),
(28, 15),
(29, 11),
(29, 14),
(30, 15),
(30, 16);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_post_author` (`author_id`);

--
-- Indexes for table `post_category`
--
ALTER TABLE `post_category`
  ADD PRIMARY KEY (`post_id`,`category_id`),
  ADD KEY `idx_post` (`post_id`),
  ADD KEY `idx_category` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_author` FOREIGN KEY (`author_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_category`
--
ALTER TABLE `post_category`
  ADD CONSTRAINT `fk_pc_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pc_post` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
