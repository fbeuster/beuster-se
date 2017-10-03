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
    <form action="<?php echo $lb->makeAdminLink('user-settings'); ?>" method="post" class="userform articleform multiFieldset">
      <fieldset>
        <legend><?php I18n::e('user.settings.label'); ?></legend>

        <fieldset>
          <label<?php echo (isset($this->errors['email_old']) ? ' class="has_error"' : ''); ?>>
            <span>
              <?php I18n::e('user.settings.email.old.label'); ?>
            </span>
            <input type="email" name="email_old" value="<?php echo $this->user->getMail(); ?>" readonly>
          </label>

          <label<?php echo (isset($this->errors['email_new']) ? ' class="has_error"' : ''); ?>>
            <span>
              <?php I18n::e('user.settings.email.new.label'); ?>
            </span>
            <input type="email" name="email_new" placeholder="<?php I18n::e('user.settings.email.new.placeholder'); ?>"<?php echo (isset($this->values, $this->values['email_new']) ? ' value="'.$this->values['email_new'].'"' : ''); ?>>
          </label>

          <input type="submit" name="change_email" value="<?php I18n::e('user.settings.email.submit'); ?>">

        </fieldset>
        <fieldset>
          <label<?php echo (isset($this->errors['password_old']) ? ' class="has_error"' : ''); ?>>
            <span>
              <?php I18n::e('user.settings.password.old.label'); ?>
            </span>
            <input type="password" name="password_old" placeholder="<?php I18n::e('user.settings.password.old.placeholder'); ?>">
          </label>

          <label<?php echo (isset($this->errors['password_new']) ? ' class="has_error"' : ''); ?>>
            <span>
              <?php I18n::e('user.settings.password.new.label'); ?>
            </span>
            <input type="password" name="password_new" placeholder="<?php I18n::e('user.settings.password.new.placeholder'); ?>">
          </label>

          <label<?php echo (isset($this->errors['password_repeat']) ? ' class="has_error"' : ''); ?>>
            <span>
              <?php I18n::e('user.settings.password.repeat.label'); ?>
            </span>
            <input type="password" name="password_repeat" placeholder="<?php I18n::e('user.settings.password.repeat.placeholder'); ?>">
          </label>

          <input type="submit" name="change_password" value="<?php I18n::e('user.settings.password.submit'); ?>">
        </fieldset>
        <input type="submit" name="change_all" value="<?php I18n::e('user.settings.all.submit'); ?>">
      </fieldset>
    </form>
  </section>
  <a href="<?php echo $lb->makeAdminLink('admin'); ?>" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
