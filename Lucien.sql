-- phpMyAdmin SQL Dump
-- version 3.4.3.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 14, 2011 at 09:07 PM
-- Server version: 5.0.77
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Lucien`
--

-- --------------------------------------------------------

--
-- Table structure for table `Category`
--

CREATE TABLE IF NOT EXISTS `Category` (
  `ID` int(11) NOT NULL auto_increment,
  `Category` varchar(50) NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Category` (`Category`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `Codes`
--

CREATE TABLE IF NOT EXISTS `Codes` (
  `ID` int(10) NOT NULL auto_increment,
  `Barcode` varchar(13) default NULL,
  `ISBN` varchar(10) default NULL,
  `Format` int(11) default NULL,
  `Category` int(11) NOT NULL default '1',
  `Series` int(11) NOT NULL default '1',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `Barcode` (`Barcode`),
  KEY `ISBN` (`ISBN`),
  KEY `Format` (`Format`),
  KEY `ID` (`ID`,`Barcode`),
  KEY `ID_2` (`ID`,`ISBN`),
  KEY `ID_3` (`ID`,`Format`),
  KEY `Category` (`Category`,`Series`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1424 ;

-- --------------------------------------------------------

--
-- Table structure for table `Format`
--

CREATE TABLE IF NOT EXISTS `Format` (
  `ID` int(11) NOT NULL auto_increment,
  `Format` varchar(50) NOT NULL,
  PRIMARY KEY  (`ID`),
  KEY `Name` (`Format`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `Series`
--

CREATE TABLE IF NOT EXISTS `Series` (
  `ID` int(11) NOT NULL,
  `Series` varchar(50) NOT NULL,
  PRIMARY KEY  (`ID`),
  FULLTEXT KEY `Series` (`Series`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Title`
--

CREATE TABLE IF NOT EXISTS `Title` (
  `ID` int(10) NOT NULL auto_increment,
  `Title` varchar(100) default NULL,
  PRIMARY KEY  (`ID`),
  KEY `Title` (`Title`),
  KEY `ID` (`ID`,`Title`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1424 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
