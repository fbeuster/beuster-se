  <div class="beContentEntry">
      <?php if(isset($data['fm'])){ ?>
      <p>Fehler bei einer oder mehreren Dateien:</p>
      <pre><?php print_r($data['fm']); ?></pre>
      <?php } ?>
      <form action="/admindown" method="post" enctype="multipart/form-data">
       <fieldset id="admin" class="backend newEntry">
        <legend>Download hinzufügen</legend>
        <label>Name:</label>
        <input type="text" name="downname" value="<?php if(isset($data['fe']['titel']))echo $data['fe']['titel']; ?>">
        <br>
        <label>Lizenz</label>
        <select name="downlic">
         <option value="0">Bitte wählen...</option>
         <option value="by">by</option>
         <option value="by-sa">by-sa</option>
         <option value="by-sa-nd">by-sa-nd</option>
         <option value="by-nc">by-nc</option>
         <option value="by-nc-nd">by-nc-nd</option>
        </select>
        <br>
        <label>Version:</label>
        <input type="text" name="downver" value="<?php if(isset($data['fe']['rel']))echo $data['fe']['rel']; ?>">
        <br><br>
        <label>Beschreibung:</label>
        <br>
        <?php
          $content  = isset($data['fe']['inhalt']) ? $data['fe']['inhalt'] : '';
          $editor   = new Editor('newsinhalt', 'downdescr', $content);
          $editor->show();
        ?>
        <br style="clear: left;" />
        <label>File</label>
        <input type="file" name="file"><br>
        <label>Logfile</label>
        <input type="file" name="log"><br><br>
        <input type="submit" name="formaction" value="Download hinzufügen" />
       </fieldset>
      </form>
    <p class="backendBackLink">
      <a href="/admin" class="back">Zurück zur Administration</a>
    </p>
  </div>