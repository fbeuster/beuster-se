<?php
  $page         = Lixter::getLix()->getPage();
  $action       = $page->getContent()['action'];
  $form_action  = $page->getContent()['form_action'];
  $submit       = $page->getContent()['submit'];
?>

<article>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
  <section class="article">
    <?php if(isset($data['errors'])){ ?>
      <div class="error">
        <div class="title">Error</div>
        <ul class="messages">
        <?php foreach ($data['errors'] as $name => $error) { ?>

          <?php if (!isset($error['message'])) { ?>
            <?php foreach ($error as $single) { ?>
              <li><?php echo $single['message']; ?></li>
            <?php } ?>

          <?php } else { ?>
            <li><?php echo $error['message']; ?></li>
          <?php } ?>

        <?php } ?>
        </ul>
      </div>
    <?php } ?>

    <form action="/<?php echo $form_action; ?>" method="post" enctype="multipart/form-data" class="userform articleform">
      <fieldset>
        <legend><?php I18n::e('admin.article.'.$action.'.label'); ?></legend>

        <?php if ($action == 'edit') { ?>

          <label class="required long <?php if(isset($data['errors']['article'])) { echo ' has_error'; } ?>">
            <span>
              <?php I18n::e('admin.article.edit.choose.label'); ?>
            </span>
            <select name="article">
              <option value="0">
                <?php I18n::e('admin.article.edit.choose.placeholder'); ?>
              </option>
              <?php foreach($data['articles'] as $article) { ?>
                <option value="<?php echo $article['id']; ?>">
                <?php echo $article['date']; ?> |
                <?php echo $article['title']; ?>
                </option>
              <?php } ?>
            </select>
          </label>
          <input type="submit" name="formactionchoose" value="<?php I18n::e('admin.article.edit.choose.submit'); ?>">
          <br>

        <?php } ?>

        <label class="required long <?php if(isset($data['errors']['title'])) { echo ' has_error'; } ?>">
          <span><?php I18n::e('admin.article.'.$action.'.title.label'); ?></span>
          <input type="text" name="title" role="newEntryTitle" placeholder="<?php I18n::e('admin.article.'.$action.'.title.placeholder'); ?>" value="<?php if (isset($data['values'], $data['values']['title'])) { echo $data['values']['title']; } ?>">
        </label>

        <?php if ($action == 'edit') { ?>

          <input type="hidden" name="article_id" size="5" value="<?php if (isset($data['values']['article_id'])) { echo $data['values']['article_id']; } ?>">

        <?php } ?>

        <label>
          <span>
            <?php I18n::e('admin.article.'.$action.'.manual_release_label'); ?>
          </span>
          <input type="checkbox" name="unlisted" <?php echo isset($data['values']) && $data['values']['unlisted'] ? ' checked="checked"' : ''; ?>>
        </label>

        <label class="<?php if(isset($data['errors']['release_date'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.article.'.$action.'.release_date.label'); ?>
          </span>
          <input type="date" name="release_date" value="<?php if(isset($data['values']['release_date'])) echo $data['values']['release_date']; ?>" placeholder="<?php I18n::e('admin.article.'.$action.'.release_date.placeholder'); ?>">
        </label>

        <label class="<?php if(isset($data['errors']['release_time'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.article.'.$action.'.release_time.label'); ?>
          </span>
          <input type="time" name="release_time" value="<?php if(isset($data['values']['release_time'])) echo $data['values']['release_time']; ?>" placeholder="<?php I18n::e('admin.article.'.$action.'.release_time.placeholder'); ?>">
        </label>

        <label class="required <?php if(isset($data['errors']['category'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.article.'.$action.'.category.label'); ?>
          </span>
          <select name="category" class="catSelect">
            <option value="error">
              <?php I18n::e('admin.article.'.$action.'.category.placeholder'); ?>
            </option>
            <?php foreach($data['categories'] as $category) {
                    if(isset($data['values']['category']) && $data['values']['category'] == $category) {
                      $selected = ' selected="selected"';
                    } else {
                      $selected = '';
                    } ?>
            <option <?php echo 'value="'.$category.'"'.$selected; ?>><?php echo $category; ?></option>
            <?php } ?>
          </select>
        </label>

        <label class="<?php if(isset($data['errors']['category_new']) || isset($data['errors']['category_parent'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.article.'.$action.'.new_category.label'); ?>
          </span>
          <select name="category_parent">
            <option value="error">
              <?php I18n::e('admin.article.edit.new_category.placeholder_select'); ?>
            </option>
            <?php foreach($data['parents'] as $parent) {
                    if(isset($data['values']['category_parent']) && $data['values']['category_parent'] == $parent) {
                      $selected = ' selected="selected"';
                    } else {
                      $selected = '';
                    } ?>
            <option <?php echo 'value="'.$parent.'"'.$selected; ?>><?php echo getCatName($parent); ?></option>
            <?php } ?>
          </select>
          <input type="text" name="category_new" placeholder="<?php I18n::e('admin.article.'.$action.'.new_category.placeholder_input'); ?>" title="<?php I18n::e('admin.article.'.$action.'.new_category.placeholder_input'); ?>" value="<?php if(isset($data['values']['category_new'])) echo $data['values']['category_new']; ?>">
        </label>

        <label class="<?php if(isset($data['errors']['playlist'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.article.'.$action.'.playlist.label'); ?>
          </span>
          <select name="playlist">
            <option value="error">
              <?php I18n::e('admin.article.'.$action.'.playlist.placeholder'); ?>
            </option>
            <?php foreach($data['playlists'] as $playlist) {
                    if(isset($data['values']['playlist']) && $data['values']['playlist'] == $playlist) {
                      $selected = ' selected="selected"';
                    } else {
                      $selected = '';
                    } ?> ?>
              <option <?php echo 'value="'.$playlist.'"'.$selected; ?>"><?php echo $playlist; ?></option>
            <?php } ?>
          </select>
        </label>

        <label class="<?php if(isset($data['errors']['playlist_new']) || isset($data['errors']['laylist_new_id'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.article.'.$action.'.new_playlist.label'); ?>
          </span>
          <input type="text" name="playlist_new" placeholder="<?php I18n::e('admin.article.'.$action.'.new_playlist.placeholder_name'); ?>" title="<?php I18n::e('admin.article.'.$action.'.new_playlist.placeholder_name'); ?>" value="<?php if(isset($data['values']['playlist_new'])) echo $data['values']['playlist_new']; ?>">

          <input type="text" name="playlist_new_id" placeholder="<?php I18n::e('admin.article.'.$action.'.new_playlist.placeholder_id'); ?>" title="<?php I18n::e('admin.article.'.$action.'.new_playlist.placeholder_id'); ?>" value="<?php if(isset($data['values']['playlist_new_id'])) echo $data['values']['playlist_new_id']; ?>">
        </label>

        <label class="required long <?php if(isset($data['errors']['tags'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.article.'.$action.'.tags.label'); ?>
          </span>
          <input type="text" name="tags" title="<?php I18n::e('admin.article.'.$action.'.tags.placeholder'); ?>" value="<?php if (isset($data['values']['tags'])) echo $data['values']['tags']; ?>" role="newEntryTags" placeholder="<?php I18n::e('admin.article.'.$action.'.tags.placeholder'); ?>">
        </label>

        <p class="newsNewHelp">
          <span class="newsNewProj" style="display: none;">
            <br>
            <label class="description alert">Projektstatus</label>
            <select name="project_status" class="projChoose drop200" disabled="disabled">
              <option value="0">Projektstatus wählen...</option>
              <option value="1">in Bearbeitung</option>
              <option value="2">nicht vordergründig</option>
              <option value="3">pausiert</option>
              <option value="4">beendet</option>
            </select>
          </span>
        </p>

        <label class="required long <?php if(isset($data['errors']['content'])) { echo ' has_error'; } ?>" for="newsinhalt">
          <span>
            <?php I18n::e('admin.article.'.$action.'.content_label'); ?>
          </span>
          <?php
            $content  = isset($data['values']['content']) ? $data['values']['content'] : '';
            $editor   = new Editor('newsinhalt', 'content', $content);
            $editor->show();
          ?>
        </label>

        <?php if ($action == 'edit' &&
                  !empty($data['images'])) { ?>
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
            <?php  foreach($data['images'] as $image) { ?>
              <tr>
                <td><img src="<?php echo makeAbsolutePath(Image::ARTICLE_IMAGE_PATH . $image['path'], '', true); ?>" name="" class="adThumb"></td>
                <td><input type="radio" name="thumbnail_old" value="<?php echo $image['id']; ?>"<?php if($image['thumb'] == 1) { echo ' checked="checked"'; } ?>></td>
                <td><input type="checkbox" name="del[]" value="<?php echo $image['id']; ?>"></td>
                <td>[img<?php echo $image['id']; ?>]</td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
          <div class="adImg">
            <img src="/images/spacer.gif" alt="">
          </div>
        <?php } ?>

        <p>
          <?php I18n::e('admin.article.'.$action.'.pictures.info', array('5MB')); ?>
        </p>
        <label<?php if (isset($data['errors']['thumbnail'])) { echo ' class="has_error"'; } ?>>
          <span>
            <?php I18n::e('admin.article.'.$action.'.thumbnail.label'); ?>
          </span>
          <input type="number" name="thumbnail" value="<?php if (isset($data['values']['thumbnail'])) echo $data['values']['thumbnail']; ?>" placeholder="<?php I18n::e('admin.article.'.$action.'.thumbnail.placeholder'); ?>">
        </label>

        <ol id="files">
          <li><input type="file" name="file[]"></li>
        </ol>

        <input type="button" value="<?php I18n::e('admin.article.'.$action.'.thumbnail.remove_field'); ?>" class="delInp">
        <input type="button" value="<?php I18n::e('admin.article.'.$action.'.thumbnail.add_field'); ?>" id="addInp"><br><br>
        <input type="submit" name="<?php echo $submit; ?>" value="<?php I18n::e('admin.article.'.$action.'.submit'); ?>" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
