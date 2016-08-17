
<article>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
  <section class="article">
    <?php if(isset($data['fe'])){ ?>
      <p class="alert">
        Es ist ein Fehler aufgetreten! Typenummer: <?php echo $data['fe']['t']; ?>
      </p>
    <?php } ?>
    <form action="/snippetnew" method="post" enctype="multipart/form-data" class="userform articleform">
      <fieldset>
        <legend>Neues Snippet erstellen</legend>
        <label class="required long">
          <span>Name</span>
          <input type="text" name="name" title="Name of the Snippet" placeholder="Name of the snippet">
        </label>
        <label class="required long">
          <span>Inhalt</span>
          <?php
            $content  = isset($data['fe']['inhalt']) ? $data['fe']['inhalt'] : '';
            $editor   = new Editor('newsinhalt', 'content', $content);
            $editor->show();
          ?>
        </label>
        <input type="submit" name="formaction" value="Snippet anlegen" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
</article>
