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

if(isset($data['snippetedit'])) {
  $snippetedit = $data['snippetedit'];

} else {
  $snippetedit = array('name' => '', 'content' => '');
} ?>
    <form action="/snippetdelete" method="post">
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
        <input type="submit" name="formactionchoose" value="Gewähltes Snippet löschen..." />
        <br />
        <label>
          Name:
          <input type="text" name="name" value="<?php echo $snippetedit['name']; ?>" readonly />
        </label>
        <br />
        <label>Inhalt:</label>
        <textarea name="content" id="newsinhalt" cols="80" rows="20" readonly ><?php echo $snippetedit['content']; ?></textarea>
        <br class="clear" />
        <input type="submit" name="formactiondel" value="Snippet löschen" />
       </fieldset>
      </form>
    <p class="backendBackLink">
      <a href="/admin" class="back">Zurück zur Administration</a>
    </p>
  </div>