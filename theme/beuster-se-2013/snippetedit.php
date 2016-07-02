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
        <?php
          $content  = $e ? $data['err']['inhalt'] : $snippetedit['content'];
          $editor   = new Editor('newsinhalt', 'content', $content);
          $editor->show();
        ?>
        <br class="clear"><br>
        <input type="submit" name="formactionchange" value="News ändern" />
      </fieldset>
    </form>
    <p class="backendBackLink">
      <a href="/admin" class="back">Zurück zur Administration</a>
    </p>
  </div>