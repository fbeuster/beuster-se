<?php

/**
 * Holding an Article.
 * \file classes/article.php
 */

/**
 * Holding an Article.
 * 
 * \class Article
 * \author Felix Beuster
 * 
 * Holding an Article and its Comment s, as well as some handling functions.
 */
class Article {

	private $loaded = false;	/**< load state */
	private $dev = false;		/**< dev state */

	private $id;				/**< article's id */
	private $enable;			/**< article's enable status */
	private $title;				/**< article's title */
	private $author;			/**< article's author */
	private $date;				/**< article's date */
	private $content;			/**< article's content */
	private $projState;			/**< article's project state */
	private $tags = array();	/**< article's tags */
	private $category;			/**< article's category */
	private $thumbnail = null;			/**< article's thumbnail */
	private $playlist = false;			/**< playlist state */

	private $comments = array();	/**< article comments */
	private $pagesCmt;				/**< article comment pages*/
	private $startCmt;				/**< article comment pages start */

	/**
	 * constructor
	 * 
	 * @param int $id The id of the Article
	 * @param int $dev Enable status which should be loaded
	 */
	public function __construct($id, $dev = false) {
		$this->id = $id;
		$this->dev = $dev;
		$this->loadArticle();
	}

	/*** PUBLIC ***/

	/**
	 * check load state
	 * @return bool
	 */
	public function isLoaded() { return $this->loaded; }

	/**
	 * check playlist state
	 * @return bool
	 */
	public function isPlaylist() { return $this->playlist; }
	
	/**
	 * check tag existence
	 * @return bool
	 */
	public function hasTags() { return !empty($this->tags); }
	
	/**
	 * add a tag to tags
	 * @param string $tag to set
	 */
	public function addTag($tag) { $this->tags[] = $tag; }
	
	/**
	 * add a Comment to comments
	 * @param Comment $comments to set
	 */
	public function addComment($comment) { $this->comments[] = $comment; }

	/**
	 * get the article link
	 * @return string
	 */
	public function getLink() {
        $removes = '#?|().,;:{}[]/';
        $strokes = array(' ', '---', '--');
        for($i = 0; $i < strlen($removes); $i++) {
            $title = str_replace($removes[$i], '', $this->title);
        }
        foreach($strokes as $char) {
            $title = str_replace($char, '-', $title);
        }
		return '/'.$this->id.'/'.lowerCat($this->category).'/'.replaceUml($title);;
	}

	/*** GETTER / SETTER ***/

	/**
	 * getter for id
	 * @return int
	 */
	public function getId() { return $this->id; }
	
	/**
	 * setter for id
	 * @param int $id to set
	 */
	public function setId($id) { $this->id = $id; }
	
	/**
	 * getter for enable
	 * @return int
	 */
	public function getEnable() { return $this->enable; }
	
	/**
	 * setter for enable
	 * @param int $enable to set
	 */
	public function setEnable($enable) { $this->enable = $enable; }
	
	/**
	 * getter for title
	 * @return string
	 */
	public function getTitle() { return $this->title; }
	
	/**
	 * setter for title
	 * @param string $title to set
	 */
	public function setTitle($title) { $this->title = $title; }

	/**
	 * getter for author
	 * @return User
	 */
	public function getAuthor() { return $this->author; }
	
	/**
	 * setter for author
	 * @param User $author to set
	 */
	public function setAuthor($author) { $this->author = $author; }
	
	/**
	 * getter for date
	 * @return int
	 */
	public function getDate() { return $this->date; }
	
	/**
	 * setter for date
	 * @param int $date to set
	 */
	public function setDate($date) { $this->date = $date; }
	
	/**
	 * getter for content
	 * @return string
	 */
	public function getContent() { return $this->content; }
	
	/**
	 * getter for parsed content
	 * @return string
	 */
	public function getContentParsed() {
		global $mob;
		if('[yt]' == substr($this->content,0,4))
        	$preApp = '<p style="text-indent:0;">';
        else
        	$preApp = '<p>';
		return $preApp.grabImages(changetext($this->content, 'inhalt', $mob)).'</p>';
	}
	
	/**
	 * getter for preview content
	 * @return string
	 */
	public function getContentPreview() {
		global $mob;
		return changetext($this->content, 'vorschau', $mob);
	}
	
	/**
	 * setter for content
	 * @param string $content to set
	 */
	public function setContent($content) { $this->content = $content; }
	
	/**
	 * getter for projState
	 * @return int
	 */
	public function getProjState() { return $this->projState; }
	
	/**
	 * setter for projState
	 * @param int $projState to set
	 */
	public function setProjState($projState) { $this->projState = $projState; }
	
	/**
	 * getter for tags
	 * @return array(string)
	 */
	public function getTags() { return $this->tags; }
	
	/**
	 * getter for tags
	 * @return string
	 */
	public function getTagsString() {
		return implode(', ', $this->tags);
		//return $this->tags;
	}
	
	/**
	 * setter for tags
	 * @param array(string) $tags to set
	 */
	public function setTags($tags) { $this->tags = $tags; }
	
	/**
	 * getter for category
	 * @return string
	 */
	public function getCategory() { return $this->category; }
	
	/**
	 * setter for category
	 * @param string $category to set
	 */
	public function setCategory($category) { $this->category = $category; }

	/**
	 * getter for thumbnail
	 * @return string|Image
	 */
	public function getThumbnail() { return $this->thumbnail; }
	
	/**
	 * setter for thumbnail
	 * @param Image $thumbnail to set
	 */
	public function setThumbnail($thumbnail) { $this->thumbnail = $thumbnail; }
	
	
	/**
	 * getter for comments
	 * @return array(Comment)
	 */
	public function getComments() { return $this->comments; }
	
	/**
	 * setter for comments
	 * @param int $comments to set
	 */
	public function setComments($comments) { $this->comments = $comments; }
	
	/**
	 * getter for pagesCmt
	 * @return int
	 */
	public function getPagesCmt() { return $this->pagesCmt; }
	
	/**
	 * setter for pagesCmt
	 * @param int $pagesCmt to set
	 */
	public function setPagesCmt($pagesCmt) { $this->pagesCmt = $pagesCmt; }
	
	/**
	 * getter for startCmt
	 * @return int
	 */
	public function getStartCmt() { return $this->startCmt; }
	
	/**
	 * setter for startCmt
	 * @param int $startCmt to set
	 */
	public function setStartCmt($startCmt) { $this->startCmt = $startCmt; }
	

	/*** PRIVATE ***/

	/**
	 * Loads the Article.
	 * 
	 * Based on current id, this loads the article.
	 */
	private function loadArticle() {
		global $sysAdrr;

		// article itself

		$fields = array('Titel', 'Autor',  'Inhalt', 'UNIX_TIMESTAMP(Datum) AS Date', 'Status');
		if($this->dev)
			$conds = array('ID = ?', 'i', array($this->id));
		else
			$conds = array('ID = ? AND enable = ?', 'ii', array($this->id, 1));
		$db = Database::getDB();
		$res = $db->select('news', $fields, $conds);

		if(count($res) != 1)
			return;

		$this->title 		= $res[0]['Titel'];
		$this->author 		= User::newFromId($res[0]['Autor']);
		$this->content 		= $res[0]['Inhalt'];
		$this->date 		= $res[0]['Date'];
		$this->projState 	= $res[0]['Status'];

		// category

		$fields = array('Cat');
		$conds = array('NewsID = ?', 'i', array($this->id));
		$res = $db->select('newscatcross', $fields, $conds);

		if(count($res) != 1)
			return;
		$this->category = getCatName($res[0]['Cat']);

		// comments

		if(isset($_GET['page']))
	        $this->startCmt = (int)$_GET['page'];
	    else 
	        $this->startCmt = 1;
		$nCmt = getCmt($this->id);
		$this->pagesCmt = getPages($nCmt, 10, $this->startCmt);

		$comments = Database::getDB()->select(
				        'kommentare',
				        array('ID'),
				        array('NewsID = ? AND ParentID = -1', 'i', array($this->id)),
				        'ORDER BY Datum DESC',
				        array('LIMIT ?, 10', 'i', array(getOffset($nCmt, 10, $this->startCmt))));
	    foreach ($comments as $k => $comment) {
	        $comment = new Comment($comment['ID']);
	        $comment->loadReplies();
	        $this->comments[] = $comment;
	    }

	    // tags

		$fields = array('tag');
		$conds = array('news_id = ?', 'i', array($this->id));
		$res = $db->select('tags', $fields, $conds);

		foreach ($res as $tag) {
			$this->tags[] = $tag['tag'];
		}

		// thumbnail

		$fields = array('ID');
		$conds = array('NewsID = ? AND Thumb = ?', 'ii', array($this->id, 1));
		$res = $db->select('pics', $fields, $conds);
        
        if(count($res) == 1)
        	$this->thumbnail = new Image($res[0]['ID']);

        // check playlist entry

        $playlistID = getPlaylistID(getNewsCat($this->id));
        if($playlistID !== false) {
            $videoID = getYouTubeIDFromArticle($this->id);
            $path = 'images/tmp/'.$playlistID.'-'.$videoID;
            $this->thumbnail = 'http://'.$sysAdrr.'/'.$path.'.jpg';
            $this->playlist = true;
        }

		$this->loaded = true;
	}
}