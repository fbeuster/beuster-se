
<article>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
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

    <form action="/newsedit" method="post" enctype="multipart/form-data" class="userform articleform">
      <fieldset>
        <legend><?php I18n::e('admin.article.edit.label'); ?></legend>
        <label class="required long">
          <span>
            <?php I18n::e('admin.article.edit.choose.label'); ?>
          </span>
          <select name="newsid">
            <option value="0">
              <?php I18n::e('admin.article.edit.choose.placeholder'); ?>
            </option>
            <?php foreach($data['news'] as $value) { ?>
              <option value="<?php echo $value['newsid']; ?>">
              <?php echo $value['newsdatum']; ?> |
              <?php echo Parser::parse($value['newstitel'], Parser::TYPE_PREVIEW); ?>
              </option>
            <?php } ?>
          </select>
        </label>
        <input type="submit" name="formactionchoose" value="<?php I18n::e('admin.article.edit.choose.submit'); ?>">
        <br>

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
          <span>
            <?php I18n::e('admin.article.edit.title.label'); ?>
          </span>
          <input type="text" name="newstitel" value="<?php if($e) echo $data['err']['titel']; else echo $newsedit['newstitel']; ?>" role="newEntryTitle" placeholder="<?php I18n::e('admin.article.edit.title.placeholder'); ?>">
        </label>

        <input type="hidden" name="newsid2" size="3" value="<?php if($e) echo $data['err']['titel']; else echo $newsedit['newsidbea']; ?>">

        <label>
          <span>
            <?php I18n::e('admin.article.edit.manual_release_label'); ?>
          </span>
          <input type="checkbox" name="enable" <?php echo isset($data['newsedit']) && $data['newsedit']['newsena'] == 0 ? ' checked="checked"' : ''; ?>>
        </label>

        <label class="required">
          <span>
            <?php I18n::e('admin.article.edit.category.label'); ?>
          </span>
          <select name="cat" class="catSelect">
            <option value="error">
              <?php I18n::e('admin.article.edit.category.placeholder'); ?>
            </option>
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
          <span>
            <?php I18n::e('admin.article.edit.new_category.label'); ?>
          </span>
          <select name="catPar">
            <option value="error">
              <?php I18n::e('admin.article.edit.new_category.placeholder_select'); ?>
              </option>
            <?php foreach($data['pars'] as $par) { ?>
              <option value="<?php echo $par; ?>">
                <?php echo $par; ?>
              </option>
            <?php } ?>
          </select>
          <input type="text" name="catneu" placeholder="<?php I18n::e('admin.article.edit.new_category.placeholder_input'); ?>" title="<?php I18n::e('admin.article.edit.new_category.placeholder_input'); ?>">
        </label>

        <label>
          <span>
            <?php I18n::e('admin.article.edit.playlist.label'); ?>
          </span>
          <select name="pl">
            <option value="error">
              <?php I18n::e('admin.article.edit.playlist.placeholder'); ?>
            </option>
            <?php foreach($data['pls'] as $pl) {
                    if($newsedit['isPlaylist'] && $newsedit['newscat'] == $pl) {
                      $selected = ' selected="selected"';
                    } else {
                      $selected = '';
                    } ?>
            <option <?php echo 'value="'.$pl.'"'.$selected.'>'.$pl; ?></option>
            <?php } ?>
          </select>
        </label>

        <label>
          <span>
            <?php I18n::e('admin.article.edit.new_playlist.label'); ?>
          </span>
          <input type="text" name="plneu" placeholder="<?php I18n::e('admin.article.edit.new_playlist.placeholder_name'); ?>" title="<?php I18n::e('admin.article.edit.new_playlist.placeholder_name'); ?>">
          <input type="text" name="plneuid" placeholder="<?php I18n::e('admin.article.edit.new_playlist.placeholder_id'); ?>" title="<?php I18n::e('admin.article.edit.new_playlist.placeholder_id'); ?>">
        </label>

        <label class="long">
          <span>
            <?php I18n::e('admin.article.edit.tags.label'); ?>
          </span>
          <input type="text" name="tags" title="<?php I18n::e('admin.article.edit.tags.placeholder'); ?>" value="<?php echo $newsedit['newstags']; ?>" role="newEntryTags" placeholder="<?php I18n::e('admin.article.edit.tags.placeholder'); ?>">
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
        </p>

        <label class="required long">
          <span>
            <?php I18n::e('admin.article.edit.content_label'); ?>
          </span>
          <?php
            $content  = $e ? $data['err']['inhalt'] : $newsedit['newsinhalt'];
            $editor   = new Editor('newsinhalt', 'newsinhalt', $content);
            $editor->show();
          ?>
        </label>

        <?php if(!empty($data['pfad'])) { ?>
          <table>
            <thead>
              <tr>
                <th>&nbsp;</th>
                <th>
                  <?php I18n::e('admin.article.edit.pictures.table.thumbnail'); ?>
                </th>
                <th>
                  <?php I18n::e('admin.article.edit.pictures.table.delete'); ?>
                </th>
                <th>
                  <?php I18n::e('admin.article.edit.pictures.table.bbcode'); ?>
                </th>
              </tr>
            </thead>
            <tbody class="adThumb">
            <?php  foreach($data['pfad'] as $pic) { ?>
              <tr>
                <td><img src="<?php echo makeAbsolutePath(Image::ARTICLE_IMAGE_PATH . $pic['pfad'], '', true); ?>" name="" class="adThumb"></td>
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
          <?php I18n::e('admin.article.edit.pictures.info', array('5MB')); ?>
        </p>
        <label class="required">
          <span>
            <?php I18n::e('admin.article.edit.thumbnail.label'); ?>
          </span>
          <input type="text" name="thumb" placeholder="<?php I18n::e('admin.article.edit.thumbnail.placeholder'); ?>">
        </label>

        <ol id="files">
          <li><input type="file" name="file[]"></li>
        </ol>

        <input type="button" value="<?php I18n::e('admin.article.edit.thumbnail.remove_field'); ?>" class="delInp">
        <input type="button" value="<?php I18n::e('admin.article.edit.thumbnail.add_field'); ?>" id="addInp"><br><br>
        <input type="submit" name="formactionchange" value="<?php I18n::e('admin.article.edit.submit'); ?>" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>