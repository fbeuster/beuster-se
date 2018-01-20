<?php

  class CommentOverviewPage extends AbstractAdminPage {

    private $article_lists  = array();
    private $unlisted       = 0;
    private $total_articles = 0;
    private $total_comments = 0;

    public function __construct() {
      $this->load();
    }

    private function load() {
      $this->setTitle(I18n::t('admin.comment.overview.label'));

      # collecting comments
      $db       = Database::getDB();
      $fields   = array('id');
      $conds    = array('ParentID = ?', 'i', array(-1));
      $options  = 'ORDER BY Datum DESC';

      $comment_list = $db->select('kommentare', $fields,
                                  $conds, $options);

      foreach ($comment_list as $key => $comment) {
        $comment_list[$key] = new Comment($comment['id']);
        $comment_list[$key]->loadReplies();
      }

      $this->comments = $comment_list;

      # get number of comments
      $fields = array('COUNT(ID) AS total_comments');
      $res    = $db->select('kommentare', $fields);

      if (count($res)) {
        $this->total_comments = $res[0]['total_comments'];
      }
    }

    public function show() {
      include 'system/views/admin/comment_overview.php';
    }
  }

?>
