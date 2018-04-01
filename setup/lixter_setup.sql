-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 02, 2015 at 12:36 AM
-- Server version: 5.5.44-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `usr_web1_2`
--

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE IF NOT EXISTS `attachments` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `file_path` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `license` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `version` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `downloads` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE IF NOT EXISTS `article_attachments` (
  `article_id` smallint(6) NOT NULL,
  `attachment_id` smallint(6) NOT NULL,
  PRIMARY KEY (`article_id`, `attachment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE `configuration` (
  `option_set` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `option_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `option_value` varchar(128) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` (`option_set`, `option_name`, `option_value`) VALUES
('search', 'case_sensitive', '0'),
('search', 'marks', '1'),
('site', 'amazon_tag', ''),
('site', 'category_page_length', '8'),
('site', 'dev_server_address', ''),
('site', 'google_analytics', ''),
('site', 'language', 'en'),
('site', 'mail', ''),
('site', 'name', 'Lixter'),
('site', 'remote_server_address', ''),
('site', 'theme', 'default'),
('site', 'title', 'My Blog'),
('site', 'url_schema', '2');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `configuration`
--
ALTER TABLE `configuration`
  ADD PRIMARY KEY (`option_set`,`option_name`);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `article_id` smallint(6) NOT NULL,
  `parent_comment_id` smallint(6) NOT NULL,
  `user_id` smallint(6) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `kommentare`
--

INSERT INTO `comments` (`id`, `user_id`, `content`, `date`, `article_id`, `enabled`, `parent_comment_id`) VALUES
(1, 10001, 'First comment', NOW(), 1, 2, -1);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `Autor` smallint(6) NOT NULL,
  `Titel` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `Inhalt` text COLLATE utf8_unicode_ci NOT NULL,
  `Datum` datetime NOT NULL,
  `enable` tinyint(1) NOT NULL,
  `Hits` smallint(6) NOT NULL,
  `Status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  FULLTEXT KEY `Inhalt` (`Inhalt`),
  FULLTEXT KEY `Titel` (`Titel`),
  FULLTEXT KEY `InhaltTitel` (`Titel`,`Inhalt`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`ID`, `Autor`, `Titel`, `Inhalt`, `Datum`, `enable`, `Hits`, `Status`) VALUES
(1, 10001, 'Hello world!', 'We are live, this is your first article!', NOW(), 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `newscat`
--

CREATE TABLE IF NOT EXISTS `newscat` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Cat` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ParentID` tinyint(4) NOT NULL DEFAULT '0',
  `Typ` tinyint(4) NOT NULL DEFAULT '2',
  `Beschreibung` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `newscat`
--

INSERT INTO `newscat` (`ID`, `Cat`, `ParentID`, `Typ`, `Beschreibung`) VALUES
(1, 'Blog', 0, 0, 'Default category');
INSERT INTO `newscat` (`ID`, `Cat`, `ParentID`, `Typ`, `Beschreibung`) VALUES
(1, 'Downloads', 0, 0, 'Download category');

-- --------------------------------------------------------

--
-- Table structure for table `newscatcross`
--

CREATE TABLE IF NOT EXISTS `newscatcross` (
  `NewsID` smallint(6) NOT NULL,
  `Cat` tinyint(4) NOT NULL,
  `CatID` smallint(6) NOT NULL,
  PRIMARY KEY (`NewsID`,`Cat`,`CatID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `newscatcross`
--

INSERT INTO `newscatcross` (`NewsID`, `Cat`, `CatID`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `article_id` smallint(6) NOT NULL,
  `caption` text COLLATE utf8_unicode_ci NOT NULL,
  `file_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `is_humb` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE IF NOT EXISTS `playlist` (
  `ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `ytID` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `catID` smallint(6) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `static_pages`
--

CREATE TABLE IF NOT EXISTS `static_pages` (
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `feedback` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `static_pages`
--

INSERT INTO `static_pages` (`title`, `content`, `url`, `feedback`) VALUES
('Imprint', 'Your imprint here.', 'imprint', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `article_id` smallint(6) NOT NULL,
  `tag` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `article_id` (`article_id`,`tag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `article_id`, `tag`) VALUES
(1, 1, 'Hello'),
(2, 1, 'world'),
(3, 1, 'sample');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `Name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `Password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `Rights` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `regDate` datetime NOT NULL,
  `Contactmail` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `Clearname` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `About` text COLLATE utf8_unicode_ci NOT NULL,
  `cmtNotify` tinyint(1) NOT NULL DEFAULT '1',
  `Website` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20000 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Name`, `Password`, `Rights`, `Email`, `regDate`, `Contactmail`, `Clearname`, `About`, `cmtNotify`, `Website`) VALUES
(10001, 'admin', 'empty', 'admin', 'admin@email.com', NOW(), 'admin@email.com', 'Admin', 'About me text', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `snippets`
--

CREATE TABLE `snippets` (
  `name` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `content_de` text COLLATE utf8_unicode_ci NOT NULL,
  `content_en` text COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `edited` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for table `snippets`
--
ALTER TABLE `snippets`
  ADD PRIMARY KEY (`name`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
