
  <?php if(isset($data['fe'])){ ?>
    <p>Es ist ein Fehler aufgetreten! Typenummer: <?php echo $data['fe']['t']; ?></p>
  <?php } ?>
    <form action="/newsneu" method="post" enctype="multipart/form-data">
      <fieldset>
        <legend>Neuen News-Eintrag verfassen</legend>
        <input type="text" name="newstitel" value="<?php if(isset($data['fe']['titel']))echo $data['fe']['titel']; ?>" role="newEntryTitle" placeholder="Titel des Blogeintrags">
        <br>
        <input type="checkbox" name="enable">
        <label>Manuelle Freigabe</label>
        <br>
        <label>Releasedatum (YYYY-MM-DD):</label>
        <input type="date" name="release" value="<?php if(isset($data['fe']['rel']))echo $data['fe']['rel']; ?>">
        <br>
        <label>Kategorie:</label>
        <select name="cat" class="catSelect">
          <option value="error">Kategorie wählen...</option>
          <?php foreach($data['cats'] as $cat) { ?>
          <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
          <?php } ?>
        </select><br>
        <label>(neu)</label>
        <select name="catPar">
          <option value="error">Parent wählen...</option>
          <?php foreach($data['pars'] as $par) { ?>
          <option value="<?php echo $par; ?>"><?php echo getCatName($par); ?></option>
          <?php } ?>
        </select>
        <input type="text" name="catneu" title="Name neue Kategorie"><br>
        <label>Playlist:</label>
        <select name="pl">
          <option value="error">Playlist wählen...</option>
          <?php foreach($data['pls'] as $pl) { ?>
          <option value="<?php echo $pl; ?>"><?php echo $pl; ?></option>
          <?php } ?>
        </select>
        <input type="text" name="plneu" title="Name neue Playlist">
        <input type="text" name="plneuid" title="ID neue Playlist"><br>
        <label>
          Tags (durch Komma trennen):
        </label>
        <input type="text" name="tags" title="Tags für den Artikel" role="newEntryTags">
        <p class="newsNeuHelp">
          <span class="newsNeuProj" style="display: none;">
            <br>
            <label class="description alert">Projektstatus</label>
            <select name="projStat" class="projChoose drop200" disabled="disabled">
              <option value="0">Projektstatus wählen...</option>
              <option value="1">in Bearbeitung</option>
              <option value="2">nicht vordergründig</option>
              <option value="3">pausiert</option>
              <option value="4">beendet</option>
            </select>
          </span>
          <br>
          <span class="newsNeuHelpPort">
            Syntax für Portfolioeintrag:<br>
            Gruppenname###ID/Dateiname###Infotext<br><br>
          </span>
        </p>
        <label>Inhalt:</label>
        <br>
        <?php
          $content  = isset($data['fe']['inhalt']) ? $data['fe']['inhalt'] : '';
          $editor   = new Editor('newsinhalt', 'newsinhalt', $content);
          $editor->show();
        ?>
        <br>
        <p style="padding: 0;">Bilder anhängen, maximal 5MB. Bestes Ergebnis bei 750*500px.</p>
        <label style="width: 100%;">
          Thumbnail (Nummer aus der Liste angeben)
          <input type="text" name="thumb">
        </label>
        <br>
        <br>
        <ol id="files">
          <li><input type="file" name="file[]"></li>
        </ol>
        <input type="button" value="Feld loeschen" class="delInp"><input type="button" value="Feld hinzufügen" id="addInp"><br><br>
        <input type="submit" name="formaction" value="News eintragen" />
      </fieldset>
    </form>
    <p>
      <a href="/admin">Zurück zur Administration</a>
    </p>
