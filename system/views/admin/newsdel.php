
<article>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
  <section class="article">
    <form action="/newsdel" method="post" class="userform articleform">
      <fieldset>
        <legend>Blogeintrag löschen</legend>

        <label class="long">
          <span>Blogeintrag wählen</span>
          <select name="newsid">
            <option value="0">Bitte wählen...</option>
            <?php foreach($data['news'] as $value){ ?>
            <option value="<?php echo $value['newsid'] ?>">
              <?php echo $value['newsdatum']; ?> | <?php echo Parser::parse($value['newstitel'], Parser::TYPE_PREVIEW); ?>
            </option>
            <?php } ?>
          </select>
        </label>

        <input type="submit" name="formactionchoose" value="Gewählten Blogeintrag löschen..." />
        <br />
        <?php
          if(isset($data['newsedit'])) {
            $newsedit = $data['newsedit'];
          } else {
            $newsedit = array( 'newstitel' => '',
                              'newsinhalt' => '',
                              'newsidbea' => '');
          }
        ?>
        <label class="long">
          <span>Titel</span>
          <input type="text" name="newstitel" value="<?php echo $newsedit['newstitel']; ?>" readonly />
        </label>

        <input type="hidden" name="newsid2" size="3" value="<?php echo $newsedit['newsidbea']; ?>" />

        <label class="long">
          <span>Inhalt</span>
          <textarea name="newsinhalt" id="newsinhalt" cols="80" rows="20" readonly ><?php echo $newsedit['newsinhalt']; ?></textarea>
        </label>

        <input type="submit" name="formactiondel" value="Blogeintrag löschen" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
</article>