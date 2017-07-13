
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

        <label class="required long <?php if(isset($this->errors['version']) || isset($this->errors['category_parent'])) { echo ' has_error'; } ?>">
          <span>
            <?php I18n::e('admin.attachment.'.$this->action.'.file.label', array('5MB')); ?>
          </span>
          <input type="file" name="file[]" title="<?php I18n::e('admin.attachment.'.$this->action.'.file.placeholder', array('5MB')); ?>" >
        </label>

        <input type="submit" name="<?php echo $this->submit; ?>" value="<?php I18n::e('admin.attachment.'.$this->action.'.submit'); ?>" />
      </fieldset>
    </form>
  </section>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
