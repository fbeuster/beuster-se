  <div class="beContentEntry">
  <?php if(isset($data['fe'])){ ?>
    <p class="alert">Es ist ein Fehler aufgetreten! Typenummer: <?php echo $data['fe']['t']; ?></p>
  <?php } ?>
    <form action="/snippetnew" method="post" enctype="multipart/form-data">
      <fieldset class="backend newEntry">
        <legend>Neues Snippet erstellen</legend>
        <label>
          Name
          <input type="text" name="name" title="Name of the Snippet">
        </label>
        <label>Inhalt:</label>
        <br>
        <?php
          $content  = isset($data['fe']['inhalt']) ? $data['fe']['inhalt'] : '';
          $editor   = new Editor('newsinhalt', 'content', $content);
          $editor->show();
        ?>
        <br class="clear">
        <input type="submit" name="formaction" value="Snippet anlegen" />
      </fieldset>
    </form>
    <p class="backendBackLink">
      <a href="/admin" class="back">Zurück zur Administration</a>
    </p>
  </div>