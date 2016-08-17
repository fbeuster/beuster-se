
<article>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
  <section class="article">

  <?php
    $e = 0;

    if(isset($data['err'])) {
      $e = 1
  ?>
    <p class="alert notice">
      Ups, da ist was schief gelaufen.<br>
      <?php echo $data['err']['type']; ?>
    </p>
  <?php } ?>

    <form action="/newsedit" method="post" class="userform articleform">
      <fieldset>
        <legend>News-Einträge bearbeiten</legend>
        <label class="required long">
          <span>News wählen</span>
          <select name="newsid">
            <option value="0">Bitte wählen...</option>
            <?php foreach($data['news'] as $value) { ?>
              <option value="<?php echo $value['newsid']; ?>">
              <?php echo $value['newsdatum']; ?> |
              <?php echo Parser::parse($value['newstitel'], Parser::TYPE_PREVIEW); ?>
              </option>
            <?php } ?>
          </select>
        </label>
        <input type="submit" name="formactionchoose" value="Gewählte News bearbeiten">
        <br>

        <label>
          <span>Manuelle Freigabe</span>
          <input type="checkbox" name="enable" <?php echo isset($data['newsedit']) && $data['newsedit']['newsena'] == 0 ? ' checked="checked"' : ''; ?>>
        </label>

        <?php
          if(isset($data['newsedit'])) {
            $newsedit = $data['newsedit'];
          } else {
            $newsedit = array( 'newstitel' => '',
                              'newsinhalt' => '',
                              'newsidbea' => '',
                              'newstags' => '');
          }
        ?>

        <label class="required long">
          <span>Titel</span>
          <input type="text" name="newstitel" value="<?php if($e) echo $data['err']['titel']; else echo $newsedit['newstitel']; ?>" role="newEntryTitle" placeholder="Titel des Blogeintrags">
        </label>

        <input type="hidden" name="newsid2" size="3" value="<?php if($e) echo $data['err']['titel']; else echo $newsedit['newsidbea']; ?>">

        <label class="required">
          <span>Kategorie</span>
          <select name="cat" class="catSelect">
            <option value="error">Kategorie wählen...</option>
            <?php foreach($data['cats'] as $cat) {
                    if(!$newsedit['isPlaylist'] && $newsedit['newscat'] == $cat) {
                      $selected = ' selected="selected"';
                    } else {
                      $selected = '';
                    } ?>
              <option <?php echo 'value="'.$cat.'"'.$selected; ?>>
                <?php echo $cat; ?>
              </option>
            <?php } ?>
          </select>
        </label>

        <label>
          <span>Neue Kategorie</span>
          <select name="catPar">
            <option value="error">Parent wählen...</option>
            <?php foreach($data['pars'] as $par) { ?>
              <option value="<?php echo $par; ?>">
                <?php echo $par; ?>
              </option>
            <?php } ?>
          </select>
          <input type="text" name="catneu" title="Name neue Kategorie">
        </label>

        <label>
          <span>Playlist</span>
          <select name="pl">
            <option value="error">Playlist wählen...</option>
            <?php foreach($data['pls'] as $pl) {
                    if($newsedit['isPlaylist'] && $newsedit['newscat'] == $pl) {
                      $selected = ' selected="selected"';
                    } else {
                      $selected = '';
                    } ?>
            <option <?php echo 'value="'.$pl.'"'.$selected.'>'.$pl; ?></option>
            <?php } ?>
          </select>
          <input type="text" name="plneu" title="Name neue Playlist">
          <input type="text" name="plneuid" title="ID neue Playlist">
        </label>

        <label class="long">
          <span>Tags</span>
          <input type="text" name="tags" title="Tags für den Artikel" value="<?php echo $newsedit['newstags']; ?>" role="newEntryTags" placeholder="Tag (durch Komma trennen)">
        </label>

        <p class="newsNewHelp">
          <span class="newsNewProj" style="display: none;">
            <br>
            <label class="alert description">Projektstatus</label>
            <select name="projStat" class="projChoose" disabled="disabled">
              <option value="0">Projektstatus wählen...</option>
              <option value="1">in Bearbeitung</option>
              <option value="2">nicht vordergründig</option>
              <option value="3">pausiert</option>
              <option value="4">beendet</option>
            </select>
          </span>
          <br>
          <span class="newsNewHelpPort">
            Syntax für Portfolioeintrag:<br>
            Gruppenname###ID/Dateiname###Infotext<br><br>
          </span>
        </p>

        <label class="required long">
          <span>Inhalt</span>
          <?php
            $content  = $e ? $data['err']['inhalt'] : $newsedit['newsinhalt'];
            $editor   = new Editor('newsinhalt', 'newsinhalt', $content);
            $editor->show();
          ?>
        </label>

        <?php if(!empty($data['pfad'])) { ?>
          <table style="float: left;">
            <thead>
              <tr>
                <th>&nbsp;</th>
                <th>Thumb.</th>
                <th>Del.</th>
                <th>Code</th>
              </tr>
            </thead>
            <tbody class="adThumb">
            <?php  foreach($data['pfad'] as $pic) {
                      $pfad = str_replace('blog/id', 'blog/thid', $pic['pfad']);
                      $pfad = str_replace('.', '_', $pfad);
            ?>
              <tr>
                <td><img src="<?php echo makeAbsolutePath($pfad, '.jpg', true); ?>" name="" class="adThumb"></td>
                <td><input type="radio" name="thumbOld" value="<?php echo $pic['id']; ?>"<?php if($pic['thumb'] == 1) { echo ' checked="checked"'; } ?>></td>
                <td><input type="checkbox" name="del[]" value="<?php echo $pic['id']; ?>"></td>
                <td>[img<?php echo $pic['id']; ?>]</td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
          <div class="adImg">
            <img src="/images/spacer.gif" alt="">
          </div>
        <?php } ?>


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

        <input type="button" value="Feld loeschen" class="delInp">
        <input type="button" value="Feld hinzufügen" id="addInp"><br><br>
        <input type="submit" name="formactionchange" value="News ändern" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back">&lt; Zurück zur Administration</a>
</article>