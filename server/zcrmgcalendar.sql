-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2021 at 05:30 AM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
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
  `responseStatus` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `calendarconfig`
--

CREATE TABLE `calendarconfig` (
  `Calendarid` int(11) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `GCalId` varchar(50) NOT NULL,
  `Status` varchar(20) NOT NULL,
  `NextSyncToken` varchar(50) NOT NULL,
  `TokenExpiry` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `Updated` bigint(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `description` text NOT NULL,
  `title` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ruleconfig`
--

CREATE TABLE `ruleconfig` (
  `ruleid` int(11) NOT NULL,
  `rulename` varchar(50) NOT NULL,
  `projectname` varchar(50) NOT NULL,
  `description` varchar(500) NOT NULL,
  `criteria` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendarconfig`
--
ALTER TABLE `calendarconfig`
  ADD PRIMARY KEY (`Calendarid`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `GCalId` (`GCalId`);

--
-- Indexes for table `ruleconfig`
--
ALTER TABLE `ruleconfig`
  ADD PRIMARY KEY (`ruleid`),
  ADD UNIQUE KEY `rulename` (`rulename`),
  ADD KEY `projectname` (`projectname`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
