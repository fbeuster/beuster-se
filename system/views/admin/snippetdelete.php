
<article>
  <a href="/admin" class="back"><?php echo I18n::t('admin.back_link'); ?></a>
  <section class="article">
    <form action="/snippetdelete" method="post" class="userform articleform">
      <fieldset>
        <legend>
          <?php echo I18n::t('admin.snippet.delete.label'); ?>
        </legend>
        <label class="long">
          <span>
            <?php echo I18n::t('admin.snippet.delete.choose.label'); ?>
          </span>
          <select name="snippetname">
            <option value="">
              <?php echo I18n::t('admin.snippet.delete.choose.placeholder'); ?>
            </option>
            <?php foreach($data['snippets'] as $value) { ?>
            <option value="<?php echo $value; ?>">
            <?php echo $value; ?>
            </option>
            <?php } ?>
          </select>
        </label>

        <input type="submit" name="formactionchoose" value="<?php echo I18n::t('admin.snippet.delete.choose.submit'); ?>" />

        <label class="long">
          <span>
            <?php echo I18n::t('admin.snippet.delete.name.label'); ?>
          </span>
          <input type="text" name="name" value="<?php echo $data['snippetedit']['name']; ?>" placeholder="<?php echo I18n::t('admin.snippet.delete.name.placeholder'); ?>" readonly />
        </label>

        <label class="long">
          <span>
            <?php echo I18n::t('admin.snippet.delete.content_label'); ?>
          </span>
          <textarea name="content" id="newsinhalt" cols="80" rows="20" readonly ><?php echo $data['snippetedit']['content']; ?></textarea>
        </label>

        <input type="submit" name="formactiondel" value="<?php echo I18n::t('admin.snippet.delete.submit'); ?>" />
      </fieldset>
    </form>
  </section>

  <a href="/admin" class="back"><?php echo I18n::t('admin.back_link'); ?></a>
</article>
