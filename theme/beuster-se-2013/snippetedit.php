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
 $e = 0;
 if(isset($data['err'])) {
  $e = 1; ?>
  <p class="alert notice">Ups, da ist was schief gelaufen.<br><?php echo $data['err']['type']; ?></p>
 <?php }

if(isset($data['snippetedit'])) {
  $snippetedit = $data['snippetedit'];

} else {
  $snippetedit = array('name' => '', 'content' => '');
} ?>

    <form action="/snippetedit" method="post">
      <fieldset class="backend newEntry">
        <legend>Snippet bearbeiten</legend>
        <label>Snippet wählen:</label>
        <select name="snippetname">
          <option value="">Bitte wählen...</option>
          <?php foreach($data['snippets'] as $value) { ?>
          <option value="<?php echo $value; ?>">
          <?php echo $value; ?>
          </option>
          <?php } ?>
        </select>
        <input type="submit" name="formactionchoose" value="Gewähltes Snippte bearbeiten">
        <br>
        <input type="hidden" name="old_name" value="<?php echo $snippetedit['name']; ?>">
        <label>
          Name:
          <input type="text" name="name" title="Name of the Snippet" value="<?php echo $snippetedit['name']; ?>" role="newEntryTags">
        </label>
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
        <textarea name="content" id="newsinhalt" cols="85" rows="20" role="newEntryContent"><?php if($e) echo $data['err']['inhalt']; else echo $snippetedit['content']; ?></textarea>
        <br class="clear"><br>
        <input type="submit" name="formactionchange" value="News ändern" />
      </fieldset>
    </form>
    <p class="backendBackLink">
      <a href="/admin" class="back">Zurück zur Administration</a>
    </p>
  </div>