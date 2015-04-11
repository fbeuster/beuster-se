<?php
    $a = array();
    if (getUserID() and hasUserRights('admin')) {
        refreshCookies();
        $a['filename'] = 'admincat.php';
        $a['data'] = array();
  
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $errMessages = array(
                1   => 'Ein Parent soll gelöscht werden, aber das Ziel für die News fehlt.',
                2   => 'Ein Parent soll gelöscht werden, aber das Ziel ebenfalls.',
                3   => 'Eine Kategorie soll gelöscht werden, doch für die News fehlt die Zielkategorie.',
                4   => 'Keine Wahl getroffen oder falscher Button.',
                5   => 'Kategorie wurde sowohl zum löschen makiert, als auch neuem Parent unterstellt.',
                6   => 'Feld für neuen Parent ist leer.',
                7   => 'Feld für neue Kategorie ist leer.',
                8   => 'Es fehlt der Parent für die neue Kategorie.',
                9   => 'Neue Kategorie existiert bereits.',
                10  => 'Neuer Parent existiert bereits.',
                11  => 'SQL - ');
            $parNames = getTopCats();
            $parIDs = array();
            foreach($parNames as $par) {
                $parIDs[] = getCatID($par);
            }
            $catNames = getSubCats();
            $catIDs = array();
            foreach($catNames as $c) {
                $catIDs[] = getCatID($c);
            }
            $err = 0;
            if(isset($_POST['parSubmitTable'])) {
                $parDels = array();
                foreach($parIDs as $id) {
                    if(isset($_POST['del'.$id])) {
                        if(isset($_POST['parDelTarget'.$id])) {
                            if($_POST['parDelTarget'.$id] !== 'err') {
                                $parDels[$id] = $_POST['parDelTarget'.$id];
                            } else {
                                $err = 1;
                            }
                        }
                    }
                }
                if(count($parDels) == 0) {
                    $err = 4;
                } else {
                    foreach($parDels as $k => $v) {
                        if(array_search($k, $parDels) !== false) {
                            $err = 2;
                        }
                    }
                    if($err == 0) {
                        // News updaten und Parents löschen
                        foreach($parDels as $del => $tar) {
                            $er = transformCat('newscat', 'ParentID', $tar, $del);
                            if($er !== true) {
                                $errMessages[11] .= $er;
                                $err = 11;
                            }
                            $er = transformCat('newscatcross', 'Cat', $tar, $del);
                            if($err == 0 && $er !== true) {
                                $errMessages[11] .= $er;
                                $err = 11;
                            }
                            $er = removeCat($del);
                            if($err == 0 && $er !== true) {
                                $errMessages[11] .= $er;
                                $err = 11;
                            }
                        }
                    }
                }
            } else if(isset($_POST['catSubmitTable'])) {
                // zu löschende sammeln
                $catDels = array();
                foreach($catIDs as $id) {
                    if(isset($_POST['catDel'.$id])) {
                        if(isset($_POST['catDeleteTarget'.$id])) {
                            if($_POST['catDeleteTarget'.$id] !== 'err') {
                                $catDels[$id] = $_POST['catDeleteTarget'.$id];
                            } else {
                                $err = 3;
                            }
                        }
                    }
                }
                // Verschiebungen sammeln
                $catNewPars = array();
                foreach($catIDs as $id) {
                    if(isset($_POST['catNewPar'.$id])) {
                        if($_POST['catNewPar'.$id] !== 'err') {
                            $catNewPars[$id] = $_POST['catNewPar'.$id];
                        }
                    }
                }
                if(count($catDels) == 0 && count($catNewPars) == 0) {
                    $err = 4;
                } else {
                    // Ist eine Kategorie in beiden Arrays?
                    foreach($catDels as $k => $v) {
                        if(array_search($k, $catDels) !== false) {
                            $err = 2;
                        }
                        if($err == 0) {
                            foreach($catDels as $k => $v) {
                                if(in_array($k, $catNewPars)) {
                                    $err = 5;
                                }
                            }
                        }
                    }     
                    if($err == 0) {
                        // TODO: Alles gut, News updaten und Cats löschen
                        foreach($catDels as $del => $tar) {
                            $er = transformNews($del, $tar, getMaxCatID($tar));
                            if($err == 0 && $er !== true) {
                                $errMessages[11] .= $er;
                                $err = 11;
                            } else {
                                $er = removeCat($del);
                                if($err == 0 && $er !== true) {
                                    $errMessages[11] .= $er;
                                    $err = 11;
                                }
                            }
                        }
                        // TODO: Alles gut, Cat neuen Parents unterstelllen
                        foreach($catNewPars as $old => $tar) {
                            $er = transformCat('newscat', 'ParentID', $tar, $old, 'ID');
                            if($err == 0 && $er !== true) {
                                $errMessages[11] .= $er;
                                $err = 11;
                            }
                        }
                    }
                }
            } else if(isset($_POST['catSubmitNewPar'])) {
                if(isset($_POST['catCreateNewPar'])) {
                    if('' == $newPar = trim($_POST['catCreateNewPar'])) {
                        $err = 6;
                    } else {
                        if(isCat($newPar)) {
                            $err = 10;
                        } else {
                            // Parent anlegen
                            if($er = createCat($newPar, 0, 0) !== true) {
                                $errMessages[11] .= $er;
                                $err = 11;
                            }
                        }
                    }     
                } else {
                    $err = 6;
                }
            } else if(isset($_POST['catSubmitNewCat'])) {
                if(isset($_POST['catCreateNewCat'], $_POST['catCreateNewCatPar'])) {
                    if('' != $newCat = trim($_POST['catCreateNewCat'])) {
                        if('err' != $newCatPar = $_POST['catCreateNewCatPar']) {
                            if(!isCat($newCat)) {
                                // Cat unter Parent anlegen
                                $er = createCat($newCat, $newCatPar, 2);
                                if($er !== true) {
                                    $errMessages[11] .= $er;
                                    $err = 11;
                                }
                            } else {
                                $err = 9;
                            }
                        } else {
                            $err = 8;
                        }
                    } else {
                        $err = 7;
                    }
                } else {
                    $err = 7;
                }
            }
            if($err == 0) {
                return showInfo('Erfolgreich abgeschlossen.<br><a href="/admin" class="back">Zurück zur Administration</a>', 'admin');
            } else {
                return showInfo('Fehler: '.$errMessages[$err].'<br><a href="/admincat" class="back">Zurück zur Kategorieverwaltung</a>', 'admincat');
            }
        }
        $cs = getSubCats();
        $cats = array();
        foreach($cs as $c) {
            $cats[] = array('id' => getCatID($c),
                            'name' => $c,
                            'parent' => getCatName(getCatParent(getCatID($c))));
        }
        $ps = getTopCats();
        $pars = array();
        foreach($ps as $p) {
            $pars[] = array('id' => $p,
                            'name' => getCatName($p));
        }
        $a['data']['cats'] = $cats;
        $a['data']['pars'] = $pars;
        return $a;
    } else if(getUserID()){
        return 'Sie haben hier keine Zugriffsrechte.';
    } else {
        return 'Sie sind nicht eingeloggt. <a href="/login" class="back">Erneut versuchen</a>';
    }
?>