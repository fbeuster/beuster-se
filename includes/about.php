<?php
  $a = array();
  $a['filename'] = 'about.tpl';
  $a['data'] = array();
  $a['data']['eType'] = 0;
  $a['data']['ec'] = '';
  $a['data']['formCnt'] = 20;
  
  if('POST' == $_SERVER['REQUEST_METHOD']) {  
    $user = $db->real_escape_string(stripslashes(trim($_POST['usr'])));
    $Inhalt = $db->real_escape_string(stripslashes(trim($_POST['usrcnt'])));
    $Usermail = $db->real_escape_string(stripslashes(trim($_POST['usrml'])));
    $webpage = $db->real_escape_string(stripslashes(trim($_POST['usrpg'])));
    $err = checkStandForm($user, $Inhalt, $Usermail, $webpage, trim($_POST['date']), $_POST['email'], $_POST['homepage'], 'feedbackForm');
    if($err == 0) {
      $mailTo = 'info@beusterse.de';
      $mailTopic = 'Usernachricht von '.$user;
      $b = '<html>';
      $b .= '<head><title>Usernachricht</title>';
      $b .= '</head>';
      $b .= '<body>';
      $b .= 'User '.$user.' ('.$webpage.') schreibt folgendes:';
      $b .= '<p>'.$Inhalt.'</p>';
      $b .= '</body></html>';
      $mailContent = $b;
      $mailHeader = 'MIME-Version: 1.0'."\n";
      $mailHeader .= 'Content-Type: text/html; charset=utf-8'."\n";
      $mailHeader .= 'From: '.$user.'<'.$Usermail.'>'."\n";
      $mailHeader .= 'Reply-To: '.$user.'<'.$Usermail.'>'."\n";
      $mailHeader .= 'X-Mailer: PHP/'.phpversion().'\r\n'; 
      if($_SERVER['SERVER_NAME'] == "beusterse.loc") {
        echo '<pre style=" width: 90%; padding: 0 5%; background: #efefef;">'.$mailContent.'</pre>';
      } else {
        $mailSent = mail($mailTo, $mailTopic, $mailContent, $mailHeader);
        if($mailsent) {
          return showInfo('Danke für deine Nachricht.', '/about');
        }
      }
    }
    $a['data']['eType'] = $err;
    $a['data']['ec'] = array('user' => $user, 'cnt' => $Inhalt, 'mail' => $Usermail, 'page' => $webpage);
  }
  $a['data']['time'] = time();

  return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
?>