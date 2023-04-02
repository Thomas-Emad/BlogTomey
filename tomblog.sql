-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2023 at 07:52 PM
-- Server version: 10.4.25-MariaDB
-- PHP Version: 8.1.10

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `blog_design`
--

INSERT INTO `blog_design` (`id`, `name_site`, `img_site`, `icon_site`, `urls_nav`, `types_post`, `colors`, `owners`, `news`, `past_visits`, `best_post`, `media_links`) VALUES
(1, 'BlogTomey', 'img_blog.jpeg', 'small_icon.x-icon', 'العاب,اكشن', 'العاب,اكشن, كرتون,برامج', 'light-color', '', '/new=? info=?Tomey Changed The Powers of Andrew To owner info=?2023-03-28 08-00/new=? info=?Tomey Changed The Powers of Andrew To admin info=?2023-03-28 08-00/new=? info=?Tomey Changed The Powers of Andrew To editor info=?2023-03-28 08-01/new=? info=?Tomey Changed The Powers of Andrew To user info=?2023-03-28 08-01/new=? info=?Tomey Changed The Powers of Andrew To owner info=?2023-03-28 08-01/new=? info=?Tomey Change In Design Site info=?2023-03-28 08-09/new=? info=?Tomey Changed The Powers of Andrew To user info=?2023-03-29 04-19/new=? info=?Tomey Change In Design Site info=?2023-03-29 04-37/new=? info=?Tomey Change In Design Site info=?2023-03-29 04-48/new=? info=?Tomey Change In Design Site info=?2023-03-29 04-48/new=? info=?Tomey Change In Design Site info=?2023-03-29 04-49/new=? info=?Tomey Change In Design Site info=?2023-03-29 04-49/new=? info=?Tomey Change In Design Site info=?2023-03-29 04-50/new=? info=?Tomey Change In Design Site info=?2023-03-29 04-52/new=? info=?Andrew Added New Post info=?2023-03-29 05-00/new=? info=?Andrew Change In Design Site info=?2023-03-29 07-31/new=? info=?Andrew Added New Post info=?2023-03-29 07-34/new=? info=?Andrew Added New Post info=?2023-03-29 07-35/new=? info=?Andrew Changed The Powers of  To user info=?2023-03-30 05-36/new=? info=?Tomey Changed The Powers of Andrew To owner info=?2023-03-30 05-37/new=? info=?Andrew Changed The Powers of  To user info=?2023-03-30 05-38/new=? info=?Tomey Changed The Powers of Andrew To editor info=?2023-03-30 05-42/new=? info=?There Is New User (sdsadasd). info=?2023-03-31 06-33/new=? info=?Tomey Added New Post info=?2023-03-31 07-28/new=? info=?There Is New User (Thomas). info=?2023-04-01 05-12/new=? info=?Tomey Changed The Powers of Thomas To owner info=?2023-04-01 05-23/new=? info=?Tomey Change In Design Site info=?2023-04-01 05-53/new=? info=?Tomey Change In Design Site info=?2023-04-01 05-58/new=? info=?Tomey Added New Post info=?2023-04-01 06-32/new=? info=?Thomas Change In Design Site info=?2023-04-01 07-20/new=? info=?Thomas Added New Post info=?2023-04-01 07-33/new=? info=?Thomas Added New Post info=?2023-04-01 07-39/new=? info=?Thomas Added New Post info=?2023-04-01 07-40/new=? info=?Thomas Added New Post info=?2023-04-01 07-40/new=? info=?Thomas Added New Post info=?2023-04-01 07-41/new=? info=?Thomas Added New Post info=?2023-04-01 07-41/new=? info=?Thomas Added New Post info=?2023-04-01 07-41/new=? info=?Thomas Added New Post info=?2023-04-01 07-42/new=? info=?admin Change In Design Site info=?2023-04-01 07-47', 311, 226735, 'http://localhost/php/projects/blog/post.php?name=36213,http://localhost/php/projects/blog/post.php?name=325574');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `name_random`, `name`, `content_one`, `content_two`, `img_box_one`, `img_box_two`, `des`, `visits`, `comment`, `time_add`, `username`, `type`, `bg_img_post`) VALUES
(46, 226735, 'تحميل برنامج اوفيس 2016 مع التفعيل مجاني', '(o_b)تحميل برنامج اوفيس 2016 مع التفعيل مجاني(c_b)/n\r\nl_k[https://www.ahmed-techno.com/2020/10/download-office-2016.html](تحميل)l_k', '          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          \r\n          ', '', '', 'تحميل برنامج اوفيس 2016 مع التفعيل مجاني\r\n', 53, '', '2023-04-01 16:32:21', '15151584', 'برامج', '8451.jpeg'),
(47, 527373, 'طريقة انشاء ايميل علي Epic Games', '(o_b)طريقة عمل اكوانت علي Epic Games(c_b)/n\r\nالطريقة سهل جدا بس اتبع معيا خطوا بخطوت/n\r\n1.هتدخل علي رابط دا بتاع Epic Games (l_k[https://www.epicgames.com/site/ar/home](الرابط مباشر)l_k)/n\r\n2.هتوس تسجيل دخول', '4.هتظهر نافذة هتختار ايميلك منها الي عاوز تعمل بيا/n\r\n5.هتملئ بيناتك . وتوس موافق علي الشروط/n\r\n          ', '1046.jpeg,6412.jpeg', '6303.jpeg,5580.jpeg', 'طريقة عمل اكوانت علي Epic Games\r\n', 5, '/nucm=? info=?9753 info=?Goood info=?2023-04-01 info=?527373', '2023-04-01 17:33:49', '9753', 'برامج', '5391.jpeg');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
