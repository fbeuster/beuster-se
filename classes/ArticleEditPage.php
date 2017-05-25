<?php

  class ArticleEditPage extends AbstractAdminPage {

    private $action       = 'edit';
    private $articles     = array();
    private $errors       = array();
    private $form_action  = 'article-edit';
    private $static_pages = array();
    private $submit       = 'formactionchange';
    private $values       = array();

    public function __construct() {
      $this->handlePost();
      $this->handleGet();
      $this->load();
    }

    private function handleGet() {
      if (  ( 'GET' == $_SERVER['REQUEST_METHOD'] &&
              isset($_GET['article']) ) ||
            ( 'POST' == $_SERVER['REQUEST_METHOD'] &&
              isset($_POST['formactionchoose']) ) ) {

        $db = Database::getDB();

        if (isset($_GET['article'])) {
          $id = trim($_GET['article']);

        } else if(isset($_POST['article'])) {
          $id = trim($_POST['article']);

        } else {
          $id = null;
        }

        if ($id == null || $id == '' || $id == 0 || !is_numeric($id)) {
          $this->errors['article'] = array(
            'message' => I18n::t('admin.article.edit.error.no_article_selected'),
            'value'   => $id);

        } else {
          # get article for edit
          $fields = array('Cat');
          $cond   = array('NewsID = ?', 'i', array($id));
          $res    = $db->select('newscatcross', $fields, $cond);

          if (count($res) == 0) {
            $this->showMessage( I18n::t('admin.article.edit.not_found'),
                                'article-edit');

          } else {
            $article  = new Article($id);
            $cat      = new Category($res[0]['Cat']);

            $this->values = array(
              'article_id'    => $id,
              'category'      => $cat->getName(),
              'content'       => Parser::parse( $article->getContent(),
                                                Parser::TYPE_EDIT),
              'is_playlist'   => $cat->isPlaylist(),
              'release_date'  => $article->getDateFormatted('Y-m-d'),
              'release_time'  => $article->getDateFormatted('H:i'),
              'tags'          => implode($article->getTags(), ','),
              'title'         => Parser::parse( $article->getTitle(),
                                                Parser::TYPE_EDIT),
              'unlisted'      => $article->getEnable() ? false : true);

            if ($cat->isPlaylist()) {
              $this->values['playlist'] = $cat->getName();
            }
          }

          $fields   = array('file_name', 'is_thumb', 'id');
          $conds    = array('article_id = ?', 'i', array($id));
          $options  = 'ORDER BY id';
          $res      = $db->select('images', $fields, $conds, $options);

          $this->images = array();

          foreach ($res as $pic) {
            $this->images[] = array('path'  => $pic['file_name'],
                                    'thumb' => $pic['is_thumb'],
                                    'id'    => $pic['id']);
          }
        }
      }
    }

    private function handlePost() {
      if ('POST' == $_SERVER['REQUEST_METHOD'] &&
          isset($_POST['formactionchange']) ) {

        $db   = Database::getDB()->getCon();
        $dbo  = Database::getDB();

        $is_new_category  = false;
        $is_new_playlist  = false;

        $title    = Parser::parse($_POST['title'], Parser::TYPE_NEW);
        $content  = Parser::parse($_POST['content'], Parser::TYPE_NEW);

        $article_id   = $_POST['article_id'];
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


        if(isset($_POST['unlisted'])) {
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
                          'article_id'      => $article_id,
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
            'message' => I18n::t('admin.article.edit.error.empty_title'),
            'value'   => $title);
        }

        if ($content == '') {
          # empty content
          $this->errors['content'] = array(
            'message' => I18n::t('admin.article.edit.error.empty_content'),
            'value'   => $content);
        }

        if ($article_id == '') {
          # missing article id
          $this->errors['article_id'] = array(
            'message' => I18n::t('admin.article.edit.error.missing_article_id'),
            'value'   => $article_id);
        }

        if ($tag_string == '') {
          # empty content
          $this->errors['tags'] = array(
            'message' => I18n::t('admin.article.edit.error.empty_tags'),
            'value'   => $tag_string);
        }

        if ($release_date == '') {
          # no date set? release today
          $release_date = date("Y-m-d", time());

        } else {
          if (!preg_match('/^[0-9]{4}(-[0-9]{2}){2}$/', $release_date)) {
            $this->errors['release_date'] = array(
              'message' => I18n::t('admin.article.edit.error.invalid_release_date'),
              'value'   => $release_date);

          } else {
            $srelease_arr = preg_split('/-/', $release_date);

            if (!checkdate($srelease_arr[1], $srelease_arr[2], $srelease_arr[0])) {
              $this->errors['release_date'] = array(
                'message' => I18n::t('admin.article.edit.error.invalid_release_date'),
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
              'message' => I18n::t('admin.article.edit.error.invalid_release_time'),
              'value'   => $release_time);

          } else {
            # adding a second value
            $release_time .= ':00';
          }
        }

        if ($category == 'error' && $category_new == '' && $playlist == 'error' && $playlist_new == '' && $category_parent == 'error' && $playlist_new_id == '') {
          # no cat/play selected and no new cat/play given
          $this->errors['category'] = array(
            'message' => I18n::t('admin.article.edit.error.missing_category_playlist'),
            'value'   => $category);
        }

        if ($category != 'error' && $playlist != 'error') {
          # new category and new playlist not possible
          $this->errors['category'] = array(
            'message' => I18n::t('admin.article.edit.error.invalid_category_playlist'),
            'value'   => $category);
        }

        if ($playlist_new != '' && $category_new != '') {
          # new category and new playlist not possible
          $this->errors['playlist_new'] = array(
            'message' => I18n::t('admin.article.edit.error.invalid_playlist_category'),
            'value'   => $playlist_new);
        }

        if ($playlist != 'error' && ($playlist_new != '' || $playlist_new_id != '')) {
          # old and new playlist not possible
          $this->errors['playlist'] = array(
            'message' => I18n::t('admin.article.edit.error.playlist_old_new'),
            'value'   => $playlist);
        }

        if ($playlist_new != '' && $playlist_new_id == '') {
          # new playlist name but id missing
          $this->errors['playlist_new_id'] = array(
            'message' => I18n::t('admin.article.edit.error.missing_playlist_id'),
            'value'   => $playlist_new_id);
        }

        if ($playlist_new_id != '' && $playlist_new == '') {
          # new playlist id but name missing
          $this->errors['playlist_new'] = array(
            'message' => I18n::t('admin.article.edit.error.missing_playlist_name'),
            'value'   => $playlist_new);
        }

        if ($category != 'error' && $category_new != '') {
          # old and new category not possible
          $this->errors['category'] = array(
            'message' => I18n::t('admin.article.edit.error.category_old_new'),
            'value'   => $category);
        }

        if ($category != 'error' && $category_parent != 'error') {
          # old category can't get new parent
          $this->errors['category'] = array(
            'message' => I18n::t('admin.article.edit.error.invalid_category_parent'),
            'value'   => $category);
        }

        if ($category_parent == 'error' && $category_new != '') {
          # new category but parent missing
          $this->errors['category_parent'] = array(
            'message' => I18n::t('admin.article.edit.error.missing_category_parent'),
            'value'   => $category_parent);
        }

        if ($category_parent != 'error' && $category_new == '') {
          # category parent but new name missing
          $this->errors['category_new'] = array(
            'message' => I18n::t('admin.article.edit.error.missing_category_name'),
            'value'   => $category_new);
        }

        if (Category::isCategoryName('Projekte') &&
            Category::newFromName('Projekte')->getId() == Category::newFromName($category)->getId()) {
          if ($project_status == 0) {
            # missing project status
            $this->errors['project_status'] = array(
              'message' => I18n::t('admin.article.edit.error.missing_project_status'),
              'value'   => $project_status);
          }

        } else {
          $project_status = 0;
        }

        if ($has_uploads) {
          if (!preg_match('/^[0-9]*$/', $thumbnail)) {
            # thumbnail number invalid
            $this->errors['thumbnail'] = array(
              'message' => I18n::t('admin.article.edit.error.invalid_thumbnail_number'),
              'value'   => $thumbnail);

          } else if ($thumbnail > count($_FILES['file']['name'])) {
            # thumbnail number too big
            $this->errors['thumbnail'] = array(
              'message' => I18n::t('admin.article.edit.error.thumbnail_too_big'),
              'value'   => $thumbnail);

          } else if ( $thumbnail < 1 &&
                      ($playlist == 'error' && $playlist_new == '')) {
            # thumbnail number too small
            # only matters for non-playlist articles
            $this->errors['thumbnail'] = array(
              'message' => I18n::t('admin.article.edit.error.thumbnail_too_small'),
              'value'   => $thumbnail);
          }
        }

        if (empty($this->errors)) {
          $release_date = $release_date . ' ' . $release_time;

          # category, sub-category or playlist?
          if ($category_new != '') {
            $category         = $category_new;
            $is_new_category  = true;

            $temp_category    = Category::newFromName($category_parent);
            $category_parent  = $temp_category->getId();

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

          $image_errors   = array();
          $image_replace  = array();

          if ($has_uploads) {
            foreach ($_FILES['file']['name'] as $key => $value) {
              if (!Image::isValidSize($_FILES['file']['size'][$key])) {
                # invlaid file size
                $image_errors[] = array(
                  'message' => I18n::t( 'admin.article.edit.error.invalid_image_size',
                                        $_FILES['file']['name'][$key]),
                  'value'   => $_FILES['file']['name'][$key]);

              } else if (!Image::isValidFormat($_FILES['file']['type'][$key])) {
                # invalid image format
                $image_errors[] = array(
                  'message' => I18n::t( 'admin.article.edit.error.invalid_image_format',
                                        $_FILES['file']['name'][$key]),
                  'value'   => $_FILES['file']['name'][$key]);

              } else {
                $saved = Image::saveUploadedImage(  $_FILES['file']['name'][$key],
                                                    $_FILES['file']['tmp_name'][$key],
                                                    $article_id, $thumbnail, $key);
                if (!$saved) {
                  # saving error
                  $image_errors[] = array(
                    'message' => I18n::t('admin.article.edit.error.image_save_failure'),
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
            $category_old_id  = getNewsCatID($article_id);
            $category_old     = getNewsCat($article_id);

            if ($is_new_category) {
              if ($is_new_playlist) {
                $category_type   = Category::CAT_TYPE_PLAYLIST;

              } else {
                $category_type   = Category::CAT_TYPE_SUB;
              }

              # new category
              Category::create($category_new, $category_parent, $category_type);

              $fields = array('MAX(ID) as idn');
              $res    = $dbo->select('newscat', $fields);

              if (count($res) > 0) {
                $category = $res[0]['idn'];
              }

              $categoryID = 1;

              if ($is_new_playlist) {
                $fields = array('ytID', 'CatID');
                $values = array('si', array( $playlist_new_id, $categoryID));
                $res    = $dbo->insert('playlist', $fields, $values);
              }

            } else {
              $category = getCatID($category);

              if ($category_old != $category) {
                $fields = array('MAX(CatID) AS new');
                $conds  = array('Cat = ?', 'i', array($category));
                $res    = $dbo->select('newscatcross', $fields, $conds);

                if (count($res) > 0) {
                  $categoryID = $res[0]['new'];

                } else {
                  # category not found error
                }

                $categoryID = $categoryID + 1;

              } else {
                $categoryID = $category_old_id;
              }
            }

            # update news entry
            $sql = "UPDATE
                      news
                    SET
                      Titel = ?,
                      Inhalt = ?,
                      enable = ?,
                      Datum = ?
                    WHERE
                      ID = ?";

            if (!$stmt = $db->prepare($sql)) {
              return $db->error;
            }

            $stmt->bind_param('ssisi', $title, $content, $is_public, $release_date, $article_id);

            if (!$stmt->execute()) {
              return $stmt->error;
            }

            $stmt->close();

            # update news x cat
            $sql = "UPDATE
                      newscatcross
                    SET
                      Cat = ?,
                      CatID = ?
                    WHERE
                      NewsID = ?";

            if (!$stmt = $db->prepare($sql)) {
              return $db->error;
            }

            $stmt->bind_param('iii', $category, $categoryID, $article_id);

            if (!$stmt->execute()) {
              return $stmt->error;
            }

            $stmt->close();

            # delete old tags
            $dbo->delete( 'tags',
                          array('news_id = ?', 'i', array($article_id)) );

            # insert new tags
            $tags = array();

            foreach (explode(',', $tag_string) as $tag) {
              if (trim($tag) !== '' && !in_array($tag, $tags)) {
                $tags[] = array($article_id, $tag);
              }
            }

            if (!empty($tags)) {
              $fields = array('news_id', 'tag');
              $values = array('is', $tags);
              $res    = $dbo->insertMany('tags', $fields, $values);
            }

            # update thumbnail
            if (isset($_POST['thumbnail_old'])) {
              $pidF = trim($_POST['thumbnail_old']);

              $fields = array('id');
              $conds  = array('article_id = ? AND is_thumb = 1', 'i', array($article_id));
              $res    = $dbo->select('images', $fields, $conds);

              if (count($res) > 0) {
                $th = $res[0]['id'];

              } else {
                # no old thumbnail found error
                $th = null;
              }

              if ($th != $pidF) {
                $sql = 'UPDATE
                          images
                        SET
                          is_thumb = 1
                        WHERE
                          id = ?';

                if (!$stmt = $db->prepare($sql)) {
                  return $db->error;
                }

                $stmt->bind_param('i', $pidF);

                if (!$stmt->execute()) {
                  return $stmt->error;
                }

                $stmt->close();

                $sql = 'UPDATE
                          images
                        SET
                          is_thumb = 0
                        WHERE
                          id = ?';

                if (!$stmt = $db->prepare($sql)) {
                  return $db->error;
                }

                $stmt->bind_param('i', $th);

                if (!$stmt->execute()) {
                  return $stmt->error;
                }

                $stmt->close();
              }
            }

            # delete pictures
            if (!empty($_POST['del'])) {
              $del = $_POST['del'];

              foreach($del as $pf) {
                $fields = array('file_name');
                $conds  = array('id = ?', 'i', array($pf));
                $res    = $dbo->select('images', $fields, $conds);

                if (count($res) > 0) {
                  Image::delete($res[0]['file_name']);
                }

                $conds = array('id = ?', 'i', array($pf));
                $dbo->delete('images', $conds);
              }
            }

            if (empty($this->errors)) {
              $this->showMessage( I18n::t('admin.article.edit.success'),
                                  'article-edit');
            }
          }
        }
      }
    }

    private function load() {
      $this->setTitle(I18n::t('admin.article.edit.label'));

      $db       = Database::getDB();
      $fields   = array('ID', 'Titel',
                        "DATE_FORMAT(Datum, '%d.%m.%Y') AS date_format");
      $options  = 'ORDER BY Datum DESC';
      $res      = $db->select('news', $fields, null, $options);

      foreach ($res as $article) {
        $this->articles[$article['ID']] = array(
          'id'    => $article['ID'],
          'date'  => $article['date_format'],
          'title' => Parser::parse( $article['Titel'],
                                    Parser::TYPE_PREVIEW ));
      }

      $this->parents = array();
      foreach (getTopCats() as $parent) {
        $this->parents[] = getCatName($parent);
      }

      $this->categories   = getSubCats();
      $this->categories[] = 'Blog';
      $this->playlists    = getPlaylists();
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
