<?php $form = Setup::getSetup()->getFormHandler(); ?>

<form action="index.php?step=custom" method="post">
  <fieldset>
    <legend><?php echo I18n::t("setup.custom.legend"); ?></legend>
    <div class="progress">
      <div class="bar w80">&nbsp;</div>
    </div>
    <?php $form->showMessages(); ?>
    <span class="nav_button"><a href="index.php?step=admin_user"><?php echo I18n::t("setup.custom.back_link"); ?></a></span>

    <span class="section_title"><?php echo I18n::t("setup.custom.title_themes"); ?></span>
    <?php
      foreach (SetupHelper::getAvailableThemes() as $theme) {
        $form->label('theme_'.$theme, $theme);
        $form->radioButton('theme_'.$theme, 'theme', array('value' => $theme));
      }
    ?>
    <hr class="separator">
    <label for="language"><?php echo I18n::t("setup.custom.label_language"); ?></label>
    <?php $form->selectField('language', 'language', SetupHelper::getAvailableLanguages(), 'en', SetupHelper::getFieldOptions('language')); ?>
    <label for="timezone"><?php echo I18n::t("setup.custom.label_timezone"); ?></label>
    <?php $form->selectField('timezone', 'timezone', SetupHelper::getAvailableTimezones(), SetupHelper::getTimezone(), SetupHelper::getFieldOptions('timezone')); ?>
    <label for="rss_path"><?php echo I18n::t("setup.custom.label_rss_path"); ?></label>
    <?php $form->textField('rss_path', 'rss_path', SetupHelper::getFieldOptions('rss_path', '/rss.xml')); ?>
    <hr>
    <input type="submit" value="<?php echo I18n::t("setup.custom.submit"); ?>" class="nav_button">
  </fieldset>
</form>
