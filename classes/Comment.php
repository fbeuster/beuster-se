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

	const ENA_DISABLED = 0;
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
	private $notifications;

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

	public static function delete($id) {
		# TODO
		# comments can be threaded, so should the delete of one
		# trigger delete of others?
		# maybe mark as deleted?
		$db 	= Database::getDB();
		$cond = array('id = ?', 'i', array($id));

		return $db->delete('comments', $cond);
	}

	public static function disable($id) {
		return self::updateEnable($id, self::ENA_DISABLED);
	}

	public static function enable($id) {
		return self::updateEnable($id, self::ENA_DEFAULT);
	}

  public static function exists($id) {
    $db = Database::getDB();

    $fields = array('content');
    $conds  = array('id = ?', 'i', array($id));
    $res    = $db->select('comments', $fields, $conds);

    return count($res) == 1;
  }

  public static function getIdFromHash($hash) {
    $db = Database::getDB();

    $fields = array('id');
    $conds  = array('MD5(id) = ?', 's', array($hash));
    $res    = $db->select('comments', $fields, $conds);

    if (count($res) == 1) {
    	return $res[0]['id'];
    }

    return -1;
  }

  private static function updateEnable($id, $status) {
    $con = Database::getDB()->getCon();
    $sql = 'UPDATE
              comments
            SET
              enabled = ?
            WHERE
              id = ?';
    $stmt = $con->prepare($sql);

    if (!$stmt) {
      return $con->error;
    }

    $stmt->bind_param('ii', $status, $id);
    if (!$stmt->execute()) {
      return $stmt->error;
    }

    $stmt->close();
  }

	/**
	 * Load replies.
	 *
	 * Based on the actual comment, this function loads all replies.
	 */
	public function loadReplies() {
		$fields = array('id');
		$conds = array('parent_comment_id = ?', 'i', array($this->id));
		$options = 'ORDER BY date DESC';
		$res = Database::getDB()->select('comments', $fields, $conds);
		foreach ($res as $rep) {
			$this->replies[] = new Comment($rep['id']);
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

	public function notificationsEnabled() {
		return $this->notifications;
	}

	/*** GET / SET ***/

	/**
	 * getter for comment id
	 * @return int
	 */
	public function getId() { return $this->id; }

	public function getLink() {
		$db 		= Database::getDB();
		$fields = array('article_id');
		$conds  = array('id = ?', 'i', array($this->id));
		$aid    = $db->select('comments', $fields, $conds);

		if (count($aid) != 1) {
			return '';
		}

		$article = new Article($aid[0]['article_id']);

		return $article->getLink().'#comment'.$this->id;
	}

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
	 * getter for comment article id
	 * @return int
	 */
	public function getNewsId() { return $this->article_id; }

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
	 * setter for comment article id
	 * @param int $newsId The comment article id
	 */
	public function setNewsId($article_id) { $this->article_id = $article_id; }

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
		$fields = array('user_id', 'content', 'UNIX_TIMESTAMP(date) AS timestamp',
										'article_id', 'enabled', 'parent_comment_id', 'notifications');
		$conds = array('id = ?', 'i', array($this->id));
		$res = Database::getDB()->select('comments', $fields, $conds);
		if(count($res) != 1)
			return;
		$this->setDate($res[0]['timestamp']);
		$this->setAuthor(User::newFromId($res[0]['user_id']));
		$this->setEnable($res[0]['enabled']);
		$this->setNewsId($res[0]['article_id']);
		$this->setContent($res[0]['content']);
		$this->setParentId($res[0]['parent_comment_id']);

		$this->notifications = $res[0]['notifications'];
		$this->loaded = true;
	}
}

?>