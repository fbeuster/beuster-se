
<article>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
  <section class="article">
  <?php if(isset($data['fe'])){ ?>
    <p>Es ist ein Fehler aufgetreten! Typenummer: <?php echo $data['fe']['t']; ?></p>
  <?php } ?>
    <form action="/newsneu" method="post" enctype="multipart/form-data" class="userform articleform">
      <fieldset>
        <legend>Neuen News-Eintrag verfassen</legend>
        <label class="required long">
          <span>Titel</span>
          <input type="text" name="newstitel" value="<?php if(isset($data['fe']['titel']))echo $data['fe']['titel']; ?>" role="newEntryTitle" placeholder="Titel des Blogeintrags">
        </label>

        <label>
          <span>Manuelle Freigabe</span>
          <input type="checkbox" name="enable">
        </label>

        <label>
          <span>Releasedatum (YYYY-MM-DD)</span>
          <input type="date" name="release" value="<?php if(isset($data['fe']['rel']))echo $data['fe']['rel']; ?>">
        </label>

        <label class="required">
          <span>Kategorie</span>
          <select name="cat" class="catSelect">
            <option value="error">Kategorie wählen...</option>
            <?php foreach($data['cats'] as $cat) { ?>
            <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
            <?php } ?>
          </select>
        </label>

        <label>
          <span>Neue Kategorie</span>
          <select name="catPar">
            <option value="error">Parent wählen...</option>
            <?php foreach($data['pars'] as $par) { ?>
            <option value="<?php echo $par; ?>"><?php echo getCatName($par); ?></option>
            <?php } ?>
          </select>
          <input type="text" name="catneu" title="Name neue Kategorie" placeholder="Name der neuen Kategorie">
        </label>

        <label>
          <span>Playlist</span>
          <select name="pl">
            <option value="error">Playlist wählen...</option>
            <?php foreach($data['pls'] as $pl) { ?>
            <option value="<?php echo $pl; ?>"><?php echo $pl; ?></option>
            <?php } ?>
          </select>
          <input type="text" name="plneu" title="Name neue Playlist" placeholder="Name der neuen Playlist">
          <input type="text" name="plneuid" title="ID neue Playlist" placeholder="ID der neuen Playlist">
        </label>

        <label class="required long">
          <span>Tags</span>
          <input type="text" name="tags" title="Tags für den Artikel" role="newEntryTags" placeholder="Tag (durch Komma trennen)">
        </label>

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

        <label class="required long">
          <span>Inhalt</span>
          <?php
            $content  = isset($data['fe']['inhalt']) ? $data['fe']['inhalt'] : '';
            $editor   = new Editor('newsinhalt', 'newsinhalt', $content);
            $editor->show();
          ?>
        </label>

        <p>
          Bilder anhängen, maximal 5MB. Bestes Ergebnis bei 960*540px
        </p>
        <label class="required">
          <span>Thumbnail-Nr.</span>
          <input type="text" name="thumb" placeholder="Nummer aus der Liste">
        </label>

        <ol id="files">
          <li><input type="file" name="file[]"></li>
        </ol>
        <input type="button" value="Feld loeschen" class="delInp"><input type="button" value="Feld hinzufügen" id="addInp"><br><br>
        <input type="submit" name="formaction" value="News eintragen" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
</article>
