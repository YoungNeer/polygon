-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 12, 2020 at 01:44 PM
-- Server version: 5.7.29-0ubuntu0.16.04.1
-- PHP Version: 7.0.33-0ubuntu0.16.04.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `facebook`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `comment_body` text NOT NULL,
  `posted_by` varchar(60) NOT NULL,
  `posted_to` varchar(60) NOT NULL,
  `date_added` datetime NOT NULL,
  `removed` tinyint(1) NOT NULL DEFAULT '0',
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `comment_body`, `posted_by`, `posted_to`, `date_added`, `removed`, `post_id`) VALUES
(1, 'guys? anyone listening?', 'neer', 'neer', '2020-03-24 21:31:27', 0, 6),
(2, 'that\'s cool', 'youngneer', 'neer', '2020-03-25 11:39:15', 0, 6),
(3, 'it\'s mickey here', 'mickey', 'neer', '2020-03-25 12:36:37', 0, 6),
(4, 'i\'m sorry!\r\njust show me the way\r\nand i will get lost\r\npromise!', 'mickey', 'youngneer', '2020-03-25 13:45:06', 0, 7),
(5, 'don\'t fly in the air\r\nbecause this post\r\nwill ascertain whether\r\nor not the\r\ncomment section is working', 'mickey', 'neer', '2020-03-25 15:29:47', 0, 6),
(6, 'as fine as always', 'neer', 'youngneer', '2020-03-30 13:23:17', 0, 24),
(7, 'i am fine', 'youngneer', 'natasha', '2020-04-18 20:58:59', 0, 26),
(8, 'that\'s cute', 'youngneer', 'neer', '2020-04-26 21:03:25', 0, 25),
(9, 'i know', 'neer', 'youngneer', '2020-04-27 14:56:57', 0, 23);

-- --------------------------------------------------------

--
-- Table structure for table `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(11) NOT NULL,
  `user_to` varchar(60) NOT NULL,
  `user_from` varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `friend_requests`
--

INSERT INTO `friend_requests` (`id`, `user_to`, `user_from`) VALUES
(1, 'neer', 'angel_priya'),
(3, 'neer', 'duke5'),
(4, 'neer', 'milind');

-- --------------------------------------------------------

--
-- Table structure for table `likes`
--

CREATE TABLE `likes` (
  `id` int(11) NOT NULL,
  `username` varchar(60) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `likes`
--

INSERT INTO `likes` (`id`, `username`, `post_id`) VALUES
(11, 'youngneer', 2),
(13, 'youngneer', 17),
(15, 'mickey', 17),
(16, 'mickey', 20),
(17, 'neer', 17),
(20, 'youngneer', 6),
(21, 'neer', 21),
(22, 'youngneer', 22),
(23, 'neer', 24),
(24, 'neer', 25),
(37, 'youngneer', 24),
(48, 'youngneer', 26),
(51, 'youngneer', 25);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `user_to` varchar(60) NOT NULL,
  `user_from` varchar(60) NOT NULL,
  `body` text NOT NULL,
  `date` datetime NOT NULL,
  `opened` tinyint(1) NOT NULL DEFAULT '0',
  `viewed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `user_to`, `user_from`, `body`, `date`, `opened`, `viewed`) VALUES
(1, 'neer', 'youngneer', 'hello neer', '2020-03-30 19:35:49', 1, 1),
(2, 'youngneer', 'neer', 'hey jackass how are you?\r\nlong time no see', '2020-03-30 20:35:25', 1, 1),
(3, 'neer', 'youngneer', 'as if you were online the whole time!', '2020-03-30 20:35:57', 1, 1),
(4, 'neer', 'youngneer', 'and who are u calling jackass!', '2020-03-30 20:36:13', 1, 1),
(5, 'youngneer', 'neer', 'ha ha! i was just teasing you', '2020-03-30 22:20:25', 1, 1),
(6, 'youngneer', 'neer', 'btw i just came from Shimla.\nwas busy there for a while...\ntell me about yourself.', '2020-03-30 22:22:38', 1, 1),
(7, 'neer', 'youngneer', 'just get lost.\r\ni\'m not talking to you', '2020-03-30 22:32:51', 1, 1),
(8, 'neer', 'youngneer', 'you should have atleast told that u were having vacation in Shimla.', '2020-03-30 22:33:45', 1, 1),
(9, 'youngneer', 'neer', 'vacation?\r\nwhen did i say i was having a vacation in Shimla', '2020-03-31 11:08:06', 1, 1),
(10, 'youngneer', 'neer', 'i was there for work', '2020-03-31 11:19:16', 1, 1),
(11, 'youngneer', 'neer', 'are u online?', '2020-04-09 19:41:18', 1, 1),
(12, 'natasha', 'neer', 'how are u natasha', '2020-04-09 19:46:09', 1, 1),
(13, 'natasha', 'neer', 'everything\'s okay?', '2020-04-09 19:47:00', 1, 1),
(14, 'neer', 'youngneer', 'yes i am', '2020-04-18 14:37:19', 1, 1),
(15, 'natasha', 'youngneer', 'i was thinking that ..', '2020-04-18 21:45:42', 1, 1),
(16, 'natasha', 'youngneer', 'how are you?', '2020-04-18 22:11:16', 1, 1),
(17, 'natasha', 'youngneer', 'please reply', '2020-04-19 13:06:41', 1, 1),
(18, 'neer', 'youngneer', 'i am online', '2020-04-19 13:19:31', 1, 1),
(19, 'youngneer', 'neer', '#metoo', '2020-04-19 17:38:45', 1, 1),
(20, 'youngneer', 'mickey', 'how are you?', '2020-04-19 17:46:29', 1, 1),
(21, 'youngneer', 'natasha', 'what is your problem exactly?', '2020-04-19 21:55:36', 1, 1),
(22, 'youngneer', 'duke5', 'you mushrik kafir', '2020-04-19 21:59:42', 0, 1),
(23, 'youngneer', 'angel_priya', 'how are you baby?', '2020-04-19 22:00:32', 0, 1),
(24, 'neer', 'mickey', 'hey there neer', '2020-04-19 22:03:45', 0, 1),
(25, 'neer', 'natasha', 'yup! all\'s fine', '2020-04-19 22:04:59', 0, 1),
(26, 'youngneer', 'bhaijaan', 'it\'s salu bhai here', '2020-04-20 13:20:50', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_to` varchar(60) NOT NULL,
  `user_from` varchar(60) NOT NULL,
  `message` text NOT NULL,
  `post_id` int(11) NOT NULL,
  `notifType` varchar(25) NOT NULL,
  `date` datetime NOT NULL,
  `opened` tinyint(4) NOT NULL DEFAULT '0',
  `viewed` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_to`, `user_from`, `message`, `post_id`, `notifType`, `date`, `opened`, `viewed`) VALUES
(31, 'neer', 'youngneer', 'Jack sparrow commented on your post', 25, 'comment', '2020-04-26 21:03:25', 1, 1),
(33, 'youngneer', 'neer', 'Neer Bishnoi commented on your post', 23, 'comment', '2020-04-27 14:56:57', 0, 1),
(34, 'neer', 'youngneer', 'Jack Sparrow liked your post', 25, 'like', '2020-04-27 15:01:09', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `body` text CHARACTER SET utf8 NOT NULL,
  `added_by` varchar(60) NOT NULL,
  `user_to` varchar(60) NOT NULL,
  `date_added` datetime NOT NULL,
  `user_closed` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `likes` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `body`, `added_by`, `user_to`, `date_added`, `user_closed`, `deleted`, `likes`) VALUES
(2, 'I am Jack Sparrow.\r\nHow are u guys?', 'youngneer', '', '2020-03-23 17:34:59', 0, 0, 1),
(6, 'i am back guys', 'neer', '', '2020-03-23 17:47:46', 0, 0, 1),
(7, 'why don\'t u just get lost', 'youngneer', '', '2020-03-24 13:59:47', 0, 0, 0),
(18, 'i am duke5', 'duke5', '', '2020-03-24 14:15:49', 0, 0, 0),
(19, 'how are u gropy?', 'mickey', '', '2020-03-24 14:16:52', 0, 0, 0),
(20, 'why is no one replying?', 'mickey', '', '2020-03-25 13:42:01', 0, 0, 1),
(22, 'what is going wrong \r\nwith u \r\npeople \r\ni really \r\ndon\'t \r\nunderstand', 'youngneer', '', '2020-03-28 22:00:56', 0, 0, 1),
(23, 'this is my post', 'youngneer', '', '2020-03-29 19:52:32', 0, 0, 0),
(24, 'how are you neer?', 'youngneer', 'neer', '2020-03-29 19:56:41', 0, 0, 2),
(25, 'it\'s really hot today. watching mickey mouse on TV!', 'neer', '', '2020-03-30 13:19:26', 0, 0, 2),
(26, 'how are u guys', 'natasha', '', '2020-04-09 19:40:36', 0, 0, 1),
(86, '<iframe class=\'youtube-card\' width=\'440\' height=\'320\' src=\'https://www.youtube.com/embed/x5K1QLSS5WA\'></iframe>', 'youngneer', '', '2020-05-11 21:54:47', 0, 0, 0),
(99, 'i just bought unity3d from nearest store and <a href=\'http://amazon.co.in\' target=\'new\'>amazon.co.in</a>', 'youngneer', '', '2020-05-12 12:20:54', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `trends`
--

CREATE TABLE `trends` (
  `tname` varchar(50) NOT NULL,
  `hits` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `trends`
--

INSERT INTO `trends` (`tname`, `hits`) VALUES
('Bhajans', 1),
('cPanel', 2),
('Good Morning', 2),
('Hello', 2),
('Kabbadi', 1),
('Store', 6),
('Unity3D', 12),
('Weather', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(32) NOT NULL,
  `lname` varchar(32) NOT NULL,
  `username` varchar(60) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `last_online` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `joined_on` date NOT NULL,
  `pic` varchar(512) NOT NULL,
  `num_posts` int(11) NOT NULL,
  `num_likes` int(11) NOT NULL,
  `ac_verified` tinyint(1) NOT NULL,
  `ac_closed` tinyint(1) NOT NULL,
  `friends` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `username`, `email`, `password`, `last_online`, `joined_on`, `pic`, `num_posts`, `num_likes`, `ac_verified`, `ac_closed`, `friends`) VALUES
(2, 'Jack', 'Sparrow', 'youngneer', 'naweresfdd', '5d41402abc4b2a76b9719d911017c592', '2020-04-17 20:41:56', '2019-12-09', 'uploads/youngneer.jpg', 176, 43, 1, 0, ',neer,natasha,angel_priya,natasha,duke5,bhaijaan,'),
(3, 'Mickey', 'Mouse', 'mickey', 'mickeymouse@disney.com', '5d41402abc4b2a76b9719d911017c592', '2020-04-17 20:41:56', '2019-12-19', 'uploads/mickey.jpg', 2, 6, 1, 0, ',youngneer,neer,'),
(4, 'Osama', 'bin Laden', 'duke5', 'hello@gmail.com', '5d41402abc4b2a76b9719d911017c592', '2020-04-17 20:41:56', '2019-12-19', 'default/3.png', 1, 0, 1, 0, ',youngneer,'),
(5, 'Neer', 'Bishnoi', 'neer', 'netroco@rediffmail.com', '5d41402abc4b2a76b9719d911017c592', '2020-04-17 20:41:56', '2019-12-21', 'uploads/neer.jpg', 14, 4, 1, 0, ',youngneer,natasha,'),
(6, 'Natasha', 'Milan', 'natasha', 'fakemail@gmail.com', '4e9c612f638bb88514ef8327b6bc02af', '2020-04-17 20:41:56', '2020-03-30', 'uploads/natasha.jpg', 1, 0, 1, 0, ',neer,'),
(7, 'Angel', 'Priya', 'angel_priya', 'angel_priya@gmail.com', '4e9c612f638bb88514ef8327b6bc02af', '2020-04-17 20:41:56', '2020-03-30', 'uploads/angel_priya.jpg', 0, 0, 1, 0, ',youngneer,'),
(8, 'Milind', 'Soman', 'milind', 'milind@gmail.com', '4e9c612f638bb88514ef8327b6bc02af', '2020-04-17 20:41:56', '2020-03-30', 'uploads/milind.jpg', 0, 0, 1, 0, ','),
(10, 'Salman', 'Khan', 'bhaijaan', 'bhaijaan@beinghuman.com', '88f8116ee6b770745e2f60fc59fdc468', '2020-04-20 12:56:58', '2020-04-20', 'uploads/bhaijaan.jpg', 0, 0, 1, 0, ',youngneer,'),
(11, 'Neelam', 'Pandey', 'neelam', 'neelam@gmail.com', '4e9c612f638bb88514ef8327b6bc02af', '2020-04-29 22:41:31', '2020-04-29', 'uploads/neelam.jpg', 0, 0, 1, 0, ','),
(12, 'Sia', 'Gupta', 'sia23', 'sia@gmail.com', '4e9c612f638bb88514ef8327b6bc02af', '2020-05-11 13:19:10', '2020-05-11', 'uploads/sia23.jpg', 0, 0, 1, 0, ','),
(13, 'Indra', 'Ranaut', 'indra', 'indra@gmail.com', '4e9c612f638bb88514ef8327b6bc02af', '2020-05-11 13:54:07', '2020-05-11', 'uploads/indra.jpg', 0, 0, 1, 0, ',');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friend_requests`
--
ALTER TABLE `friend_requests`
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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trends`
--
ALTER TABLE `trends`
  ADD UNIQUE KEY `tname` (`tname`);

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
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `likes`
--
ALTER TABLE `likes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
