-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 01, 2021 at 05:06 AM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zcrmgcalendar`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendees`
--

CREATE TABLE `attendees` (
  `id` int(11) NOT NULL,
  `eventid` int(11) NOT NULL,
  `attendee` varchar(75) NOT NULL,
  `responsestatus` varchar(50) NOT NULL,
  `ztaskid` bigint(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `attendees`
--

INSERT INTO `attendees` (`id`, `eventid`, `attendee`, `responsestatus`, `ztaskid`) VALUES
(32, 16, 'appadurai@bizappln.com', 'accepted', 1600500000001428043),
(33, 16, 'appadurai@yaalidatrixproj.com', 'accepted', 1600500000001429019),
(34, 16, 'appadurai@gmail.com', 'needsAction', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `calendarconfig`
--

CREATE TABLE `calendarconfig` (
  `calendarid` int(11) NOT NULL,
  `gcalid` varchar(50) NOT NULL,
  `nextsynctoken` varchar(50) NOT NULL,
  `tokenexpiry` bigint(20) NOT NULL,
  `userid` int(11) NOT NULL,
  `channelid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `calendarconfig`
--

INSERT INTO `calendarconfig` (`calendarid`, `gcalid`, `nextsynctoken`, `tokenexpiry`, `userid`, `channelid`) VALUES
(6, 'primary', 'CPDjy_WdtPACEPDjy_WdtPACGAUg6IvmsQE=', 1621820637558, 0, '4ede92f10b83c7da5fbc');

-- --------------------------------------------------------

--
-- Table structure for table `calendaruser`
--

CREATE TABLE `calendaruser` (
  `userid` int(11) NOT NULL,
  `guserid` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `calendaruser`
--

INSERT INTO `calendaruser` (`userid`, `guserid`, `email`, `status`) VALUES
(0, 'C00p350n8', 'appadurai@yaalidatrixproj.com', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `gevent`
--

CREATE TABLE `gevent` (
  `eventid` int(11) NOT NULL,
  `calendarid` int(11) NOT NULL,
  `geventid` varchar(50) NOT NULL,
  `starttime` bigint(20) NOT NULL,
  `endtime` bigint(20) NOT NULL,
  `created` bigint(20) NOT NULL,
  `updated` bigint(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `title` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `gevent`
--

INSERT INTO `gevent` (`eventid`, `calendarid`, `geventid`, `starttime`, `endtime`, `created`, `updated`, `status`, `description`, `title`) VALUES
(16, 6, '7j95ls5oo4cb4cj1hbsiuk2ut4', 1620271800, 1620275400, 1620197958, 1621304353, 'confirmed', 'Testing resource id notification an other parameters', 'Testing ResourceId NOtification');

-- --------------------------------------------------------

--
-- Table structure for table `ruleconfig`
--

CREATE TABLE `ruleconfig` (
  `ruleid` int(11) NOT NULL,
  `rulename` varchar(50) NOT NULL,
  `zprojectid` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL,
  `criteria` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`criteria`)),
  `priority` int(5) NOT NULL,
  `emails` varchar(1000) NOT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ruleconfig`
--

INSERT INTO `ruleconfig` (`ruleid`, `rulename`, `zprojectid`, `description`, `criteria`, `priority`, `emails`, `isactive`) VALUES
(1, 'Sample Rule', '1600500000001081005', 'Sample Description for this rule..', '{\"condition\":\"OR\",\"rules\":[{\"id\":\"description\",\"field\":\"description\",\"type\":\"string\",\"input\":\"text\",\"operator\":\"contains\",\"value\":\"Demo\"},{\"id\":\"email\",\"field\":\"email\",\"type\":\"string\",\"input\":\"text\",\"operator\":\"equal\",\"value\":\"jhon@test.com\"},{\"condition\":\"OR\",\"rules\":[{\"condition\":\"OR\",\"rules\":[{\"id\":\"summary\",\"field\":\"summary\",\"type\":\"string\",\"input\":\"text\",\"operator\":\"contains\",\"value\":\"Meeting\"},{\"id\":\"email\",\"field\":\"email\",\"type\":\"string\",\"input\":\"text\",\"operator\":\"contains\",\"value\":\"zylker\"}]},{\"condition\":\"OR\",\"rules\":[{\"id\":\"description\",\"field\":\"description\",\"type\":\"string\",\"input\":\"text\",\"operator\":\"not_contains\",\"value\":\"google\"},{\"id\":\"summary\",\"field\":\"summary\",\"type\":\"string\",\"input\":\"text\",\"operator\":\"equal\",\"value\":\"Demo Meeting\"}]}]}],\"valid\":true}', 1, 'appadurai@bizappln.com', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendees`
--
ALTER TABLE `attendees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calendarconfig`
--
ALTER TABLE `calendarconfig`
  ADD PRIMARY KEY (`calendarid`),
  ADD UNIQUE KEY `GCalId` (`gcalid`);

--
-- Indexes for table `calendaruser`
--
ALTER TABLE `calendaruser`
  ADD PRIMARY KEY (`status`);

--
-- Indexes for table `gevent`
--
ALTER TABLE `gevent`
  ADD PRIMARY KEY (`eventid`);

--
-- Indexes for table `ruleconfig`
--
ALTER TABLE `ruleconfig`
  ADD PRIMARY KEY (`ruleid`),
  ADD UNIQUE KEY `rulename` (`rulename`),
  ADD KEY `projectname` (`zprojectid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendees`
--
ALTER TABLE `attendees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `calendarconfig`
--
ALTER TABLE `calendarconfig`
  MODIFY `calendarid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `gevent`
--
ALTER TABLE `gevent`
  MODIFY `eventid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `ruleconfig`
--
ALTER TABLE `ruleconfig`
  MODIFY `ruleid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
