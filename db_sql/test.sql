-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 28, 2010 at 11:12 AM
-- Server version: 5.1.36
-- PHP Version: 5.2.9-2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `metube`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `username` varchar(50) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(60) DEFAULT NULL,
  `type` varchar(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`username`, `password`, `email`, `type`) VALUES
('cpsc4620', 'cpsc6620', 'dbMS@clemson.edu', '1');

-- --------------------------------------------------------

--
-- Table structure for table `download`
--

CREATE TABLE IF NOT EXISTS `download` (
  `downloadid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `mediaid` int(11) NOT NULL,
  `downloadtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`downloadid`),
  KEY `username` (`username`),
  KEY `mediaid` (`mediaid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `download`
--

INSERT INTO `download` (`downloadid`, `username`, `mediaid`, `downloadtime`) VALUES
(1, 'metube', 5, '2008-09-06 12:48:21'),
(2, 'metube', 4, '2008-09-06 12:49:36'),
(3, 'metube', 4, '2008-09-06 13:18:44');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `mediaid` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(64) NOT NULL,
  `filepath` varchar(256) NOT NULL,
  `type` varchar(30) DEFAULT '0',
  `lastaccesstime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`mediaid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`mediaid`, `filename`, `filepath`, `type`, `lastaccesstime`) VALUES
(3, 'sample2.wmv', 'uploads/metube/', 'video/x-ms-wmv', '2010-01-28 10:58:45'),
(4, 'sample3.wmv', 'uploads/metube/', 'video/x-ms-wmv', '2010-01-28 10:58:58'),
(5, 'sample1.wmv', 'uploads/metube/', 'video/x-ms-wmv', '2010-01-28 10:59:11'),
(9, 'nintendogs_wallcoo.com_6.jpg', 'uploads/metube/', 'image/jpeg', '2010-01-28 10:59:05');

-- --------------------------------------------------------

--
-- Table structure for table `upload`
--

CREATE TABLE IF NOT EXISTS `upload` (
  `uploadid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `mediaid` int(11) NOT NULL,
  `uploadtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`uploadid`),
  KEY `username` (`username`),
  KEY `mediaid` (`mediaid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `upload`
--

INSERT INTO `upload` (`uploadid`, `username`, `mediaid`, `uploadtime`) VALUES
(3, 'metube', 3, '2008-09-05 15:52:19'),
(4, 'metube', 4, '2008-09-05 15:53:10'),
(5, 'metube', 5, '2008-09-05 15:53:47'),
(9, 'metube', 9, '2008-09-05 16:28:36');
