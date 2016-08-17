
<article>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
  <section class="article">
    <?php if(isset($data['fm'])){ ?>
      <p>Fehler bei einer oder mehreren Dateien:</p>
      <pre><?php print_r($data['fm']); ?></pre>
    <?php } ?>

    <form action="/admindown" method="post" enctype="multipart/form-data" class="userform articleform">
      <fieldset id="admin">
        <legend>Download hinzufügen</legend>
        <label class="required long">
          <span>Name</span>
          <input type="text" name="downname" value="<?php if(isset($data['fe']['titel']))echo $data['fe']['titel']; ?>" placeholder="Name des Downloads">
        </label>

        <label class="required">
          <span>Lizenz</span>
          <select name="downlic">
            <option value="0">Bitte wählen...</option>
            <option value="by">by</option>
            <option value="by-sa">by-sa</option>
            <option value="by-sa-nd">by-sa-nd</option>
            <option value="by-nc">by-nc</option>
            <option value="by-nc-nd">by-nc-nd</option>
          </select>
        </label>

        <label class="required">
          <span>Version</span>
          <input type="text" name="downver" value="<?php if(isset($data['fe']['rel']))echo $data['fe']['rel']; ?>" placeholder="Version number">
        </label>

        <label class="required long">
          <span>Beschreibung</span>
          <?php
            $content  = isset($data['fe']['inhalt']) ? $data['fe']['inhalt'] : '';
            $editor   = new Editor('newsinhalt', 'downdescr', $content);
            $editor->show();
          ?>
        </label>

        <label class="required">
          <span>File</span>
          <input type="file" name="file">
        </label>

        <label class="required">
          <span>Logfile</span>
          <input type="file" name="log">
        </label>

        <input type="submit" name="formaction" value="Download hinzufügen" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
</article>
