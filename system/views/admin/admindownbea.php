
<article>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
  <section class="article">
    <?php if(isset($data['fm'])){ ?>
      <p>Fehler bei einer oder mehreren Dateien:</p>
      <pre><?php print_r($data['fm']); ?></pre>
    <?php } ?>

    <form action="/admindownbea" method="post" class="userform articleform">
      <fieldset id="admin">
        <legend>Download bearbeiten</legend>

        <label class="required long">
          <span>Donwload wählen</span>
          <select name="down">
           <option value="0">Bitte wählen...</option>
           <?php foreach($data['downs'] as $down) { ?>
           <option value="<?php echo $down['id']; ?>"><?php echo $down['name']; ?></option>
           <?php } ?>
          </select>
        </label>

        <input type="submit" name="formactionchoose" value="Wählen">

        <label class="required long">
          <span>Name</span>
          <?php
            if (isset($data['fe'])) {
              $name = $data['fe']['titel'];

            } else {
              if (isset($data['down'])) {
                $name = $data['down']['name'];
              } else {
                $name = '';
              }
            }
          ?>
          <input type="text" name="downname" value="<?php echo $name ?>">
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
          <?php
            $version = isset($data['down']) ? $data['down']['ver'] : '';
          ?>
          <input type="text" name="downver" value="<?php echo $version; ?>">
        </label>

        <label class="required long" for="newsinhalt">
          <span>Beschreibung</span>
          <?php
            $content  = isset($data['fe']) ? $data['fe']['descr'] : (isset($data['down']) ? $data['down']['descr'] : '');
            $editor   = new Editor('newsinhalt', 'downdescr', $content);
            $editor->show();
          ?>
        </label>

        <input type="submit" name="formaction" value="Download hinzufügen" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
</article>
