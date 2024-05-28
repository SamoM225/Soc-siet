-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: 127.0.0.1
-- Čas generovania: Út 28.Máj 2024, 21:01
-- Verzia serveru: 10.4.32-MariaDB
-- Verzia PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `social_network`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int(11) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_passwd` varchar(255) NOT NULL,
  `account_reg_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `account_enabled` tinyint(1) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `pfp` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `accounts`
--

INSERT INTO `accounts` (`account_id`, `account_name`, `account_passwd`, `account_reg_time`, `account_enabled`, `role`, `pfp`) VALUES
(1, 'testuser', '$2y$10$2B6J4aREMFhovZKUWTgKJOMIHtPLPMA9VmSTNtU36xbxIW.UG1oBe', '2024-04-24 11:43:49', 1, 'admin', NULL),
(3, 'testuser2', '$2y$10$T8siz6Skv/jTVQOjaq7WkeiWgfUVefU9s8IsWqe8uJkID4QR/7PQm', '2024-04-29 08:08:36', 1, 'admin', NULL),
(4, 'root', '$2y$10$/DYZ9s/xUT9deceNG4SUZ.4Xj4.evErg/fyNgxCZrRZ9ZsteVKe4m', '2024-04-29 08:10:10', 1, 'admin', '../assets/pfp/6630cc4fa52fd_post3.png'),
(5, 'testuserxxx', '$2y$10$Y1JiRVqHJTciWqTPO.DWl.lSt2FgWLltC/WKurp1igOop8YfBsnye', '2024-04-30 09:04:28', 1, NULL, NULL),
(6, 'testovanie', '$2y$10$Vs93ozwmlRARcZKfJpz0W.6cL4CgFrUxIhxFzeRIw/DruJms0gJjC', '2024-05-06 10:25:49', 1, 'user', NULL),
(7, 'testtesttest', '$2y$10$RgkSTkkXpAm.UCsPh8EVReAsAyhPL1PC.YLdlcXEpZNlO01h0zn7u', '2024-05-14 08:28:10', 1, 'user', NULL),
(9, 'katka', '$2y$10$w6Pz3fFTRbEC2KakmJToJetECBXBHRCwvMK.lqRIyl22CWYvWQT/e', '2024-05-26 12:38:03', 1, 'admin', '../assets/pfp/6654ff391c1a6_fokta.png'),
(10, 'keszo', '$2y$10$.0necvMqMtU4scA4IMTjluBB8FY/9jV4bIzMF9G89K1r3hX.N6n3u', '2024-05-27 08:48:04', 1, 'user', NULL),
(11, 'test', '$2y$10$f6eG0mhJ3zN1e9OhINcz2unqweQDN2sCfKbDTB.DWTUoGIj9yQgTy', '2024-05-27 21:49:47', 1, 'user', NULL);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `comment_text` text DEFAULT NULL,
  `comment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `account_id`, `comment_text`, `comment_date`) VALUES
(4, 12, 4, 'testovanie', '2024-05-06 19:46:03'),
(5, 12, 4, 'testjs', '2024-05-07 15:07:27'),
(6, 16, 4, 'testovanie koentarov', '2024-05-26 14:29:35'),
(7, 17, 9, 'testajax', '2024-05-26 20:32:58');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `friend_list`
--

CREATE TABLE `friend_list` (
  `id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `friend_list`
--

INSERT INTO `friend_list` (`id`, `account_id`, `friend_id`, `status`) VALUES
(17, 6, 4, 'Accepted'),
(18, 4, 6, 'Accepted'),
(19, 9, 3, 'Request'),
(20, 3, 9, 'Pending'),
(21, 9, 4, 'Accepted'),
(22, 4, 9, 'Accepted'),
(23, 4, 3, 'Request'),
(24, 3, 4, 'Pending'),
(25, 4, 1, 'Request'),
(26, 1, 4, 'Pending'),
(27, 4, 7, 'Request'),
(28, 7, 4, 'Pending'),
(29, 4, 5, 'Request'),
(30, 5, 4, 'Pending'),
(31, 9, 1, 'Request'),
(32, 1, 9, 'Pending'),
(33, 9, 6, 'Request'),
(34, 6, 9, 'Pending'),
(35, 9, 5, 'Request'),
(36, 5, 9, 'Pending'),
(37, 9, 7, 'Request'),
(38, 7, 9, 'Pending'),
(39, 10, 9, 'Accepted'),
(40, 9, 10, 'Accepted'),
(41, 10, 3, 'Request'),
(42, 3, 10, 'Pending'),
(43, 10, 5, 'Request'),
(44, 5, 10, 'Pending'),
(45, 10, 1, 'Request'),
(46, 1, 10, 'Pending'),
(47, 10, 6, 'Request'),
(48, 6, 10, 'Pending'),
(49, 10, 7, 'Request'),
(50, 7, 10, 'Pending'),
(51, 10, 4, 'Accepted'),
(52, 4, 10, 'Accepted'),
(53, 11, 4, 'Accepted'),
(54, 4, 11, 'Accepted'),
(55, 11, 9, 'Accepted'),
(56, 9, 11, 'Accepted');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `account_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `post_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `posts`
--

INSERT INTO `posts` (`post_id`, `account_id`, `description`, `img`, `post_date`) VALUES
(12, 4, 'test\r\n', NULL, '2024-04-29 19:07:47'),
(13, 4, 'teeeest\r\n', NULL, '2024-04-29 19:07:51'),
(14, 4, 'will this work', '../assets/uploads/6630ca6209156_post1.png', '2024-04-30 10:39:30'),
(15, 1, 'testing', NULL, '2024-04-30 11:19:05'),
(16, 4, 'test js', NULL, '2024-05-07 15:07:39'),
(17, 4, 'testing, day 26/5/2025', NULL, '2024-05-26 14:29:22'),
(18, 9, 'Dobrý večer', NULL, '2024-05-26 20:54:27');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `idx_account_name` (`account_name`);

--
-- Indexy pre tabuľku `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `account_id` (`account_id`);

--
-- Indexy pre tabuľku `friend_list`
--
ALTER TABLE `friend_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `friend_id` (`friend_id`);

--
-- Indexy pre tabuľku `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `account_id` (`account_id`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pre tabuľku `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pre tabuľku `friend_list`
--
ALTER TABLE `friend_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pre tabuľku `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`) ON DELETE CASCADE;

--
-- Obmedzenie pre tabuľku `friend_list`
--
ALTER TABLE `friend_list`
  ADD CONSTRAINT `friend_list_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`),
  ADD CONSTRAINT `friend_list_ibfk_2` FOREIGN KEY (`friend_id`) REFERENCES `accounts` (`account_id`);

--
-- Obmedzenie pre tabuľku `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`account_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
