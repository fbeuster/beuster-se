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
          <li><?php echo $error['message']; ?></li>
        <?php } ?>
        </ul>
      </div>
    <?php } ?>
    <form action="/<?php echo $form_action; ?>" method="post" enctype="multipart/form-data" class="userform articleform">
      <fieldset>
        <legend><?php I18n::e('admin.static_page.'.$action.'.label'); ?></legend>

        <?php if ($action == 'edit') { ?>

          <label class="required long <?php if(isset($data['errors']['static_page'])) { echo ' has_error'; } ?>">
            <span>
              <?php I18n::e('admin.static_page.edit.choose.label'); ?>
            </span>
            <select name="static_page">
              <option value="0">
                <?php I18n::e('admin.static_page.edit.choose.placeholder'); ?>
              </option>
              <?php foreach($data['static_pages'] as $static_page) { ?>
                <option value="<?php echo $static_page['url']; ?>">
                <?php echo $static_page['title']; ?>
                </option>
              <?php } ?>
            </select>
          </label>
          <input type="submit" name="formactionchoose" value="<?php I18n::e('admin.article.edit.choose.submit'); ?>">
          <br>

        <?php } ?>

        <label class="required long <?php if(isset($data['errors']['url'])) { echo ' has_error'; } ?>">
          <span><?php I18n::e('admin.static_page.'.$action.'.url.label'); ?></span>
          <input type="text" name="url" title="<?php I18n::e('admin.static_page.'.$action.'.url.placeholder'); ?>" placeholder="<?php I18n::e('admin.static_page.'.$action.'.url.placeholder'); ?>"
          value="<?php if (isset($data['values'], $data['values']['url'])) { echo $data['values']['url']; } ?>">
        </label>

        <?php if ($action == 'edit') { ?>
          <input type="hidden" name="old_url" value="<?php echo $data['values']['url']; ?>">
        <?php } ?>

        <label class="required long <?php if(isset($data['errors']['title'])) { echo ' has_error'; } ?>">
          <span><?php I18n::e('admin.static_page.'.$action.'.title.label'); ?></span>
          <input type="text" name="title" title="<?php I18n::e('admin.static_page.'.$action.'.title.placeholder'); ?>" placeholder="<?php I18n::e('admin.static_page.'.$action.'.title.placeholder'); ?>"
          value="<?php if (isset($data['values'], $data['values']['title'])) { echo $data['values']['title']; } ?>">
        </label>

        <label>
          <span>
            <?php I18n::e('admin.static_page.'.$action.'.feedback_label'); ?>
          </span>
          <input type="checkbox" name="has_feedback" <?php echo isset($data['values']) && $data['values']['has_feedback'] ? ' checked="checked"' : ''; ?>>
        </label>

        <label class="required long <?php if(isset($data['errors']['content'])) { echo ' has_error'; } ?>" for="newsinhalt">
          <span><?php I18n::e('admin.static_page.'.$action.'.content_label'); ?></span>
          <?php
            $content  = isset($data['values'], $data['values']['content']) ? $data['values']['content'] : '';
            $editor   = new Editor('newsinhalt', 'content', $content);
            $editor->show();
          ?>
        </label>
        <input type="submit" name="<?php echo $submit; ?>" value="<?php I18n::e('admin.static_page.'.$action.'.submit'); ?>" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
