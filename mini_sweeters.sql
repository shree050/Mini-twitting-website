-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2026 at 09:37 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mini_sweeters`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_logs`
--

INSERT INTO `admin_logs` (`id`, `admin_id`, `action`, `created_at`) VALUES
(1, NULL, 'Deleted tweet 3', '2026-03-12 15:37:27'),
(2, 1, 'Banned user 5', '2026-03-12 15:58:19'),
(3, 1, 'Banned user 5', '2026-03-12 16:32:38'),
(4, 1, 'Banned user 5', '2026-03-12 16:37:12'),
(5, 1, 'Banned user 5', '2026-03-12 16:37:38'),
(6, 1, 'Banned user 5', '2026-03-12 16:37:49'),
(7, 1, 'Banned user 5', '2026-03-13 05:58:51'),
(8, 1, 'Banned user 5', '2026-03-13 06:02:05'),
(9, 1, 'Unbanned user 5', '2026-03-13 06:02:09'),
(10, 1, 'Deleted tweet 23', '2026-03-20 16:57:20'),
(11, 1, 'Deleted tweet 21', '2026-03-20 16:57:28'),
(12, 1, 'Deleted tweet 24', '2026-03-20 17:19:01'),
(13, 1, 'Deleted tweet 20', '2026-03-20 17:19:05'),
(14, 1, 'Deleted tweet 20', '2026-03-20 17:19:11'),
(15, 1, 'Deleted tweet 13', '2026-03-29 15:14:26'),
(16, 1, 'Deleted tweet 26', '2026-03-29 15:14:30'),
(17, 1, 'Deleted tweet 6', '2026-03-29 15:14:34'),
(18, 1, 'Deleted tweet 17', '2026-03-29 15:14:37'),
(19, 1, 'Deleted tweet 25', '2026-03-29 15:14:40'),
(20, 1, 'Deleted tweet 16', '2026-03-29 15:14:45'),
(21, 1, 'Deleted tweet 30', '2026-03-29 15:14:49'),
(22, 1, 'Deleted tweet 30', '2026-03-29 15:16:12'),
(23, 1, 'Deleted tweet 30', '2026-03-29 15:17:20'),
(24, 1, 'Deleted tweet 30', '2026-03-29 15:17:21'),
(25, 1, 'Deleted tweet 30', '2026-03-29 15:17:21'),
(26, 1, 'Deleted tweet 30', '2026-03-29 15:17:21'),
(27, 1, 'Deleted tweet 30', '2026-03-29 15:17:22'),
(28, 1, 'Deleted tweet 30', '2026-03-29 15:17:22'),
(29, 1, 'Deleted tweet 30', '2026-03-29 15:21:31');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tweet_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `tweet_id`, `comment`) VALUES
(2, 4, 1, 'does works'),
(4, 1, 2, 'hello'),
(8, 1, 4, 'does it work!!'),
(12, 5, 1, 'Hello Anushree'),
(14, 5, 16, 'hello'),
(15, 5, 6, 'Hello Anushree'),
(16, 1, 17, 'hello'),
(17, 1, 6, 'does it work!!'),
(18, 1, 28, 'beautiful'),
(19, 10, 34, 'happens all the time bro'),
(20, 10, 31, 'yepp the satisfaction though');

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `id` int(11) NOT NULL,
  `follower_id` int(11) DEFAULT NULL,
  `following_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`id`, `follower_id`, `following_id`) VALUES
(2, 1, 4),
(3, 5, 1),
(4, 1, 5),
(5, 4, 5),
(6, 4, 1),
(7, 10, 6),
(8, 10, 1),
(9, 10, 5),
(10, 10, 4);

-- --------------------------------------------------------

--
-- Table structure for table `hashtags`
--

CREATE TABLE `hashtags` (
  `id` int(11) NOT NULL,
  `tag` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tweet_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `tweet_id`, `created_at`) VALUES
(1, 1, 1, '2026-03-12 15:19:02'),
(5, 1, 2, '2026-03-12 15:19:02'),
(12, 4, 2, '2026-03-12 15:19:02'),
(17, 4, 1, '2026-03-12 15:19:02'),
(22, 4, 5, '2026-03-12 15:19:02'),
(23, 4, 4, '2026-03-12 15:19:02'),
(26, 1, 4, '2026-03-12 15:19:02'),
(27, 1, 3, '2026-03-12 15:19:02'),
(28, 4, 3, '2026-03-12 15:19:02'),
(29, 1, 5, '2026-03-12 15:19:02'),
(30, 5, 5, '2026-03-12 15:19:02'),
(31, 5, 3, '2026-03-12 15:19:02'),
(32, 5, 1, '2026-03-12 15:19:02'),
(33, 4, 9, '2026-03-12 15:19:02'),
(34, 4, 8, '2026-03-12 15:19:02'),
(35, 4, 6, '2026-03-12 15:40:09'),
(36, 5, 16, '2026-03-13 08:39:11'),
(37, 5, 6, '2026-03-13 08:39:38'),
(38, 1, 20, '2026-03-17 14:53:27'),
(39, 4, 20, '2026-03-17 18:12:26'),
(40, 4, 13, '2026-03-20 13:49:08'),
(42, 1, 22, '2026-03-20 17:30:31'),
(43, 1, 6, '2026-03-20 17:30:36'),
(44, 1, 25, '2026-03-20 17:54:26'),
(45, 1, 13, '2026-03-25 14:23:48'),
(46, 1, 26, '2026-03-26 15:02:34'),
(47, 1, 17, '2026-03-26 15:08:46');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `seen` tinyint(1) DEFAULT 0,
  `type` enum('text','image') DEFAULT 'text'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `created_at`, `seen`, `type`) VALUES
(1, 4, 1, 'hie', '2026-01-15 16:42:48', 1, 'text'),
(2, 4, 1, 'Are you there!!', '2026-01-15 16:46:34', 1, 'text'),
(3, 4, 1, 'helloooo', '2026-01-15 16:53:17', 1, 'text'),
(4, 1, 4, 'yesss', '2026-01-15 16:58:52', 1, 'text'),
(5, 1, 4, 'he', '2026-01-16 07:06:34', 1, 'text'),
(6, 4, 5, 'hello', '2026-01-20 05:30:22', 1, 'text'),
(7, 1, 4, 'hello', '2026-03-17 16:41:45', 1, 'text'),
(8, 4, 1, 'its good right', '2026-03-17 18:14:35', 1, 'text'),
(9, 4, 1, 'logo looks good', '2026-03-17 18:20:12', 1, 'text'),
(11, 4, 1, '/mini_sweeters_pro/uploads/1773772485_logo.svg', '2026-03-17 18:34:45', 1, 'image'),
(12, 1, 4, 'hello', '2026-03-20 17:11:26', 1, 'text'),
(13, 1, 4, 'hello', '2026-03-26 15:48:44', 1, 'text');

-- --------------------------------------------------------

--
-- Table structure for table `moderation_queue`
--

CREATE TABLE `moderation_queue` (
  `id` int(11) NOT NULL,
  `tweet_id` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `actor_id` int(11) DEFAULT NULL,
  `from_user_id` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `reference_id` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `actor_id`, `from_user_id`, `type`, `reference_id`, `message`, `is_read`, `created_at`) VALUES
(1, 1, NULL, NULL, 'message', NULL, 'You received a new message', 1, '2026-01-15 16:42:48'),
(2, 1, NULL, NULL, 'message', NULL, 'You received a new message', 1, '2026-01-15 16:46:34'),
(3, 1, NULL, NULL, 'message', NULL, 'You received a new message', 1, '2026-01-15 16:53:17'),
(4, 4, NULL, NULL, 'message', NULL, 'You received a new message', 1, '2026-01-15 16:58:52'),
(5, 4, NULL, NULL, 'like', NULL, 'Someone liked your tweet', 1, '2026-01-15 16:58:59'),
(6, 4, NULL, NULL, 'comment', NULL, 'Someone commented on your tweet', 1, '2026-01-15 16:59:12'),
(7, 4, NULL, NULL, 'follow', NULL, 'Someone followed you', 1, '2026-01-15 17:05:41'),
(8, 4, NULL, NULL, 'follow', NULL, 'Someone followed you', 1, '2026-01-16 06:52:34'),
(9, 1, NULL, NULL, 'follow', NULL, 'Someone followed you', 1, '2026-01-16 06:54:59'),
(10, 1, NULL, NULL, 'comment', NULL, 'Someone commented on your tweet', 1, '2026-01-16 06:56:02'),
(11, 5, NULL, NULL, 'follow', NULL, 'Someone followed you', 1, '2026-01-16 06:59:20'),
(12, 5, NULL, NULL, 'comment', NULL, 'Someone commented on your tweet', 1, '2026-01-16 07:04:25'),
(13, 4, NULL, NULL, 'message', NULL, 'You received a new message', 1, '2026-01-16 07:06:34'),
(14, 5, NULL, NULL, 'follow', NULL, 'Someone followed you', 1, '2026-01-16 07:09:37'),
(15, 1, NULL, NULL, 'follow', NULL, 'Someone followed you', 1, '2026-01-20 05:22:40'),
(16, 1, NULL, NULL, 'comment', NULL, 'Someone commented on your tweet', 1, '2026-02-24 08:26:16'),
(17, 5, 1, NULL, 'like', 4, NULL, 1, '2026-02-25 07:07:32'),
(18, 4, NULL, NULL, 'comment', NULL, 'Someone commented on your tweet', 1, '2026-02-25 07:07:48'),
(19, 1, 4, NULL, 'like', 3, NULL, 1, '2026-02-25 07:29:36'),
(20, 4, NULL, NULL, 'comment', NULL, 'Someone commented on your tweet', 1, '2026-02-25 07:34:47'),
(21, 1, 5, NULL, 'like', 5, NULL, 1, '2026-02-26 06:45:14'),
(22, 1, 5, NULL, 'like', 3, NULL, 1, '2026-02-26 06:45:18'),
(23, 1, 5, NULL, 'like', 1, NULL, 1, '2026-02-26 06:45:23'),
(24, 1, NULL, NULL, 'comment', NULL, 'Someone commented on your tweet', 1, '2026-02-26 06:45:39'),
(25, 1, 4, NULL, 'like', 9, NULL, 1, '2026-03-12 07:47:33'),
(26, 1, 4, NULL, 'like', 8, NULL, 1, '2026-03-12 07:47:39'),
(27, 1, 4, NULL, 'like', 6, NULL, 1, '2026-03-12 15:40:09'),
(28, 1, 5, NULL, 'like', 6, NULL, 1, '2026-03-13 08:39:38'),
(29, 1, NULL, NULL, 'comment', NULL, 'Someone commented on your tweet', 1, '2026-03-13 08:39:54'),
(30, 1, 4, NULL, 'like', 20, NULL, 1, '2026-03-17 18:12:26'),
(31, 5, 4, NULL, 'like', 13, NULL, 0, '2026-03-20 13:49:08'),
(32, 4, 1, NULL, 'like', 22, NULL, 1, '2026-03-20 17:30:31'),
(33, 5, 1, NULL, 'like', 13, NULL, 0, '2026-03-25 14:23:48'),
(34, 6, NULL, NULL, 'comment', NULL, 'Someone commented on your tweet', 0, '2026-03-29 15:19:06'),
(35, 6, NULL, NULL, 'comment', NULL, 'Someone commented on your tweet', 0, '2026-03-29 15:19:40');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `tweet_id` int(11) DEFAULT NULL,
  `reported_by` int(11) DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tweets`
--

CREATE TABLE `tweets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` varchar(280) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` text DEFAULT NULL,
  `is_retweet` tinyint(4) DEFAULT 0,
  `original_tweet_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tweets`
--

INSERT INTO `tweets` (`id`, `user_id`, `content`, `created_at`, `image`, `is_retweet`, `original_tweet_id`) VALUES
(4, 5, 'Hello Anushree!!', '2026-01-16 06:55:34', NULL, 0, NULL),
(15, 5, 'hello', '2026-03-13 08:12:30', '', 1, NULL),
(22, 4, ' just used hashtag i guess i am the first.     #MiniSweeters', '2026-03-17 18:58:55', '', 0, NULL),
(28, 1, 'sunset', '2026-03-26 16:20:25', '1774541989_0_20250206125349.JPG', 1, NULL),
(29, 1, 'life when you choose It-blah blah blah', '2026-03-27 13:58:03', '1774619883_0_20250206130302.JPG', 0, NULL),
(31, 6, '“When code works on first try 😳”  #MiniSweeters #PHP #WebDev #Coding                                                                                      ', '2026-03-29 14:56:14', '', 0, NULL),
(32, 6, '“Me: I’ll sleep early today\r\nAlso me at 3AM: debugging 🧠💥” #Coding #CS #WebDev #CodeForLife', '2026-03-29 15:09:47', '1774796987_0_code.jpg,1774796987_1_code.png', 0, NULL),
(34, 6, '“New AI tool just dropped 👀 thoughts?” #shocker', '2026-03-29 15:11:07', '', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `bio` varchar(255) DEFAULT '',
  `status` varchar(20) DEFAULT 'active',
  `profile` varchar(255) DEFAULT 'default.png',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verified` tinyint(1) DEFAULT 0,
  `banner` varchar(255) DEFAULT NULL,
  `last_seen` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `bio`, `status`, `profile`, `created_at`, `verified`, `banner`, `last_seen`) VALUES
(1, 'Anushree Gurav', 'Anushree21', 'anushreegurav55@gmail.com', '$2y$10$iGPgk7hkdzHpLAE/zL1Ahev81sl2T/RX.WF.JgRiOamMWpw1bfznS', 'Serendipity!!', 'active', 'user_1_1773389488.jpg', '2026-03-12 15:05:30', 0, NULL, '2026-03-29 20:06:58'),
(4, 'Anuja gurav', 'anuja11', 'anujagurav777@gmail.com', '$2y$10$HgGRXNMktYtA0rhmN.hqH.H9APF6JzcgtXzhfX9keXTNE3PcCTQpW', 'All Your Perfects!!', 'active', 'user_4_1773301635.jpg', '2026-03-12 15:05:30', 0, NULL, '2026-03-27 19:30:01'),
(5, 'Kaustubh Mehetar', 'Kaustubh26', 'kaustubh@gmail.com', '$2y$10$a9QAz0q2AOI.g1hPFEph/O0FC82K.2VuoT3FETy8GNP3K0kmplmCu', '', 'active', 'user_5_1773389531.jpg', '2026-03-12 15:05:30', 0, NULL, '2026-03-17 23:48:35'),
(6, 'Asisi Volkov', 'lowkeylegend007', 'asg2155555@gmail.com', '$2y$10$YtNB9eTb51KrJRg9Fgt6A..joMuQ1JA3T3WD/xFlLgPTe2S4Mvp2i', 'yepp!!! right place.', 'active', 'user_6_1774795920.jpg', '2026-03-29 14:39:59', 0, NULL, '2026-03-29 20:09:59'),
(10, 'Poppy Trusova', 'cryingsince2005', 'shree2005@gmail.com', '$2y$10$ilD5e3iaACePzJ.JzkW.UukQy7Mip9PQzHOY0zsJTm2sEwZT0RSLe', 'turning coffee into code ☕', 'active', 'user_10_1774797643.jpg', '2026-03-29 15:18:27', 0, NULL, '2026-03-29 20:48:27');

-- --------------------------------------------------------

--
-- Table structure for table `user_ip_logs`
--

CREATE TABLE `user_ip_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hashtags`
--
ALTER TABLE `hashtags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `moderation_queue`
--
ALTER TABLE `moderation_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tweets`
--
ALTER TABLE `tweets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_ip_logs`
--
ALTER TABLE `user_ip_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hashtags`
--
ALTER TABLE `hashtags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `moderation_queue`
--
ALTER TABLE `moderation_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tweets`
--
ALTER TABLE `tweets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user_ip_logs`
--
ALTER TABLE `user_ip_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
