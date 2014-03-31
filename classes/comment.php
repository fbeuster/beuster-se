<?php

/**
 * \file comment.php
 * \brief Comment
 */

/**
 * \brief comment class
 * 
 * Holding a single comments, it's replies and provide handling functions
 */
class Comment {

	const ENA_DEFAULT = 1;
	const ENA_ADMIN = 2;

	private $loaded = false;

	private $id;
	private $date;
	private $author;
	private $enable;
	private $newsId;
	private $content;
	private $parentId;

	private $replies = array();

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
	 * \brief load replies
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
	 * \brief has comment replies?
	 * 
	 * Just checks, weather the comment has replies or not
	 * 
	 * @return bool
	 */
	public function hasReplies() {
		return count($this->replies) > 0;
	}

	/*** GET / SET ***/

	public function getId() { return $this->id; }
	public function getDate() { return $this->date; }
	public function getAuthor() { return $this->author; }
	public function getEnable() { return $this->enable; }
	public function getNewsId() { return $this->newsId; }
	public function getContent() { return $this->content; }
	public function getReplies() { return $this->replies; }
	public function getParentId() { return $this->parentId; }
	public function getContentParsed() {
		global $mob;
		return changetext($this->content, 'cmtInhalt', $mob);
	}

	public function setDate($date) { $this->date = $date; }
	public function setAuthor($author) { $this->author = $author; }
	public function setEnable($enable) { $this->enable = $enable; }
	public function setNewsId($newsId) { $this->newsId = $newsId; }
	public function setContent($content) { $this->content = $content; }
	public function setParentId($parentId) { $this->parentId = $parentId; }

	/*** PRIVATE ***/

	/**
	 * \brief load comment
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