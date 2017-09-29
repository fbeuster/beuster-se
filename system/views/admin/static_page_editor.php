<?php
  $lb = Lixter::getLix()->getLinkBuilder();
?>

<article>
  <a href="<?php echo $lb->makeAdminLink('admin'); ?>" class="back"><?php I18n::e('admin.back_link'); ?></a>
  <section class="article">
    <?php if(count($this->errors)){ ?>
      <div class="error">
        <div class="title">Error</div>
        <ul class="messages">
        <?php foreach ($this->errors as $name => $error) { ?>
          <li><?php echo $error['message']; ?></li>
        <?php } ?>
        </ul>
      </div>
    <?php } ?>
    <form action="<?php echo $lb->makeAdminLink($this->form_action); ?>" method="post" enctype="multipart/form-data" class="userform articleform">
      <fieldset>
        <legend><?php I18n::e('admin.static_page.editor.actions.'.$this->action.'.label'); ?></legend>

        <?php if ($this->action == 'edit') { ?>

          <label class="required long <?php if(isset($this->errors['static_page'])) { echo ' has_error'; } ?>">
            <span>
              <?php I18n::e('admin.static_page.editor.choose.label'); ?>
            </span>
            <select name="static_page">
              <option value="0">
                <?php I18n::e('admin.static_page.editor.choose.placeholder'); ?>
              </option>
              <?php foreach($this->static_pages as $static_page) { ?>
                <option value="<?php echo $static_page['url']; ?>">
                <?php echo $static_page['title']; ?>
                </option>
              <?php } ?>
            </select>
          </label>
          <input type="submit" name="formactionchoose" value="<?php I18n::e('admin.article.editor.choose.submit'); ?>">
          <br>

        <?php } ?>

        <label class="required long <?php if(isset($this->errors['url'])) { echo ' has_error'; } ?>">
          <span><?php I18n::e('admin.static_page.editor.url.label'); ?></span>
          <input type="text" name="url" title="<?php I18n::e('admin.static_page.editor.url.placeholder'); ?>" placeholder="<?php I18n::e('admin.static_page.editor.url.placeholder'); ?>"
          value="<?php if (isset($this->values, $this->values['url'])) { echo $this->values['url']; } ?>">
        </label>

        <?php if ($this->action == 'edit') { ?>
          <input type="hidden" name="old_url" value="<?php echo $this->values['url']; ?>">
        <?php } ?>

        <label class="required long <?php if(isset($this->errors['title'])) { echo ' has_error'; } ?>">
          <span><?php I18n::e('admin.static_page.editor.title.label'); ?></span>
          <input type="text" name="title" title="<?php I18n::e('admin.static_page.editor.title.placeholder'); ?>" placeholder="<?php I18n::e('admin.static_page.editor.title.placeholder'); ?>"
          value="<?php if (isset($this->values, $this->values['title'])) { echo $this->values['title']; } ?>">
        </label>

        <label>
          <span>
            <?php I18n::e('admin.static_page.editor.feedback_label'); ?>
          </span>
          <input type="checkbox" name="has_feedback" <?php echo isset($this->values, $this->values['has_feedback']) && $this->values['has_feedback'] ? ' checked="checked"' : ''; ?>>
        </label>

        <label class="required long <?php if(isset($this->errors['content'])) { echo ' has_error'; } ?>" for="newsinhalt">
          <span><?php I18n::e('admin.static_page.editor.content_label'); ?></span>
          <?php
            $content  = isset($this->values, $this->values['content']) ? $this->values['content'] : '';
            $editor   = new Editor('newsinhalt', 'content', $content);
            $editor->show();
          ?>
        </label>
        <input type="submit" name="<?php echo $this->submit; ?>" value="<?php I18n::e('admin.static_page.editor.actions.'.$this->action.'.submit'); ?>" />
      </fieldset>
    </form>
  </section>
  <a href="<?php echo $lb->makeAdminLink('admin'); ?>" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
