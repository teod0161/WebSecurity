-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2018 at 02:22 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 5.6.38

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--
CREATE DATABASE IF NOT EXISTS `blog` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `blog`;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_data` longtext,
  `user_id` varchar(50) DEFAULT NULL,
  `title` varchar(50) DEFAULT NULL,
  `description` tinytext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `articles_id_uindex` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `articles`:
--

--
-- Truncate table before insert `articles`
--

TRUNCATE TABLE `articles`;
--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `article_data`, `user_id`, `title`, `description`) VALUES
(14, '{\"blocks\":[{\"key\":\"ahftu\",\"text\":\"React JS boilerplates used to be difficult to come by, and even more difficult to implement. \",\"type\":\"unstyled\",\"depth\":0,\"inlineStyleRanges\":[],\"entityRanges\":[],\"data\":{}},{\"key\":\"80h51\",\"text\":\"Fortunately though, there now exists create-react-app.\",\"type\":\"unstyled\",\"depth\":0,\"inlineStyleRanges\":[{\"offset\":0,\"length\":18,\"style\":\"BOLD\"},{\"offset\":37,\"length\":16,\"style\":\"UNDERLINE\"}],\"entityRanges\":[],\"data\":{}},{\"key\":\"al6v2\",\"text\":\"\",\"type\":\"unstyled\",\"depth\":0,\"inlineStyleRanges\":[],\"entityRanges\":[],\"data\":{}},{\"key\":\"eh3v0\",\"text\":\"How does it work ? It\'s simple, just do npm install -g create-react-app to install the tool globaly and then run create-react-app demo.  \",\"type\":\"unstyled\",\"depth\":0,\"inlineStyleRanges\":[{\"offset\":40,\"length\":32,\"style\":\"CODE\"},{\"offset\":113,\"length\":24,\"style\":\"CODE\"}],\"entityRanges\":[],\"data\":{}}],\"entityMap\":{}}', '5bccc0f72ad2f', 'Hello React', NULL),
(16, '{\"blocks\":[{\"key\":\"dg7fa\",\"text\":\"Well ... I lied, this article is not that awesome ... !!awesome\",\"type\":\"unstyled\",\"depth\":0,\"inlineStyleRanges\":[{\"offset\":54,\"length\":9,\"style\":\"CODE\"}],\"entityRanges\":[],\"data\":{}}],\"entityMap\":{}}', '5bccc0f72ad2f', 'This is my awesome article', ''),
(25, '{\"blocks\":[{\"key\":\"6dso5\",\"text\":\"dfdfdsfsdf\",\"type\":\"unstyled\",\"depth\":0,\"inlineStyleRanges\":[],\"entityRanges\":[],\"data\":{}}],\"entityMap\":{}}', '5bf087cfdf6bc', 'Test', 'sdfdsf');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` longtext,
  `user_id` varchar(50) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `comments_id_uindex` (`id`),
  KEY `comments_articles_id_fk` (`article_id`),
  KEY `comments_users_user_id_fk` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `comments`:
--   `article_id`
--       `articles` -> `id`
--   `user_id`
--       `users` -> `user_id`
--

--
-- Truncate table before insert `comments`
--

TRUNCATE TABLE `comments`;
--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `comment`, `user_id`, `article_id`) VALUES
(5, 'test', NULL, 16);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `avatar_path` varchar(50) DEFAULT NULL,
  `password` varchar(250) NOT NULL,
  `user_id` varchar(50) NOT NULL,
  `forgot_password` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `role` varchar(50) NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_user_id_uindex` (`user_id`),
  UNIQUE KEY `users_email_uindex` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- RELATIONSHIPS FOR TABLE `users`:
--

--
-- Truncate table before insert `users`
--

TRUNCATE TABLE `users`;
--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `avatar_path`, `password`, `user_id`, `forgot_password`, `role`) VALUES
(2, 'Andrei Ungureanu', 'andrei@gmail.com', '5bccc0f72ad2f.jpg', '$2y$12$W7ExeVXow3.IrhLdCHWLHOMnH7FaZ9q4zMrBbZsce8fKICiox6Aaq', '5bccc0f72ad2f', 0, 'admin'),
(9, 'Teodor', 'teo9696@abv.bg', '5bf087cfdf6bc.jpg', '$2y$12$W7ExeVXow3.IrhLdCHWLHOMnH7FaZ9q4zMrBbZsce8fKICiox6Aaq', '5bf087cfdf6bc', 0, 'admin');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_articles_id_fk` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_users_user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;


--
-- Metadata
--
USE `phpmyadmin`;

--
-- Metadata for table articles
--
-- Error reading data for table phpmyadmin.pma__column_info: #1100 - Table 'pma__column_info' was not locked with LOCK TABLES
-- Error reading data for table phpmyadmin.pma__table_uiprefs: #1100 - Table 'pma__table_uiprefs' was not locked with LOCK TABLES
-- Error reading data for table phpmyadmin.pma__tracking: #1100 - Table 'pma__tracking' was not locked with LOCK TABLES

--
-- Metadata for table comments
--
-- Error reading data for table phpmyadmin.pma__column_info: #1100 - Table 'pma__column_info' was not locked with LOCK TABLES
-- Error reading data for table phpmyadmin.pma__table_uiprefs: #1100 - Table 'pma__table_uiprefs' was not locked with LOCK TABLES
-- Error reading data for table phpmyadmin.pma__tracking: #1100 - Table 'pma__tracking' was not locked with LOCK TABLES

--
-- Metadata for table users
--
-- Error reading data for table phpmyadmin.pma__column_info: #1100 - Table 'pma__column_info' was not locked with LOCK TABLES
-- Error reading data for table phpmyadmin.pma__table_uiprefs: #1100 - Table 'pma__table_uiprefs' was not locked with LOCK TABLES
-- Error reading data for table phpmyadmin.pma__tracking: #1100 - Table 'pma__tracking' was not locked with LOCK TABLES

--
-- Metadata for database blog
--
-- Error reading data for table phpmyadmin.pma__bookmark: #1100 - Table 'pma__bookmark' was not locked with LOCK TABLES
-- Error reading data for table phpmyadmin.pma__relation: #1100 - Table 'pma__relation' was not locked with LOCK TABLES
-- Error reading data for table phpmyadmin.pma__savedsearches: #1100 - Table 'pma__savedsearches' was not locked with LOCK TABLES
-- Error reading data for table phpmyadmin.pma__central_columns: #1100 - Table 'pma__central_columns' was not locked with LOCK TABLES
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
