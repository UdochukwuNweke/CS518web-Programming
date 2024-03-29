-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 30, 2017 at 01:53 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `CS518DB`
--
CREATE DATABASE `CS518DB`;
USE `CS518DB`;

-- --------------------------------------------------------

--
-- Table structure for table `Channel`
--

CREATE TABLE IF NOT EXISTS `Channel` (
`channel_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `purpose` varchar(140) NOT NULL,
  `type` varchar(50) NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Channel`
--

INSERT INTO `Channel` (`channel_id`, `name`, `purpose`, `type`, `creator_id`) VALUES
(1, 'general', 'Generic messages', 'PUBLIC', 1),
(2, 'random', 'Random messages', 'PUBLIC', 1),
(3, 'jokes', 'For fun jokes', 'PUBLIC', 1),
(4, 'secrets', 'For sharing secrets', 'PRIVATE', 7),
(5, 'music', 'for sharing your favorite music', 'PUBLIC', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Channel_Membership`
--

CREATE TABLE IF NOT EXISTS `Channel_Membership` (
`channel_membership_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Channel_Membership`
--

INSERT INTO `Channel_Membership` (`channel_membership_id`, `channel_id`, `user_id`) VALUES
(17, 1, 1),
(18, 1, 2),
(19, 1, 3),
(20, 1, 4),
(21, 1, 5),
(22, 1, 6),
(23, 1, 7),
(24, 1, 8),
(25, 2, 1),
(26, 2, 2),
(27, 2, 3),
(28, 2, 4),
(29, 2, 5),
(30, 2, 6),
(31, 2, 7),
(32, 2, 8),
(33, 3, 1),
(34, 4, 7),
(35, 5, 1),
(36, 4, 8);

-- --------------------------------------------------------

--
-- Table structure for table `Post`
--

CREATE TABLE IF NOT EXISTS `Post` (
`post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Post`
--

INSERT INTO `Post` (`post_id`, `user_id`, `fname`, `lname`, `channel_id`, `parent_id`, `datetime`, `content`) VALUES
(3, 2, 'Sally', 'Carrera', 1, 1, '2017-09-25 21:30:05', 'reply to hello db'),
(11, 2, 'Sally', 'Carrera', 1, -1, '2017-10-16 03:58:28', 'sally'),
(28, 1, 'Tow', 'Mater', 2, -1, '2017-10-22 17:32:58', 'Rand'),
(29, 1, 'Tow', 'Mater', 2, -1, '2017-10-22 17:35:01', 'Rand'),
(30, 1, 'Tow', 'Mater', 2, -1, '2017-10-22 17:35:20', 'Rand'),
(31, 1, 'Tow', 'Mater', 2, -1, '2017-10-22 17:35:48', 'Rand'),
(32, 1, 'Tow', 'Mater', 2, -1, '2017-10-22 17:35:59', 'Rand'),
(33, 1, 'Tow', 'Mater', 2, -1, '2017-10-22 17:36:16', 'Rand'),
(34, 1, 'Tow', 'Mater', 2, -1, '2017-10-22 17:36:34', 'Rand'),
(35, 1, 'Tow', 'Mater', 2, -1, '2017-10-22 17:36:39', 'Rand'),
(40, 1, 'Tow', 'Mater', 1, -1, '2017-10-22 17:47:54', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut perspiciatis unde omnis iste natus error sit vo'),
(41, 1, 'Tow', 'Mater', 2, -1, '2017-10-22 17:48:39', 'mcqueen is my best friend'),
(42, 5, 'Lightning', 'McQueen', 1, -1, '2017-10-22 17:50:29', 'i hope i win another piston cup!'),
(43, 5, 'Lightning', 'McQueen', 1, -1, '2017-10-22 17:50:45', '1'),
(44, 5, 'Lightning', 'McQueen', 1, -1, '2017-10-22 17:50:48', '2'),
(45, 5, 'Lightning', 'McQueen', 1, -1, '2017-10-22 17:50:51', '3'),
(73, 1, 'Tow', 'Mater', 1, 70, '2017-10-22 20:17:43', 'replying abc 70'),
(74, 1, 'Tow', 'Mater', 1, 73, '2017-10-22 20:18:56', 'Enter reply'),
(75, 4, 'Finn', 'McMissile', 1, 11, '2017-10-22 20:20:37', 'missile first reply to sally'),
(76, 1, 'Tow', 'Mater', 1, 45, '2017-10-22 23:30:01', 'Tow reply mcqueen 45'),
(77, 1, 'Tow', 'Mater', 2, 28, '2017-10-22 23:30:38', 'tow reply tow 28'),
(78, 1, 'Tow', 'Mater', 2, 28, '2017-10-22 23:31:13', 'Enter reply'),
(79, 1, 'Tow', 'Mater', 2, 28, '2017-10-22 23:31:16', 'Enter reply'),
(80, 1, 'Tow', 'Mater', 2, 28, '2017-10-22 23:31:19', 'Enter reply'),
(81, 1, 'Tow', 'Mater', 1, 70, '2017-10-22 23:36:08', 'another tow-tow rep'),
(82, 1, 'Tow', 'Mater', 1, 81, '2017-10-22 23:36:32', 'tow-tow-tow-rep'),
(88, 3, 'Doc', 'Hudson', 1, 11, '2017-10-23 22:45:53', 'Doc reply sally again'),
(89, 3, 'Doc', 'Hudson', 1, 70, '2017-10-23 22:57:08', 'doc rep two 70'),
(90, 7, 'Udo', 'Nweke', 1, -1, '2017-10-27 00:55:02', 'Hello world'),
(91, 3, 'Doc', 'Hudson', 1, 90, '2017-10-27 01:03:59', 'Hello swift, i like your album'),
(92, 8, 'John', 'Snow', 1, -1, '2017-10-27 01:26:19', 'Winter is coming'),
(93, 1, 'Tow', 'Mater', 1, 92, '2017-10-27 01:30:42', 'John nice image'),
(94, 9, 'Jack', 'Hightower', 1, 92, '2017-10-27 20:31:12', 'Hello John snow this is jack'),
(96, 8, 'John', 'Snow', 4, -1, '2017-10-29 20:42:55', 'Hello secrets first message');

-- --------------------------------------------------------

--
-- Table structure for table `Reaction`
--

CREATE TABLE IF NOT EXISTS `Reaction` (
`reaction_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `reaction_type_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Reaction`
--

INSERT INTO `Reaction` (`reaction_id`, `post_id`, `user_id`, `fname`, `lname`, `reaction_type_id`) VALUES
(25, 11, 3, 'Doc', 'Hudson', 1),
(28, 11, 1, 'Tow', 'Mater', 1),
(29, 70, 3, 'Doc', 'Hudson', 1),
(30, 11, 2, 'Sally', 'Carrera', 1),
(33, 88, 2, 'Sally', 'Carrera', 1),
(34, 77, 2, 'Sally', 'Carrera', 1),
(37, 45, 1, 'Tow', 'Mater', 2),
(38, 92, 1, 'Tow', 'Mater', 1),
(40, 92, 9, 'Jack', 'Hightower', 2),
(41, 96, 7, 'Udo', 'Nweke', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Reaction_Type`
--

CREATE TABLE IF NOT EXISTS `Reaction_Type` (
`reaction_type_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `emoji` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Reaction_Type`
--

INSERT INTO `Reaction_Type` (`reaction_type_id`, `name`, `emoji`) VALUES
(1, 'thumbs_up', '&#128077;'),
(2, 'thumbs_down', '&#128078;');

-- --------------------------------------------------------

--
-- Table structure for table `User`
--

CREATE TABLE IF NOT EXISTS `User` (
`user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`user_id`, `fname`, `lname`, `email`, `password`) VALUES
(1, 'Tow', 'Mater', 'mater@rsprings.gov', '@mater'),
(2, 'Sally', 'Carrera', 'porsche@rsprings.gov', '@sally'),
(3, 'Doc', 'Hudson', 'hornet@rsprings.gov', '@doc'),
(4, 'Finn', 'McMissile', 'topsecret@agent.org', '@mcmissile'),
(5, 'Lightning', 'McQueen', 'kachow@rusteze.com', '@mcqueen'),
(6, 'Chick', 'Hicks', 'chinga@cars.com', '@chick'),
(7, 'Udo', 'Nweke', 'unweke@cs.odu.edu', '@nweke'),
(8, 'John', 'Snow', 'winter@stark.com', '@snow');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Channel`
--
ALTER TABLE `Channel`
 ADD PRIMARY KEY (`channel_id`);

--
-- Indexes for table `Channel_Membership`
--
ALTER TABLE `Channel_Membership`
 ADD PRIMARY KEY (`channel_membership_id`);

--
-- Indexes for table `Post`
--
ALTER TABLE `Post`
 ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `Reaction`
--
ALTER TABLE `Reaction`
 ADD PRIMARY KEY (`reaction_id`);

--
-- Indexes for table `Reaction_Type`
--
ALTER TABLE `Reaction_Type`
 ADD PRIMARY KEY (`reaction_type_id`);

--
-- Indexes for table `User`
--
ALTER TABLE `User`
 ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Channel`
--
ALTER TABLE `Channel`
MODIFY `channel_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `Channel_Membership`
--
ALTER TABLE `Channel_Membership`
MODIFY `channel_membership_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `Post`
--
ALTER TABLE `Post`
MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=97;
--
-- AUTO_INCREMENT for table `Reaction`
--
ALTER TABLE `Reaction`
MODIFY `reaction_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `Reaction_Type`
--
ALTER TABLE `Reaction_Type`
MODIFY `reaction_type_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
