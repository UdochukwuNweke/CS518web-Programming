-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 14, 2017 at 05:14 AM
-- Server version: 5.7.19-0ubuntu0.16.04.1
-- PHP Version: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `CS518DB`
--

CREATE DATABASE `CS518DB`;
USE `CS518DB`;

-- --------------------------------------------------------

--
-- Table structure for table `Channel`
--

CREATE TABLE `Channel` (
  `channel_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) NOT NULL,
  `purpose` varchar(500) NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Post`
--

CREATE TABLE `Post` (
  `post_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `channel_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `content` varchar(500) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Post`
--

INSERT INTO `Post` (`post_id`, `datetime`, `channel_id`, `parent_id`, `content`, `fname`, `lname`, `user_id`) VALUES
(29, '2017-09-29 15:37:32', 5, 5, 'it is right. I have to be sure and convinced.', 'Tow', 'Mater', 1),
(31, '2017-10-01 22:43:10', 5, 5, 'it is right. I have to be sure and convinced.', 'Tow', 'Mater', 1),
(54, '2017-10-02 17:20:17', 6, 6, 'it is right.', 'Tow', 'Mater', 1),
(55, '2017-10-03 02:02:15', 6, 6, 'it is right.', 'Tow', 'Mater', 2),
(58, '2017-10-14 04:42:11', 5, 5, 'it is right.', 'Tow', 'Mater', 4);

-- --------------------------------------------------------

--
-- Table structure for table `Reaction`
--

CREATE TABLE `Reaction` (
  `reaction_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reaction_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Reaction_Type`
--

CREATE TABLE `Reaction_Type` (
  `reaction_type_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `emoji` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `fname`, `lname`, `email`, `password`) VALUES
(1, 'Tow', 'Mater', 'mater@rsprings.gov', '@mater'),
(3, 'Sally', 'Carrera', 'porsche@rspring.gov', '@sally'),
(4, 'Doc', 'Hudson', 'hornet@rspring.gov', '@doc'),
(5, 'Finn', 'McMissile', 'topsecret@agent.org', '@mcmissile');

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
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Channel`
--
ALTER TABLE `Channel`
  MODIFY `channel_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Post`
--
ALTER TABLE `Post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;
--
-- AUTO_INCREMENT for table `Reaction`
--
ALTER TABLE `Reaction`
  MODIFY `reaction_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Reaction_Type`
--
ALTER TABLE `Reaction_Type`
  MODIFY `reaction_type_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
