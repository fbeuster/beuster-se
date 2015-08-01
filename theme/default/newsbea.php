
<?php

 $e = 0;
 if(isset($data['err'])) {
  $e = 1;?>
  <p class="alert notice">Ups, da ist was schief gelaufen.<br><?php echo $data['err']['type']; ?></p>
 <?php } ?>
    <form action="/newsbea" method="post">
      <fieldset>
        <legend>News-Einträge bearbeiten</legend>
        <label>News wählen:</label>
        <select name="newsid">
          <option value="0">Bitte wählen...</option>
          <?php foreach($data['news'] as $value) { ?>
          <option value="<?php echo $value['newsid'] ?>">
          <?php echo $value['newsdatum']; ?> | <?php echo Parser::parse($value['newstitel'], Parser::TYPE_PREVIEW); ?>
          </option>
          <?php } ?>
        </select>
        <input type="submit" name="formactionchoose" value="Gewählte News bearbeiten">
        <br>
        <label>Manuelle Freigabe</label>
        <input type="checkbox" name="enable" <?php echo isset($data['newsbea']) && $data['newsbea']['newsena'] == 0 ? ' checked="checked"' : ''; ?>>
        <br>
        <?php if(isset($data['newsbea'])) {$newsbea = $data['newsbea'];}
              else {$newsbea = array('newstitel' => '', 'newsinhalt' => '', 'newsidbea' => '', 'newstags' => '');}?>
        <input type="text" name="newstitel" value="<?php if($e) echo $data['err']['titel']; else echo $newsbea['newstitel']; ?>" role="newEntryTitle" placeholder="Titel des Blogeintrags">
        <input type="hidden" name="newsid2" size="3" value="<?php if($e) echo $data['err']['titel']; else echo $newsbea['newsidbea']; ?>">
        <br>
        <label>Kategorie:</label>
        <select name="cat" class="catSelect">
          <option value="error">Kategorie wählen...</option>
          <?php foreach($data['cats'] as $cat) {
                  if(!$newsbea['isPlaylist'] && $newsbea['newscat'] == $cat) {
                    $selected = ' selected="selected"';
                  } else {
                    $selected = '';
                  } ?>
          <option<?php echo ' value="'.$cat.'"'.$selected.'>'.$cat; ?></option>
          <?php } ?>
        </select><br>
        <label>(neu)</label>
        <select name="catPar">
          <option value="error">Parent wählen...</option>
          <?php foreach($data['pars'] as $par) { ?>
          <option value="<?php echo $par; ?>"><?php echo $par; ?></option>
          <?php } ?>
        </select>
        <input type="text" name="catneu" title="Name neue Kategorie"><br>
        <label>Playlist:</label>
        <select name="pl">
          <option value="error">Playlist wählen...</option>
          <?php foreach($data['pls'] as $pl) {
                  if($newsbea['isPlaylist'] && $newsbea['newscat'] == $pl) {
                    $selected = ' selected="selected"';
                  } else {
                    $selected = '';
                  } ?>
          <option<?php echo ' value="'.$pl.'"'.$selected.'>'.$pl; ?></option>
          <?php } ?>
        </select>
        <input type="text" name="plneu" title="Name neue Playlist">
        <input type="text" name="plneuid" title="ID neue Playlist"><br>
        <label>
          Tags (durch Komma trennen):
        </label>
        <input type="text" name="tags" title="Tags für den Artikel" value="<?php echo $newsbea['newstags']; ?>" role="newEntryTags">
        <p class="newsNeuHelp">
          <span class="newsNeuProj" style="display: none;">
            <br>
            <label class="alert description">Projektstatus</label>
            <select name="projStat" class="projChoose" disabled="disabled">
              <option value="0">Projektstatus wählen...</option>
              <option value="1">in Bearbeitung</option>
              <option value="2">nicht vordergründig</option>
              <option value="3">pausiert</option>
              <option value="4">beendet</option>
            </select>
          </span>
          <br>
          <span class="newsNeuHelpPort">
            Syntax für Portfolioeintrag:<br>
            Gruppenname###ID/Dateiname###Infotext<br><br>
          </span>
        </p>
        <label>Inhalt:</label>
        <br>
        <div class="bbDiv">
          <div class="bbSpan bbSpanFirst">
            <button class="bbImg" type="button" title="Fett" id="btnbold">&nbsp;</button>
            <button class="bbImg" type="button" title="Kursiv" id="btnitalic">&nbsp;</button>
            <button class="bbImg" type="button" title="Unterstrichen" id="btnunderline">&nbsp;</button>
            <button class="bbImg" type="button" title="Makiert" id="btnmark">&nbsp;</button>
            <button class="bbImg" type="button" title="Als Gelöscht auszeichnen" id="btndel">&nbsp;</button>
            <button class="bbImg" type="button" title="Eingefügt (nach Del.)" id="btnins">&nbsp;</button>
          </div>
          <div class="bbSpan">
            <button class="bbImg" type="button" title="Anführungsstriche" id="btnquote">&nbsp;</button>
            <button class="bbImg" type="button" title="Inline-Zitat" id="btncite">&nbsp;</button>
            <button class="bbImg" type="button" title="Blockzitat" id="btnbquote">&nbsp;</button>
          </div>
          <div class="bbSpan">
            <button class="bbImg" type="button" title="Aufzählung" id="btnol">&nbsp;</button>
            <button class="bbImg" type="button" title="Liste" id="btnul">&nbsp;</button>
            <button class="bbImg" type="button" title="Listenelement" id="btnli">&nbsp;</button>
          </div>
          <div class="bbSpan">
            <button class="bbImg" type="button" title="Codebereich" id="btncode">&nbsp;</button>
            <button class="bbImg" type="button" title="Neuer Absatz" id="btnpar">&nbsp;</button>
          </div>
          <div class="bbSpan">
            <button class="bbImg" type="button" title="Link einfügen" id="btnlink">&nbsp;</button>
            <button class="bbImg" type="button" title="YouTube-Video einbetten" id="btnyt">&nbsp;</button>
            <button class="bbImg" type="button" title="YouTube-Playlist einbinden" id="btnplay">&nbsp;</button>
            <button class="bbImg" type="button" title="Amazon Affiliate" id="btnamazon">&nbsp;</button>
          </div>
          <div class="bbSpan">
            <button class="bbImg" type="button" title=":)" id="smsmile">&nbsp;</button>
            <button class="bbImg" type="button" title=":(" id="smlaugh">&nbsp;</button>
            <button class="bbImg" type="button" title=":D" id="smsad">&nbsp;</button>
            <button class="bbImg" type="button" title=";)" id="smone">&nbsp;</button>
          </div>
          <div class="bbSpan">
            <button class="bbImg" type="button" title="Überschrift 2" id="btnuber2">&nbsp;</button>
            <button class="bbImg" type="button" title="Überschrift 3" id="btnuber3">&nbsp;</button>
          </div>
        </div>
        <textarea name="newsinhalt" id="newsinhalt" cols="85" rows="20" role="newEntryContent"><?php if($e) echo $data['err']['inhalt']; else echo $newsbea['newsinhalt']; ?></textarea>
        ><br>
        <?php if(!empty($data['pfad'])){ ?>
        <table style="float: left;">
          <thead>
            <tr>
              <th>&nbsp;</th>
              <th>Thumb.</th>
              <th>Del.</th>
              <th>Code</th>
            </tr>
          </thead>
          <tbody class="adThumb">
          <?php  foreach($data['pfad'] as $pic) {
                    $pfad = str_replace('blog/id', 'blog/thid', $pic['pfad']);
                    $pfad = str_replace('.', '_', $pfad);
          ?>
            <tr>
              <td><img src="<?php echo makeAbsolutePath($pfad, '.jpg'); ?>" name="" class="adThumb"></td>
              <td><input type="radio" name="thumbOld" value="<?php echo $pic['id']; ?>"<?php if($pic['thumb'] == 1) { echo ' checked="checked"'; } ?>></td>
              <td><input type="checkbox" name="del[]" value="<?php echo $pic['id']; ?>"></td>
              <td>[img<?php echo $pic['id']; ?>]</td>
            </tr>
          <?php } ?>
          </tbody>
        </table>
        <div class="adImg">
          <img src="/images/spacer.gif" alt="">
        </div>
        <?php } ?>
        <p style="padding: 0;">Bilder anhängen, maximal 5MB. Bestes Ergebnis bei 750*500px.</p>
        <label style="width: 100%;">
          Thumbnail (Nummer aus der Liste angeben, frei lassen für alte Einstellung)
          <input type="text" name="thumb">
        </label>
        <br>
        <ol id="files">
          <li><input type="file" name="file[]"></li>
        </ol>
        <input type="button" value="Feld loeschen" class="delInp"><input type="button" value="Feld hinzufügen" id="addInp"><br><br>
        <input type="submit" name="formactionchange" value="News ändern" />
      </fieldset>
    </form>
    <p>
      <a href="/admin">Zurück zur Administration</a>
    </p>