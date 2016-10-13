
<article>
  <section class="article">
    <form action="/login" method="post" class="userform articleform">
      <fieldset>
        <legend><?php I18n::e('login.form.legend'); ?></legend>
        <label class="required">
          <span><?php I18n::e('login.form.name.label'); ?></span>
          <input type="text" name="Username" required="required"
            placeholder="<?php I18n::e('login.form.name.placeholder'); ?>">
        </label>
        <label class="required">
          <span><?php I18n::e('login.form.password.label'); ?></span>
          <input type="password" name="Password" required="required"
            placeholder="<?php I18n::e('login.form.password.placeholder'); ?>">
        </label>
        <br>
        <input type="submit" name="formaction" value="<?php I18n::e('login.form.submit'); ?>">
      </fieldset>
    </form>
  </section>
</article>