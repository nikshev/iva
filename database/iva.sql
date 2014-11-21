-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 12, 2014 at 03:34 PM
-- Server version: 5.5.37-0ubuntu0.13.10.1
-- PHP Version: 5.5.3-1ubuntu2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `iva`
--

-- --------------------------------------------------------

--
-- Table structure for table `inflation`
--

CREATE TABLE IF NOT EXISTS `inflation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period_start` date NOT NULL,
  `inflation` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lawsuit`
--

CREATE TABLE IF NOT EXISTS `lawsuit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fn` text COLLATE utf8_unicode_ci NOT NULL,
  `sn` text COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `tin` text COLLATE utf8_unicode_ci NOT NULL,
  `tar` float NOT NULL,
  `ac` text COLLATE utf8_unicode_ci NOT NULL,
  `asd` date NOT NULL,
  `astd` date NOT NULL,
  `pd` date NOT NULL,
  `mps` float NOT NULL,
  `debt` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lawsuit_debt`
--

CREATE TABLE IF NOT EXISTS `lawsuit_debt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lawsuit_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `amount` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `rates`
--

CREATE TABLE IF NOT EXISTS `rates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `period_start` date NOT NULL,
  `rate` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `lawsuit_calc`
--

CREATE TABLE IF NOT EXISTS `lawsuit_calc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lawsuit_id` int(11) NOT NULL,
  `debt_id` int(11) NOT NULL,
  `days` int(11) NOT NULL,
  `percent` float NOT NULL,
  `inflation_av` float NOT NULL,
  `inflation_sum` float NOT NULL,
  `rate_start` date NOT NULL,
  `rate_stop` date NOT NULL,
  `rate_days` int(11) NOT NULL,
  `rate` float NOT NULL,
  `rate_summ` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
