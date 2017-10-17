-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 17, 2017 at 03:12 AM
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
  `purpose` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Channel`
--

INSERT INTO `Channel` (`channel_id`, `name`, `purpose`, `type`, `creator_id`) VALUES
(1, 'General', 'Generic messages', 'PUBLIC', 1),
(2, 'Random', 'Random messages', 'PUBLIC', 1);

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
  `content` varchar(500) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Post`
--

INSERT INTO `Post` (`post_id`, `user_id`, `fname`, `lname`, `channel_id`, `parent_id`, `datetime`, `content`) VALUES
(1, 1, 'Tom', 'Mater', 1, -1, '2017-09-25 21:25:01', 'hello db'),
(3, 2, 'Sally', 'Carrera', 1, 1, '2017-09-25 21:30:05', 'reply to hello db'),
(6, 1, 'Tow', 'Mater', 2, -1, '2017-10-06 16:02:02', 'FIrst client side post at random'),
(7, 1, 'Tow', 'Mater', 2, -1, '2017-10-06 16:02:57', 'FIrst client side post at random'),
(8, 1, 'Tow', 'Mater', 1, -1, '2017-10-16 03:56:44', 'new message'),
(9, 1, 'Tow', 'Mater', 1, -1, '2017-10-16 03:57:25', 'new message'),
(10, 1, 'Tow', 'Mater', 1, -1, '2017-10-16 03:57:31', 'news msdfjadslkfjdaskfj'),
(11, 2, 'Sally', 'Carrera', 1, -1, '2017-10-16 03:58:28', 'sally');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `User`
--

INSERT INTO `User` (`user_id`, `fname`, `lname`, `email`, `password`) VALUES
(1, 'Tow', 'Mater', 'mater@rsprings.gov', '@mater'),
(2, 'Sally', 'Carrera', 'porsche@rsprings.gov', '@sally'),
(3, 'Doc', 'Hudson', 'hornet@rsprings.gov', '@doc'),
(4, 'Finn', 'McMissile', 'topsecret@agent.org', '@mcmissile'),
(5, 'Lightning', 'McQueen', 'kachow@rusteze.com', '@mcqueen'),
(6, 'Chick', 'Hicks', 'chinga@cars.com', '@chick');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Channel`
--
ALTER TABLE `Channel`
 ADD PRIMARY KEY (`channel_id`);

--
-- Indexes for table `Post`
--
ALTER TABLE `Post`
 ADD PRIMARY KEY (`post_id`);

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
MODIFY `channel_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Post`
--
ALTER TABLE `Post`
MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
