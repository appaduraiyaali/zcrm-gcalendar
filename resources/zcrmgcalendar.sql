-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 18, 2021 at 12:12 AM
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
  `responsestatus` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `channelid` varchar(50) NOT NULL,
  `watcherid` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `title` varchar(1000) NOT NULL,
  `ztaskid` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
-- Indexes for dumped tables
--

--
-- Indexes for table `attendees`
--
ALTER TABLE `attendees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_attendees_gevent` (`eventid`);

--
-- Indexes for table `calendarconfig`
--
ALTER TABLE `calendarconfig`
  ADD PRIMARY KEY (`calendarid`),
  ADD UNIQUE KEY `GCalId` (`gcalid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `calendaruser`
--
ALTER TABLE `calendaruser`
  ADD PRIMARY KEY (`userid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `gevent`
--
ALTER TABLE `gevent`
  ADD PRIMARY KEY (`eventid`),
  ADD KEY `calendarid` (`calendarid`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calendarconfig`
--
ALTER TABLE `calendarconfig`
  MODIFY `calendarid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gevent`
--
ALTER TABLE `gevent`
  MODIFY `eventid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ruleconfig`
--
ALTER TABLE `ruleconfig`
  MODIFY `ruleid` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendees`
--
ALTER TABLE `attendees`
  ADD CONSTRAINT `fk_attendees_gevent` FOREIGN KEY (`eventid`) REFERENCES `gevent` (`eventid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `calendarconfig`
--
ALTER TABLE `calendarconfig`
  ADD CONSTRAINT `fk_calendarconfig_calendaruser` FOREIGN KEY (`userid`) REFERENCES `calendaruser` (`userid`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `gevent`
--
ALTER TABLE `gevent`
  ADD CONSTRAINT `fk_gevent_calendarconfig` FOREIGN KEY (`calendarid`) REFERENCES `calendarconfig` (`calendarid`) ON DELETE CASCADE ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
