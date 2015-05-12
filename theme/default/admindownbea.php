
      <?php if(isset($data['fm'])){ ?>
      <p>Fehler bei einer oder mehreren Dateien:</p>
      <pre><?php print_r($data['fm']); ?></pre>
      <?php } ?>
      <form action="/admindownbea" method="post">
       <fieldset id="admin">
        <label>Donwload wählen:</label>
        <select name="down">
         <option value="0">Bitte wählen...</option>
         <?php foreach($data['downs'] as $down) { ?>
         <option value="<?php echo $down['id']; ?>"><?php echo $down['name']; ?></option>
         <?php } ?>
        </select>
        <input type="submit" name="formactionchoose" value="Wählen">
        <br><br>
        <legend>Download bearbeiten</legend>
        <label>Name:</label>
        <?php $name = isset($data['fe']) ? $data['fe']['titel'] : (isset($data['down']) ? $data['down']['name'] : ''); ?>
        <input type="text" name="downname" value="<?php echo $name ?>">
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
        <?php $version = isset($data['down']) ? $data['down']['ver'] : ''; ?>
        <input type="text" name="downver" value="<?php echo $version; ?>">
        <br><br>
        <label>Beschreibung:</label>
        <br>
        <p class="bb">
          <noscript>
          Möglicher BB-Code:<br />
          <?php foreach($bb as $bblist) {
                echo $bblist.'<br />';
                } ?>
          </noscript>
          <script>
           edToolbar('newsinhalt');
          </script>
        </p>
        <?php $description = isset($data['fe']) ? $data['fe']['descr'] : (isset($data['down']) ? $data['down']['descr'] : ''); ?>
        <textarea name="downdescr" id="newsinhalt" cols="100" rows="20"><?php echo $description; ?></textarea>
        <br class="clear"><br>
        <input type="submit" name="formaction" value="Download hinzufügen" />
       </fieldset>
      </form>
    <p>
      <a href="/admin">Zurück zur Administration</a>
    </p>
