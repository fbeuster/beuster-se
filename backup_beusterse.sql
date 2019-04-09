-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2017 at 11:28 PM
-- Server version: 10.1.10-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beusterse`
--

-- --------------------------------------------------------

--
-- Table structure for table `downcats`
--

DROP TABLE IF EXISTS `downcats`;
CREATE TABLE `downcats` (
  `ID` tinyint(4) NOT NULL,
  `Catname` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `downcats` (`ID`, `Catname`) VALUES
(1, 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

DROP TABLE IF EXISTS `downloads`;
CREATE TABLE `downloads` (
  `ID` tinyint(4) NOT NULL,
  `Name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Description` text COLLATE utf8_unicode_ci NOT NULL,
  `Version` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `License` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `File` tinyint(4) NOT NULL,
  `Log` tinyint(4) NOT NULL,
  `CatID` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `downloads`
--

INSERT INTO `downloads` (`ID`, `Name`, `Description`, `Version`, `License`, `File`, `Log`, `CatID`) VALUES
(3, 'Tester', 'test', '1.0', 'by-sa', 15, 16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `ID` tinyint(4) NOT NULL,
  `Name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Path` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `downloads` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`ID`, `Name`, `Path`, `downloads`) VALUES
(15, 'test_file.txt', 'files/test_file.txt', 0),
(16, 'test_log.txt', 'files/test_log.txt', 0);

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` smallint(6) NOT NULL,
  `article_id` smallint(6) NOT NULL,
  `caption` text COLLATE utf8_unicode_ci NOT NULL,
  `file_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `is_thumb` tinyint(1) NOT NULL,
  `upload_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `images`
--

INSERT INTO `images` (`id`, `article_id`, `caption`, `file_name`, `is_thumb`, `upload_date`) VALUES
(1, 19, '20160101_143034.jpg', 'id19date20160619n0.jpg', 1, '2016-06-19 16:33:33'),
(2, 19, '20160102_133440.jpg', 'id19date20160619n1.jpg', 0, '2016-06-19 16:33:33'),
(3, 26, '20160105_145226.jpg', '20160105_145226.jpg', 1, '2017-01-04 17:04:26');

-- --------------------------------------------------------

--
-- Table structure for table `kommentare`
--

DROP TABLE IF EXISTS `kommentare`;
CREATE TABLE `kommentare` (
  `ID` smallint(6) NOT NULL,
  `UID` smallint(6) NOT NULL,
  `Inhalt` text COLLATE utf8_unicode_ci NOT NULL,
  `Datum` datetime NOT NULL,
  `NewsID` smallint(6) NOT NULL,
  `Frei` tinyint(1) NOT NULL,
  `ParentID` smallint(6) NOT NULL DEFAULT '-1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `kommentare`
--

INSERT INTO `kommentare` (`ID`, `UID`, `Inhalt`, `Datum`, `NewsID`, `Frei`, `ParentID`) VALUES
(1, 10001, 'First comment', '2015-12-02 00:12:54', 1, 2, -1),
(2, 10003, 'Testing', '2016-03-16 19:42:46', 1, 0, -1),
(3, 10003, 'I\'m yet another test comment and should not be an answer...', '2016-07-01 21:27:13', 1, 0, -1),
(4, 10004, 'but I am!', '2016-07-01 21:28:16', 1, 0, 3),
(5, 10003, 'Hey!', '2016-07-01 21:33:46', 1, 0, 3),
(6, 10005, 'Just another comment', '2016-07-02 19:10:17', 1, 2, -1);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `ID` smallint(6) NOT NULL,
  `Autor` smallint(6) NOT NULL,
  `Titel` varchar(120) COLLATE utf8_unicode_ci NOT NULL,
  `Inhalt` text COLLATE utf8_unicode_ci NOT NULL,
  `Datum` datetime NOT NULL,
  `enable` tinyint(1) NOT NULL,
  `Hits` smallint(6) NOT NULL,
  `Status` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`ID`, `Autor`, `Titel`, `Inhalt`, `Datum`, `enable`, `Hits`, `Status`) VALUES
(1, 10002, 'Hello world!', 'We are live, this is your first article!', '2015-12-02 00:12:54', 1, 0, 0),
(6, 10002, 'Roadtrip', '[yt]eSyINOKaOv8[/yt]', '2016-03-02 01:00:34', 1, 0, 0),
(15, 10002, 'Sunrise Fur', '[yt]V53DDlorQ6Q[/yt]', '2016-03-03 18:57:36', 1, 0, 0),
(16, 10002, 'Demoartikel', '[h2]Headline 2[/h2]\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Duis semper fermentum interdum. Cras elementum odio vel mattis rutrum. Cras interdum gravida dolor, eu euismod ipsum rutrum elementum. Praesent dui velit, suscipit id porttitor et, tempor vitae lectus. Sed euismod dignissim justo a ornare. Suspendisse vel justo eget nisi lobortis sodales. Pellentesque pellentesque turpis vel ipsum semper elementum. Fusce porttitor diam quam, vel placerat turpis commodo sit amet. Nam dictum sapien consequat, condimentum nisl nec, auctor velit.\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Duis semper fermentum interdum. Cras elementum odio vel mattis rutrum. Cras interdum gravida dolor, eu euismod ipsum rutrum elementum. Praesent dui velit, suscipit id porttitor et, tempor vitae lectus. Sed euismod dignissim justo a ornare. Suspendisse vel justo eget nisi lobortis sodales. Pellentesque pellentesque turpis vel ipsum semper elementum. Fusce porttitor diam quam, vel placerat turpis commodo sit amet. Nam dictum sapien consequat, condimentum nisl nec, auctor velit. \r\n\r\n[h3]Headline 3[/h3]\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Duis semper fermentum interdum. Cras elementum odio vel mattis rutrum. Cras interdum gravida dolor, eu euismod ipsum rutrum elementum. Praesent dui velit, suscipit id porttitor et, tempor vitae lectus. Sed euismod dignissim justo a ornare. Suspendisse vel justo eget nisi lobortis sodales. Pellentesque pellentesque turpis vel ipsum semper elementum. Fusce porttitor diam quam, vel placerat turpis commodo sit amet. Nam dictum sapien consequat, condimentum nisl nec, auctor velit.\r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Duis semper fermentum interdum. Cras elementum odio vel mattis rutrum. Cras interdum gravida dolor, eu euismod ipsum rutrum elementum. Praesent dui velit, suscipit id porttitor et, tempor vitae lectus. Sed euismod dignissim justo a ornare. Suspendisse vel justo eget nisi lobortis sodales. Pellentesque pellentesque turpis vel ipsum semper elementum. Fusce porttitor diam quam, vel placerat turpis commodo sit amet. Nam dictum sapien consequat, condimentum nisl nec, auctor velit.\r\n\r\nAlso, you can embed images in articles as well. [img1]\r\n\r\n[b]Bold[/b] [i]Italic[/i] [u]Underline[/u]\r\n[mark]Mark[/mark] [del]Deleted[/del] [ins]Inserted[/ins]\r\n\r\n[quote]In quotes[/quote]\r\n[cite=http://google.com]Inline cite[/cite]\r\n[bquote=http://beusterse.de]Block cite[/bquote]\r\n\r\n[ol][li]First item[/li]\r\n[li]Second item[/li]\r\n[li]Third item[/li][/ol]\r\n\r\n[ol][li]First item[/li]\r\n[li]Second item\r\n[ol][li]First sub item\r\n[ol][li]First sub sub item[/li][/ol][/li]\r\n[li]Second sub item[/li][/ol][/li]\r\n[li]Thrid item[/li][/ol]\r\n\r\n[ul][li]An item[/li]\r\n[li]An item[/li]\r\n[li]An item[/li][/ul]\r\n\r\n[ul][li]An item[/li]\r\n[li]An item\r\n[ul][li]A sub item[/li]\r\n[li]A sub item[/li][/ul][/li]\r\n[li]An item[/li][/ul]\r\n\r\nA list with some text before it.\r\n[ul][li]An item[/li]\r\n[li]An item[/li]\r\n[li]An item[/li][/ul]\r\n\r\n[ul][li]An item[/li]\r\n[li]An item[/li]\r\n[li]An item[/li][/ul]\r\nA list with some text after it.\r\n\r\n[code]Some code[/code]\r\n\r\n[url=http://google.com]A link[/url]\r\n[asin=123456]An Amazon link[/asin]\r\n\r\nEmbedded video\r\n[yt]XVIrzLbOpuA[/yt]\r\n\r\nEmbedded playlist\r\n[play]PLesGhGI6pN8m9viXSJq7dNmaIzXlx0ZX-[/play]\r\n\r\nEmojis\r\n:) :( :D ;)\r\n\r\nSnippert Usage\r\n[snip test]', '2016-03-20 13:37:10', 1, 0, 0),
(17, 10002, 'Las Vegas Strip at Night', '[yt]0NaB5ETd4Eo[/yt]', '2016-03-24 01:06:24', 1, 0, 0),
(18, 10002, 'Technikausstattung', 'Interessiert mit welcher Kamera dieses oder jenes Video gedreht wurde? Oder auf welchem Rechner mit welcher Software geschnitten wurde? Hier gebe ich mal einen kurzen Überblick über meine Ausstattung.\r\nAlle Links führen zu Amazon, um langfristig eine Shopseite verlinken zu können. (Und ja, vielleicht verlinkt auch deshalb, weil es Affiliate Links sind. Kostet euch nichts extra, bringt mir dann aber 1€ mehr.)\r\n\r\nVielleicht eins noch, auch wenn diese jetzt lang und teuer aussehen mag, lasst euch nicht entmutigen, Videos mit weniger Technik zu machen. Es ist alles über Jahre hinweg zusammengetragen worden, könnte vermutlich deutlich besser sein, aber es geht eben auch mit weniger, bspw. nur dem Smartphone.\r\n\r\n[h2]Kameras[/h2]\r\n[ul][li]Canon EOS 550D[/li]\r\n[li]Canon Legria Mini X[/li]\r\n[li]GoPro Hero 4 Black[/li][/ul]\r\n\r\n[h2]Objektive[/h2]\r\n[ul][li]Sigma 17-50mm f2.8[/li]\r\n[li]Canon 50mm f1.8 II[/li][/ul]\r\n\r\n[h2]Tontechnik[/h2]\r\n[ul][li]Rode Videomic[/li]\r\n[li]Zoom H1[/li][/ul]\r\n\r\n[h2]Zubehör[/h2]\r\n[ul][li]Großes Stativ[/li]\r\n[li]Kleines Stativ[/li]\r\n[li]Manfrotto Kopf[/li]\r\n[li]Kugelkopf[/li]\r\n[li]Glidecam[/li]\r\n[li]Videoleuchte[/li][/ul]\r\n\r\n[h2]Computer[/h2]\r\n[ul][li]Dell 24" irgendwas (2mal)[/li]\r\n[li]Corsair K70 RGB[/li]\r\n[li]Razor Deathadder[/li]\r\n[li]CPU[/li]\r\n[li]RAM[/li]\r\n[li]GraKa 770[/li]\r\n[li]x TB HDD[/li]\r\n[li]x GB SSD[/li]\r\n[li]Case[/li][/ul]\r\n\r\n[h2]Software[/h2]\r\n[ul][li]Windows 10[/li]\r\n[li]Linux Mint 17.1[/li]\r\n[li]Adobe Creative Suite 5.5 Production Premium[/li]\r\n[li]Audacity[/li]\r\n[li]Sublime Text 3[/li]\r\n[li]Magic Lantern[/li][/ul]', '2016-03-31 17:00:42', 1, 0, 0),
(19, 10002, 'Image Testing', 'Some test for testing', '2016-06-19 16:33:33', 1, 0, 0),
(20, 10002, 'Filler', 'I\'m a filler article. Or something.', '2016-06-25 22:30:08', 1, 1, 0),
(26, 10002, 'Tester', 'Testing...', '2017-12-17 13:37:00', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `newscat`
--

DROP TABLE IF EXISTS `newscat`;
CREATE TABLE `newscat` (
  `ID` int(11) NOT NULL,
  `Cat` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `ParentID` tinyint(4) NOT NULL DEFAULT '0',
  `Typ` tinyint(4) NOT NULL DEFAULT '2',
  `Beschreibung` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `newscat`
--

INSERT INTO `newscat` (`ID`, `Cat`, `ParentID`, `Typ`, `Beschreibung`) VALUES
(1, 'Blog', 0, 0, 'Default category'),
(3, 'Timelapses', 7, 1, ''),
(7, 'Videos', 0, 0, ''),
(8, 'Huppa', 7, 2, ''),
(9, 'Downloads', 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `newscatcross`
--

DROP TABLE IF EXISTS `newscatcross`;
CREATE TABLE `newscatcross` (
  `NewsID` smallint(6) NOT NULL,
  `Cat` tinyint(4) NOT NULL,
  `CatID` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `newscatcross`
--

INSERT INTO `newscatcross` (`NewsID`, `Cat`, `CatID`) VALUES
(1, 1, 1),
(6, 3, 1),
(15, 3, 2),
(16, 1, 2),
(17, 3, 3),
(18, 1, 3),
(19, 1, 4),
(20, 1, 5),
(26, 1, 6);

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

DROP TABLE IF EXISTS `playlist`;
CREATE TABLE `playlist` (
  `ID` smallint(6) NOT NULL,
  `ytID` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `catID` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `playlist`
--

INSERT INTO `playlist` (`ID`, `ytID`, `catID`) VALUES
(1, 'PLesGhGI6pN8m9viXSJq7dNmaIzXlx0Z', 3);

-- --------------------------------------------------------

--
-- Table structure for table `snippets`
--

DROP TABLE IF EXISTS `snippets`;
CREATE TABLE `snippets` (
  `name` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `content_de` text COLLATE utf8_unicode_ci NOT NULL,
  `content_en` text COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL,
  `edited` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `snippets`
--

INSERT INTO `snippets` (`name`, `content_de`, `content_en`, `created`, `edited`) VALUES
('aa', '[h2]Hauptaudio[/h2][p]\r\nHiermit wird der Ton f?r die meisten meiner Videos vor der Kamera aufgenommen.\r\n[/p][p]\r\n[ul][li][asin=B0007U9SOC]Rode Videomic Pro[/asin] (in alter Version)[/li]\r\n[li][asin=B01AUXNJRW]Rode Deadcat[/asin] (in alter Version)[/li]\r\n[li][asin=B003QKBVYK]Zoom H1[/asin] mitsamt [asin=B003YG6ETS]Zubeh?r[/asin][/li][/ul]', '[h2]Hauptaudio[/h2][p]\r\nHiermit wird der Ton f?r die meisten meiner Videos vor der Kamera aufgenommen.\r\n[/p][p]\r\n[ul][li][asin=B0007U9SOC]Rode Videomic Pro[/asin] (in alter Version)[/li]\r\n[li][asin=B01AUXNJRW]Rode Deadcat[/asin] (in alter Version)[/li]\r\n[li][asin=B003QKBVYK]Zoom H1[/asin] mitsamt [asin=B003YG6ETS]Zubeh?r[/asin][/li][/ul]', '2016-06-22 22:49:16', '2016-06-23 22:46:08'),
('cc', '[h2]Hauptkamera[/h2][p]\r\nMeine Hauptkamera, die den Großteil meiner Videos gefilmt hat. Kommt ein wenig in die Tage, ist aber dennoch ok.\r\n[/p][p]\r\n[ul][li][asin=B0037KM2II]Canon EOS 550D[/asin][/li]\r\n[li][asin=B003A6H27K]Sigma 17-50mm f/2.8[/asin][/li]\r\n[li][asin=B00XKSBMQA]Canon 50mm f/1.8 STM[/asin][/li]\r\n[li][asin=B0029LHW7M]Cullmann MAGNESIT 525 Stativ[/asin][/li]\r\n[li][asin=B001D2LJ3Q]Manfrotto 701HDV Fluid Head[/asin][/li]\r\n[li][asin=B00140TJF2]Zusatzakkus[/asin][/li]\r\n[li]verschiedenste Speicherkarten[/li]\r\n[li]Magic Lantern Firmware[/li][/ul]', '[h2]Hauptkamera[/h2][p]\r\nMeine Hauptkamera, die den Großteil meiner Videos gefilmt hat. Kommt ein wenig in die Tage, ist aber dennoch ok.\r\n[/p][p]\r\n[ul][li][asin=B0037KM2II]Canon EOS 550D[/asin][/li]\r\n[li][asin=B003A6H27K]Sigma 17-50mm f/2.8[/asin][/li]\r\n[li][asin=B00XKSBMQA]Canon 50mm f/1.8 STM[/asin][/li]\r\n[li][asin=B0029LHW7M]Cullmann MAGNESIT 525 Stativ[/asin][/li]\r\n[li][asin=B001D2LJ3Q]Manfrotto 701HDV Fluid Head[/asin][/li]\r\n[li][asin=B00140TJF2]Zusatzakkus[/asin][/li]\r\n[li]verschiedenste Speicherkarten[/li]\r\n[li]Magic Lantern Firmware[/li][/ul]', '2016-06-23 02:55:42', '2016-10-07 16:01:20'),
('ga', '[h2]GoPro und Zubeh?r[/h2][p]\r\nDie GoPro ist f?r mich ein kleiner Allrounder und wird manchmal als Actioncam, manchmal als Haupt- oder sogar Zweitaufnahme genutzt bspw. als Facecam bei Screencasts.\r\n[/p][p]\r\n[ul][li][asin=B00O32GGTK]GoPro Hero 4 Black[/asin][/li]\r\n[li][asin=B013D1Z57U]Akkus[/asin] von Drittanbietern[/li]\r\n[li][url=https://www.indiegogo.com/projects/slopes-for-gopro-world-s-first-polyhedron-stand#/]SLOPES[/url][/li]\r\n[li]diverse Mounts[/li][/ul]', '[h2]GoPro und Zubeh?r[/h2][p]\r\nDie GoPro ist f?r mich ein kleiner Allrounder und wird manchmal als Actioncam, manchmal als Haupt- oder sogar Zweitaufnahme genutzt bspw. als Facecam bei Screencasts.\r\n[/p][p]\r\n[ul][li][asin=B00O32GGTK]GoPro Hero 4 Black[/asin][/li]\r\n[li][asin=B013D1Z57U]Akkus[/asin] von Drittanbietern[/li]\r\n[li][url=https://www.indiegogo.com/projects/slopes-for-gopro-world-s-first-polyhedron-stand#/]SLOPES[/url][/li]\r\n[li]diverse Mounts[/li][/ul]', '2016-06-23 21:48:59', '2016-06-23 22:46:40'),
('pg', '[h2]Computer Hardware[/h2][p]\r\n?ber die Jahre gab und gibt es hier wohl die meisten Ver?nderungen, aktuell ist es wohl ein recht durchschnittlicher Gaming-PC. Mit einigen Anpassungen f?r Videoschnitt :)\r\n[/p][p]\r\n[ul][li][asin=B004FA8NOQ]Intel i7 2600K[/asin][/li]\r\n[li][asin=B0050AFS84]Asus P8Z68-V Pro[/asin][/li]\r\n[li][asin=B007BBQPUA]Samsung 830 SSD, 128 GB[/asin][/li]\r\n[li][asin=B013QFRS2S]Western Digital Blue, 2TB[/asin][/li]\r\n[li][asin=B003WE9WQO]NZXT Phantom Big-Tower[/asin][/li]\r\n[li][asin=B005LN1JEC]2x Dell U2212HM, 21"[/asin][/li]\r\n[li][asin=B00NYDSQ42]Corsair K70 RGB, MX Brown[/asin][/li]\r\n[li][asin=B00ABS62C6]Razer DeathAdder[/asin][/li]\r\n[li][asin=B003PAIVBC]Logitech C910[/asin][/li]\r\n[li][asin=B0007NQH98]MXL 770 Mikrofon[/asin][/li]\r\n[li][asin=B009B15N0Q]Focusrite Scarlett 2i4[/asin][/li]\r\n[li][asin=B00FA1WA5W]Bose Computer MusicMonitor[/asin][/li]\r\n[li][asin=B0016MNAAI]Beyerdynamic DT-770 Pro 80[/asin][/li][/ul]', '[h2]Computer Hardware[/h2][p]\r\n?ber die Jahre gab und gibt es hier wohl die meisten Ver?nderungen, aktuell ist es wohl ein recht durchschnittlicher Gaming-PC. Mit einigen Anpassungen f?r Videoschnitt :)\r\n[/p][p]\r\n[ul][li][asin=B004FA8NOQ]Intel i7 2600K[/asin][/li]\r\n[li][asin=B0050AFS84]Asus P8Z68-V Pro[/asin][/li]\r\n[li][asin=B007BBQPUA]Samsung 830 SSD, 128 GB[/asin][/li]\r\n[li][asin=B013QFRS2S]Western Digital Blue, 2TB[/asin][/li]\r\n[li][asin=B003WE9WQO]NZXT Phantom Big-Tower[/asin][/li]\r\n[li][asin=B005LN1JEC]2x Dell U2212HM, 21"[/asin][/li]\r\n[li][asin=B00NYDSQ42]Corsair K70 RGB, MX Brown[/asin][/li]\r\n[li][asin=B00ABS62C6]Razer DeathAdder[/asin][/li]\r\n[li][asin=B003PAIVBC]Logitech C910[/asin][/li]\r\n[li][asin=B0007NQH98]MXL 770 Mikrofon[/asin][/li]\r\n[li][asin=B009B15N0Q]Focusrite Scarlett 2i4[/asin][/li]\r\n[li][asin=B00FA1WA5W]Bose Computer MusicMonitor[/asin][/li]\r\n[li][asin=B0016MNAAI]Beyerdynamic DT-770 Pro 80[/asin][/li][/ul]', '2016-06-23 22:24:08', '2016-06-23 22:46:53'),
('sd', '[h2]Computer Software[/h2][p]\r\nRecht unspecktakul?r ist es hier, und die CS5.5 geh?rt mittlerweile zum alten Eisen. Dennoch bekomme ich damit alles umgesetzt und reize es sicherlich auch nicht aus.\r\n[/p][p]\r\n[ul][li]Windows 10[/li]\r\n[li]Linux Mint 17.1[/li]\r\n[li]Adobe CS 5.5 Production Premium[/li]\r\n[li]Blender[/li]\r\n[li]Audacity[/li]\r\n[li]Sublime Text 3[/li][/ul]', '[h2]Computer Software[/h2][p]\r\nRecht unspecktakul?r ist es hier, und die CS5.5 geh?rt mittlerweile zum alten Eisen. Dennoch bekomme ich damit alles umgesetzt und reize es sicherlich auch nicht aus.\r\n[/p][p]\r\n[ul][li]Windows 10[/li]\r\n[li]Linux Mint 17.1[/li]\r\n[li]Adobe CS 5.5 Production Premium[/li]\r\n[li]Blender[/li]\r\n[li]Audacity[/li]\r\n[li]Sublime Text 3[/li][/ul]', '2016-06-23 22:30:56', '2016-06-23 22:47:07'),
('va', '[h2]Vlog-Kamera[/h2][p]\r\nBin ich unterwegs, ist das die Kamera meiner Wahl. Der Ton ist super (au?er im Wind) und der Weitwinkel recht hilfreich.\r\n[/p][p]\r\n[ul][li][asin=B00HTV6GB0]Canon Legria Mini X[/asin][/li]\r\n[li]kleines [asin=B000EBFN70]Hama Dreibeinstativ[/asin]\r\n[/li][/ul]', '[h2]Vlog-Kamera[/h2][p]\r\nBin ich unterwegs, ist das die Kamera meiner Wahl. Der Ton ist super (au?er im Wind) und der Weitwinkel recht hilfreich.\r\n[/p][p]\r\n[ul][li][asin=B00HTV6GB0]Canon Legria Mini X[/asin][/li]\r\n[li]kleines [asin=B000EBFN70]Hama Dreibeinstativ[/asin]\r\n[/li][/ul]', '2016-06-23 21:00:54', '2016-06-23 22:47:16');

-- --------------------------------------------------------

--
-- Table structure for table `static_pages`
--

DROP TABLE IF EXISTS `static_pages`;
CREATE TABLE `static_pages` (
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `feedback` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `static_pages`
--

INSERT INTO `static_pages` (`title`, `content`, `url`, `feedback`) VALUES
('Equipment', 'Welche Technik wurde für dieses Video eingesetzt? Eigentlich nicht so viel, unten siehst du eine Liste vom wichtigsten. Und denk dran, ein gutes Video lebt nicht von Technik. Mit der richtigen Story kann sogar ein Handyvideo überzeugen.\r\n\r\nDie meisten Links hier führen zu Amazon, um langfristig eine Shopseite verlinken zu können. (Und ja, es sind Affiliate Links. Kostet euch nichts extra, bringt mir dann aber 1€ mehr.)', 'equipment', 0),
('Impressum', 'Das Impressum bezieht sich sowohl folgende Internetpräsenzen und Social Media Angebote:\r\nbeusterse.de ([url]http://beusterse.de[/url]),\r\nFacebook ([url]http://www.facebook.com/beusterse[/url])\r\nTwitter ([url]http://twitter.com/FBeuster[/url])\r\nYoutube ([url]http://youtube.com/waterwebdesign[/url]) und\r\nGoogle+ (auch Google Plus genannt) ([url]https://plus.google.com/102857640059997003370[/url])\r\n\r\nVerantwortlich für dieses Angebot und Inhalt / Angaben gemäß § 5 TMG:\r\n[address]Felix Beuster\r\nGartenstraße 43\r\n18246 Bützow\r\ninfo [at] beusterse.de[/address]\r\n\r\nInhaltlich Verantwortlicher gemäß § 55 Abs. 2 RStV: Felix Beuster\r\n\r\n[h2]Datenschutz und Nutzungsbedingungen[/h2]\r\n[ol]\r\n[li]Datenschutz\r\n[ol]\r\n[li]Erhobene Daten beim Kommentieren (Name, Website, Email, Inhalt) werden gespeichert.[/li]\r\n[li]Eine Erhebung weiterer Daten erfolgt nicht.[/li]\r\n[li]Mit Ausnahme der Email-Adresse sind diese Daten öffentlich unter den jeweiligen Artikeln zu sehen.[/li]\r\n[li]Eine Weitergabe an Dritte erfolgt nicht.[/li]\r\n[/ol]\r\n[/li]\r\n[li]beusterse.de verwendet die Services Google Analytics und Google AdSense der Firma Google. Hierbei werden auch Cookies duch Google auf ihrem Computer abgelegt, um eine Auswertung zu ermöglichen. Die Daten werden mitunter in die USA übertragen und von Goole ausgewertet. [url=https://www.google.de/accounts/TOS]Mehr dazu in den Google-Bestimmungen.[/url][/li]\r\n[li]Anmerkungen zum Inhalt\r\n[ol]\r\n[li]Ich übernehme keinerlei Gewähr für die Aktualität, Korrektheit, Vollständigkeit oder Qualität der bereitgestellten Informationen.[/li]\r\n[li]Dieses Onlineangebot ist kostenlos und unverbindlich.[/li]\r\n[li]Ich behalte mir vor, beusterse.de in Teilen oder als Ganzes ohne weitere Ankündigung zu erweitern, ändern oder zu löschen.[/li]\r\n[/ol]\r\n[/li]\r\n[li]Direkte und indirekte Links\r\n[ol]\r\n[li]Ich übernehme im Allgemeinen keine Verantwortung für Inhalt auf verlinkten Websites.[/li]\r\n[li]Zum Zeitpunkt der Linksetzung ist kein illegaler Inhalt auf diesen Websites hinterlegt. Sollte dies doch einmal der Fall sein, werde ich nach Kenntnissnahme des Sachverhaltes den Link zeitnah entfernen.[/li]\r\n[li]Sollten Nutzer Links auf beusterse.de veröffentlichen, so bestätigen sie, dass hinter den Links keine illegalen Inhalte liegen. Sollte dies doch der Fall sein, werden diese Links und/oder der gesamte Beitrag ohne Vorwarnung gelöscht.[/li]\r\n[/ol]\r\n[/li]\r\n[li]Urheberrecht\r\n[ol]\r\n[li]Sofern nicht anders angegeben sind alle hier publizierten Werke (Texte, Bilder, Videos, Programme und Vorlagen) von mir erstellt und unterliegen dem Urheberecht.[/li]\r\n[li]Darüberhinaus kann es gesonderte Richtlinien zu publizierten Werken geben, diese werden dann sichtbar kenntlich gemacht.[/li]\r\n[li]Sofern nicht anders angegeben ist eine erneute Publikation meiner Werke ohne ausdrückliche Einverständniserklärung meinerseits nicht gestattet.[/li]\r\n[li]Alle nicht von mir stammenden Werke wurden nach geltemdem Recht und Lizenzen verwendet.[/li]\r\n[/ol]\r\n[/li]\r\n[/ol]', 'impressum', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `ID` smallint(6) NOT NULL,
  `news_id` smallint(6) NOT NULL,
  `tag` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`ID`, `news_id`, `tag`) VALUES
(37, 0, 'Testing'),
(26, 1, 'Hello'),
(28, 1, 'sample'),
(27, 1, 'world'),
(4, 6, 'roadtrip'),
(22, 15, 'fur'),
(21, 15, 'sunrise'),
(36, 16, 'Demo'),
(31, 17, 'vegas'),
(33, 18, 'bla'),
(38, 19, 'Testing'),
(60, 26, 'Tag'),
(59, 26, 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `ID` smallint(6) NOT NULL,
  `Name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `Password` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `Rights` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `regDate` datetime NOT NULL,
  `Contactmail` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `Clearname` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `About` text COLLATE utf8_unicode_ci NOT NULL,
  `cmtNotify` tinyint(1) NOT NULL DEFAULT '1',
  `Website` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `Name`, `Password`, `Rights`, `Email`, `regDate`, `Contactmail`, `Clearname`, `About`, `cmtNotify`, `Website`) VALUES
(10002, 'admin', 'C7AD44CBAD762A5DA0A452F9E854FDC1E0E7A52A38015F23F3EAB1D80B931DD472634DFAC71CD34EBC35D16AB7FB8A90C81F975113D6C7538DC69DD8DE9077EC', 'admin', 'admin@beusterse.local', '2016-09-26 23:42:09', '', 'Felix Beuster', '', 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `downcats`
--
ALTER TABLE `downcats`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kommentare`
--
ALTER TABLE `kommentare`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`ID`);
ALTER TABLE `news` ADD FULLTEXT KEY `Inhalt` (`Inhalt`);

--
-- Indexes for table `newscat`
--
ALTER TABLE `newscat`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `newscatcross`
--
ALTER TABLE `newscatcross`
  ADD PRIMARY KEY (`NewsID`,`Cat`,`CatID`);

--
-- Indexes for table `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `snippets`
--
ALTER TABLE `snippets`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `static_pages`
--
ALTER TABLE `static_pages`
  ADD PRIMARY KEY (`url`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `news_id` (`news_id`,`tag`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `downcats`
--
ALTER TABLE `downcats`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `ID` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `kommentare`
--
ALTER TABLE `kommentare`
  MODIFY `ID` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `ID` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `newscat`
--
ALTER TABLE `newscat`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `playlist`
--
ALTER TABLE `playlist`
  MODIFY `ID` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `ID` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10003;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
