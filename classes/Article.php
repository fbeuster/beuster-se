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

  private $loaded = false;  /**< load state */

  private $id;        /**< article's id */
  private $enable;      /**< article's enable status */
  private $title;       /**< article's title */
  private $author;      /**< article's author */
  private $date;        /**< article's date */
  private $content;     /**< article's content */
  private $projState;     /**< article's project state */
  private $tags = array();  /**< article's tags */
  private $category;      /**< article's category */
  private $thumbnail = null;      /**< article's thumbnail */
  private $playlist = false;      /**< playlist state */

  private $attachments = array();
  private $comments = array();  /**< article comments */
  private $commentsCount;   /**< article comments count */
  private $gallery = array();
  private $pagesCmt;        /**< article comment pages*/
  private $startCmt;        /**< article comment pages start */

  /**
   * constructor
   *
   * @param int $id The id of the Article
   */
  public function __construct($id) {
    $this->id = $id;
    $this->loadArticle();
  }

  public static function exists($id) {
    $db = Database::getDB();

    $fields = array('content');
    $conds  = array('id = ?', 'i', array($id));
    $res    = $db->select('articles', $fields, $conds);

    return count($res) == 1;
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
    $lb = Lixter::getLix()->getLinkBuilder();

    if ($lb == null) {
      $lb = Api::getApi()->getLinkBuilder();
    }

    if ($lb == null) {
      return '';

    } else {
      return $lb->makeArticleLink($this->id,
                                  $this->getCategory()->getNameUrl(),
                                  $this->title);
    }
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
  public function getEnable() { return $this->public; }

  /**
   * setter for enable
   * @param int $enable to set
   */
  public function setEnable($public) { $this->public = $public; }

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
   * gets and formats the article date
   * @param String $format The target format for the date
   */
  public function getDateFormatted($format) {
    return date($format, $this->date);
  }

  /**
   * getter for content
   * @return string
   */
  public function getContent() { return $this->content; }

  /**
   * getter for decorated content
   * @return string
   */
  public function getContentDecorated() {
    $id = new ImageDecorator($this->getContentParsed());
    $sd = new SnippetDecorator($id->getContent());

    return $sd->getContent();
  }

  /**
   * getter for parsed content
   * @return string
   */
  public function getContentParsed() {
    $preApp = ('[yt]' == substr($this->content,0,4)) ? '<p style="text-indent:0;">' : '<p>';
    return $preApp.Parser::parse($this->content, Parser::TYPE_CONTENT).'</p>';
  }

  /**
   * getter for preview content
   * @return string
   */
  public function getContentPreview($length = Parser::DEFAULT_PREVIEW_LENGTH) {
    return Parser::parse($this->content, Parser::TYPE_PREVIEW, $length);
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
  }

  /**
   * setter for tags
   * @param array(string) $tags to set
   */
  public function setTags($tags) { $this->tags = $tags; }

  /**
   * getter for category
   * @return Category
   */
  public function getCategory() { return new Category($this->category); }

  /**
   * setter for category
   * @param Category $category to set
   */
  public function setCategory($category) { $this->category = $category->getId(); }

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
   * getter for gallery
   * @return array(Image)
   */
  public function getGallery() { return $this->gallery; }


  /**
   * getter for attachments
   * @return array(Image)
   */
  public function getAttachments() { return $this->attachments; }


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
   * getter for comments count
   * @return int
   */
  public function getCommentsCount() { return $this->commentsCount; }

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

    # article itself
    $fields = array('title', 'author',  'content', 'UNIX_TIMESTAMP(created) AS date_created', 'UNIX_TIMESTAMP(edited) AS date_edited', 'public');
    $conds  = array('ID = ?', 'i', array($this->id));

    $db   = Database::getDB();
    $res  = $db->select('articles', $fields, $conds);

    if (count($res) != 1) {
      return;
    }

    $this->title      = $res[0]['title'];
    $this->author     = User::newFromId($res[0]['author']);
    $this->content    = $res[0]['content'];
    $this->date       = $res[0]['date_created'];
    $this->date       = $res[0]['date_edited'];
    $this->public     = $res[0]['public'];

    # category
    $fields = array('category_id');
    $conds  = array('article_id = ?', 'i', array($this->id));
    $res    = $db->select('article_categories', $fields, $conds);

    if (count($res) != 1) {
      return;
    }

    $this->category = $res[0]['category_id'];

    # comments
    if (isset($_GET['page'])) {
      $this->startCmt = (int) $_GET['page'];

    } else {
      $this->startCmt = 1;
    }

    $nCmt = getCmt($this->id);
    $this->pagesCmt = getPages($nCmt, 10, $this->startCmt);

    $fields   = array('id');
    $conds    = array('article_id = ? AND parent_comment_id = -1', 'i', array($this->id));
    $options  = 'ORDER BY date DESC';
    $limit    = array('LIMIT ?, 10', 'i', array(getOffset($nCmt, 10, $this->startCmt)));
    $comments = $db->select('comments', $fields, $conds, $options, $limit);

    foreach ($comments as $k => $comment) {
      $comment = new Comment($comment['id']);
      $comment->loadReplies();
      $this->comments[] = $comment;
    }

    # comments count
    $fields = array('COUNT(id) as count');
    $conds  = array('article_id = ?', 'i', array($this->id));
    $res    = $db->select('comments', $fields, $conds);

    foreach ($res as $key => $value) {
      $this->commentsCount = $value['count'];
    }

    # tags
    $fields = array('tag');
    $conds  = array('article_id = ?', 'i', array($this->id));
    $res    = $db->select('tags', $fields, $conds);

    foreach ($res as $tag) {
      $this->tags[] = $tag['tag'];
    }

    # thumbnail
    $fields = array('image_id');
    $conds  = array('article_id = ? AND is_thumbnail = ?', 'ii', array($this->id, 1));
    $res    = $db->select('article_images', $fields, $conds);

    if (count($res) == 1) {
      $this->thumbnail = new Image($res[0]['image_id']);
    }

    # gallery
    $fields = array('image_id');
    $conds  = array('article_id = ?', 'i', array($this->id));
    $res    = $db->select('article_images', $fields, $conds);

    foreach ($res as $image) {
      $this->gallery[] = new Image($image['image_id']);
    }

    # attachments
    $fields = array('attachments.id');
    $conds  = array('article_attachments.article_id = ?', 'i', array($this->id));
    $joins  = ' JOIN article_attachments ON article_attachments.attachment_id = attachments.id'.
              ' JOIN articles ON articles.id = article_attachments.article_id';
    $attachments = $db->select('attachments', $fields, $conds, null, null, $joins);

    foreach ($attachments as $attachment) {
      $this->attachments[] = new File($attachment['id']);
    }

    # check playlist entry
    $category = $this->getCategory();

    if ($category->isPlaylist()) {
      $playlistID = $category->getPlaylistId();
      $video_id   = getYouTubeIDFromArticle($this->id);
      $path       = 'images/tmp/'.$playlistID.'-'.$video_id.'.jpg';

      # missing thumbnail? try to fetch it
      if (!file_exists($path)) {
        $thumbnail = 'https://img.youtube.com/vi/'.$video_id.'/maxresdefault.jpg';

        # fetching failed, clear path/thumbnail for this article
        if (!Image::storeRemoteImage($thumbnail, $path)) {
          $path = null;
        }
      }

      $this->thumbnail = $path;
      $this->playlist = true;
    }

    $this->loaded = true;
  }
}