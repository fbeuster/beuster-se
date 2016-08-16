
<article>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
  <section class="article">
    <?php
      if(isset($data['snippetedit'])) {
        $snippetedit = $data['snippetedit'];

      } else {
        $snippetedit = array('name' => '', 'content' => '');
      }
    ?>
    <form action="/snippetdelete" method="post" class="userform articleform">
      <fieldset>
        <legend>Snippet bearbeiten</legend>
        <label class="long">
          <span>Snippet wählen</span>
          <select name="snippetname">
            <option value="">Bitte wählen...</option>
            <?php foreach($data['snippets'] as $value) { ?>
            <option value="<?php echo $value; ?>">
            <?php echo $value; ?>
            </option>
            <?php } ?>
          </select>
        </label>

        <input type="submit" name="formactionchoose" value="Gewähltes Snippet löschen..." />

        <label class="long">
          <span>Name</span>
          <input type="text" name="name" value="<?php echo $snippetedit['name']; ?>" readonly />
        </label>

        <label class="long">
          <span>Inhalt</span>
          <textarea name="content" id="newsinhalt" cols="80" rows="20" readonly ><?php echo $snippetedit['content']; ?></textarea>
        </label>

        <input type="submit" name="formactiondel" value="Snippet löschen" />
      </fieldset>
    </form>
  </section>

  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
</article>
