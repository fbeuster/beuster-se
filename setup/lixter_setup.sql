SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `api_tokens`
--

CREATE TABLE `api_tokens` (
  `token` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `extra` text CHARACTER SET utf8 COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `author` smallint(6) NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `created` datetime NOT NULL,
  `public` tinyint(1) NOT NULL,
  `hits` int(11) NOT NULL,
  `edited` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `author`, `title`, `content`, `created`, `public`, `hits`, `edited`) VALUES
(1, 10001, 'Hello world!', 'We are live, this is your first article!', '2015-12-02 00:12:54', 1, 0, '2015-12-02 00:12:54');


-- --------------------------------------------------------

--
-- Table structure for table `article_attachments`
--

CREATE TABLE `article_attachments` (
  `article_id` smallint(6) NOT NULL,
  `attachment_id` tinyint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `article_categories`
--

CREATE TABLE `article_categories` (
  `article_id` smallint(6) NOT NULL,
  `category_id` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `article_categories`
--

INSERT INTO `article_categories` (`article_id`, `category_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `article_images`
--

CREATE TABLE `article_images` (
  `article_id` smallint(6) NOT NULL DEFAULT '0',
  `image_id` smallint(6) NOT NULL DEFAULT '0',
  `is_thumbnail` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` smallint(6) NOT NULL,
  `file_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `file_path` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `downloads` smallint(6) NOT NULL,
  `license` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL,
  `version` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` smallint(6) NOT NULL,
  `name` varchar(64) CHARACTER SET utf8 NOT NULL,
  `parent_category_id` smallint(6) NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_category_id`, `type`, `description`) VALUES
(1, 'Blog', 0, 0, 'Default category');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` smallint(6) NOT NULL,
  `user_id` smallint(6) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `article_id` smallint(6) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `parent_comment_id` smallint(6) NOT NULL,
  `notifications` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `content`, `date`, `article_id`, `enabled`, `parent_comment_id`, `notifications`) VALUES
(1, 10001, 'First comment', '2015-12-02 00:12:54', 1, 2, -1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE `configuration` (
  `option_set` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `option_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `option_value` varchar(128) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` (`option_set`, `option_name`, `option_value`) VALUES
('dev', 'debug', '0'),
('dev', 'dev_server_address', 'beusterse.local'),
('dev', 'remote_server_address', 'beusterse.de'),
('ext', 'amazon_tag', 'beustersede-21'),
('ext', 'google_adsense_ad', '<!-- beusterseBanner --> <ins class=\"adsbygoogle\"      style=\"display:block\"      data-ad-client=\"ca-pub-4132935023049723\"      data-ad-slot=\"4340633755\"      data-ad-format=\"auto\"></ins>'),
('ext', 'google_analytics', 'UA-1710454-3'),
('meta', 'mail', 'info@beusterse.de'),
('meta', 'name', 'beuster{se}'),
('meta', 'title', 'Blog, Tipps und Videos'),
('search', 'case_sensitive', '0'),
('search', 'marks', 'on'),
('site', 'category_page_length', '8'),
('site', 'language', 'de'),
('site', 'theme', 'beuster-se-2016'),
('site', 'url_schema', '1');

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE `images` (
  `id` smallint(6) NOT NULL,
  `caption` text COLLATE utf8_unicode_ci NOT NULL,
  `file_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `upload_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

CREATE TABLE `playlist` (
  `id` smallint(6) NOT NULL,
  `playlist_id` varchar(64) CHARACTER SET utf8 NOT NULL,
  `category_id` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `static_pages`
--

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
('Imprint', 'Your imprint here.', 'imprint', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` smallint(6) NOT NULL,
  `article_id` smallint(6) NOT NULL,
  `tag` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

CREATE TABLE `users` (
  `id` smallint(6) NOT NULL,
  `username` varchar(64) CHARACTER SET utf8 NOT NULL,
  `password_hash` varchar(128) CHARACTER SET utf8 NOT NULL,
  `rights` varchar(20) CHARACTER SET utf8 NOT NULL,
  `mail` varchar(128) CHARACTER SET utf8 NOT NULL,
  `registered` datetime NOT NULL,
  `contact_mail` varchar(128) CHARACTER SET utf8 NOT NULL,
  `screen_name` varchar(128) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `website` varchar(128) CHARACTER SET utf8 NOT NULL,
  `profile_image` text CHARACTER SET utf8 NOT NULL,
  `token` varchar(128) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `rights`, `mail`, `registered`, `contact_mail`, `screen_name`, `description`, `website`, `profile_image`, `token`) VALUES
(10002, 'admin', 'empty', 'admin', 'admin@mail.com', 'NOW()', '', 'Admin', '', '', '', ''),

-- --------------------------------------------------------

--
-- Indexes for table `api_tokens`
--
ALTER TABLE `api_tokens`
  ADD PRIMARY KEY (`token`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `articles` ADD FULLTEXT KEY `Inhalt` (`content`);
ALTER TABLE `articles` ADD FULLTEXT KEY `InhaltTitel` (`title`,`content`);
ALTER TABLE `articles` ADD FULLTEXT KEY `Titel` (`title`);

--
-- Indexes for table `article_attachments`
--
ALTER TABLE `article_attachments`
  ADD PRIMARY KEY (`article_id`,`attachment_id`);

--
-- Indexes for table `article_categories`
--
ALTER TABLE `article_categories`
  ADD PRIMARY KEY (`article_id`,`category_id`);

--
-- Indexes for table `article_images`
--
ALTER TABLE `article_images`
  ADD PRIMARY KEY (`article_id`,`image_id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `configuration`
--
ALTER TABLE `configuration`
  ADD PRIMARY KEY (`option_set`,`option_name`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`id`);

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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `news_id` (`article_id`,`tag`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `article_categories`
--
ALTER TABLE `article_categories`
  MODIFY `article_id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `playlist`
--
ALTER TABLE `playlist`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10002;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
