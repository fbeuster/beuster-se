# Changelog

## 06.06.2014 - v0.13 - Categroy work

~~~~~
+	classes/Category.php: added newFromName()
+	classes/Category.php: added getNameUrlStatic()
+	classes/Category.php: added isLoaded()
+	classes/Category.php: added isTopCategory()
+	classes/Category.php: added isPortfolio()
*	classes/Category.php: refactored $a in getNameUrl()
*	classes/Category.php: rewritten isCategoryName()
*	includes/news.php: make more use of Category
~~~~~

## 10.05.2014 - v0.12 - Categroy and Lixter class

~~~~~
+	classes/Category.php: new class to hold categories
+	classes/Lixter.php: new main class
*	index.php: make use of Lixter class
*	following files now use Category:
*		includes/news.php
*		themes/beusterse-2013/htmlaside.php
*		themes/beusterse-2013/news.php
*		themes/beusterse-2013/playlist.php
*		themes/default/htmlaside.php
*		themes/default/news.php
*		themes/default/playlist.php
*	themes/beusterse-2013/playlist.php: bugfixing for Article usage
*	themes/default/playlist.php: bugfixing for Article usage
*	renamed:
*		article.php > Article.php
*		comment.php > Comment.php
*		database.php > Database.php
*		image.php > Image.php
*		parser.php > Parser.php
*		user.php > User.php
~~~~~

## 08.05.2014 - v0.11 - Article and Image Class

~~~~~
+	classes/article.php: new class to hold an article
*	classes/comment.php: tweaked documentation
*	classes/database.php: fixed bug for joining tables
+	classes/image.php: new class to hold an article image
*	settings/functionsPage.php: getPageType() now uses term 'article' instead of 'news'
*	settings/generators.php: bug fixing in generatePager()
*	settings/parser.php: moved to classes/parser.php
+	index.php: added article, image, parser to load routine
*	following files now use the article class:
*		includes/news.php
*		includes/newsone.php
*		themes/beusterse-2013/htmlheader.php
*		themes/beusterse-2013/news.php
*		themes/beusterse-2013/newsone.php
*		themes/default/htmlheader.php
*		themes/default/news.php
*		themes/default/newsone.php
~~~~~

## 03.04.2014 - v0.10 - Documentation

~~~~~
*	Changelog.md: changed code markup to match doxygen
+	Doxyfile: doxygen settings
*	filled documentation  for
*		classes/comment.php
*		classes/database.php
*		classes/user.php
*		settings/parser.php
*	.gitignore: added documentation/ to ignores
	Theme beuster{se} 2013
*		styles/main.css: fixed header font bug
~~~~~

## 31.03.2014 - v0.9 - Comment class

~~~~~
+	classes/comment.php: handles a user
+	classes/databae.php: added $limit to select()
*	includes/news.php: fixed critical bug
*	following files now use the comment class:
*		index.php
*		includes/newsone.php
*		settings/generators.php
~~~~~

## 31.03.2014 - v0.8 - User class

~~~~~
+	index.php: added section to load classes
+	classes/user.php: handles a user
*	following files now use the user class:
*		includes/aboutMod.php
*		includes/news.php
*		includes/newsone.php
*		settings/generators.php
*		settings/modules.php
*		theme/beuster-se-2013/aboutMod.php
*		theme/beuster-se-2013/news.php
*		theme/default/aboutMod.php
*		theme/default/news.php
~~~~~

## 30.03.2014 - v0.7 - Database

~~~~~
*	classes/database.php: moved from settings/
+	classes/database.php: added select()
*	settings/functions.php: migrated getCmt() to new database select
*	settings/functions.php: migrated getNewsPicsNumber() to new database select
~~~~~

## 29.03.2014 - v0.6.1 - Bugfixes

~~~~~
*	includes/newsone.php: fixed fetch bug for new user check
*	includes/newsone.php: fixed info bug
*	settings/function.php: fixed template bug in showInfo()
*	settings/functionNews.php: fixed local check bug in notifyAdmin()
~~~~~

## 28.03.2014 - v0.6 - Comment, Database, Clean Up

~~~~~
*	comment authors now saved in users db table (instant of redundant in comments)
+	implemented database class to get rid of carrying $db across
+	added theme folder for themes
*	moved template system to theme/
+	added default theme (without styles for now)
*	moved theme related images into theme folder
*	general clean up
*	some bug fixing
~~~~~

## 07.03.2014 - v0.5 - Flattr Button

~~~~~
+	settings/modules.php: added moduleDonate() for flattr
+	styles/main.css: new styles flattr box
+	templates/htmlaside.tpl: insert moduleDonate();
~~~~~

## 05.03.2014 - v0.4.2 - Search Fixes

~~~~~
+	styles/main.css: new styles for search info text
*	templates/search.tpl: fixed pager positioning bug
~~~~~

## 04.03.2014 - v0.4.1 - ASIN Fix

~~~~~
*	settings/parser.php: fixed removing of \[asin=\]\[/asin\]
~~~~~

## 04.03.2014 - v0.4 - Added ASIN-Button

~~~~~
+	images/sprite.png: added button for affiliates
+	settings/main.js: added affiliate button
+	settings/parser.php: added parsing for new \[asin=\]\[/asin\]
*	styles/main.css: updated sprite positions
+	templates/newsbea.tpl: added button for affiliates
+	templates/newsneu.tpl: added button for affiliates
~~~~~

## 03.03.2014 - v0.3.1 - Responsive Fixes

~~~~~
*	styles/main.css: fixed .beContentEntry display for single articles
*	styles/main.css: adjusted some margins for .beMainAside
~~~~~

## 02.03.2014 - v0.3 - Comment Reply

~~~~~
*	images/commentReply.png: updated with cancel graphics
+	settings/generators.php: added reset button in genFormPublic()
+	settings/main.js: added reply cancel button and logic
+	styles/main.css: added styles for reset and cancel button
~~~~~

## 01.03.2014 - v0.2 - Code Embadding

~~~~~
*	settings/parser.php: code parsing now works line by line
*	settings/parser.php: code parsing now works in one method
-	settings/parser.php: ArticleParser::codeSecondPass() not needed
*	settings/parser.php: ArticleParser::codeFirstPass() renamed ArticleParser::parseCode()
*	styles/main.css: updated code highlighting
~~~~~

## 22.02.2014 - v0.1.1 - Readme

~~~~~
+	README.md
~~~~~

## 22.02.2014 - v0.1 - Initial commit