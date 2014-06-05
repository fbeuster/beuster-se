<?php
  $a['filename'] = 'portfolio.php';
  $return = '';
  $db = Database::getDB()->getCon();
 
  $portID = getCatID('Portfolio');
 
  $sql = "SELECT
            news.Titel,
            news.Inhalt,
            pics.Name,
            pics.Pfad
          FROM
            news
          JOIN
            newscatcross ON
            news.ID = newscatcross.NewsID
          LEFT JOIN
            pics ON
            pics.NewsID = news.ID
          WHERE
            newscatcross.Cat = ?";
 // Titel  -> <article id="xxx">
 // Inhalt -> Group###Text unter Bild
  if(!$stmt = $db->prepare($sql))
    $return = $db->error;
  if($return == '')
    $stmt->bind_param('i', $portID);
  if($return == '')
    if(!$stmt->execute())
      $return = $stmt->error;
  if($return == '') {
    $stmt->bind_result($portFileName, $portFileText, $portFilePicName, $portFilePicPath);
    $portFiles = array();
    while($stmt->fetch()) {
      $portFileText = explode('###', $portFileText);
      $portFiles[] = array( 'id'    => $portFileText[1],
                            'name'  => $portFileName,
                            'text'  => changetext($portFileText[2], 'inhalt', $mob),
                            'group' => $portFileText[0],
                            'file'  => $portFilePicName,
                            'path'  => 'http://'.$sysAdrr.'/'.$portFilePicPath);
    }
    $stmt->close();
    $portFilesSorted = array();
    foreach($portFiles as $portFile) {
      $portFilesSorted[$portFile['group']][] = $portFile;
    }
    $a['data']['portFiles'] = $portFilesSorted;
  }
  $a['data']['ret'] = $return;
?>