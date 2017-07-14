
<article>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
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

    <form action="/<?php echo $this->form_action; ?>" method="post" enctype="multipart/form-data" class="userform articleform">
      <fieldset>
        <legend><?php I18n::e('admin.attachment.'.$this->action.'.label'); ?></legend>

        <?php if ($this->action == 'edit') { ?>

          <label class="required long <?php if(isset($this->errors['attachment'])) { echo ' has_error'; } ?>">
            <span>
              <?php I18n::e('admin.attachment.edit.choose.label'); ?>
            </span>
            <select name="attachment">
              <option value="0">
                <?php I18n::e('admin.attachment.edit.choose.placeholder'); ?>
              </option>
              <?php foreach($this->attachments as $attachments) { ?>
                <option value="<?php echo $attachments['id']; ?>">
                <?php echo $attachments['file_name']; ?>
                </option>
              <?php } ?>
            </select>
          </label>
          <input type="submit" name="formselect" value="<?php I18n::e('admin.attachment.edit.choose.submit'); ?>">
          <br>

          <input type="hidden" name="attachment_id" size="5" value="<?php if (isset($this->values['id'])) { echo $this->values['id']; } ?>">

        <?php } ?>

        <label class="required <?php if(isset($this->errors['name'])) { echo ' has_error'; } ?>">
          <span><?php I18n::e('admin.attachment.'.$this->action.'.name.label'); ?></span>
          <input type="text" name="name" role="newEntryTitle" placeholder="<?php I18n::e('admin.attachment.'.$this->action.'.name.placeholder'); ?>" title="<?php I18n::e('admin.attachment.'.$this->action.'.name.placeholder'); ?>" value="<?php if (isset($this->values, $this->values['name'])) { echo $this->values['name']; } ?>">
        </label>

        <label class="required <?php if(isset($this->errors['license']) || isset($this->errors['category_parent'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.attachment.'.$this->action.'.license.label'); ?>
          </span>
          <input type="text" name="license" placeholder="<?php I18n::e('admin.attachment.'.$this->action.'.license.placeholder'); ?>" title="<?php I18n::e('admin.attachment.'.$this->action.'.license.placeholder'); ?>" value="<?php if(isset($this->values['license'])) echo $this->values['license']; ?>">
        </label>

        <label class="required <?php if(isset($this->errors['version']) || isset($this->errors['category_parent'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.attachment.'.$this->action.'.version.label'); ?>
          </span>
          <input type="text" name="version" placeholder="<?php I18n::e('admin.attachment.'.$this->action.'.version.placeholder'); ?>" title="<?php I18n::e('admin.attachment.'.$this->action.'.version.placeholder'); ?>" value="<?php if(isset($this->values['version'])) echo $this->values['version']; ?>">
        </label>

        <?php if ($this->action == 'new') { ?>

          <label class="required long <?php if(isset($this->errors['version']) || isset($this->errors['category_parent'])) { echo ' has_error'; } ?>">
            <span>
              <?php I18n::e('admin.attachment.new.file.label', array('5MB')); ?>
            </span>
            <input type="file" name="file[]" title="<?php I18n::e('admin.attachment.new.file.placeholder', array('5MB')); ?>" >
          </label>

        <?php } else if ( $this->action == 'edit' &&
                          isset($this->values['id'])) { ?>
          <p class="current_file">
            <?php I18n::e('admin.attachment.edit.file.label',
                          $this->values['path']); ?>
          </p>
        <?php } ?>

        <input type="submit" name="<?php echo $this->submit; ?>" value="<?php I18n::e('admin.attachment.'.$this->action.'.submit'); ?>" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
