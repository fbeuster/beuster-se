<?php
 $a = array();
 $a['filename'] = 'downloads.tpl';
 $a['data'] = array();
 
 $sql = "SELECT
            downloads.ID,
            downloads.Name,
            downloads.Description,
            downloads.Version,
            downloads.License,
            downloads.Log,
            downloads.File,
            files.Path,
            files.downloads,
            downcats.CatName
        FROM
            downloads
        LEFT JOIN
            files ON downloads.File = files.ID
        JOIN
            downcats ON downloads.CatID = downcats.ID";
 if(!$stmt = $db->prepare($sql)) {return $db->error;}
 if(!$stmt->execute()) {return $result->error;}
 
 $down = array();     
 $stmt->bind_result($id, $name, $descr, $ver, $lic, $log, $fileID, $path, $downloads, $cat);  
 while($stmt->fetch()) {
  $down[$id] = array('id'     => $id,
                    'name'    => $name,
                    'descr'   => changetext($descr, $mob, 'inhalt'),
                    'ver'     => $ver,
                    'path'    => $path,
                    'anz'     => $downloads,
                    'fileID'  => $fileID,
                    'size'    => 0,
                    'lic'     => $lic,
                    'log'     => $log,
                    'loganz'  => 0,
                    'logpath' => '',
                    'logsize' => 0,
                    'cat'     => $cat);
 }
 $stmt->close();
 foreach ($down as $entry) {
  if($entry['log'] != 0) {
   $sql = "SELECT
              Path,
              downloads
           FROM
              files
           WHERE
              ID = ?";
   if(!$stmt = $db->prepare($sql)) {return $db->error;}
   $stmt->bind_param('i', $entry['log']);
   if(!$stmt->execute()) {return $result->error;}
   $stmt->bind_result($path, $anz);
   while($stmt->fetch()) {
    $down[$entry['id']]['loganz'] = $anz;
    $down[$entry['id']]['logpath'] = $path;
   }
   $stmt->close();
   $down[$entry['id']]['logsize'] = getSize(filesize($down[$entry['id']]['logpath']));
  }
   $down[$entry['id']]['size'] = getSize(filesize($down[$entry['id']]['path']));
 }
 $a['data']['down'] = $down;
 
 if(isset($_GET['id'])) {
  $qId = trim($_GET['id']);
  
  $dIds = array();
  $sql = "SELECT
              ID,
              path
          FROM
              files";
  if(!$stmt = $db->prepare($sql)){return $db->error;}
  if(!$stmt->execute()){return $stmt->error;}
  $stmt->bind_result($dId, $file);
  while($stmt->fetch()) {
   $dIds[$dId] = $file;
  }
  $stmt->close();
  
  foreach($dIds as $key => $value) {
   if(md5($key) == $qId) {
    $dId = $key;
    $file = $value;
   }
  }
  $a['data']['download'] = $file;
  $a['data']['refresh'] = $file;
  $sql = "UPDATE
              files
          SET
              downloads = downloads + 1
          WHERE
              ID = ?";
  if(!$stmt = $db->prepare($sql)){return $db->error;}
  $stmt->bind_param('i', $dId);
  if(!$stmt->execute()){return $stmt->error;}
  $stmt->close();
 }
 return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
?>