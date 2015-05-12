
      <?php if(isset($data['fm'])){ ?>
      <p>Fehler bei einer oder mehreren Dateien:</p>
      <pre><?php print_r($data['fm']); ?></pre>
      <?php } ?>
      <form action="/admindown" method="post" enctype="multipart/form-data">
       <fieldset id="admin">
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
            <button class="bbImg" type="button" title="Amazon Affiliate" id="btnamazon">&nbsp;</button>
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
        <textarea name="downdescr"  id="newsinhalt" cols="100" rows="20"><?php if(isset($data['fe']['inhalt']))echo $data['fe']['inhalt']; ?></textarea>
        <br style="clear: left;" />
        <label>File</label>
        <input type="file" name="file"><br>
        <label>Logfile</label>
        <input type="file" name="log"><br><br>
        <input type="submit" name="formaction" value="Download hinzufügen" />
       </fieldset>
      </form>
    <p>
      <a href="/admin">Zurück zur Administration</a>
    </p>
