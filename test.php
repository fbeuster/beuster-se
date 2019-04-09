<?php

  $file = 'files/test.file';

  echo '<pre>'; print_r($_SERVER); echo '</pre>';
  echo '<pre>'; print_r(php_sapi_name()); echo '</pre>';

  // header('Content-Description: File Transfer');
  // header('Content-Type: application/octet-stream');
  // header('Content-Disposition: attachment; filename='.basename($file));
  // header('Content-Transfer-Encoding: binary');
  // header('Expires: 0');
  // header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
  // header('Pragma: public');
  // header('Content-Length: ' . filesize($file));

  // ob_clean();
  // flush();

  // readfile($file);
  // flush();

  // if (!empty($_COOKIE))
  // error_log('test');
  // exit;

?>
