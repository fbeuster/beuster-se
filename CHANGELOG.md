# Changelog

## 31.01.2014 - v0.9 - Comment class

```
+	classes/comment.php: handles a user
+	classes/databae.php: added $limit to select()
*	includes/news.php: fixed critical bug
*	following files now use ther user class:
*		index.php
*		includes/newsone.php
*		settings/generators.php
```

## 31.01.2014 - v0.8 - User class

```
+	index.php: added section to load classes
+	classes/user.php: handles a user
*	following files now use ther user class:
*		includes/aboutMod.php
*		includes/news.php
*		includes/newsone.php
*		settings/generators.php
*		settings/modules.php
*		theme/beuster-se-2013/aboutMod.php
*		theme/beuster-se-2013/news.php
*		theme/default/aboutMod.php
*		theme/default/news.php
```

## 30.03.2014 - v0.7 - Database

```
*	classes/database.php: moved from settings/
+	classes/database.php: added select()
*	settings/functions.php: migrated getCmt() to new database select
*	settings/functions.php: migrated getNewsPicsNumber() to new database select
```

## 29.03.2014 - v0.6.1 - Bugfixes

```
*	includes/newsone.php: fixed fetch bug for new user check
*	includes/newsone.php: fixed info bug
*	settings/function.php: fixed template bug in showInfo()
*	settings/functionNews.php: fixed local check bug in notifyAdmin()
```

## 28.03.2014 - v0.6 - Comment, Database, Clean Up

```
*	comment authors now saved in users db table (instant of redundant in comments)
+	implemented database class to get rid of carrying $db across
+	added theme folder for themes
*	moved template system to theme/
+	added default theme (without styles for now)
*	moved theme related images into theme folder
*	general clean up
*	some bug fixing
```

## 07.03.2014 - v0.5 - Flattr Button

```
+	settings/modules.php: added moduleDonate() for flattr
+	styles/main.css: new styles flattr box
+	templates/htmlaside.tpl: insert moduleDonate();
```

## 05.03.2014 - v0.4.2 - Search Fixes

```
+	styles/main.css: new styles for search info text
*	templates/search.tpl: fixed pager positioning bug
```

## 04.03.2014 - v0.4.1 - ASIN Fix

```
*	settings/parser.php: fixed removing of \[asin=\]\[/asin\]
```

## 04.03.2014 - v0.4 - Added ASIN-Button

```
+	images/sprite.png: added button for affiliates
+	settings/main.js: added affiliate button
+	settings/parser.php: added parsing for new \[asin=\]\[/asin\]
*	styles/main.css: updated sprite positions
+	templates/newsbea.tpl: added button for affiliates
+	templates/newsneu.tpl: added button for affiliates
```

## 03.03.2014 - v0.3.1 - Responsive Fixes

```
*	styles/main.css: fixed .beContentEntry display for single articles
*	styles/main.css: adjusted some margins for .beMainAside
```

## 02.03.2014 - v0.3 - Comment Reply

```
*	images/commentReply.png: updated with cancel graphics
+	settings/generators.php: added reset button in genFormPublic()
+	settings/main.js: added reply cancel button and logic
+	styles/main.css: added styles for reset and cancel button
```

## 01.03.2014 - v0.2 - Code Embadding

```
*	settings/parser.php: code parsing now works line by line
*	settings/parser.php: code parsing now works in one method
-	settings/parser.php: ArticleParser::codeSecondPass() not needed
*	settings/parser.php: ArticleParser::codeFirstPass() renamed ArticleParser::parseCode()
*	styles/main.css: updated code highlighting
```

## 22.02.2014 - v0.1.1 - Readme

```
+	README.md
```

## 22.02.2014 - v0.1 - Initial commit