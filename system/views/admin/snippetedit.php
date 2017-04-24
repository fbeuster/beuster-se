
<article>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
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
    <?php
      if(isset($data['values'])) {
        $values = $data['values'];

      } else {
        $values = array('name' => '', 'content' => '');
      }
    ?>

    <form action="/snippetedit" method="post" class="userform articleform">
      <fieldset>
        <legend>
          <?php I18n::e('admin.snippet.edit.label'); ?>
        </legend>
        <label class="required long">
          <span>
            <?php I18n::e('admin.snippet.edit.choose.label'); ?>
          </span>
          <select name="snippetname">
            <option value="">
              <?php I18n::e('admin.snippet.edit.choose.placeholder'); ?>
            </option>
            <?php foreach($data['snippets'] as $value) { ?>
            <option value="<?php echo $value; ?>">
            <?php echo $value; ?>
            </option>
            <?php } ?>
          </select>
        </label>

        <input type="submit" name="formactionchoose" value="<?php I18n::e('admin.snippet.edit.choose.submit'); ?>">

        <input type="hidden" name="old_name" value="<?php echo $values['name']; ?>">
        <label class="required long">
          <span>
            <?php I18n::e('admin.snippet.edit.name.label'); ?>
          </span>
          <input type="text" name="name" title="<?php I18n::e('admin.snippet.edit.name.placeholder'); ?>" placeholder="<?php I18n::e('admin.snippet.edit.name.placeholder'); ?>" value="<?php echo $values['name']; ?>" role="newEntryTags">
        </label>

        <label class="required long" for="newsinhalt">
          <span>
            <?php I18n::e('admin.snippet.edit.content_label'); ?>
          </span>
          <?php
            $content  = isset($values['content']) ? $values['content'] : '';
            $editor   = new Editor('newsinhalt', 'content', $content);
            $editor->show();
          ?>
        </label>

        <input type="submit" name="formactionchange" value="<?php I18n::e('admin.snippet.edit.submit'); ?>" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
