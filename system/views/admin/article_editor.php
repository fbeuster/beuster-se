<?php
  $lb = Lixter::getLix()->getLinkBuilder();
?>

<article>
  <?php include 'navbar.php'; ?>
  <section class="article">
    <?php if(count($this->errors)){ ?>
      <div class="error">
        <div class="title">Error</div>
        <ul class="messages">
        <?php foreach ($this->errors as $name => $error) { ?>

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

    <form action="<?php echo $lb->makeAdminLink($this->form_action); ?>" method="post" enctype="multipart/form-data" class="userform articleform">
      <fieldset>
        <legend><?php I18n::e('admin.article.editor.actions.'.$this->action.'.label'); ?></legend>

        <?php if ($this->action == 'edit') { ?>

          <label class="required long <?php if(isset($this->errors['article'])) { echo ' has_error'; } ?>">
            <span>
              <?php I18n::e('admin.article.editor.choose.label'); ?>
            </span>
            <select name="article">
              <option value="0">
                <?php I18n::e('admin.article.editor.choose.placeholder'); ?>
              </option>
              <?php foreach($this->articles as $article) { ?>
                <option value="<?php echo $article['id']; ?>">
                <?php echo $article['date']; ?> |
                <?php echo $article['title']; ?>
                </option>
              <?php } ?>
            </select>
          </label>
          <input type="submit" name="formactionchoose" value="<?php I18n::e('admin.article.editor.choose.submit'); ?>">
          <br>

        <?php } ?>

        <label class="required long <?php if(isset($this->errors['title'])) { echo ' has_error'; } ?>">
          <span><?php I18n::e('admin.article.editor.title.label'); ?></span>
          <input type="text" name="title" role="newEntryTitle" placeholder="<?php I18n::e('admin.article.editor.title.placeholder'); ?>" value="<?php if (isset($this->values, $this->values['title'])) { echo $this->values['title']; } ?>">
        </label>

        <?php if ($this->action == 'edit') { ?>

          <input type="hidden" name="article_id" size="5" value="<?php if (isset($this->values['article_id'])) { echo $this->values['article_id']; } ?>">

        <?php } ?>

        <label>
          <span>
            <?php I18n::e('admin.article.editor.manual_release_label'); ?>
          </span>
          <input type="checkbox" name="unlisted" <?php echo isset($this->values, $this->values['unlisted']) && $this->values['unlisted'] ? ' checked="checked"' : ''; ?>>
        </label>

        <label class="<?php if(isset($this->errors['release_date'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.article.editor.release_date.label'); ?>
          </span>
          <input type="date" name="release_date" value="<?php if(isset($this->values['release_date'])) echo $this->values['release_date']; ?>" placeholder="<?php I18n::e('admin.article.editor.release_date.placeholder'); ?>">
        </label>

        <label class="<?php if(isset($this->errors['release_time'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.article.editor.release_time.label'); ?>
          </span>
          <input type="time" name="release_time" value="<?php if(isset($this->values['release_time'])) echo $this->values['release_time']; ?>" placeholder="<?php I18n::e('admin.article.editor.release_time.placeholder'); ?>">
        </label>

        <label class="required <?php if(isset($this->errors['category'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.article.editor.category.label'); ?>
          </span>
          <select name="category" class="catSelect">
            <option value="error">
              <?php I18n::e('admin.article.editor.category.placeholder'); ?>
            </option>
            <?php foreach($this->categories as $category) {
                    if(isset($this->values['category']) && $this->values['category'] == $category) {
                      $selected = ' selected="selected"';
                    } else {
                      $selected = '';
                    } ?>
            <option <?php echo 'value="'.$category.'"'.$selected; ?>><?php echo $category; ?></option>
            <?php } ?>
          </select>
        </label>

        <label class="<?php if(isset($this->errors['category_new']) || isset($this->errors['category_parent'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.article.editor.new_category.label'); ?>
          </span>
          <select name="category_parent">
            <option value="error">
              <?php I18n::e('admin.article.editor.new_category.placeholder_select'); ?>
            </option>
            <?php foreach($this->parents as $parent) {
                    if(isset($this->values['category_parent']) && $this->values['category_parent'] == $parent) {
                      $selected = ' selected="selected"';
                    } else {
                      $selected = '';
                    } ?>
            <option <?php echo 'value="'.$parent.'"'.$selected; ?>><?php echo $parent; ?></option>
            <?php } ?>
          </select>
          <input type="text" name="category_new" placeholder="<?php I18n::e('admin.article.editor.new_category.placeholder_input'); ?>" title="<?php I18n::e('admin.article.editor.new_category.placeholder_input'); ?>" value="<?php if(isset($this->values['category_new'])) echo $this->values['category_new']; ?>">
        </label>

        <label class="<?php if(isset($this->errors['playlist'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.article.editor.playlist.label'); ?>
          </span>
          <select name="playlist">
            <option value="error">
              <?php I18n::e('admin.article.editor.playlist.placeholder'); ?>
            </option>
            <?php foreach($this->playlists as $playlist) {
                    if(isset($this->values['playlist']) && $this->values['playlist'] == $playlist) {
                      $selected = ' selected="selected"';
                    } else {
                      $selected = '';
                    } ?> ?>
              <option <?php echo 'value="'.$playlist.'"'.$selected; ?>"><?php echo $playlist; ?></option>
            <?php } ?>
          </select>
        </label>

        <label class="<?php if(isset($this->errors['playlist_new']) || isset($this->errors['laylist_new_id'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.article.editor.new_playlist.label'); ?>
          </span>
          <input type="text" name="playlist_new" placeholder="<?php I18n::e('admin.article.editor.new_playlist.placeholder_name'); ?>" title="<?php I18n::e('admin.article.editor.new_playlist.placeholder_name'); ?>" value="<?php if(isset($this->values['playlist_new'])) echo $this->values['playlist_new']; ?>">

          <input type="text" name="playlist_new_id" placeholder="<?php I18n::e('admin.article.editor.new_playlist.placeholder_id'); ?>" title="<?php I18n::e('admin.article.editor.new_playlist.placeholder_id'); ?>" value="<?php if(isset($this->values['playlist_new_id'])) echo $this->values['playlist_new_id']; ?>">
        </label>

        <label class="required long <?php if(isset($this->errors['tags'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.article.editor.tags.label'); ?>
          </span>
          <input type="text" name="tags" title="<?php I18n::e('admin.article.editor.tags.placeholder'); ?>" value="<?php if (isset($this->values['tags'])) echo $this->values['tags']; ?>" role="newEntryTags" placeholder="<?php I18n::e('admin.article.editor.tags.placeholder'); ?>">
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

        <label class="required long <?php if(isset($this->errors['content'])) { echo ' has_error'; } ?>" for="newsinhalt">
          <span>
            <?php I18n::e('admin.article.editor.content_label'); ?>
          </span>
          <?php
            $content  = isset($this->values['content']) ? $this->values['content'] : '';
            $editor   = new Editor('newsinhalt', 'content', $content);
            $editor->show();
          ?>
        </label>

        <?php if ($this->action == 'edit' &&
                  !empty($this->images)) { ?>
          <table>
            <thead>
              <tr>
                <th>&nbsp;</th>
                <th>
                  <?php I18n::e('admin.article.editor.pictures.table.thumbnail'); ?>
                </th>
                <th>
                  <?php I18n::e('admin.article.editor.pictures.table.delete'); ?>
                </th>
                <th>
                  <?php I18n::e('admin.article.editor.pictures.table.bbcode'); ?>
                </th>
              </tr>
            </thead>
            <tbody class="adThumb">
            <?php  foreach($this->images as $image) { ?>
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
            <img src="/<?php echo Lixter::getLix()->getSystemFile('assets/img/spacer.gif'); ?>" alt="">
          </div>
        <?php } ?>

        <p class="section_header section_opener">
          <?php I18n::e('admin.article.editor.section.images'); ?>
        </p>

        <div class="section_expander">
          <p>
            <?php I18n::e('admin.article.editor.pictures.info', array('5MB')); ?>
          </p>
          <label<?php if (isset($this->errors['thumbnail'])) { echo ' class="has_error"'; } ?>>
            <span>
              <?php I18n::e('admin.article.editor.thumbnail.label'); ?>
            </span>
            <input type="number" name="thumbnail" value="<?php if (isset($this->values['thumbnail'])) echo $this->values['thumbnail']; ?>" placeholder="<?php I18n::e('admin.article.editor.thumbnail.placeholder'); ?>">
          </label>

          <ol id="files">
            <li><input type="file" name="file[]"></li>
          </ol>

          <input type="button" value="<?php I18n::e('admin.article.editor.thumbnail.remove_field'); ?>" class="delInp">
          <input type="button" value="<?php I18n::e('admin.article.editor.thumbnail.add_field'); ?>" id="addInp">
        </div>

        <p class="section_header section_opener">
          <?php I18n::e('admin.article.editor.section.attachments'); ?>
        </p>

        <div class="section_expander">
          <label class="<?php if(isset($this->errors['attachments'])) { echo ' has_error'; } ?>">
            <span>
              <?php I18n::e('admin.article.editor.attachments.label'); ?>
            </span>
            <input type="hidden" name="attachments" value="<?php if (isset($this->values['attachments'])) { echo $this->values['attachments']; } else { echo ';'; } ?>">
            <select name="attachments_select">
              <option value="error">
                <?php I18n::e('admin.article.editor.attachments.placeholder'); ?>
              </option>
              <?php foreach($this->attachments as $attachment) { ?>
                <option <?php echo 'value="'.$attachment['id'].'"'; ?>"><?php echo $attachment['file_name']; ?></option>
              <?php } ?>
            </select>
          </label>
          <ul class="current_attachments"></ul>
        </div>

        <input type="submit" name="<?php echo $this->submit; ?>" value="<?php I18n::e('admin.article.editor.actions.'.$this->action.'.submit'); ?>" />
      </fieldset>
    </form>
  </section>

  <section class="preview article">
    <h2><?php I18n::e('admin.article.preview.title'); ?></h2>
    <p><?php I18n::e('admin.article.preview.description'); ?></p>
    <form class="controls">
      <input type="button"  id="preview_manual_update"
              title="<?php I18n::e('admin.article.preview.manual_update.title'); ?>"
              value="<?php I18n::e('admin.article.preview.manual_update.label'); ?>">

      <label title="<?php I18n::e('admin.article.preview.auto_update.title'); ?>">
        <input type="checkbox" id="preview_auto_update">
        <?php I18n::e('admin.article.preview.auto_update.label'); ?>
      </label>
    </form>
    <div class="content"></div>
  </section>
</article>
