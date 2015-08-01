<?php

/**
 * Holding an Article comment.
 * \file classes/comment.php
 */

/**
 * Holding an Article comment.
 *
 * \class Comment
 * \author Felix Beuster
 *
 * Holding a single comment, its replies and provide handling functions
 */
class Comment {

	const ENA_DEFAULT = 1;	/**< enabled names of comment */
	const ENA_ADMIN = 2;	/**< enabled names of comment */

	private $loaded = false;	/**< comment loaded? */

	private $id;		/**< comment id */
	private $date;		/**< comment date */
	private $author;	/**< comment author */
	private $enable;	/**< comment enable status */
	private $newsId;	/**< comment news id */
	private $content;	/**< comment content */
	private $parentId;	/**< comment parent id*/

	private $replies = array();	/**< comment replies */

	/**
	 * constructor
	 *
	 * @param int $cid comment id
	 */
	public function __construct($cid) {
		$this->id = $cid;
		$this->loadComment();
	}

	/*** PUBLIC ***/

	/**
	 * Load replies.
	 *
	 * Based on the actual comment, this function loads all replies.
	 */
	public function loadReplies() {
		$fields = array('ID');
		$conds = array('ParentID = ?', 'i', array($this->id));
		$options = 'ORDER BY Datum DESC';
		$res = Database::getDB()->select('kommentare', $fields, $conds);
		foreach ($res as $rep) {
			$this->replies[] = new Comment($rep['ID']);
		}
	}

	/**
	 * Has comment replies.
	 *
	 * Just checks, weather the comment has replies or not
	 *
	 * @return bool
	 */
	public function hasReplies() {
		return count($this->replies) > 0;
	}

	/*** GET / SET ***/

	/**
	 * getter for comment id
	 * @return int
	 */
	public function getId() { return $this->id; }

	/**
	 * getter for comment date
	 * @return int
	 */
	public function getDate() { return $this->date; }

	/**
	 * getter for comment author
	 * @return string
	 */
	public function getAuthor() { return $this->author; }

	/**
	 * getter for comment enable status
	 * @return bool
	 */
	public function getEnable() { return $this->enable; }

	/**
	 * getter for comment news id
	 * @return int
	 */
	public function getNewsId() { return $this->newsId; }

	/**
	 * getter for comment content
	 * @return string
	 */
	public function getContent() { return $this->content; }

	/**
	 * getter for comment replies
	 * @return array(Comment)
	 */
	public function getReplies() { return $this->replies; }

	/**
	 * getter for comment parent id
	 * @return int
	 */
	public function getParentId() { return $this->parentId; }

	/**
	 * getter for comment pares content
	 * @return string
	 */
	public function getContentParsed() {
		return Parser::parse($this->content, Parser::TYPE_COMMENT);
	}

	/**
	 * setter for comment date
	 * @param int $date The comment date as unix timestamp
	 */
	public function setDate($date) { $this->date = $date; }

	/**
	 * setter for comment author
	 * @param string $author The comment authore
	 */
	public function setAuthor($author) { $this->author = $author; }

	/**
	 * setter for comment enable status
	 * @param bool $enable The comment enable status
	 */
	public function setEnable($enable) { $this->enable = $enable; }

	/**
	 * setter for comment news id
	 * @param int $newsId The comment news id
	 */
	public function setNewsId($newsId) { $this->newsId = $newsId; }

	/**
	 * setter for comment content
	 * @param string $content The comment content
	 */
	public function setContent($content) { $this->content = $content; }

	/**
	 * setter for comment parent id
	 * @param int $parentId The comment parent ud
	 */
	public function setParentId($parentId) { $this->parentId = $parentId; }

	/*** PRIVATE ***/

	/**
	 * Loads a comment.
	 *
	 * This loads a comment from database, refrenced by comment id
	 */
	private function loadComment() {
		$fields = array('UID', 'Inhalt', 'UNIX_TIMESTAMP(Datum) AS Date', 'NewsID', 'Frei', 'ParentID');
		$conds = array('ID = ?', 'i', array($this->id));
		$res = Database::getDB()->select('kommentare', $fields, $conds);
		if(count($res) != 1)
			return;
		$this->setDate($res[0]['Date']);
		$this->setAuthor(User::newFromId($res[0]['UID']));
		$this->setEnable($res[0]['Frei']);
		$this->setNewsId($res[0]['NewsID']);
		$this->setContent($res[0]['Inhalt']);
		$this->setParentId($res[0]['ParentID']);
		$this->loaded = true;
	}
}

?>