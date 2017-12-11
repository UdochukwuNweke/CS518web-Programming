-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 11, 2017 at 07:42 PM
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
CREATE DATABASE IF NOT EXISTS `CS518DB`;
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
  `state` varchar(50) NOT NULL,
  `creator_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Channel`
--

INSERT INTO `Channel` (`channel_id`, `name`, `purpose`, `type`, `state`, `creator_id`) VALUES
(1, 'general', 'Generic messages', 'PUBLIC', 'ACTIVE', 1),
(2, 'random', 'Random messages', 'PUBLIC', 'ACTIVE', 1),
(3, 'jokes', 'For fun jokes', 'PUBLIC', 'ARCHIVE', 1),
(4, 'secrets', 'For sharing secrets', 'PRIVATE', 'ACTIVE', 7),
(5, 'music', 'for sharing your favorite music', 'PUBLIC', 'ACTIVE', 1);

-- --------------------------------------------------------

--
-- Table structure for table `Channel_Membership`
--

CREATE TABLE IF NOT EXISTS `Channel_Membership` (
`channel_membership_id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=utf8;

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
(24, 1, 8),
(25, 2, 1),
(26, 2, 2),
(27, 2, 3),
(28, 2, 4),
(29, 2, 5),
(30, 2, 6),
(32, 2, 8),
(33, 3, 1),
(36, 4, 8),
(38, 5, 8),
(39, 1, 11),
(40, 2, 11),
(41, 3, 11),
(46, 1, 14),
(47, 2, 14),
(48, 1, 15),
(50, 1, 16),
(51, 2, 16),
(52, 1, 17),
(53, 2, 17),
(54, 1, 18),
(55, 2, 18),
(58, 3, 14),
(68, 1, 7),
(69, 2, 7),
(70, 3, 7),
(71, 3, 17),
(73, 2, 15),
(74, 3, 15),
(75, 4, 15),
(76, -1, 17),
(77, -1, 1);

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
  `pair_user_id` varchar(50) NOT NULL,
  `datetime` datetime NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=388 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Post`
--

INSERT INTO `Post` (`post_id`, `user_id`, `fname`, `lname`, `channel_id`, `parent_id`, `pair_user_id`, `datetime`, `content`) VALUES
(3, 2, 'Sally', 'Carrera', 1, 1, '', '2017-09-25 21:30:05', 'reply to hello db'),
(11, 2, 'Sally', 'Carrera', 1, -1, '', '2017-10-16 03:58:28', 'sally'),
(28, 1, 'Tow', 'Mater', 2, -1, '', '2017-10-22 17:32:58', 'Rand'),
(29, 1, 'Tow', 'Mater', 2, -1, '', '2017-10-22 17:35:01', 'Rand'),
(30, 1, 'Tow', 'Mater', 2, -1, '', '2017-10-22 17:35:20', 'Rand'),
(31, 1, 'Tow', 'Mater', 2, -1, '', '2017-10-22 17:35:48', 'Rand'),
(32, 1, 'Tow', 'Mater', 2, -1, '', '2017-10-22 17:35:59', 'Rand'),
(33, 1, 'Tow', 'Mater', 2, -1, '', '2017-10-22 17:36:16', 'Rand'),
(34, 1, 'Tow', 'Mater', 2, -1, '', '2017-10-22 17:36:34', 'Rand'),
(35, 1, 'Tow', 'Mater', 2, -1, '', '2017-10-22 17:36:39', 'Rand'),
(41, 1, 'Tow', 'Mater', 2, -1, '', '2017-10-22 17:48:39', 'mcqueen is my best friend'),
(42, 5, 'Lightning', 'McQueen', 1, -1, '', '2017-10-22 17:50:29', 'i hope i win another piston cup!'),
(43, 5, 'Lightning', 'McQueen', 1, -1, '', '2017-10-22 17:50:45', '1'),
(44, 5, 'Lightning', 'McQueen', 1, -1, '', '2017-10-22 17:50:48', '2'),
(45, 5, 'Lightning', 'McQueen', 1, -1, '', '2017-10-22 17:50:51', '3'),
(73, 1, 'Tow', 'Mater', 1, 70, '', '2017-10-22 20:17:43', 'replying abc 70'),
(74, 1, 'Tow', 'Mater', 1, 73, '', '2017-10-22 20:18:56', 'Enter reply'),
(75, 4, 'Finn', 'McMissile', 1, 11, '', '2017-10-22 20:20:37', 'missile first reply to sally'),
(76, 1, 'Tow', 'Mater', 1, 45, '', '2017-10-22 23:30:01', 'Tow reply mcqueen 45'),
(77, 1, 'Tow', 'Mater', 2, 28, '', '2017-10-22 23:30:38', 'tow reply tow 28'),
(78, 1, 'Tow', 'Mater', 2, 28, '', '2017-10-22 23:31:13', 'Enter reply'),
(79, 1, 'Tow', 'Mater', 2, 28, '', '2017-10-22 23:31:16', 'Enter reply'),
(80, 1, 'Tow', 'Mater', 2, 28, '', '2017-10-22 23:31:19', 'Enter reply'),
(81, 1, 'Tow', 'Mater', 1, 70, '', '2017-10-22 23:36:08', 'another tow-tow rep'),
(82, 1, 'Tow', 'Mater', 1, 81, '', '2017-10-22 23:36:32', 'tow-tow-tow-rep'),
(88, 3, 'Doc', 'Hudson', 1, 11, '', '2017-10-23 22:45:53', 'Doc reply sally again'),
(89, 3, 'Doc', 'Hudson', 1, 70, '', '2017-10-23 22:57:08', 'doc rep two 70'),
(90, 7, 'Udo', 'Nweke', 1, -1, '', '2017-10-27 00:55:02', 'Hello world'),
(91, 3, 'Doc', 'Hudson', 1, 90, '', '2017-10-27 01:03:59', 'Hello swift, i like your album'),
(92, 8, 'John', 'Snow', 1, -1, '', '2017-10-27 01:26:19', 'Winter is coming'),
(93, 1, 'Tow', 'Mater', 1, 92, '', '2017-10-27 01:30:42', 'John nice image'),
(94, 9, 'Portia', 'Hightower', 1, 92, '', '2017-10-27 20:31:12', 'Hello John snow this is portia'),
(96, 8, 'John', 'Snow', 4, -1, '', '2017-10-29 20:42:55', 'Hello secrets first message'),
(100, 7, 'Udo', 'Nweke', 4, -1, '', '2017-10-29 21:24:18', 'adfjj'),
(103, 7, 'Udo', 'Nweke', 3, -1, '', '2017-10-29 21:36:57', 'Now a member of jokes due to post'),
(104, 8, 'John', 'Snow', 5, -1, '', '2017-10-29 21:37:33', 'Now a member of music due to post'),
(105, 1, 'Tow', 'Mater', 2, 28, '', '2017-10-30 21:19:12', 'testing focus'),
(106, 1, 'Tow', 'Mater', 1, 40, '', '2017-11-12 23:48:07', 'helo myself'),
(107, 7, 'Udo', 'Nweke', 4, -1, '', '2017-11-15 13:27:35', 'testing after kicked out of secrets'),
(108, 7, 'Udo', 'Nweke', 1, -1, '', '2017-11-15 14:14:10', 'now memb'),
(109, 17, 'ADMINISTRATOR', '', 1, 11, '', '2017-11-15 14:27:28', 'Hello sally'),
(117, 17, 'ADMINISTRATOR', '', 3, 103, '', '2017-11-15 15:19:54', 'Hello'),
(118, 7, 'Udo', 'Nweke', 3, 103, '', '2017-11-15 17:58:32', 'HLOO'),
(128, 17, 'ADMINISTRATOR', '', 1, 92, '', '2017-11-15 18:36:22', 'Testeirnasdflaskjfladjflkajdfljdafs'),
(129, 17, 'ADMINISTRATOR', '', 1, 93, '', '2017-11-15 18:36:47', 'Tow \r\nVery very       nice\r\n     imagea'),
(135, 17, 'ADMINISTRATOR', '', 2, -1, '', '2017-11-15 19:20:20', '&lt;script&gt; alert(''hello''); &lt;/script&gt;'),
(137, 7, 'Udo', 'Nweke', 2, 135, '', '2017-11-15 19:35:26', 'Hello Testing witou pre'),
(139, 17, 'ADMINISTRATOR', '', 2, 135, '', '2017-11-15 19:40:03', 'terst pres'),
(144, 17, 'ADMINISTRATOR', '', 2, 135, '', '2017-11-15 19:48:38', 'without prev'),
(145, 17, 'ADMINISTRATOR', '', 2, 135, '', '2017-11-15 19:48:48', '<pre>with prev</pre>'),
(146, 7, 'Udo', 'Nweke', 2, 135, '', '2017-11-15 19:49:13', 'here is it without'),
(147, 7, 'Udo', 'Nweke', 2, 135, '', '2017-11-15 19:49:50', '<pre>here is the code: \r\n&lt;!--\r\n&lt;!DOCTYPE html&gt;\r\n&lt;html&gt;\r\n&lt;body&gt;\r\n\r\n&lt;h2&gt;My First JavaScript&lt;/h2&gt;\r\n\r\n&lt;button type=&quot;button&quot;\r\nonclick=&quot;document.getElementById(''demo'').innerHTML = Date()&quot;&gt;\r\nClick me to display Date and Time.&lt;/button&gt;\r\n\r\n&lt;p id=&quot;demo&quot;&gt;&lt;/p&gt;\r\n\r\n&lt;/body&gt;\r\n&lt;/html&gt;</pre>'),
(150, 17, 'ADMINISTRATOR', '', 2, 137, '', '2017-11-15 19:51:16', '<pre>Text in a pre element\r\nis displayed in a fixed-width\r\nfont, and it preserves\r\nboth      spaces and\r\nline breaks</pre>'),
(151, 17, 'ADMINISTRATOR', '', 1, 108, '', '2017-11-15 20:03:04', '&lt;pre&gt;\r\n               test\r\n&lt;/pre&gt;'),
(152, 17, 'ADMINISTRATOR', '', 1, 108, '', '2017-11-17 09:29:05', 'test after enctype=&quot;multipart/form-data'),
(153, 17, 'ADMINISTRATOR', '', 1, -1, '', '2017-11-17 10:06:36', 'kadf\r\nlakdjf\r\nlakdjf'),
(156, 17, 'ADMINISTRATOR', '', 1, 108, '', '2017-11-17 10:57:03', 'test ordinary'),
(157, 17, 'ADMINISTRATOR', '', 1, -1, '', '2017-11-17 10:57:13', 'test ordinary'),
(167, 7, 'Udo', 'Nweke', 1, 153, '', '2017-11-17 15:35:30', 'adsf'),
(184, 17, 'ADMINISTRATOR', '', 1, 183, '', '2017-11-17 16:03:20', '<div><br><img src="./postImgs/em9Pz0YshQ.jpg" alt="postImg" class="postImg"></div>'),
(230, 17, 'ADMINISTRATOR', '', 1, -1, '', '2017-11-18 18:55:27', 'first msg after pagination'),
(231, 17, 'ADMINISTRATOR', '', 1, -1, '', '2017-11-18 19:14:05', 'second message after pagination'),
(232, 17, 'ADMINISTRATOR', '', 1, -1, '', '2017-11-18 19:14:20', 'third msg after pagination'),
(233, 17, 'ADMINISTRATOR', '', 1, -1, '', '2017-11-18 19:14:40', 'fourth msg after pagination<div><br><img src="./postImgs/zNUiJOflqa.jpg" alt="postImg" class="postImg"></div>'),
(234, 17, 'ADMINISTRATOR', '', 1, -1, '', '2017-11-18 19:14:52', 'fifth message after pagination'),
(235, 1, 'Tow', 'Mater', 1, -1, '', '2017-11-18 23:58:00', 'first message after ajax'),
(275, 17, 'ADMINISTRATOR', '', 1, -1, '', '2017-12-07 00:47:05', 'adsfadf'),
(276, 17, 'ADMINISTRATOR', '', 1, -1, '', '2017-12-07 00:47:08', 'adfasdfasdf'),
(277, 17, 'ADMINISTRATOR', '', 1, -1, '', '2017-12-07 00:47:36', 'adf'),
(355, 17, 'ADMINISTRATOR', '', 1, -1, '', '2017-12-09 18:13:24', 't0'),
(356, 17, 'ADMINISTRATOR', '', 1, 355, '', '2017-12-09 18:13:30', 't1'),
(365, 17, 'ADMINISTRATOR', '', 1, -1, '', '2017-12-09 18:37:12', 'gen'),
(366, 17, 'ADMINISTRATOR', '', 1, 365, '', '2017-12-09 18:38:34', 'rep to gen'),
(372, 17, 'ADMINISTRATOR', '', -1, -1, '1.17', '2017-12-09 19:08:50', 'f0'),
(373, 17, 'ADMINISTRATOR', '', -1, -1, '1.17', '2017-12-09 19:08:58', 'f1'),
(374, 1, 'Tow', 'Mater', -1, -1, '1.17', '2017-12-09 19:09:23', 'g0'),
(375, 17, 'ADMINISTRATOR', '', -1, 374, '1.17', '2017-12-09 19:10:35', 'f3'),
(377, 17, 'ADMINISTRATOR', '', -1, -1, '1.17', '2017-12-09 19:23:26', 'f4'),
(378, 17, 'ADMINISTRATOR', '', -1, -1, '1.17', '2017-12-09 19:25:01', 'f5'),
(379, 1, 'Tow', 'Mater', -1, 378, '1.17', '2017-12-09 19:25:19', 'f6'),
(380, 17, 'ADMINISTRATOR', '', 1, -1, '', '2017-12-09 19:29:22', 'hello'),
(381, 1, 'Tow', 'Mater', 1, 380, '', '2017-12-09 19:29:32', 'repp hello');

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
) ENGINE=InnoDB AUTO_INCREMENT=197 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Reaction`
--

INSERT INTO `Reaction` (`reaction_id`, `post_id`, `user_id`, `fname`, `lname`, `reaction_type_id`) VALUES
(25, 11, 3, 'Doc', 'Hudson', 1),
(29, 70, 3, 'Doc', 'Hudson', 1),
(30, 11, 2, 'Sally', 'Carrera', 1),
(33, 88, 2, 'Sally', 'Carrera', 1),
(34, 77, 2, 'Sally', 'Carrera', 1),
(37, 45, 1, 'Tow', 'Mater', 2),
(40, 92, 9, 'Portia', 'Hightower', 2),
(41, 96, 7, 'Udo', 'Nweke', 1),
(44, 104, 7, 'Udo', 'Nweke', 1),
(71, 103, 11, 'Idris', 'Elba', 2),
(73, 11, 11, 'Idris', 'Elba', 1),
(74, 40, 11, 'Idris', 'Elba', 1),
(86, 92, 11, 'Idris', 'Elba', 1),
(119, 93, 1, 'Tow', 'Mater', 0),
(177, 92, 1, 'Tow', 'Mater', 1),
(178, 11, 1, 'Tow', 'Mater', 2),
(180, 103, 17, 'ADMINISTRATOR', '', 2),
(184, 40, 17, 'ADMINISTRATOR', '', 1),
(186, 131, 7, 'Udo', 'Nweke', 2),
(187, 131, 17, 'ADMINISTRATOR', '', 2),
(189, 135, 7, 'Udo', 'Nweke', 2),
(192, 152, 17, 'ADMINISTRATOR', '', 1),
(193, 135, 17, 'ADMINISTRATOR', '', 2),
(195, 235, 1, 'Tow', 'Mater', 2),
(196, 235, 17, 'ADMINISTRATOR', '', 1);

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
-- Table structure for table `Role`
--

CREATE TABLE IF NOT EXISTS `Role` (
`role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_type` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Role`
--

INSERT INTO `Role` (`role_id`, `user_id`, `role_type`) VALUES
(2, 16, 'DEFAULT'),
(3, 1, 'DEFAULT'),
(4, 2, 'DEFAULT'),
(5, 3, 'DEFAULT'),
(6, 4, 'DEFAULT'),
(7, 5, 'DEFAULT'),
(8, 6, 'DEFAULT'),
(9, 7, 'DEFAULT'),
(10, 8, 'DEFAULT'),
(11, 11, 'DEFAULT'),
(12, 14, 'DEFAULT'),
(13, 15, 'DEFAULT'),
(14, 16, 'DEFAULT'),
(15, 17, 'ADMIN'),
(16, 18, 'DEFAULT');

-- --------------------------------------------------------

--
-- Table structure for table `Settings`
--

CREATE TABLE IF NOT EXISTS `Settings` (
  `settings_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `two_factor_active` tinyint(1) NOT NULL,
  `two_factor_challenge` varchar(50) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8;

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
(7, 'Udo', 'Nweke', 'preciousudochi@gmail.com', '@nweke'),
(8, 'John', 'Snow', 'winter@stark.com', '@snow'),
(11, 'Idris', 'Elba', 'idris@elba.com', '@elba'),
(14, 'Peter', 'John', 'peter@john.com', '@john'),
(15, 'Barack', 'Obama', 'barack@obama.com', '@obama'),
(16, 'Newbie', 'newbie', 'newbie@newbie.com', 'newbie'),
(17, 'ADMINISTRATOR', '', 'admin@slack.com', '@admin'),
(18, 'newbie2', 'newbie2', 'newbie2@newbie.com', 'pass');

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
-- Indexes for table `Role`
--
ALTER TABLE `Role`
 ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `Settings`
--
ALTER TABLE `Settings`
 ADD PRIMARY KEY (`settings_id`);

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
MODIFY `channel_membership_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=78;
--
-- AUTO_INCREMENT for table `Post`
--
ALTER TABLE `Post`
MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=388;
--
-- AUTO_INCREMENT for table `Reaction`
--
ALTER TABLE `Reaction`
MODIFY `reaction_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=197;
--
-- AUTO_INCREMENT for table `Reaction_Type`
--
ALTER TABLE `Reaction_Type`
MODIFY `reaction_type_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `Role`
--
ALTER TABLE `Role`
MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `User`
--
ALTER TABLE `User`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
