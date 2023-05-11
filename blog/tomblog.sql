-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2023 at 07:00 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tomblog`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_design`
--

CREATE TABLE `blog_design` (
  `id` int(11) NOT NULL,
  `name_site` varchar(255) NOT NULL,
  `img_site` varchar(255) NOT NULL,
  `icon_site` varchar(255) NOT NULL,
  `urls_nav` varchar(255) NOT NULL,
  `types_post` varchar(255) NOT NULL,
  `colors` varchar(255) NOT NULL,
  `owners` varchar(255) NOT NULL,
  `news` longtext NOT NULL,
  `past_visits` int(11) NOT NULL,
  `best_post` int(11) NOT NULL,
  `media_links` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `blog_design`
--

INSERT INTO `blog_design` (`id`, `name_site`, `img_site`, `icon_site`, `urls_nav`, `types_post`, `colors`, `owners`, `news`, `past_visits`, `best_post`, `media_links`) VALUES
(1, 'BlogTomey', 'img_blog.jpeg', 'small_icon.x-icon', 'العاب,اكشن', 'العاب,اكشن, كرتون,برامج', 'light-color', '', '/new=? info=?Tomey Changed The Powers of Andrew To owner info=?2023-03-28 08-00/new=? info=?Tomey Changed The Powers of Andrew To admin info=?2023-03-28 08-00/new=? info=?Tomey Changed The Powers of Andrew To editor info=?2023-03-28 08-01/new=? info=?Tomey Changed The Powers of Andrew To user info=?2023-03-28 08-01/new=? info=?Tomey Changed The Powers of Andrew To owner info=?2023-03-28 08-01/new=? info=?Tomey Change In Design Site info=?2023-03-28 08-09/new=? info=?Tomey Changed The Powers of Andrew To user info=?2023-03-29 04-19/new=? info=?Tomey Change In Design Site info=?2023-03-29 04-37/new=? info=?Tomey Change In Design Site info=?2023-03-29 04-48/new=? info=?Tomey Change In Design Site info=?2023-03-29 04-48/new=? info=?Tomey Change In Design Site info=?2023-03-29 04-49/new=? info=?Tomey Change In Design Site info=?2023-03-29 04-49/new=? info=?Tomey Change In Design Site info=?2023-03-29 04-50/new=? info=?Tomey Change In Design Site info=?2023-03-29 04-52/new=? info=?Andrew Added New Post info=?2023-03-29 05-00/new=? info=?Andrew Change In Design Site info=?2023-03-29 07-31/new=? info=?Andrew Added New Post info=?2023-03-29 07-34/new=? info=?Andrew Added New Post info=?2023-03-29 07-35/new=? info=?Andrew Changed The Powers of  To user info=?2023-03-30 05-36/new=? info=?Tomey Changed The Powers of Andrew To owner info=?2023-03-30 05-37/new=? info=?Andrew Changed The Powers of  To user info=?2023-03-30 05-38/new=? info=?Tomey Changed The Powers of Andrew To editor info=?2023-03-30 05-42/new=? info=?There Is New User (sdsadasd). info=?2023-03-31 06-33/new=? info=?Tomey Added New Post info=?2023-03-31 07-28/new=? info=?There Is New User (Thomas). info=?2023-04-01 05-12/new=? info=?Tomey Changed The Powers of Thomas To owner info=?2023-04-01 05-23/new=? info=?Tomey Change In Design Site info=?2023-04-01 05-53/new=? info=?Tomey Change In Design Site info=?2023-04-01 05-58/new=? info=?Tomey Added New Post info=?2023-04-01 06-32/new=? info=?Thomas Change In Design Site info=?2023-04-01 07-20/new=? info=?Thomas Added New Post info=?2023-04-01 07-33/new=? info=?Thomas Added New Post info=?2023-04-01 07-39/new=? info=?Thomas Added New Post info=?2023-04-01 07-40/new=? info=?Thomas Added New Post info=?2023-04-01 07-40/new=? info=?Thomas Added New Post info=?2023-04-01 07-41/new=? info=?Thomas Added New Post info=?2023-04-01 07-41/new=? info=?Thomas Added New Post info=?2023-04-01 07-41/new=? info=?Thomas Added New Post info=?2023-04-01 07-42/new=? info=?admin Change In Design Site info=?2023-04-01 07-47', 378, 226735, 'http://localhost/php/projects/blog/post.php?name=36213,http://localhost/php/projects/blog/post.php?name=325574');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL,
  `time_add` timestamp NOT NULL DEFAULT current_timestamp(),
  `random_post` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `username`, `message`, `time_add`, `random_post`) VALUES
(2, '9753', 'sd', '2023-05-11 16:36:01', 226735);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `name_random` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `content_one` longtext NOT NULL,
  `content_two` longtext NOT NULL,
  `img_box_one` varchar(255) NOT NULL,
  `img_box_two` varchar(255) NOT NULL,
  `des` varchar(255) NOT NULL,
  `visits` int(11) NOT NULL,
  `comment` mediumtext NOT NULL,
  `time_add` timestamp NOT NULL DEFAULT current_timestamp(),
  `username` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `bg_img_post` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `name_random`, `name`, `content_one`, `content_two`, `img_box_one`, `img_box_two`, `des`, `visits`, `comment`, `time_add`, `username`, `type`, `bg_img_post`) VALUES
(46, 226735, 'تحميل برنامج اوفيس 2016 مع التفعيل مجاني', '(o_b)تحميل برنامج اوفيس 2016 مع التفعيل مجاني(c_b)/n\r\nl_k[https://www.ahmed-techno.com/2020/10/download-office-2016.html](تحميل)l_k', '          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          ', '', '', 'تحميل برنامج اوفيس 2016 مع التفعيل مجاني\r\n', 53, '', '2023-04-01 16:32:21', '15151584', 'برامج', '8451.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `img_profile` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `permission` enum('owner','admin','editor','user') NOT NULL,
  `time_create` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `info` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `img_profile`, `name`, `email`, `password`, `permission`, `time_create`, `info`) VALUES
(1, '15151584', '15151584.png', 'Tomey', 'thomas.emad.shawky@gmail.com', '28896740', 'owner', '2023-04-01 17:51:50', 'Lorem ipsum dolor sit,\r\n'),
(17, '9753', '150.png', 'admin', 'admin@gmail.com', '123456', 'owner', '2023-04-01 17:46:25', 'How are You ?');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog_design`
--
ALTER TABLE `blog_design`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commects_ibfk_1` (`random_post`),
  ADD KEY `commects_ibfk_2` (`username`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name_random` (`name_random`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog_design`
--
ALTER TABLE `blog_design`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`random_post`) REFERENCES `posts` (`name_random`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
