<?php $form = Setup::getSetup()->getFormHandler(); ?>

<form action="index.php?step=custom" method="post">
  <fieldset>
    <?php $form->showMessages(); ?>
    <span><a href="index.php?step=admin_user">Back</a></span>
    <br>
    <legend>customization</legend>

    <?php
      foreach (SetupHelper::getAvailableThemes() as $theme) {
        $form->label('theme_'.$theme, $theme);
        $form->radioButton('theme_'.$theme, 'theme', array('value' => $theme));
      }
    ?>
    <br>
    <label for="language">language</label>
    <?php $form->selectField('language', 'language', SetupHelper::getAvailableLanguages(), 'en', SetupHelper::getFieldOptions('language')); ?>
    <br>
    <label for="timezone">timezone</label>
    <?php $form->selectField('timezone', 'timezone', SetupHelper::getAvailableTimezones(), SetupHelper::getTimezone(), SetupHelper::getFieldOptions('timezone')); ?>
    <br>
    <label for="rss_path">rss_path</label>
    <?php $form->textField('rss_path', 'rss_path', SetupHelper::getFieldOptions('rss_path', '/rss.xml')); ?>
    <br>
    <input type="submit" value="Save and next">
  </fieldset>
</form>
