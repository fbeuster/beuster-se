
<article>
  <a href="/admin" class="back"><?php echo I18n::t('admin.back_link'); ?></a>
  <section class="article">
    <?php if(isset($data['fe'])){ ?>
      <p class="alert">
        Es ist ein Fehler aufgetreten! Typenummer: <?php echo $data['fe']['t']; ?>
      </p>
    <?php } ?>
    <form action="/snippetnew" method="post" enctype="multipart/form-data" class="userform articleform">
      <fieldset>
        <legend><?php echo I18n::t('admin.snippet.new.label'); ?></legend>
        <label class="required long">
          <span><?php echo I18n::t('admin.snippet.new.name.label'); ?></span>
          <input type="text" name="name" title="<?php echo I18n::t('admin.snippet.new.name.placeholder'); ?>" placeholder="<?php echo I18n::t('admin.snippet.new.name.placeholder'); ?>">
        </label>
        <label class="required long">
          <span><?php echo I18n::t('admin.snippet.new.content_label'); ?></span>
          <?php
            $content  = isset($data['fe']['inhalt']) ? $data['fe']['inhalt'] : '';
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
