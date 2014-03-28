<?php
    $a = array();
    $a['data'] = array();
    $db = Database::getDB()->getCon();
    if(isset($_GET['d'])) {
        $d = trim($_GET['d']) + 0;
        if(is_int($d)) {
            $a['filename'] = 'downloadsone.php';
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
                        downcats ON downloads.CatID = downcats.ID
                    WHERE
                        downloads.ID = ?
                    ORDER BY
                        downcats.CatName ASC,
                        files.downloads DESC";
            if(!$stmt = $db->prepare($sql)) {return $db->error;}
            $stmt->bind_param('i', $d);
            if(!$stmt->execute()) {return $result->error;}
            $stmt->bind_result($id, $name, $descr, $ver, $lic, $log, $fileID, $path, $downloads, $cat);  
            if($stmt->fetch()) {
                $down = array(  'id'      => $id,
                                'name'    => $name,
                                'descr'   => changetext($descr, 'inhalt', $mob),
                                'ver'     => $ver,
                                'path'    => $path,
                                'anz'     => $downloads,
                                'fileID'  => $fileID,
                                'size'    => 0,
                                'lic'     => $lic,
                                'log'     => $log,
                                'loganz'  => 0,
                                'logpath' => '',
                                'logname' => '',
                                'logsize' => 0,
                                'cat'     => $cat,
                                'val'     => time() - (42 * 13 + 37));
            }
            $stmt->close();
            if($down['log'] != 0) {
                $sql = "SELECT
                            Name,
                            Path,
                            downloads
                        FROM
                            files
                        WHERE
                            ID = ?";
                if(!$stmt = $db->prepare($sql)) {return $db->error;}
                $stmt->bind_param('i', $down['log']);
                if(!$stmt->execute()) {return $result->error;}
                $stmt->bind_result($name, $path, $anz);
                while($stmt->fetch()) {
                    $down['loganz'] = $anz;
                    $down['logpath'] = $path;
                    $down['logname'] = $name;
                }
                $stmt->close();
                $down['logsize'] = getSize(filesize($down['logpath']));
            }
            $down['size'] = getSize(filesize($down['path']));
            $down['val'] = filesize($down['path']) + time();
            $a['data']['down'] = $down;
            if(isset($_GET['id']) && isset($_GET['f'])) {
                $qId = trim($_GET['id']);
                $f = trim($_GET['f']);
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
                    if(md5($key) == $f) {
                        $dId = $key;
                        $file = $value;
                    }
                }
    
                if(isset($_GET['finished'])) {
                    if(time() - (42 * 13 + 37) - $qId < 5) {
                        $a['data']['download'] = $file;
                        $a['data']['refresh'] = $file;
                    } else {
                        $redirect = 'Location: http://'.$_SERVER['SERVER_NAME'].'/'.$down['id'].'/downloads/'.$down['name'];
                        header($redirect);
                        exit;
                    }
                } else {
                    if(abs($down['val'] - $qId) < 60) {
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
                        $redirect = 'Location: http://'.$_SERVER['SERVER_NAME'].'/'.$down['id'].'/downloads/'.$down['name'].'/finished/'.(time() - (42 * 13 + 37)).'-'.md5($dId);
                        header($redirect);
                        exit;
                    } else {
                        $redirect = 'Location: http://'.$_SERVER['SERVER_NAME'].'/'.$down['id'].'/downloads/'.$down['name'];
                        header($redirect);
                        exit;
                    }
                }
            }
        } else {
            // n ist keine Integer
        }
    } else {
        $a['filename'] = 'downloads.php';
        $sql = "SELECT
            downloads.ID,
            downloads.Name,
            downloads.Description,
            files.downloads,
            downcats.CatName
         FROM
            downloads
         LEFT JOIN
            files ON downloads.File = files.ID
         JOIN
            downcats ON downloads.CatID = downcats.ID
         ORDER BY
            downcats.CatName ASC,
            downloads.Name DESC,
            files.downloads DESC";
        if(!$stmt = $db->prepare($sql)) {return $db->error;}
        if(!$stmt->execute()) {return $result->error;}
        $down = array();     
        $stmt->bind_result($id, $name, $descr, $downloads, $cat);  
        while($stmt->fetch()) {
            $down[$id] = array( 'id'    => $id,
                                'name'  => $name,
                                'descr' => changetext($descr, 'vorschau', $mob, 200),
                                'anz'   => $downloads,
                                'cat'   => $cat);
        }
        $stmt->close();
        $downCats = array();
        foreach ($down as $entry) {
            if(!in_array($entry['cat'], $downCats)) {
                $downCats[] = $entry['cat'];
            }
        }
        $a['data']['down'] = $down;
        $a['data']['downCats'] = $downCats;
    }
    return $a; // nicht Vergessen, sonst enthält $ret nur den Wert int(1)
?>