  <div class="beContentEntry">
<?php
/*
 * Daten:
 * 'news' -- Array mit Newsbeiträgen mit folgendem Aufbau
 *           Array(
 *              newsid    - ID der News
 *              newstitel - Titel der News
 *              newsdatum - Verfassungsdatum der News
 *           )
 * 'newsbea' -- Array mit zu editierenden Newsbeitrag mit folgendem Aufbau
 *              newsid    - ID der News
 *              newstitel - Titel der News
 *              newsdatum - Verfassungsdatum der News
 */
?>
    <form action="/newsdel" method="post">
      <fieldset class="backend newEntry">
        <legend>Blogeintrag löschen</legend>
        <label>Blogeintrag wählen:</label>
        <select name="newsid">
          <option value="0">Bitte wählen...</option>
          <?php foreach($data['news'] as $value){ ?>
          <option value="<?php echo $value['newsid'] ?>">
            <?php echo $value['newsdatum']; ?> | <?php echo Parser::parse($value['newstitel'], Parser::TYPE_PREVIEW); ?>
          </option>
          <?php } ?>
        </select>
        <input type="submit" name="formactionchoose" value="Gewählten Blogeintrag löschen..." />
        <br />
        <?php if(isset($data['newsbea'])) {$newsbea = $data['newsbea'];}
              else {$newsbea = array('newstitel' => '', 'newsinhalt' => '', 'newsidbea' => '');}?>
        <label>Titel:</label>
        <input type="text" name="newstitel" value="<?php echo $newsbea['newstitel']; ?>" readonly />
        <input type="hidden" name="newsid2" size="3" value="<?php echo $newsbea['newsidbea']; ?>" />
        <br />
        <label style="float: left;">Inhalt:</label>
        <textarea name="newsinhalt" id="newsinhalt" cols="80" rows="20" style="vertical-align: top; float: left;" readonly ><?php echo $newsbea['newsinhalt']; ?></textarea>
        <br style="clear: left;" />
        <input type="submit" name="formactiondel" value="Blogeintrag löschen" />
       </fieldset>
      </form>
    <p class="backendBackLink">
      <a href="/admin" class="back">Zurück zur Administration</a>
    </p>
  </div>