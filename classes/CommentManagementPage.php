<?php

  class CommentManagementPage extends AbstractAdminPage {

    private $comments;

    public function __construct() {
      $this->loadComments();
      $this->handlePost();
      $this->load();
    }

    private function handlePost() {
      if($_SERVER['REQUEST_METHOD'] == 'POST') {
        foreach ($this->comments as $comment) {
          if (isset($_POST[$comment['id']]) &&
              $_POST[$comment['id']] == 'delete') {
            Comment::delete($comment['id']);
          }

          if (isset($_POST[$comment['id']]) &&
              $_POST[$comment['id']] == 'enable') {
            Comment::enable($comment['id']);
          }
        }

        $lb       = Lixter::getLix()->getLinkBuilder();
        $link     = '<br /><a href="'.$lb->makeAdminLink('admin').'">'.
                    I18n::t('admin.back_link').'</a>';
        $message  = I18n::t('admin.category.success').$link;
        $this->showMessage($message, 'admin');
      }
    }

    private function load() {
      $this->setTitle(I18n::t('admin.comment.label'));
      $this->loadComments(true);
    }

    private function loadComments($deep_loading = false) {
      $this->comments = array();
      $db       = Database::getDB();
      $fields   = array('id', 'user_id', 'article_id', 'content',
                        'UNIX_TIMESTAMP(date) AS comment_date');
      $conds    = array('enabled = ?', 'i', array(0));
      $options  = 'ORDER BY comment_date DESC, article_id DESC';
      $results  = $db->select('comments', $fields, $conds, $options);

      foreach($results as $result) {
        if ($deep_loading) {
          $user = User::newFromId( $result['user_id'] );
          $news = new Article( $result['article_id'] );

        } else {
          $user = $result['user_id'];
          $news = $result['article_id'];
        }

        $this->comments[] = array(
          'content' => Parser::parse( $result['content'],
                                      Parser::TYPE_COMMENT),
          'date'    => $result['comment_date'],
          'id'      => $result['id'],
          'user'    => $user,
          'news'    => $news );
      }
    }

    public function show() {
      if ($this->has_message) {
        include 'system/views/admin/static.php';

      } else {
        include 'system/views/admin/comment_management.php';
      }
    }
  }

?>
