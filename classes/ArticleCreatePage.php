<?php

  class ArticleCreatePage extends AbstractAdminPage {


    private $action       = 'new';
    private $errors       = array();
    private $form_action  = 'article-create';
    private $submit       = 'formaction';
    private $values       = array();

    public function __construct() {
      $this->handlePost();
      $this->load();
    }

    private function handlePost() {
      if ('POST' == $_SERVER['REQUEST_METHOD']) {
        $db       = Database::getDB()->getCon();
        $dbo      = Database::getDB();
        $user     = User::newFromCookie();

        $title    = Parser::parse($_POST['title'], Parser::TYPE_NEW);
        $content  = Parser::parse($_POST['content'], Parser::TYPE_NEW);

        $attachments  = trim($_POST['attachments']);
        $release_date = trim($_POST['release_date']);
        $release_time = trim($_POST['release_time']);
        $tag_string   = trim($_POST['tags']);
        $thumbnail    = trim($_POST['thumbnail']);

        $category         = $_POST['category'];
        $category_new     = trim($_POST['category_new']);
        $category_parent  = $_POST['category_parent'];

        $playlist         = $_POST['playlist'];
        $playlist_new     = trim($_POST['playlist_new']);
        $playlist_new_id  = trim($_POST['playlist_new_id']);

        if (isset($_POST['unlisted'])) {
          $is_public = false;

        } else {
          $is_public = true;
        }

        if (isset($_POST['project_status'])) {
          $project_status = trim($_POST['project_status']);
        }

        # check if $_FILES is empty or not
        if (empty($_FILES)) {
          $has_uploads = false;
        } else if (is_array($_FILES['file']['error'])) {
          $has_uploads = $_FILES['file']['error'][0] != 4;

        } else {
          $has_uploads = $_FILES['file']['error'] != 4;
        }

        $this->values = array(
                          'attachments'     => $attachments,
                          'category'        => $category,
                          'category_new'    => $category_new,
                          'category_parent' => $category_parent,
                          'content'         => $content,
                          'playlist'        => $playlist,
                          'playlist_new'    => $playlist_new,
                          'playlist_new_id' => $playlist_new_id,
                          'release_date'    => $release_date,
                          'release_time'    => $release_time,
                          'tags'            => $tag_string,
                          'title'           => $title,
                          'thumbnail'       => $thumbnail,
                          'unlisted'        => !$is_public);

        if ($title == '') {
          # empty title
          $this->errors['title'] = array(
            'message' => I18n::t('admin.article.editor.error.empty_title'),
            'value'   => $title);
        }

        if ($content == '') {
          # empty content
          $this->errors['content'] = array(
            'message' => I18n::t('admin.article.editor.error.empty_content'),
            'value'   => $content);
        }

        if ($tag_string == '') {
          # empty content
          $this->errors['tags'] = array(
            'message' => I18n::t('admin.article.editor.error.empty_tags'),
            'value'   => $tag_string);
        }

        if ($release_date == '') {
          # no date set? release today
          $release_date = date("Y-m-d", time());

        } else {
          if (!preg_match('/^[0-9]{4}(-[0-9]{2}){2}$/', $release_date)) {
            $this->errors['release_date'] = array(
              'message' => I18n::t('admin.article.editor.error.invalid_release_date'),
              'value'   => $release_date);

          } else {
            $srelease_arr = preg_split('/-/', $release_date);

            if (!checkdate($srelease_arr[1], $srelease_arr[2], $srelease_arr[0])) {
              $this->errors['release_date'] = array(
                'message' => I18n::t('admin.article.editor.error.invalid_release_date'),
                'value'   => $release_date);
            }
          }
        }

        if ($release_time == '') {
          # no time set? release on current time
          $release_time = date("H:i:s", time());

        } else {
          if (!preg_match('/^(2[0-3]|[01][0-9]):([0-5][0-9])$/', $release_time)) {
            $this->errors['release_time'] = array(
              'message' => I18n::t('admin.article.editor.error.invalid_release_time'),
              'value'   => $release_time);

          } else {
            # adding a second value
            $release_time .= ':00';
          }
        }

        if ($category == 'error' && $category_new == '' && $playlist == 'error' && $playlist_new == '' && $category_parent == 'error' && $playlist_new_id == '') {
          # no cat/play selected and no new cat/play given
          $this->errors['category'] = array(
            'message' => I18n::t('admin.article.editor.error.missing_category_playlist'),
            'value'   => $category);
        }

        if ($category != 'error' && $playlist != 'error') {
          # new category and new playlist not possible
          $this->errors['category'] = array(
            'message' => I18n::t('admin.article.editor.error.invalid_category_playlist'),
            'value'   => $category);
        }

        if ($playlist_new != '' && $category_new != '') {
          # new category and new playlist not possible
          $this->errors['playlist_new'] = array(
            'message' => I18n::t('admin.article.editor.error.invalid_playlist_category'),
            'value'   => $playlist_new);
        }

        if ($playlist != 'error' && ($playlist_new != '' || $playlist_new_id != '')) {
          # old and new playlist not possible
          $this->errors['playlist'] = array(
            'message' => I18n::t('admin.article.editor.error.playlist_old_new'),
            'value'   => $playlist);
        }

        if ($playlist_new != '' && $playlist_new_id == '') {
          # new playlist name but id missing
          $this->errors['playlist_new_id'] = array(
            'message' => I18n::t('admin.article.editor.error.missing_playlist_id'),
            'value'   => $playlist_new_id);
        }

        if ($playlist_new_id != '' && $playlist_new == '') {
          # new playlist id but name missing
          $this->errors['playlist_new'] = array(
            'message' => I18n::t('admin.article.editor.error.missing_playlist_name'),
            'value'   => $playlist_new);
        }

        if ($category != 'error' && $category_new != '') {
          # old and new category not possible
          $this->errors['category'] = array(
            'message' => I18n::t('admin.article.editor.error.category_old_new'),
            'value'   => $category);
        }

        if ($category != 'error' && $category_parent != 'error') {
          # old category can't get new parent
          $this->errors['category'] = array(
            'message' => I18n::t('admin.article.editor.error.invalid_category_parent'),
            'value'   => $category);
        }

        if ($category_parent == 'error' && $category_new != '') {
          # new category but parent missing
          $this->errors['category_parent'] = array(
            'message' => I18n::t('admin.article.editor.error.missing_category_parent'),
            'value'   => $category_parent);
        }

        if ($category_parent != 'error' && $category_new == '') {
          # category parent but new name missing
          $this->errors['category_new'] = array(
            'message' => I18n::t('admin.article.editor.error.missing_category_name'),
            'value'   => $category_new);
        }

        if (Category::isCategoryName('Projekte') &&
            Category::newFromName('Projekte')->getId() == Category::newFromName($category)->getId()) {
          if ($project_status == 0) {
            # missing project status
            $this->errors['project_status'] = array(
              'message' => I18n::t('admin.article.editor.error.missing_project_status'),
              'value'   => $project_status);
          }

        } else {
          $project_status = 0;
        }

        if (!$has_uploads || $playlist != 'error' || $playlist_new != '') {
          $thumbnail = 0;

        } else {
          if (!preg_match('/^[0-9]*$/', $thumbnail)) {
            # thumbnail number invalid
            $this->errors['thumbnail'] = array(
              'message' => I18n::t('admin.article.editor.error.invalid_thumbnail_number'),
              'value'   => $thumbnail);

          } else if ($thumbnail > count($_FILES['file']['name'])) {
            # thumbnail number too big
            $this->errors['thumbnail'] = array(
              'message' => I18n::t('admin.article.editor.error.thumbnail_too_big'),
              'value'   => $thumbnail);

          } else if ($thumbnail < 1) {
            # thumbnail number too small
            $this->errors['thumbnail'] = array(
              'message' => I18n::t('admin.article.editor.error.thumbnail_too_small'),
              'value'   => $thumbnail);
          }
        }

        if (empty($this->errors)) {
          $release_date = $release_date . ' ' . $release_time;

          # category, sub-category or playlist?
          if ($category_new != '') {
            $category         = $category_new;
            $is_new_category  = true;

          } else if ($playlist_new != '') {
            $category         = $playlist_new;
            $is_new_category  = true;
            $is_new_playlist  = true;

          } else if ($category == 'error' && $playlist != 'error'){
            $category = $playlist;
          }

          if (!$is_new_category) {
            $o_category           = Category::newFromName($category);
            $category_id          = $o_category->getId();
            $category_article_id  = $o_category->getMaxArticleId() + 1;

          } else {
            $category_article_id = 1;
          }

          # insert the article
          $fields = array('Autor', 'Titel', 'Inhalt', 'Datum', 'enable', 'Status');
          $values = array('isssii', array($user->getId(), $title, $content, $release_date, $is_public, $project_status));
          $id     = $dbo->insert('news', $fields, $values);

          # upload images
          $image_errors   = array();
          $image_replace  = array();

          if ($has_uploads) {
            foreach ($_FILES['file']['name'] as $key => $value) {
              if (!Image::isValidSize($_FILES['file']['size'][$key])) {
                # invlaid file size
                $image_errors[] = array(
                  'message' => I18n::t( 'admin.article.editor.error.invalid_image_size',
                                        $_FILES['file']['name'][$key]),
                  'value'   => $_FILES['file']['name'][$key]);

              } else if (!Image::isValidFormat($_FILES['file']['type'][$key])) {
                # invalid image format
                $image_errors[] = array(
                  'message' => I18n::t( 'admin.article.editor.error.invalid_image_format',
                                        $_FILES['file']['name'][$key]),
                  'value'   => $_FILES['file']['name'][$key]);

              } else {
                $saved = Image::saveUploadedImage(  $_FILES['file']['name'][$key],
                                                    $_FILES['file']['tmp_name'][$key],
                                                    $id, $thumbnail, $key);
                if (!$saved) {
                  # saving error
                  $image_errors[] = array(
                    'message' => I18n::t('admin.article.editor.error.image_save_failure'),
                    'value'   => $_FILES['file']['name'][$key]);

                } else {
                  $image_replace[] = array('n' => $key + 1, 'id' => $saved);
                }
              }
            }
          }

          if (!empty($image_errors)) {
            # image errors, clean up the mess
            $fields = array('file_name');
            $conds  = array('article_id = ?', 'i', array($id));
            $images = $dbo->select('images', $fields, $conds);

            foreach ($images as $image) {
              Image::delete($image['file_name']);
            }

            $cond = array('ID = ?', 'i', array($id));
            $res  = $dbo->delete('news', $cond);

            $cond = array('article_id = ?', 'i', array($id));
            $res  = $dbo->delete('images', $cond);

            $this->errors['files'] = $image_errors;

          } else {
            # update image short codes
            $content_new = $content;

            foreach ($image_replace as $image) {
              $search       = '[img'.$image['n'].']';
              $replace      = '[img'.$image['id'].']';
              $content_new  = str_replace($search, $replace, $content_new);
            }

            $sql = "UPDATE news SET Inhalt = ? WHERE ID = ?";

            if (!$stmt = $db->prepare($sql)) {
              return $db->error;
            }

            $stmt->bind_param('si', $content_new, $id);

            if (!$stmt->execute()) {
              return $stmt->error;
            }

            $stmt->close();

            # handle new category creation
            if ($is_new_category) {
              # set new category parameters
              if ($is_new_playlist) {
                $category_type   = Category::CAT_TYPE_PLAYLIST;
                $category_parent = 7;

              } else {
                $category_type   = Category::CAT_TYPE_SUB;
                $category_parent = Category::newFromName($category_parent)->getId();
              }

              # create new category
              Category::cerate($category, $category_parent, $category_type);

              # create new playlist
              if ($is_new_playlist) {
                $fields = array('ytID', 'catID');
                $values = array('si', array($playlist_new_id, $category_id));
                $res    = $dbo->insert('playlist', $fields, $values);
              }
            }

            $o_category = Category::newFromName($category);

            # grab video thumbnail
            if ($o_category->isPlaylist()) {
              $video_id       = getYouTubeIDFromArticle($id);
              $playlist_id    = $o_category->getPlaylistID();
              $thumbnail      = 'https://img.youtube.com/vi/'.$video_id.'/maxresdefault.jpg';
              $store_path     = 'images/tmp/'.$playlist_id.'-'.$video_id.'.jpg';

              Image::storeRemoteImage($thumbnail, $store_path);
            }


            # insert tags to DB
            $tags = array();

            foreach (explode(',', $tag_string) as $tag) {
              if (trim($tag) !== '' && !in_array($tag, $tags)) {
                $tags[] = array($id, $tag);
              }
            }

            if (!empty($tags)) {
              $fields = array('news_id', 'tag');
              $values = array('is', $tags);
              $res    = $dbo->insertMany('tags', $fields, $values);
            }

            # insert attachments to DB
            $attachments_write = array();

            foreach (explode(';', $attachments) as $attachment) {
              if (trim($attachment) !== '' &&
                  !in_array($attachment, $attachments_write)) {
                $attachments_write[] = array($id, $attachment);
              }
            }

            if (!empty($attachments_write)) {
              $fields = array('article_id', 'attachment_id');
              $values = array('ii', $attachments_write);
              $res    = $dbo->insertMany('article_attachments',
                                          $fields, $values);
            }

            # connect category and article
            $fields = array('NewsID', 'Cat', 'CatID');
            $values = array('iii', array($id, $o_category->getId(), $category_article_id));
            $res    = $dbo->insert('newscatcross', $fields, $values);

            # add rss entry
            if ($is_public && isset($rssFeedPath)) {
              $article  = new Article($id);
              $protocol = Lixter::getLix()->getProtocol();
              $url      = $protocol.'://'.Utilities::getSystemAddress().$article->getLink();
              addRssItem( $rssFeedPath,
                          $title,
                          str_replace('###link###', $url, Parser::parse($content, Parser::TYPE_PREVIEW)),
                          date("D, j M Y H:i:s ", time()).'GMT',
                          $id,
                          $url);
            }
          }
        }

        if (empty($this->errors)) {
          $this->showMessage( I18n::t('admin.article.editor.actions.new.success'),
                              'admin');
        }
      }
    }

    private function load() {
      $this->setTitle(I18n::t('admin.article.editor.actions.new.label'));

      $this->parents      = getTopCats();
      $this->categories   = getSubCats();
      $this->categories[] = 'Blog';
      $this->playlists    = getPlaylists();

      $db     = Database::getDB();
      $fields = array('id', 'file_name');
      $this->attachments = $db->select('attachments', $fields);

      sort($this->categories);
    }

    public function show() {
      if ($this->has_message) {
        include 'system/views/admin/static.php';

      } else {
        include 'system/views/admin/article_editor.php';
      }
    }
  }

?>
