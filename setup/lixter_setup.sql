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
-- Table structure for table `downcats`
--

CREATE TABLE IF NOT EXISTS `downcats` (
  `ID` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Catname` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE IF NOT EXISTS `downloads` (
  `ID` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Description` text COLLATE utf8_unicode_ci NOT NULL,
  `Version` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `License` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `File` tinyint(4) NOT NULL,
  `Log` tinyint(4) NOT NULL,
  `CatID` tinyint(4) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE IF NOT EXISTS `files` (
  `ID` tinyint(4) NOT NULL AUTO_INCREMENT,
  `Name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Path` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `downloads` smallint(6) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `files`
--

-- --------------------------------------------------------

--
-- Table structure for table `kommentare`
--

CREATE TABLE IF NOT EXISTS `kommentare` (
  `ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `UID` smallint(6) NOT NULL,
  `Inhalt` text COLLATE utf8_unicode_ci NOT NULL,
  `Datum` datetime NOT NULL,
  `NewsID` smallint(6) NOT NULL,
  `Frei` tinyint(1) NOT NULL,
  `ParentID` smallint(6) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `kommentare`
--

INSERT INTO `kommentare` (`ID`, `UID`, `Inhalt`, `Datum`, `NewsID`, `Frei`, `ParentID`) VALUES
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
  PRIMARY KEY (`ID`)
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `newscat`
--

INSERT INTO `newscat` (`ID`, `Cat`, `ParentID`, `Typ`, `Beschreibung`) VALUES
(1, 'Blog', 0, 0, 'Default category');

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
-- Table structure for table `pics`
--

CREATE TABLE IF NOT EXISTS `pics` (
  `ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `NewsID` smallint(6) NOT NULL,
  `Name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `Pfad` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `Thumb` tinyint(1) NOT NULL,
  `Titel` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`)
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
('Impressum', 'Das Impressum bezieht sich sowohl folgende Internetpräsenzen und Social Media Angebote:\r\nbeuster.de ([url]http://beusterse.de[/url]),\r\nFacebook ([url]http://www.facebook.com/beusterse[/url])\r\nTwitter ([url]http://twitter.com/FBeuster[/url])\r\nYoutube ([url]http://youtube.com/waterwebdesign[/url]) und\r\nGoogle+ (auch Google Plus genannt) ([url]https://plus.google.com/102857640059997003370[/url])\r\n\r\nVerantwortlich für dieses Angebot und Inhalt / Angaben gemäß § 5 TMG:\r\n[address]Felix Beuster\r\nGartenstraße 43\r\n18246 Bützow\r\ninfo [at] beusterse.de[/address]\r\n\r\nInhaltlich Verantwortlicher gemäß § 55 Abs. 2 RStV: Felix Beuster\r\n\r\n[h2]Datenschutz und Nutzungsbedingungen[/h2]\r\n[ol]\r\n[li]Datenschutz\r\n[ol]\r\n[li]Erhobene Daten beim Kommentieren (Name, Website, Email, Inhalt) werden gespeichert.[/li]\r\n[li]Eine Erhebung weiterer Daten erfolgt nicht.[/li]\r\n[li]Mit Ausnahme der Email-Adresse sind diese Daten öffentlich unter den jeweiligen Artikeln zu sehen.[/li]\r\n[li]Eine Weitergabe an Dritte erfolgt nicht.[/li]\r\n[/ol]\r\n[/li]\r\n[li]beusterse.de verwendet die Services Google Analytics und Google AdSense der Firma Google. Hierbei werden auch Cookies duch Google auf ihrem Computer abgelegt, um eine Auswertung zu ermöglichen. Die Daten werden mitunter in die USA übertragen und von Goole ausgewertet. [url=https://www.google.de/accounts/TOS]Mehr dazu in den Google-Bestimmungen.[/url][/li]\r\n[li]Anmerkungen zum Inhalt\r\n[ol]\r\n[li]Ich übernehme keinerlei Gewähr für die Aktualität, Korrektheit, Vollständigkeit oder Qualität der bereitgestellten Informationen.[/li]\r\n[li]Dieses Onlineangebot ist kostenlos und unverbindlich.[/li]\r\n[li]Ich behalte mir vor, beusterse.de in Teilen oder als Ganzes ohne weitere Ankündigung zu erweitern, ändern oder zu löschen.[/li]\r\n[/ol]\r\n[/li]\r\n[li]Direkte und indirekte Links\r\n[ol]\r\n[li]Ich übernehme im Allgemeinen keine Verantwortung für Inhalt auf verlinkten Websites.[/li]\r\n[li]Zum Zeitpunkt der Linksetzung ist kein illegaler Inhalt auf diesen Websites hinterlegt. Sollte dies doch einmal der Fall sein, werde ich nach Kenntnissnahme des Sachverhaltes den Link zeitnah entfernen.[/li]\r\n[li]Sollten Nutzer Links auf beusterse.de veröffentlichen, so bestätigen sie, dass hinter den Links keine illegalen Inhalte liegen. Sollte dies doch der Fall sein, werden diese Links und/oder der gesamte Beitrag ohne Vorwarnung gelöscht.[/li]\r\n[/ol]\r\n[/li]\r\n[li]Urheberrecht\r\n[ol]\r\n[li]Sofern nicht anders angegeben sind alle hier publizierten Werke (Texte, Bilder, Videos, Programme und Vorlagen) von mir erstellt und unterliegen dem Urheberecht.[/li]\r\n[li]Darüberhinaus kann es gesonderte Richtlinien zu publizierten Werken geben, diese werden dann sichtbar kenntlich gemacht.[/li]\r\n[li]Sofern nicht anders angegeben ist eine erneute Publikation meiner Werke ohne ausdrückliche Einverständniserklärung meinerseits nicht gestattet.[/li]\r\n[li]Alle nicht von mir stammenden Werke wurden nach geltemdem Recht und Lizenzen verwendet.[/li]\r\n[/ol]\r\n[/li]\r\n[/ol]', 'impressum', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `ID` smallint(6) NOT NULL AUTO_INCREMENT,
  `news_id` smallint(6) NOT NULL,
  `tag` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `news_id` (`news_id`,`tag`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`ID`, `news_id`, `tag`) VALUES
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
  `name` varchar(20) NOT NULL,
  `content_de` text NOT NULL,
  `content_en` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `snippets`
--
ALTER TABLE `snippets`
  ADD PRIMARY KEY (`name`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
