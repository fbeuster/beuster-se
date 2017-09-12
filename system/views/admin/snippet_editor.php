
<article>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
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

    <form action="/<?php echo $this->form_action; ?>" method="post" class="userform articleform">
      <fieldset>
        <legend>
          <?php I18n::e('admin.snippet.editor.actions.'.$this->action.'.label'); ?>
        </legend>

        <?php if ($this->action == 'edit') { ?>
          <label class="required long">
            <span>
              <?php I18n::e('admin.snippet.editor.choose.label'); ?>
            </span>
            <select name="snippetname">
              <option value="">
                <?php I18n::e('admin.snippet.editor.choose.placeholder'); ?>
              </option>
              <?php foreach($this->snippets as $value) { ?>
              <option value="<?php echo $value; ?>">
              <?php echo $value; ?>
              </option>
              <?php } ?>
            </select>
          </label>

          <input type="submit" name="formactionchoose" value="<?php I18n::e('admin.snippet.editor.choose.submit'); ?>">

          <input type="hidden" name="old_name" value="<?php echo $this->values['name']; ?>">

        <?php } ?>

        <label class="required long">
          <span>
            <?php I18n::e('admin.snippet.editor.name.label'); ?>
          </span>
          <input type="text" name="name" title="<?php I18n::e('admin.snippet.editor.name.placeholder'); ?>" placeholder="<?php I18n::e('admin.snippet.editor.name.placeholder'); ?>" value="<?php echo isset($this->values['name']) ? $this->values['name'] : ''; ?>" role="newEntryTags">
        </label>

        <label class="required long" for="newsinhalt">
          <span>
            <?php I18n::e('admin.snippet.editor.content_label'); ?>
          </span>
          <?php
            $content  = isset($this->values['content']) ? $this->values['content'] : '';
            $editor   = new Editor('newsinhalt', 'content', $content);
            $editor->show();
          ?>
        </label>

        <input type="submit" name="<?php echo $this->submit; ?>" value="<?php I18n::e('admin.snippet.editor.actions.'.$this->action.'.submit'); ?>" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
