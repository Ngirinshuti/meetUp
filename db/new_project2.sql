-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 07, 2021 at 08:18 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `new_project2`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(255) NOT NULL,
  `comment` text COLLATE utf8mb4_bin NOT NULL,
  `post_id` int(255) NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `comment`, `post_id`, `username`, `created_at`) VALUES
(1, 'Okay', 142, 'valentin', '2021-10-26 15:27:56'),
(2, 'Yeah`', 141, 'valentin', '2021-10-26 15:28:10'),
(3, 'Okay', 142, 'valentin', '2021-10-26 15:30:44'),
(4, 'hjsdhjjhdhjsd', 142, 'valentin', '2021-10-26 15:43:35'),
(5, 'Okay', 142, 'valentin', '2021-10-26 15:51:06'),
(6, 'Okay', 142, 'valentin', '2021-10-26 15:52:04'),
(7, 'dshjjhsd', 142, 'valentin', '2021-10-26 15:52:11'),
(8, 'khjdhjsdhj', 142, 'valentin', '2021-10-26 15:52:51'),
(9, 'jdjjs', 140, 'valentin', '2021-10-26 16:11:19'),
(10, 'Okay', 138, 'valentin', '2021-10-26 16:12:37'),
(11, 'Here we are', 139, 'valentin', '2021-10-26 16:14:09'),
(12, 'Okay', 142, 'valentin', '2021-10-26 16:16:17'),
(13, 'yyyyyyyyyyyyy', 137, 'vava', '2021-10-26 19:52:58'),
(14, 'yes', 141, 'vava', '2021-10-26 19:53:45'),
(15, 'yes yes', 141, 'vava', '2021-10-26 19:53:53'),
(16, 'hhhhhhhhhh', 142, 'enzo', '2021-10-27 06:19:08'),
(17, 'hhhhhhhhhh', 142, 'enzo', '2021-10-27 06:19:20'),
(18, 'hhhhhhhhhh99999999999', 142, 'enzo', '2021-10-27 06:19:27'),
(19, 'i dont have any', 144, 'vava', '2021-10-27 06:40:14'),
(20, 'why', 144, 'vava', '2021-10-27 06:41:37'),
(21, 'why', 144, 'vava', '2021-10-27 06:41:41'),
(22, 'mmmm', 146, 'vava', '2021-10-28 19:41:16'),
(23, 'mmmm', 146, 'vava', '2021-10-28 19:41:16'),
(24, 'nnnnnnnnnnnnnnnnnnnnn', 146, 'vava', '2021-10-28 20:08:45'),
(25, 'bvcxxxxxxxxxxxxxxxxxxxxxxx', 146, 'vava', '2021-10-28 20:08:53'),
(26, 'cvvvvvvvvvvvvvvvvvvvvvvvvvv', 146, 'vava', '2021-10-28 20:08:57'),
(27, 'bbbbbbbbbbbbbbbbbbbbbbbbbbbb', 146, 'vava', '2021-10-28 20:09:00'),
(28, 'bbbbbbbbbbbbbbbbbbbbbbbbb', 146, 'vava', '2021-10-28 20:09:02'),
(29, 'vvv', 147, 'enzo', '2021-10-29 07:23:47'),
(30, 'gg', 147, 'enzo', '2021-10-29 07:28:02'),
(31, 'gggghg', 147, 'enzo', '2021-10-29 07:28:11'),
(32, 'hhh', 147, 'enzo', '2021-10-29 07:34:58'),
(33, 'hg', 147, 'enzo', '2021-10-29 07:35:54'),
(34, '❤️😒', 147, 'enzo', '2021-10-29 09:21:15'),
(35, '💋', 147, 'enzo', '2021-10-29 09:22:09'),
(36, '😍❤️', 147, 'enzo', '2021-10-29 10:46:02'),
(37, '😍❤️🙈', 147, 'enzo', '2021-10-29 10:46:16'),
(38, 'jjjj', 147, 'vava', '2021-10-29 10:47:57'),
(39, 'hey brother❤️❤️❤️', 147, 'valentin', '2021-10-29 12:52:56'),
(40, 'jhdshjjhsd', 147, 'valentin', '2021-10-29 14:09:37'),
(41, '❤️❤️', 147, 'valentin', '2021-10-29 16:26:06'),
(42, '😃😃😃😃😃😃', 147, 'valentin', '2021-10-29 17:17:51'),
(43, '😃❤️❤️❤️😱😱😱😱', 137, 'valentin', '2021-10-29 19:59:40'),
(44, 'cool 🌋🌋🌋💪💪💪🙏🙏🙏🙏', 137, 'valentin', '2021-10-29 20:00:54'),
(45, '💞💞💞💘💘💘💗💗💗💔💔', 137, 'valentin', '2021-10-29 20:01:23'),
(46, 'Yeah!', 157, 'valentin', '2021-10-29 20:18:20'),
(47, 'Hey!', 158, 'coder', '2021-10-30 18:37:47'),
(48, 'What is up', 158, 'coder', '2021-10-30 18:38:27'),
(49, '😳😚😝😝😝😢😢', 163, 'valentin', '2021-10-31 16:43:01'),
(50, 'Where are u now?', 139, 'valentin', '2021-10-31 17:01:26'),
(51, 'Great', 162, 'valentin', '2021-10-31 17:07:32'),
(52, 'He', 163, 'valentin', '2021-10-31 20:22:56'),
(53, 'ok', 163, 'valentin', '2021-10-31 20:50:23'),
(54, 'jadja', 163, 'valentin', '2021-10-31 20:53:14'),
(55, 'Ueee', 163, 'valentin', '2021-10-31 20:53:27'),
(56, 'Oka\r\n\r\n', 163, 'valentin', '2021-10-31 20:54:31'),
(57, 'Hello', 163, 'valentin', '2021-10-31 20:55:26'),
(58, 'Hey', 163, 'valentin', '2021-10-31 20:55:40'),
(59, 'Oka', 163, 'valentin', '2021-10-31 20:55:44'),
(60, 'kahjas', 163, 'valentin', '2021-10-31 20:55:46'),
(61, 'basbjsbnas', 163, 'valentin', '2021-10-31 20:55:48'),
(62, 'sbasbas', 163, 'valentin', '2021-10-31 20:55:49'),
(63, 'basbjas', 163, 'valentin', '2021-10-31 20:55:50'),
(64, 'nnjasa', 163, 'valentin', '2021-10-31 20:55:51'),
(65, 'masbas', 163, 'valentin', '2021-10-31 20:55:52'),
(66, 'asmnsa', 163, 'valentin', '2021-10-31 20:55:53'),
(67, 'nsanmsa', 163, 'valentin', '2021-10-31 20:55:54'),
(68, 'nasas', 163, 'valentin', '2021-10-31 20:55:54'),
(69, 'nasms', 163, 'valentin', '2021-10-31 20:55:55'),
(70, 'nsnmas', 163, 'valentin', '2021-10-31 20:55:56'),
(71, 'sa,nas', 163, 'valentin', '2021-10-31 20:55:57'),
(72, 'mas,sa', 163, 'valentin', '2021-10-31 20:55:58'),
(73, 'msa,msa', 163, 'valentin', '2021-10-31 20:55:59'),
(74, ',sa,,as', 163, 'valentin', '2021-10-31 20:56:00'),
(75, 'l;lll', 163, 'valentin', '2021-10-31 21:23:53'),
(76, ',l', 163, 'valentin', '2021-10-31 21:23:55'),
(77, ',', 163, 'valentin', '2021-10-31 21:23:55'),
(78, 'l', 163, 'valentin', '2021-10-31 21:23:56'),
(79, ';', 163, 'valentin', '2021-10-31 21:23:57'),
(80, 'klldsds', 163, 'valentin', '2021-10-31 21:24:00'),
(81, 'sdsd', 163, 'valentin', '2021-10-31 21:24:01'),
(82, 's', 163, 'valentin', '2021-10-31 21:24:01'),
(83, 'ds', 163, 'valentin', '2021-10-31 21:24:02'),
(84, 'ss', 163, 'valentin', '2021-10-31 21:24:02'),
(85, 'd', 163, 'valentin', '2021-10-31 21:24:03'),
(86, 'fs', 163, 'valentin', '2021-10-31 21:24:03'),
(87, 'f', 163, 'valentin', '2021-10-31 21:24:03'),
(88, 's', 163, 'valentin', '2021-10-31 21:24:03'),
(89, 'das', 163, 'valentin', '2021-10-31 21:24:04'),
(90, 'asd', 163, 'valentin', '2021-10-31 21:24:04'),
(91, 'd', 163, 'valentin', '2021-10-31 21:24:04'),
(92, 'a', 163, 'valentin', '2021-10-31 21:24:05'),
(93, 'a', 163, 'valentin', '2021-10-31 21:24:05'),
(94, 'd', 163, 'valentin', '2021-10-31 21:24:05'),
(95, 'sd', 163, 'valentin', '2021-10-31 21:24:05'),
(96, 'as', 163, 'valentin', '2021-10-31 21:24:06'),
(97, 'a', 163, 'valentin', '2021-10-31 21:24:06'),
(98, 'd', 163, 'valentin', '2021-10-31 21:24:06'),
(99, '😚😝😝😝', 163, 'valentin', '2021-10-31 21:46:22'),
(100, 'Hahhaha', 174, 'valentin', '2021-11-01 19:12:42'),
(101, 'Yeh!', 165, 'valentin', '2021-11-01 19:12:58'),
(102, '😘😘😘😘😔😔😔😔', 165, 'valentin', '2021-11-01 19:13:39'),
(103, '😢😢😢😢😢😢', 174, 'valentin', '2021-11-03 12:05:48'),
(104, 'Hahahah', 174, 'valentin', '2021-11-03 12:18:04'),
(105, 'Commenting', 177, 'valentin', '2021-11-05 13:43:24');

-- --------------------------------------------------------

--
-- Table structure for table `comment_likes`
--

CREATE TABLE `comment_likes` (
  `comment_id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `comment_likes`
--

INSERT INTO `comment_likes` (`comment_id`, `username`) VALUES
(2, 'valentin'),
(4, 'valentin'),
(5, 'vava'),
(6, 'valentin'),
(7, 'valentin'),
(10, 'valentin'),
(13, 'vava'),
(19, 'vava'),
(37, 'valentin'),
(41, 'valentin'),
(47, 'coder'),
(50, 'valentin'),
(51, 'valentin'),
(52, 'valentin'),
(81, 'valentin'),
(82, 'valentin'),
(84, 'valentin'),
(99, 'valentin');

-- --------------------------------------------------------

--
-- Table structure for table `friendrequest`
--

CREATE TABLE `friendrequest` (
  `sender` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `reciever` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `friendrequest`
--

INSERT INTO `friendrequest` (`sender`, `reciever`, `date`) VALUES
('coder', 'yeahp', '2021-11-07 07:29:02'),
('polite', 'Makuza', '2021-10-27 06:32:18'),
('valentin', 'polite', '2021-10-30 14:00:33'),
('vava', 'yeahp', '2021-10-26 19:39:16');

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `friend` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `partener` varchar(100) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`friend`, `partener`) VALUES
('Lissouba', 'vava'),
('Makuza', 'enzo'),
('Makuza', 'vava'),
('enzo', 'coder'),
('polite', 'coder'),
('polite', 'enzo'),
('polite', 'vava'),
('valentin', 'coder'),
('valentin', 'enzo'),
('vava', 'coder');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `profile_pic` varchar(255) NOT NULL,
  `about` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `profile_pic`, `about`) VALUES
(1, 'My Niggas', '', '0'),
(2, 'Gang', '', '0'),
(3, 'Prudent Ngiri', '', '0'),
(4, 'All stars', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `reciever` varchar(100) COLLATE utf8mb4_bin DEFAULT NULL,
  `body` varchar(1000) COLLATE utf8mb4_bin NOT NULL,
  `date_` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(10) COLLATE utf8mb4_bin NOT NULL DEFAULT 'unread',
  `story_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender`, `reciever`, `body`, `date_`, `status`, `story_id`, `created_at`, `group_id`) VALUES
(1, 'coder', 'vava', 'Hi\n', '2021-11-07 18:28:53', 'unread', NULL, '2021-11-07 18:28:53', NULL),
(2, 'vava', 'coder', 'Hi\n', '2021-11-07 18:29:29', 'unread', NULL, '2021-11-07 18:29:29', NULL),
(3, 'coder', 'vava', 'kk\n', '2021-11-07 18:30:55', 'unread', NULL, '2021-11-07 18:30:55', NULL),
(4, 'vava', 'coder', 'kk\n', '2021-11-07 18:31:03', 'unread', NULL, '2021-11-07 18:31:03', NULL),
(5, 'coder', 'vava', 'ok\n', '2021-11-07 18:31:13', 'unread', NULL, '2021-11-07 18:31:13', NULL),
(6, 'coder', 'vava', 'HJa\n', '2021-11-07 18:34:04', 'unread', NULL, '2021-11-07 18:34:04', NULL),
(7, 'vava', 'coder', 'Hey Boi!', '2021-11-07 18:34:21', 'unread', NULL, '2021-11-07 18:34:21', NULL),
(8, 'vava', 'coder', 'I\'m conviced this is un real!', '2021-11-07 18:34:46', 'unread', NULL, '2021-11-07 18:34:46', NULL),
(9, 'coder', 'vava', 'Hahha\n', '2021-11-07 18:34:51', 'unread', NULL, '2021-11-07 18:34:51', NULL),
(10, 'coder', 'vava', 'Really\n', '2021-11-07 18:34:53', 'unread', NULL, '2021-11-07 18:34:53', NULL),
(11, 'coder', 'vava', 'Another ONe\n', '2021-11-07 18:35:20', 'unread', NULL, '2021-11-07 18:35:20', NULL),
(12, 'coder', NULL, 'Hey!', '2021-11-07 18:35:37', 'unread', NULL, '2021-11-07 18:35:37', 4),
(13, 'coder', NULL, 'ANY ONE HERE\n', '2021-11-07 18:35:53', 'unread', NULL, '2021-11-07 18:35:53', 4),
(14, 'vava', NULL, 'yEAH\n', '2021-11-07 18:36:00', 'unread', NULL, '2021-11-07 18:36:00', 4),
(15, 'vava', NULL, 'ACTUAALY\n', '2021-11-07 18:36:04', 'unread', NULL, '2021-11-07 18:36:04', 4),
(16, 'vava', NULL, 'i\'M here!', '2021-11-07 18:36:14', 'unread', NULL, '2021-11-07 18:36:14', 4),
(17, 'coder', NULL, 'Good thing\n', '2021-11-07 18:36:25', 'unread', NULL, '2021-11-07 18:36:25', 4),
(18, 'vava', 'Makuza', ':(\n', '2021-11-07 18:37:20', 'unread', NULL, '2021-11-07 18:37:20', NULL),
(19, 'vava', 'Makuza', 'hshs\n', '2021-11-07 18:39:00', 'unread', NULL, '2021-11-07 18:39:00', NULL),
(20, 'valentin', 'coder', 'hgghhA\n', '2021-11-07 18:39:07', 'unread', NULL, '2021-11-07 18:39:07', NULL),
(21, 'vava', 'Makuza', 'hahaha', '2021-11-07 18:39:55', 'unread', NULL, '2021-11-07 18:39:55', NULL),
(22, 'vava', 'Makuza', 'hahaha💔💔💔', '2021-11-07 18:40:06', 'unread', NULL, '2021-11-07 18:40:06', NULL),
(23, 'vava', 'Makuza', 'Okay', '2021-11-07 18:48:12', 'unread', NULL, '2021-11-07 18:48:12', NULL),
(24, 'vava', 'Makuza', 'hjshjhsja\nashjsjahjhasjhsa\nsahjsahjhjsa\nsakjsakjkjashashjhjas\nasjhashjashjjhashjas\nsahjjhashjashjjhas\nashjjhashjhsahjjhashjas\nsahsahjjhashjjash\nhjshjsdhj😐😐😐😐😛😛😛😛😛😛😝😝\n😁😁😁😁😁\n😔😔😔', '2021-11-07 18:51:53', 'unread', NULL, '2021-11-07 18:51:53', NULL),
(25, 'vava', 'Makuza', 'Hahahha\n', '2021-11-07 18:55:22', 'unread', NULL, '2021-11-07 18:55:22', NULL),
(26, 'vava', 'Makuza', 'Great!\n', '2021-11-07 18:55:31', 'unread', NULL, '2021-11-07 18:55:31', NULL),
(27, 'vava', 'Makuza', 'Hahha', '2021-11-07 18:56:18', 'unread', NULL, '2021-11-07 18:56:18', NULL),
(28, 'vava', 'Makuza', '😍😍😍😍👿👿', '2021-11-07 18:56:30', 'unread', NULL, '2021-11-07 18:56:30', NULL),
(29, 'coder', NULL, '😁😁😁😁😁', '2021-11-07 18:57:12', 'unread', NULL, '2021-11-07 18:57:12', 4),
(30, 'coder', NULL, '😊😊😊😊', '2021-11-07 18:57:58', 'unread', NULL, '2021-11-07 18:57:58', 4),
(31, 'coder', NULL, 'Hi', '2021-11-07 19:03:38', 'unread', NULL, '2021-11-07 19:03:38', 4),
(32, 'coder', NULL, 'Good', '2021-11-07 19:04:04', 'unread', NULL, '2021-11-07 19:04:04', 4),
(33, 'valentin', 'coder', 'hh', '2021-11-07 19:04:16', 'unread', NULL, '2021-11-07 19:04:16', NULL),
(34, 'valentin', 'coder', 'kkk', '2021-11-07 19:04:28', 'unread', NULL, '2021-11-07 19:04:28', NULL),
(35, 'coder', 'valentin', 'hhh', '2021-11-07 19:04:44', 'unread', NULL, '2021-11-07 19:04:44', NULL),
(36, 'coder', 'valentin', '👍', '2021-11-07 19:06:47', 'unread', NULL, '2021-11-07 19:06:47', NULL),
(37, 'coder', 'valentin', '👍', '2021-11-07 19:10:28', 'unread', NULL, '2021-11-07 19:10:28', NULL),
(38, 'valentin', 'coder', 'hjashjas', '2021-11-07 19:10:36', 'unread', NULL, '2021-11-07 19:10:36', NULL),
(39, 'coder', NULL, 'hh', '2021-11-07 19:10:58', 'unread', NULL, '2021-11-07 19:10:58', 4),
(40, 'valentin', NULL, 'ok', '2021-11-07 19:11:12', 'unread', NULL, '2021-11-07 19:11:12', 4),
(41, 'valentin', NULL, 'Hahha!', '2021-11-07 19:11:17', 'unread', NULL, '2021-11-07 19:11:17', 4),
(42, 'valentin', NULL, 'Loving it ❤️❤️❤️', '2021-11-07 19:11:25', 'unread', NULL, '2021-11-07 19:11:25', 4),
(43, 'coder', NULL, '❤️❤️❤️❤️❤️❤️❤️❤️❤️❤️💋💋💋💋💋💋', '2021-11-07 19:11:34', 'unread', NULL, '2021-11-07 19:11:34', 4),
(44, 'valentin', NULL, '😘😘 O Sweetie', '2021-11-07 19:11:43', 'unread', NULL, '2021-11-07 19:11:43', 4),
(45, 'vava', NULL, 'Hahha', '2021-11-07 19:12:43', 'unread', NULL, '2021-11-07 19:12:43', 4),
(46, 'vava', NULL, 'I see ya', '2021-11-07 19:12:47', 'unread', NULL, '2021-11-07 19:12:47', 4),
(47, 'valentin', NULL, 'UUU', '2021-11-07 19:12:54', 'unread', NULL, '2021-11-07 19:12:54', 4),
(48, 'valentin', NULL, 'Hearing over isn\'t nice', '2021-11-07 19:13:08', 'unread', NULL, '2021-11-07 19:13:08', 4),
(49, 'coder', NULL, 'can hear u ttoo', '2021-11-07 19:13:21', 'unread', NULL, '2021-11-07 19:13:21', 4),
(50, 'coder', NULL, 'where are others', '2021-11-07 19:13:28', 'unread', NULL, '2021-11-07 19:13:28', 4),
(51, 'coder', NULL, '???', '2021-11-07 19:13:30', 'unread', NULL, '2021-11-07 19:13:30', 4),
(52, 'vava', NULL, '😁😱😱😱😱', '2021-11-07 19:13:39', 'unread', NULL, '2021-11-07 19:13:39', 4),
(53, 'vava', NULL, 'I just don\'t know', '2021-11-07 19:13:47', 'unread', NULL, '2021-11-07 19:13:47', 4),
(54, 'vava', NULL, '☀️☀️☀️☀️☀️', '2021-11-07 19:14:47', 'unread', NULL, '2021-11-07 19:14:47', 4),
(55, 'vava', NULL, 'You are sunshine t mylife', '2021-11-07 19:15:01', 'unread', NULL, '2021-11-07 19:15:01', 4),
(56, 'valentin', NULL, 'You too', '2021-11-07 19:15:08', 'unread', NULL, '2021-11-07 19:15:08', 4),
(57, 'coder', NULL, '❤️❤️❤️❤️ Lovel;y', '2021-11-07 19:15:18', 'unread', NULL, '2021-11-07 19:15:18', 4),
(58, 'coder', NULL, 'broke my heart 💔💔💔💔', '2021-11-07 19:15:41', 'unread', NULL, '2021-11-07 19:15:41', 4),
(59, 'valentin', NULL, 'are you guys serious?', '2021-11-07 19:15:56', 'unread', NULL, '2021-11-07 19:15:56', 4);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `post` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `video` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `post`, `image`, `video`, `username`, `date`) VALUES
(137, 'Hey there', '', '', 'vava', '2021-10-24 19:44:57'),
(138, 'For our country!', '7489868.png', '', 'valentin', '2021-10-25 19:00:41'),
(139, '', '9706891.png', '', 'valentin', '2021-10-26 08:39:09'),
(140, '', '', '', 'valentin', '2021-10-26 08:39:20'),
(141, '', '4174936.jpg', '', 'valentin', '2021-10-26 09:23:15'),
(142, 'Oka', '', '', 'valentin', '2021-10-26 09:28:24'),
(143, 'hjkjhjgjhghj', '', '', 'vava', '2021-10-26 20:04:31'),
(144, 'my first post, i want your ideas', '8423956.jpg', '', 'polite', '2021-10-27 06:39:36'),
(145, 'gfg', '', '', 'vava', '2021-10-27 07:15:16'),
(146, '', '4625995.png', '', 'vava', '2021-10-27 07:15:23'),
(147, 'another one', '', '', 'enzo', '2021-10-28 20:16:14'),
(148, '💋', '', '', 'enzo', '2021-10-29 09:21:26'),
(149, 'ghghfghfgh❤️', '', '', 'enzo', '2021-10-29 09:21:44'),
(150, '🙈❤️', '', '', 'enzo', '2021-10-29 10:46:26'),
(151, '❤️❤️', '', '', 'enzo', '2021-10-29 10:46:38'),
(152, 'kkkkk', '', '', 'vava', '2021-10-29 10:48:03'),
(153, 'jjkhjkjhhkhjk', '', '', 'vava', '2021-10-29 10:48:25'),
(154, '😍😍😍😍😍😍', '', '', 'valentin', '2021-10-29 16:53:15'),
(155, '🔱🔱🔱🔱', '', '', 'valentin', '2021-10-29 17:10:15'),
(156, '😕😕😕😀😀😀😳😳', '', '', 'valentin', '2021-10-29 19:46:04'),
(157, '', '3076781.jpg', '', 'valentin', '2021-10-29 20:11:36'),
(158, '🌊🌊🌀🌀⭐️⭐️🌠🌠🌠🌌🌋🌋🌍🌍☀️☀️🎄🎄🎉🎉🎊🎊🎌🎌🎓🎓🎓🎒🎒🎒💝💝💝🎆🎆🎇🎇🎇🎁🎁🎥🎥🎥📣📣📣💰💰💰💰📭📭📭📪📪📪📌📌📌✂️✂️✂️📂📂📂📓📓🎨🎨🎨🎸🎸🎸🃏🃏🃏🏀🏀🏀🏀⚽️⚽️⚽️🏄🏄🏄🏊🏊🏊🏂', '', '', 'coder', '2021-10-30 18:13:24'),
(159, '🌊🌊🌀🌀⭐️⭐️🌠🌠🌠🌌🌋🌋🌍🌍☀️☀️🎄🎄🎉🎉🎊🎊🎌🎌🎓🎓🎓🎒🎒🎒💝💝💝🎆🎆🎇🎇🎇🎁🎁🎥🎥🎥📣📣📣💰💰💰💰📭📭📭📪📪📪📌📌📌✂️✂️✂️📂📂📂📓📓🎨🎨🎨🎸🎸🎸🃏🃏🃏🏀🏀🏀🏀⚽️⚽️⚽️🏄🏄🏄🏊🏊🏊🏂', '', '', 'coder', '2021-10-30 18:13:33'),
(160, 'Hello, brother!', '', '', 'coder', '2021-10-30 18:35:59'),
(161, 'Emoji Post\r\n\r\n\r\n❤️❤️❤️', '6441351.jpg', '', 'valentin', '2021-10-30 22:51:02'),
(162, 'Reverse it😂😂😂😂', '6792686.jpg', '', 'valentin', '2021-10-30 22:52:09'),
(163, '😀😀😀😀🇫🇷🇫🇷🇯🇵🇯🇵🚩', '', '', 'valentin', '2021-10-31 14:35:25'),
(164, '', '8768192.png', '', 'valentin', '2021-10-31 21:52:39'),
(165, 'qqqqqq', '', '6391660.mp4', 'valentin', '2021-11-01 13:22:26'),
(166, '', '', '544488.mp4', 'valentin', '2021-11-01 13:27:22'),
(167, 'dddddddddd', '', '478114.mp4', 'valentin', '2021-11-01 13:30:19'),
(168, 'Hello, brother', '', '', 'valentin', '2021-11-01 15:35:55'),
(169, '', '', '7101377.mp4', 'valentin', '2021-11-01 15:45:55'),
(170, '', '', '7120938.mp4', 'valentin', '2021-11-01 15:46:21'),
(171, '', '', '', 'valentin', '2021-11-01 15:46:28'),
(172, '', '', '4886986.mp4', 'valentin', '2021-11-01 15:52:13'),
(173, '', '5022394.jpeg', '', 'valentin', '2021-11-01 15:52:25'),
(174, '', '9547530.jpg', '', 'valentin', '2021-11-01 16:02:15'),
(175, '☺️☺️☺️☺️☺️💋💋💋💋❤️❤️❤️', '', '', 'valentin', '2021-11-03 13:29:00'),
(176, '\r\n☺️☺️☺️☺️💋💋\r\n\r\n\r\n                                    \r\n\r\n                        ☺️☺️☺️☺️☺️💋💋💋💋\r\n☺️', '', '', 'valentin', '2021-11-03 13:29:43'),
(177, '', '1769857.jpg', '9297791.mp4', 'valentin', '2021-11-03 16:07:18');

-- --------------------------------------------------------

--
-- Table structure for table `post_likes`
--

CREATE TABLE `post_likes` (
  `username` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `post_likes`
--

INSERT INTO `post_likes` (`username`, `post_id`) VALUES
('coder', 159),
('enzo', 150),
('enzo', 166),
('polite', 137),
('polite', 138),
('polite', 139),
('polite', 140),
('polite', 141),
('polite', 144),
('valentin', 138),
('valentin', 139),
('valentin', 140),
('valentin', 141),
('valentin', 147),
('valentin', 150),
('valentin', 151),
('valentin', 154),
('valentin', 155),
('valentin', 157),
('valentin', 162),
('valentin', 163),
('valentin', 165),
('valentin', 167),
('valentin', 168),
('valentin', 172),
('valentin', 174),
('vava', 137),
('vava', 138),
('vava', 139),
('vava', 140),
('vava', 141),
('vava', 142),
('vava', 143),
('vava', 144),
('vava', 145);

-- --------------------------------------------------------

--
-- Table structure for table `pwdreset`
--

CREATE TABLE `pwdreset` (
  `id` int(200) NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `token` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `expires` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `verified` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `pwdreset`
--

INSERT INTO `pwdreset` (`id`, `email`, `token`, `expires`, `created_at`, `verified`) VALUES
(8, 'ishimwevalentin3@gmail.com', 'ced8465c5ac3bafe8fc1bb6417e308d92506d1b9fe287746589c5ab7a16640cd', NULL, '2021-10-30 20:50:50', 1),
(9, 'ishimwevalentin3@gmail.com', 'f814c8d69e42dba35c6a64c67fafa8ce01948251a0f34405af19e371efa71716', NULL, '2021-10-30 20:52:51', 0),
(10, 'ishimwevalentin3@gmail.com', '910fcf2aa226ed653a6e37784dd424622630b20b689cc9dae9c5ec282ced8e3f', NULL, '2021-10-30 20:55:40', 1),
(11, 'ishimwevalentin3@gmail.com', 'f4ef8d45b01754b99828a43a5fd69d541273fa52273db2431d43e931b9c0883c', NULL, '2021-10-30 21:02:38', 1),
(12, 'ishimwedeveloper@gmail.com', '8b5bec0ca8af16b571681ae40b23ad051086e408f04786f0c99a2ce8cc71e206', NULL, '2021-11-03 16:23:35', 1);

-- --------------------------------------------------------

--
-- Table structure for table `stories`
--

CREATE TABLE `stories` (
  `id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `image` varchar(300) COLLATE utf8mb4_bin NOT NULL,
  `description` text COLLATE utf8mb4_bin NOT NULL,
  `expired` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `has_media` tinyint(4) DEFAULT 0,
  `media` varchar(300) COLLATE utf8mb4_bin NOT NULL,
  `views` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `stories`
--

INSERT INTO `stories` (`id`, `username`, `image`, `description`, `expired`, `created_at`, `has_media`, `media`, `views`) VALUES
(53, 'vava', '6969390', 'Hashye!', 0, '2021-10-24 19:22:05', 0, '', 1),
(54, 'valentin', '', 'what are u doing', 0, '2021-10-25 22:08:49', 0, '', 2),
(55, 'vava', '', 'Doing somthing cool', 0, '2021-10-26 07:45:12', 1, '1769599', 1),
(56, 'polite', '', 'my first status', 0, '2021-10-27 06:37:09', 0, '', 2),
(57, 'vava', '323090', '', 0, '2021-10-27 07:36:56', 0, '', 2),
(58, 'vava', '', 'fghjjjjjjjjjjjjk', 0, '2021-10-27 07:37:11', 0, '', 2),
(59, 'vava', '404980', '', 0, '2021-10-27 07:39:06', 0, '', 1),
(60, 'vava', '', 'hgggggggggg', 0, '2021-10-28 20:06:56', 0, '', 2),
(61, 'vava', '', 'ffffffffffffffff', 0, '2021-10-28 20:07:24', 0, '', 1),
(62, 'vava', '', '❤️❤️❤️', 0, '2021-10-29 10:48:39', 0, '', 1),
(63, 'valentin', '', '💋💋💋💋💋💋💋', 0, '2021-10-29 16:14:13', 0, '', 1),
(64, 'valentin', '', '😒😁😁😁\r\n\r\nThis is so cool', 0, '2021-10-29 16:36:51', 0, '', 1),
(65, 'valentin', '', '💋💋💋💋', 0, '2021-10-29 17:53:27', 0, '', 1),
(66, 'valentin', '', 'nbnbassab', 0, '2021-10-29 21:07:23', 0, '', 1),
(67, 'coder', '', '❤️😊😊😊❤️❤️❤️❤️😊😊😊😊', 0, '2021-10-30 18:41:59', 0, '', 1),
(68, 'coder', '', '', 0, '2021-10-30 18:46:55', 0, '', 1),
(69, 'valentin', '', '😍😍😍😍', 0, '2021-10-31 14:36:23', 0, '', 1),
(70, 'valentin', '', '', 0, '2021-11-01 12:05:54', 0, '', 2),
(71, 'valentin', '188781', '', 0, '2021-11-01 12:09:28', 0, '', 2),
(72, 'valentin', '2425067image', '', 0, '2021-11-01 12:14:28', 0, '', 1),
(73, 'valentin', '2216141download WDA.png', '', 0, '2021-11-01 12:16:07', 0, '', 1),
(74, 'valentin', '9562718Web capture_15-10-2021_15161_web.facebook.com.jpeg', '', 0, '2021-11-01 12:21:01', 0, '', 1),
(75, 'valentin', '', '', 0, '2021-11-01 12:24:30', 1, '299359510 React Hooks Explained __ Plus Build your own from Scratch.mp4', 1),
(76, 'valentin', '', '😂😂😍😍😜😜😜', 0, '2021-11-03 16:03:14', 0, '', 1),
(77, 'valentin', '', '', 0, '2021-11-03 16:03:37', 0, '', 1),
(78, 'valentin', '2396453', '', 0, '2021-11-03 16:04:50', 0, '', 1),
(79, 'valentin', '', '', 0, '2021-11-03 16:05:32', 1, '2133682', 1),
(80, 'valentin', '', '😔😔😘😘😘😘', 0, '2021-11-03 16:06:02', 0, '', 1),
(81, 'valentin', '825898', '😐😐', 0, '2021-11-05 14:03:28', 0, '', 2),
(82, 'valentin', '', 'hELlo FrIeNdS', 0, '2021-11-05 14:04:48', 0, '', 2),
(83, 'enzo', '', 'Hey there!', 0, '2021-11-05 14:05:30', 0, '', 2),
(84, 'valentin', '4088697', 'Back in days', 0, '2021-11-06 13:01:21', 0, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `story_views`
--

CREATE TABLE `story_views` (
  `story_id` int(11) NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `story_views`
--

INSERT INTO `story_views` (`story_id`, `username`) VALUES
(53, 'vava'),
(54, 'valentin'),
(54, 'vava'),
(55, 'vava'),
(56, 'polite'),
(56, 'vava'),
(57, 'polite'),
(57, 'vava'),
(58, 'polite'),
(58, 'vava'),
(59, 'vava'),
(60, 'enzo'),
(60, 'vava'),
(61, 'vava'),
(62, 'vava'),
(63, 'valentin'),
(64, 'valentin'),
(65, 'valentin'),
(66, 'valentin'),
(67, 'coder'),
(68, 'coder'),
(69, 'valentin'),
(70, 'enzo'),
(70, 'valentin'),
(71, 'enzo'),
(71, 'valentin'),
(72, 'valentin'),
(73, 'valentin'),
(74, 'valentin'),
(75, 'valentin'),
(76, 'valentin'),
(77, 'valentin'),
(78, 'valentin'),
(79, 'valentin'),
(80, 'valentin'),
(81, 'enzo'),
(81, 'valentin'),
(82, 'enzo'),
(82, 'valentin'),
(83, 'enzo'),
(83, 'valentin'),
(84, 'valentin');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `fname` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `lname` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `dob` date NOT NULL,
  `sex` varchar(7) COLLATE utf8mb4_bin NOT NULL,
  `username` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `about` varchar(500) COLLATE utf8mb4_bin NOT NULL DEFAULT 'Unknown',
  `profile_pic` varchar(500) COLLATE utf8mb4_bin NOT NULL DEFAULT 'default.png',
  `address` varchar(100) COLLATE utf8mb4_bin DEFAULT 'Unknown',
  `status` varchar(10) COLLATE utf8mb4_bin NOT NULL DEFAULT 'offline',
  `code` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `verified` tinyint(4) NOT NULL DEFAULT 0,
  `remember_me` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `last_seen` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`fname`, `lname`, `email`, `dob`, `sex`, `username`, `password`, `about`, `profile_pic`, `address`, `status`, `code`, `verified`, `remember_me`, `last_seen`) VALUES
('niyonsaba', 'pascal', 'niyopascalg@gmail.com', '1997-10-07', 'Male', 'Lissouba', 'e091dbf01fb1d6484fc5e69138b0ae89c1ce30b3', 'I am student', 'default.png', 'Rwanda', 'online', '433730', 0, '', '2021-11-07 08:17:55'),
('Nsanzimana', 'Emmanuel', 'nsanzimanaofficial@gmail.com', '2021-10-20', 'Male', 'Makuza', '4c8b3664cee92bd72dafa03a6513b984850a1b78', 'Unknown', 'default.png', 'Unknown', 'offline', '520666', 0, '', '2021-11-07 08:17:55'),
('Hero', 'Coder', 'ishimwedeveloper@gmail.com', '0000-00-00', '', 'coder', '$2y$10$uEgSDtZ48dvBDk/u55sG3.IocqPU4nfL0hKNXmEz6F1.DaiyHrw6y', '', '8187406.jpg', '', 'online', '920288', 1, '$2y$10$.OGIUVY.ZNufaBXCONAeve1Heyv8zwusYQ89HoloPf3v5CeXrFYlG', '2021-11-07 13:07:50'),
('ngirinshuti', 'prudent', 'prudentenri001@gmail.com', '2021-10-20', 'male', 'enzo', '$2y$10$dbea5X8k6dJ8aNFNq74IeeCfJA2i5aFfp4GQ.7JhkoSq3b4euzsHS', 'UnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnknownUnk', '867301.jpg', 'Unknown', 'offline', '500388', 1, '$2y$10$LrCZ.ACtX1M6ulg3Y4p/NOGuSsmybrmZrYsCGlaB2DXUS8nssRce2', '2021-11-07 15:15:12'),
('Nshimiyimana', 'Viateur', 'nvipolite@gmail.com', '0000-00-00', '', 'polite', '$2y$10$Eb.k/wn.aSMvxvtxLK3FEei7NvODhmtzi17FYnWl/zNVSqlIV2FM6', 'i am single', '371.png', 'Rwanda', 'offline', '422235', 1, '', '2021-11-07 08:17:55'),
('ISHIMWE', 'Valentin', 'ishimwevalentin3@gmail.com', '2002-10-11', 'male', 'valentin', '$2y$10$D0OuEX/QrFruB4xAJEQtLemSBffjimHOskI1mkINEoc9efB1COqeq', 'I am a good guy bro', '2504713.JPG', 'Muganza Sector Office', 'offline', '110085', 1, '$2y$10$Nv.i5.1zPf2JwQuw1Wz.Yu6/DloOdXNJ3cisn0GeQ13Qzfd/VPLQi', '2021-11-07 15:06:35'),
('ishimwe', 'valentin', 'prudentenz001@gmail.com', '2021-10-20', 'male', 'vava', '$2y$10$0.ER4L9nsmnE8mLXRr52ROPNxIgjXV3TrTbyfpOfUq1ahpIu4KPEe', 'w', '4151669.jpg', 'Unknown', 'online', '530036', 1, '$2y$10$rIRD/ks3bi9zdeCPzIouvOw2hc6wJBqJ8rFzC.lrXUyuMDrLn.Ubu', '2021-11-07 08:17:55'),
('ISHIMWE', '', 'ishimwevalentin3@gmail.comw', '0000-00-00', '', 'yeahp', '$2y$10$1xjROiN4i/3aQhpa8QU6EO2qnMDv40fjPlinSipovCdoQCHU9kJTW', '', '', '', 'offline', '870581', 0, '', '2021-11-07 08:17:55');

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE `user_groups` (
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `group_id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_groups`
--

INSERT INTO `user_groups` (`username`, `group_id`, `role`) VALUES
('Makuza', 1, 'member'),
('Makuza', 2, 'member'),
('Makuza', 3, 'member'),
('coder', 4, 'admin'),
('enzo', 1, 'admin'),
('enzo', 2, 'admin'),
('enzo', 3, 'admin'),
('enzo', 4, 'member'),
('polite', 2, 'member'),
('polite', 3, 'member'),
('polite', 4, 'member'),
('valentin', 1, 'member'),
('valentin', 4, 'member'),
('vava', 4, 'member');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_comments` (`username`),
  ADD KEY `post_comments` (`post_id`);

--
-- Indexes for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD PRIMARY KEY (`comment_id`,`username`),
  ADD KEY `user_comment_likes` (`username`);

--
-- Indexes for table `friendrequest`
--
ALTER TABLE `friendrequest`
  ADD PRIMARY KEY (`sender`,`reciever`),
  ADD KEY `reciever` (`reciever`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`friend`,`partener`),
  ADD KEY `partener` (`partener`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date_` (`date_`),
  ADD KEY `reciever` (`reciever`),
  ADD KEY `sender` (`sender`),
  ADD KEY `story_reply` (`story_id`),
  ADD KEY `group_messages` (`group_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `posts_ibfk_1` (`username`);

--
-- Indexes for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD PRIMARY KEY (`username`,`post_id`),
  ADD KEY `post_likes` (`post_id`);

--
-- Indexes for table `pwdreset`
--
ALTER TABLE `pwdreset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_resets` (`email`);

--
-- Indexes for table `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_stories` (`username`);

--
-- Indexes for table `story_views`
--
ALTER TABLE `story_views`
  ADD PRIMARY KEY (`story_id`,`username`),
  ADD KEY `story_viewer` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD PRIMARY KEY (`username`,`group_id`),
  ADD KEY `group_users` (`group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=178;

--
-- AUTO_INCREMENT for table `pwdreset`
--
ALTER TABLE `pwdreset`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `stories`
--
ALTER TABLE `stories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `post_comments` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_comments` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comment_likes`
--
ALTER TABLE `comment_likes`
  ADD CONSTRAINT `comment_likes` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_comment_likes` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `friendrequest`
--
ALTER TABLE `friendrequest`
  ADD CONSTRAINT `friendrequest_ibfk_1` FOREIGN KEY (`reciever`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `friendrequest_ibfk_2` FOREIGN KEY (`sender`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`friend`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`partener`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `group_messages` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reciever` FOREIGN KEY (`reciever`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sender` FOREIGN KEY (`sender`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `story_reply` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `post_likes`
--
ALTER TABLE `post_likes`
  ADD CONSTRAINT `post_likes` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_liked_posts` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pwdreset`
--
ALTER TABLE `pwdreset`
  ADD CONSTRAINT `user_resets` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stories`
--
ALTER TABLE `stories`
  ADD CONSTRAINT `user_stories` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `story_views`
--
ALTER TABLE `story_views`
  ADD CONSTRAINT `story_viewed` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `story_viewer` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_groups`
--
ALTER TABLE `user_groups`
  ADD CONSTRAINT `group_users` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_groups_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
