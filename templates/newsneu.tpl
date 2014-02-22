  <div class="beContentEntry">
  <?php if(isset($data['fe'])){ ?>
    <p class="alert">Es ist ein Fehler aufgetreten! Typenummer: <?php echo $data['fe']['t']; ?></p>
  <?php } ?>
    <form action="/newsneu" method="post" enctype="multipart/form-data">
      <fieldset class="backend newEntry">
        <legend>Neuen News-Eintrag verfassen</legend>
        <input type="text" name="newstitel" value="<?php if(isset($data['fe']['titel']))echo $data['fe']['titel']; ?>" role="newEntryTitle" placeholder="Titel des Blogeintrags">
        <br>
        <input type="checkbox" name="enable">
        <label>Manuelle Freigabe</label>
        <br>
        <label>Releasedatum (YYYY-MM-DD):</label>
        <input type="date" name="release" value="<?php if(isset($data['fe']['rel']))echo $data['fe']['rel']; ?>">
        <br>
        <label class="description">Kategorie:</label>
        <select name="cat" class="catSelect drop200">
          <option value="error" class="optTop">Kategorie wählen...</option>
          <?php foreach($data['cats'] as $cat) { ?>
          <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
          <?php } ?>
        </select><br>
        <label class="description">(neu)</label>
        <select name="catPar" class="drop200">
          <option value="error" class="optTop">Parent wählen...</option>
          <?php foreach($data['pars'] as $par) { ?>
          <option value="<?php echo $par; ?>"><?php echo getCatName($db, $par); ?></option>
          <?php } ?>
        </select>
        <input type="text" name="catneu" title="Name neue Kategorie"><br>
        <label class="description">Playlist:</label>
        <select name="pl" class="drop200">
          <option value="error" class="optTop">Playlist wählen...</option>
          <?php foreach($data['pls'] as $pl) { ?>
          <option value="<?php echo $pl; ?>"><?php echo $pl; ?></option>
          <?php } ?>
        </select>
        <input type="text" name="plneu" title="Name neue Playlist">
        <input type="text" name="plneuid" title="ID neue Playlist">
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
        <div class="bbDiv">
          <div class="bbSpan bbSpanFirst">
            <button class="bbImg" type="button" title="Fett" id="btnbold">&nbsp;</button>
            <button class="bbImg" type="button" title="Kursiv" id="btnitalic">&nbsp;</button>
            <button class="bbImg" type="button" title="Unterstrichen" id="btnunderline">&nbsp;</button>
            <button class="bbImg" type="button" title="Makiert" id="btnmark">&nbsp;</button>
            <button class="bbImg" type="button" title="Als Gelöscht auszeichnen" id="btndel">&nbsp;</button>
            <button class="bbImg" type="button" title="Eingefügt (nach Del.)" id="btnins">&nbsp;</button>
          </div>
          <div class="bbSpan">
            <button class="bbImg" type="button" title="Anführungsstriche" id="btnquote">&nbsp;</button>
            <button class="bbImg" type="button" title="Inline-Zitat" id="btncite">&nbsp;</button>
            <button class="bbImg" type="button" title="Blockzitat" id="btnbquote">&nbsp;</button>
          </div>
          <div class="bbSpan">
            <button class="bbImg" type="button" title="Aufzählung" id="btnol">&nbsp;</button>
            <button class="bbImg" type="button" title="Liste" id="btnul">&nbsp;</button>
            <button class="bbImg" type="button" title="Listenelement" id="btnli">&nbsp;</button>
          </div>
          <div class="bbSpan">
            <button class="bbImg" type="button" title="Codebereich" id="btncode">&nbsp;</button>
            <button class="bbImg" type="button" title="Neuer Absatz" id="btnpar">&nbsp;</button>
          </div>
          <div class="bbSpan">
            <button class="bbImg" type="button" title="Link einfügen" id="btnlink">&nbsp;</button>
            <button class="bbImg" type="button" title="YouTube-Video einbetten" id="btnyt">&nbsp;</button>
            <button class="bbImg" type="button" title="YouTube-Playlist einbinden" id="btnplay">&nbsp;</button>
          </div>
          <div class="bbSpan">
            <button class="bbImg" type="button" title=":)" id="smsmile">&nbsp;</button>
            <button class="bbImg" type="button" title=":(" id="smlaugh">&nbsp;</button>
            <button class="bbImg" type="button" title=":D" id="smsad">&nbsp;</button>
            <button class="bbImg" type="button" title=";)" id="smone">&nbsp;</button>
          </div>
          <div class="bbSpan">
            <button class="bbImg" type="button" title="Überschrift 2" id="btnuber2">&nbsp;</button>
            <button class="bbImg" type="button" title="Überschrift 3" id="btnuber3">&nbsp;</button>
          </div>
        </div>
        <textarea name="newsinhalt" id="newsinhalt" cols="85" rows="20" role="newEntryContent"><?php if(isset($data['fe']['inhalt']))echo $data['fe']['inhalt']; ?></textarea>
        <br class="clear"><br>
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
    <p class="backendBackLink">
      <a href="/admin" class="back">Zurück zur Administration</a>
    </p>
  </div>