<?php
  $lb = Lixter::getLix()->getLinkBuilder();
?>

<article>
  <section class="article">
    <form action="<?php echo $lb->makeAdminLink('login'); ?>" method="post" class="userform articleform">
      <fieldset>
        <legend><?php I18n::e('login.form.legend'); ?></legend>
        <label class="required">
          <span><?php I18n::e('login.form.name.label'); ?></span>
          <input type="text" name="user_name" required="required"
            placeholder="<?php I18n::e('login.form.name.placeholder'); ?>">
        </label>
        <label class="required">
          <span><?php I18n::e('login.form.password.label'); ?></span>
          <input type="password" name="password" required="required"
            placeholder="<?php I18n::e('login.form.password.placeholder'); ?>">
        </label>
        <br>
        <input type="submit" name="formaction_login" value="<?php I18n::e('login.form.submit'); ?>">
      </fieldset>
    </form>
  </section>
</article>