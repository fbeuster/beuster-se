<?php
/**
 * \file classes/user.php
 * \brief Class for a general User
 */

/**
 * Class for a general User.
 * \class User
 * \author Felix Beuster
 *
 * providing a user class as well as helpfull and required funcionts
 */
class User {

	const BY_ID = 1;	/**< referenced by id */
	const BY_MAIL = 2;	/**< referenced by mail */
	const BY_NAME = 3;	/**< referenced by name */

	private $by = null;			/**< referenced by */
	private $loaded = false;	/**< user loaded? */

	private $id; 		/**< user's id */
	private $mail; 		/**< user's mail */
	private $name; 		/**< user's name */
	private $rights; 	/**< user's rights */
	private $website; 	/**< user's website */
	private $clearname; /**< user's clear name */

	/**
	 * construcotr
	 *
	 * @param int $by Referenced by constant, us constant from User
	 * @return type
	 */
	public function __construct($by) {
		$this->by = $by;
	}

	/*** PUBLIC ***/

	public static function isAuthor($name) {
		$author = User::newFromName($name);

		if ($author == null) {
			return false;
		}

		$db 		= Database::getDB();
		$fields = array('COUNT(`ID`) AS articles');
		$conds 	= array('Autor = ?', 'i', array($author->getId()));
		$res 		= $db->select('news', $fields, $conds);

		if (count($res)) {
			return $res[0]['articles'] > 0;
		}

		return false;
	}

	/**
	 * User from id.
	 *
	 * construct new user from id
	 *
	 * @param int $id the user id
	 * @return User
	 */
	public static function newFromId($id) {

		// if no valid id, set it 0
		if(!is_int($id) || $id <= 0)
			$id = 0;

		// new
		$u = new self(self::BY_ID);
		$u->setId($id);
		$u->loadUser();
		return $u;
	}

	/**
	 * User from mail.
	 *
	 * construct new user from mail adress
	 *
	 * @param string $mail the mail adress
	 * @return User
	 */
	public static function newFromMail($mail) {

		// if no valid mail, set it ''
		if(!is_string($mail))
			$mail = '';

		// new
		$u = new self(self::BY_MAIL);
		$u->setMail($mail);
		$u->loadUser();
		return $u;
	}

	/**
	 * User from name.
	 *
	 * construct new user from user name (login name)
	 *
	 * @param string $name the login name
	 * @return User
	 */
	public static function newFromName($name) {

		// if no valid mail, set it ''
		if(!is_string($name))
			$name = '';

		// new
		$u = new self(self::BY_NAME);
		$u->setName($name);
		$u->loadUser();

		if ($u->isLoaded()) {
			return $u;
		}

		return null;
	}

	public static function newFromCookie() {
    if (!isset($_COOKIE['user_id'], $_COOKIE['password']))
      return null;

    $u = new self(self::BY_ID);
    $u->setID($_COOKIE['user_id']);
    $u->loadUser();

    if(!$u->checkPassword($_COOKIE['password']))
      return null;

    return $u;
	}

	public function checkPassword($password) {
		$fields = array('Password');
		$conds 	= array('ID = ?', 'i', array($this->id));
		$res 		= Database::getDB()->select('users', $fields, $conds);

		if(count($res) !== 1)
			return false;

		return $password === $res[0]['Password'];
	}

	/**
	 * Loads a user.
	 *
	 * loading user based by reference variable $by
	 */
	public function loadUser() {

		$fields = array('ID, Email, Name, Rights, Clearname, Website');
		switch ($this->by) {
			case self::BY_ID:
				$conds = array('ID = ?', 'i', array($this->id));
				break;
			case self::BY_MAIL:
				$conds = array('Email = ?', 's', array($this->mail));
				break;
			case self::BY_NAME:
				$conds = array('name = ?', 's', array($this->name));
				break;
			default:
				return;
		}
		$res = Database::getDB()->select('users', $fields, $conds);
		if(count($res) !== 1)
			return;
		$this->setId($res[0]['ID']);
		$this->setMail($res[0]['Email']);
		$this->setName($res[0]['Name']);
		$this->setRights($res[0]['Rights']);
		$this->setWebsite($res[0]['Website']);
		$this->setClearname($res[0]['Clearname']);
		$this->loaded = true;
	}

	/**
	 * User about text (html).
	 *
	 * get and build the user's about text to html
	 *
	 * @return String
	 */
	public function buildInfo() {
		return str_replace(
			'[contactmail]',
			'</p><address>'.str_replace('@', ' [at] ', $this->getUserInfo('Contactmail')).'</address><p>',
			Parser::parse($this->getUserInfo('About'), Parser::TYPE_CONTENT));
	}

	/**
	 * Single user info.
	 *
	 * gets a siingle user information from database
	 *
	 * @param String $info the database column to be requested
	 * @return String
	 */
	public function getUserInfo($info) {
		if(!$this->loaded) {
			return '';
		} else {
			$rinfo = Database::getDB()->select('users', array($info), array('ID = ?', 'i', array($this->id)));
			return $rinfo[0][$info];
		}
	}

	/**
	 * Admin right check.
	 *
	 * is the current user an admin
	 *
	 * @return bool
	 */
	public function isAdmin() {
		return $this->rights === 'admin';
	}

	/**
	 * User load status.
	 *
	 * is a user loaded from db?
	 *
	 * @return bool
	 */
	public function isLoaded() {
		return $this->loaded;
	}

	/**
	 * Refresh existing cookies.
	 *
	 * @param String $pass set a new password (optional)
	 */
  public function refreshCookies($pass = ''){
    if ($pass == '') {
      $password_hash = $_COOKIE['password'];

    } else {
      $password_hash = $pass;
    }

    setcookie('user_id', $this->id, strtotime("+1 day"), '/');
    setcookie('password', $password_hash, strtotime("+1 day"), '/');
  }

	/*** GET / SET ***/

	public function getId() { return $this->id; }				/**< get id */
	public function getMail() { return $this->mail; }			/**< get mail */
	public function getName() { return $this->name; }			/**< get name */
	public function getRights() { return $this->rights; }		/**< get rights */
	public function getWebsite() { return $this->website; }		/**< get website */
	public function getClearname() { return $this->clearname; }	/**< get clearname */

	public function setId($id) { $this->id = $id; }								/**< set id */
	public function setMail($mail) { $this->mail = $mail; }						/**< set mail */
	public function setName($name) { $this->name = $name; }						/**< set name */
	public function setRights($rights) { $this->rights = $rights; }				/**< set rights */
	public function setWebsite($website) { $this->website = $website; }			/**< set website */
	public function setClearname($clearname) { $this->clearname = $clearname; }	/**< set clearname */
}

?>