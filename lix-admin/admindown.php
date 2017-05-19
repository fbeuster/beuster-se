<?php
  $a    = array();
  $user = User::newFromCookie();

  if ($user && $user->isAdmin()) {
    $user->refreshCookies();

    $a['filename']  = 'admindown.php';
    $a['data']      = array();

    $max_file_size = 5242880;
    $min_file_size = 0;

    if ('POST' == $_SERVER['REQUEST_METHOD']) {
      if (!isset( $_POST['title'], $_POST['description'],
                  $_POST['version'], $_FILES['file'],
                  $_POST['formaction'])) {
        return INVALID_FORM;
      }

      $db           = Database::getDB();
      $description  = Parser::parse($_POST['description'], Parser::TYPE_NEW);
      $license      = $_POST['license'];
      $title        = Parser::parse($_POST['title'], Parser::TYPE_NEW);
      $version      = $_POST['version'];

      if ('' == $title OR '' == $description OR '' == $version) {
        return EMPTY_FORM;
      }

      if (0 == $license) {
        $license = 'by-sa';
      }

      $log_id = 0;

      if (!empty($_FILES['file'])) {
        $e = array();

        if ($_FILES['file']['size'] > $min_file_size &&
            $_FILES['file']['size'] < $max_file_size) {
          $file_path = 'files/' . $_FILES['file']['name'];

          if (!file_exists($file_path)) {

            # upload file
            move_uploaded_file($_FILES['file']['tmp_name'], $file_path);

            $file_name  = $_FILES['file']['name'];
            $fields     = array('Name', 'Path');
            $values     = array('ss', array($file_name, $file_path));
            $file_id    = $db->insert('files', $fields, $values);

          } else {

            # file already_exists
            $e[] = $_FILES['file']['name'];
          }

        } else if($_FILES['file']['size'] != 0){

          # file too large
          $e[] = $_FILES['file']['name'];
        }

        if (empty($e)) {
          if (!empty($_FILES['log'])) {
            if ($_FILES['log']['size'] > $min_file_size &&
                $_FILES['log']['size'] < $max_file_size) {
              $log_path = 'files/'.$_FILES['log']['name'];

              if (!file_exists($log_path)) {

                # upload log file
                move_uploaded_file($_FILES['log']['tmp_name'], $log_path);

                $log_name = $_FILES['log']['name'];
                $fields   = array('Name', 'Path');
                $values   = array('ss', array($log_name, $log_path));
                $log_id   = $db->insert('files', $fields, $values);

              } else {

                # log file already exists
                $e[] = $_FILES['log']['name'];

                $cond = array('ID = ?', 'i', array($file_id));
                $db->delete('files', $cond);

                unlink($file_path);
              }

            } else if($_FILES['log']['size'] != 0) {

              # log file too large
              $e[] = $_FILES['log']['name'];

              $cond = array('ID = ?', 'i', array($file_id));
              $db->delete('files', $cond);

              unlink($file_path);
            }
          }

          if (empty($e)) {

            # insert download page entry
            $fields = array('Name', 'Description', 'Version',
                            'License', 'File', 'Log');
            $values = array('ssssii', array($title, $description,
                                            $version, $license,
                                            $file_id, $log_id));
            $download_id = $db->insert('downloads', $fields, $values);

            return showInfo('Der Download wurde hinzugefügt. <br /><a href="/admin" class="back">Zurück zur Administration</a>', 'admin');

          } else {
            # error with log file
            $a['data']['fe'] = array( 'name'  => $title,
                                      'descr' => $description);
            $a['data']['fm'] = $e;
          }

        } else {
          # error with file
          $a['data']['fe'] = array( 'name'  => $title,
                                    'descr' => $description);
          $a['data']['fm'] = $e;
        }

      } else {
        # no files attaced
        $a['data']['fe'] = array( 'name'  => $title,
                                  'descr' => $description);
      }
    }
    return $a;

  } else if($user){
    return showInfo(I18n::t('admin.no_access'), 'blog');

  } else {
    $link = ' <a href="/login">'.I18n::t('admin.try_again').'</a>';
    return showInfo(I18n::t('admin.not_logged_in').$link, 'login');
  }

?>
