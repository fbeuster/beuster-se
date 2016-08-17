
<article>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
  <section class="article">

    <?php
      $e = 0;

      if(isset($data['err'])) {
        $e = 1;
    ?>
      <p class="alert notice">
        Ups, da ist was schief gelaufen.<br>
        <?php echo $data['err']['type']; ?>
      </p>
    <?php
      }

      if(isset($data['snippetedit'])) {
        $snippetedit = $data['snippetedit'];

      } else {
        $snippetedit = array('name' => '', 'content' => '');
      }
    ?>

    <form action="/snippetedit" method="post" class="userform articleform">
      <fieldset>
        <legend>Snippet bearbeiten</legend>
        <label class="required long">
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

        <input type="submit" name="formactionchoose" value="Gewähltes Snippte bearbeiten">

        <input type="hidden" name="old_name" value="<?php echo $snippetedit['name']; ?>">
        <label class="required long">
          <span>Name</span>
          <input type="text" name="name" title="Name of the Snippet" value="<?php echo $snippetedit['name']; ?>" role="newEntryTags">
        </label>

        <label class="required long">
          <span>Inhalt</span>
          <?php
            $content  = $e ? $data['err']['inhalt'] : $snippetedit['content'];
            $editor   = new Editor('newsinhalt', 'content', $content);
            $editor->show();
          ?>
        </label>

        <input type="submit" name="formactionchange" value="News ändern" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
</article>
