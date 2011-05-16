-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 25, 2011 at 06:53 PM
-- Server version: 5.0.92
-- PHP Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `rgochee_cs130`
--

-- --------------------------------------------------------

--
-- Table structure for table `Fields`
--

DROP TABLE IF EXISTS `Fields`;
CREATE TABLE IF NOT EXISTS `Fields` (
  `field_id` int(11) NOT NULL auto_increment,
  `field_name` varchar(80) NOT NULL,
  `form_id` int(11) NOT NULL,
  `field_type` enum('textbox','checkbox','radio','dropdown','textarea') NOT NULL,
  `field_options` text NOT NULL,
  `field_required` tinyint(1) NOT NULL,
  `field_description` text NOT NULL,
  `field_order` mediumint(9) NOT NULL,
  PRIMARY KEY  (`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Filled_Forms`
--

DROP TABLE IF EXISTS `Filled_Forms`;
CREATE TABLE IF NOT EXISTS `Filled_Forms` (
  `instance_id` int(11) NOT NULL auto_increment,
  `form_id` int(11) NOT NULL,
  `user` varchar(20) NOT NULL,
  `time` int(13) NOT NULL,
  PRIMARY KEY  (`instance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Filled_Values`
--

DROP TABLE IF EXISTS `Filled_Values`;
CREATE TABLE IF NOT EXISTS `Filled_Values` (
  `field_id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`field_id`,`instance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Forms`
--

DROP TABLE IF EXISTS `Forms`;
CREATE TABLE IF NOT EXISTS `Forms` (
  `form_id` int(11) NOT NULL auto_increment,
  `form_name` varchar(50) NOT NULL,
  `user` varchar(20) NOT NULL,
  `form_description` text NOT NULL,
  `form_disabled` tinyint(1) NOT NULL,
  PRIMARY KEY  (`form_id`),
  UNIQUE KEY `form_name` (`form_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
