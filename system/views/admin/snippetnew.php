
<article>
  <a href="/admin" class="back"><?php echo I18n::t('admin.back_link'); ?></a>
  <section class="article">
    <?php if(isset($data['errors'])){ ?>
      <div class="error">
        <div class="title">Error</div>
        <ul class="messages">
        <?php foreach ($data['errors'] as $name => $error) { ?>
          <li><?php echo $error['message']; ?></li>
        <?php } ?>
        </ul>
      </div>
    <?php } ?>
    <form action="/snippetnew" method="post" enctype="multipart/form-data" class="userform articleform">
      <fieldset>
        <legend><?php echo I18n::t('admin.snippet.new.label'); ?></legend>
        <label class="required long <?php if(isset($data['errors']['name'])) { echo ' has_error'; } ?>">
          <span><?php echo I18n::t('admin.snippet.new.name.label'); ?></span>
          <input type="text" name="name" title="<?php echo I18n::t('admin.snippet.new.name.placeholder'); ?>" placeholder="<?php echo I18n::t('admin.snippet.new.name.placeholder'); ?>"
          value="<?php if (isset($data['values'], $data['values']['name'])) { echo $data['values']['name']; } ?>">
        </label>
        <label class="required long <?php if(isset($data['errors']['content'])) { echo ' has_error'; } ?>">
          <span><?php echo I18n::t('admin.snippet.new.content_label'); ?></span>
          <?php
            $content  = isset($data['values'], $data['values']['content']) ? $data['values']['content'] : '';
            $editor   = new Editor('newsinhalt', 'content', $content);
            $editor->show();
          ?>
        </label>
        <input type="submit" name="formaction" value="<?php echo I18n::t('admin.snippet.new.submit'); ?>" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back"><?php echo I18n::t('admin.back_link'); ?></a>
</article>
