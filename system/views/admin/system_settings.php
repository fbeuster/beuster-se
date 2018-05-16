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
    <h1><?php I18n::e('admin.system_settings.label'); ?></h1>
    <form action="<?php echo $lb->makeAdminLink('system-settings'); ?>" method="post" class="userform articleform multiFieldset">
      <fieldset>
        <fieldset>
          <legend>
            <?php I18n::e('admin.system_settings.meta.label'); ?>
          </legend>
          <label class="required
          <?php echo (isset($this->errors['meta-name'])) ? ' has_error' : ''; ?>">
            <span>
              <?php I18n::e('admin.system_settings.meta.name.label'); ?>
            </span>
            <input type="text" name="meta-name"
                    placeholder="<?php I18n::e('admin.system_settings.meta.name.placeholder'); ?>"
                    titlt="<?php I18n::e('admin.system_settings.meta.name.placeholder'); ?>"
                    value="<?php echo $this->values['meta.name']; ?>">
          </label>
          <label class="required
          <?php echo (isset($this->errors['meta-title'])) ? ' has_error' : ''; ?>">
            <span>
              <?php I18n::e('admin.system_settings.meta.title.label'); ?>
            </span>
            <input type="text" name="meta-title"
                    placeholder="<?php I18n::e('admin.system_settings.meta.title.placeholder'); ?>"
                    title="<?php I18n::e('admin.system_settings.meta.title.placeholder'); ?>"
                    value="<?php echo $this->values['meta.title']; ?>">
          </label>
          <label class="required
          <?php echo (isset($this->errors['meta-mail'])) ? ' has_error' : ''; ?>">
            <span>
              <?php I18n::e('admin.system_settings.meta.mail.label'); ?>
            </span>
            <input type="text" name="meta-mail"
                    placeholder="<?php I18n::e('admin.system_settings.meta.mail.placeholder'); ?>"
                    title="<?php I18n::e('admin.system_settings.meta.mail.placeholder'); ?>"
                    value="<?php echo $this->values['meta.mail']; ?>">
          </label>
        </fieldset>

        <fieldset>
          <legend>
            <?php I18n::e('admin.system_settings.ext.label'); ?>
          </legend>
          <label class="">
            <span>
              <?php I18n::e('admin.system_settings.ext.amazon_tag.label'); ?>
            </span>
            <input type="text" name="ext-amazon_tag"
                    placeholder="<?php I18n::e('admin.system_settings.ext.amazon_tag.placeholder'); ?>"
                    title="<?php I18n::e('admin.system_settings.ext.amazon_tag.placeholder'); ?>"
                    value="<?php echo $this->values['ext.amazon_tag']; ?>">
          </label>
          <label class="">
            <span>
              <?php I18n::e('admin.system_settings.ext.google_adsense_ad.label'); ?>
            </span>
            <input type="text" name="ext-google_adsense_ad"
                    placeholder="<?php I18n::e('admin.system_settings.ext.google_adsense_ad.placeholder'); ?>"
                    title="<?php I18n::e('admin.system_settings.ext.google_adsense_ad.placeholder'); ?>"
                    value="<?php echo $this->values['ext.google_adsense_ad']; ?>">
          </label>
          <label class="">
            <span>
              <?php I18n::e('admin.system_settings.ext.google_analytics.label'); ?>
            </span>
            <input type="text" name="ext-google_analytics"
                    placeholder="<?php I18n::e('admin.system_settings.ext.google_analytics.placeholder'); ?>"
                    title="<?php I18n::e('admin.system_settings.ext.google_analytics.placeholder'); ?>"
                    value="<?php echo $this->values['ext.google_analytics']; ?>">
          </label>
        </fieldset>

        <fieldset>
          <legend>
            <?php I18n::e('admin.system_settings.site.label'); ?>
          </legend>
          <label class="required
          <?php echo (isset($this->errors['site-theme'])) ? ' has_error' : ''; ?>">
            <span>
              <?php I18n::e('admin.system_settings.site.theme.label'); ?>
            </span>
            <select name="site-theme"
                    title="<?php I18n::e('admin.system_settings.site.theme.placeholder'); ?>">
              <?php foreach ($this->themes as $theme) { ?>
                <option value="<?php echo $theme; ?>"
                  <?php if($theme == $this->values['site.theme']) { ?>
                    selected="selected"
                  <?php } ?> >
                  <?php echo $theme; ?>
                </option>
              <?php } ?>
            </select>
          </label>
          <label class="required
          <?php echo (isset($this->errors['site-language'])) ? ' has_error' : ''; ?>">
            <span>
              <?php I18n::e('admin.system_settings.site.language.label'); ?>
            </span>
            <select name="site-language"
                    title="<?php I18n::e('admin.system_settings.site.language.placeholder'); ?>">
              <?php foreach ($this->languages as $language) { ?>
                <option value="<?php echo $language; ?>"
                  <?php if($language == $this->values['site.language']) { ?>
                    selected="selected"
                  <?php } ?> >
                  <?php echo $language; ?>
                </option>
              <?php } ?>
            </select>
          </label>
          <label class="required
          <?php echo (isset($this->errors['site-category_page_length'])) ? ' has_error' : ''; ?>">
            <span>
              <?php I18n::e('admin.system_settings.site.category_page_length.label'); ?>
            </span>
            <input type="number" name="site-category_page_length"
                    min="1" step="1"
                    title="<?php I18n::e('admin.system_settings.site.category_page_length.placeholder'); ?>"
                    value="<?php echo $this->values['site.category_page_length']; ?>">
          </label>
          <label class="required
          <?php echo (isset($this->errors['site-url_schema'])) ? ' has_error' : ''; ?>">
            <span>
              <?php I18n::e('admin.system_settings.site.url_schema.label'); ?>
            </span>
            <select name="site-url_schema"
                    title="<?php I18n::e('admin.system_settings.site.url_schema.placeholder'); ?>">
              <?php foreach ($this->url_schemas as $key => $url_schema) { ?>
                <option value="<?php echo $key; ?>"
                  <?php if($key == $this->values['site.url_schema']) { ?>
                    selected="selected"
                  <?php } ?> >
                  <?php echo $url_schema; ?>
                </option>
              <?php } ?>
            </select>
          </label>
        </fieldset>

        <fieldset>
          <legend>
            <?php I18n::e('admin.system_settings.dev.label'); ?>
          </legend>
          <label class="">
            <span>
              <?php I18n::e('admin.system_settings.dev.debug_mode.label'); ?>
            </span>
            <input type="checkbox" name="dev-debug"
                    title="<?php I18n::e('admin.system_settings.dev.debug_mode.placeholder'); ?>"
                    <?php if ($this->values['dev.debug']) { ?>
                      checked="checked"
                    <?php } ?> >
          </label>
          <label class="
          <?php echo (isset($this->errors['dev-dev_server_address'])) ? 'has_error' : ''; ?>">
            <span>
              <?php I18n::e('admin.system_settings.dev.dev_server_address.label'); ?>
            </span>
            <input type="text" name="dev-dev_server_address"
                    placeholder="<?php I18n::e('admin.system_settings.dev.dev_server_address.placeholder'); ?>"
                    value="<?php echo $this->values['dev.dev_server_address']; ?>">
          </label>
          <label class="">
            <span>
              <?php I18n::e('admin.system_settings.dev.remote_server_address.label'); ?>
            </span>
            <input type="text" name="dev-remote_server_address"
                    placeholder="<?php I18n::e('admin.system_settings.dev.remote_server_address.placeholder'); ?>"
                    value="<?php echo $this->values['dev.remote_server_address']; ?>">
          </label>
        </fieldset>

        <fieldset>
          <legend>
            <?php I18n::e('admin.system_settings.search.label'); ?>
          </legend>
          <label class="">
            <span>
              <?php I18n::e('admin.system_settings.search.case_sensitive.label'); ?>
            </span>
            <input type="checkbox" name="search-case_sensitive"
                    title="<?php I18n::e('admin.system_settings.search.case_sensitive.placeholder'); ?>"
                    <?php if ($this->values['search.case_sensitive']) { ?>
                      checked="checked"
                    <?php } ?> >
          </label>
          <label class="">
            <span>
              <?php I18n::e('admin.system_settings.search.marks.label'); ?>
            </span>
            <input type="checkbox" name="search-marks"
                    title="<?php I18n::e('admin.system_settings.search.marks.placeholder'); ?>"
                    <?php if ($this->values['search.marks']) { ?>
                      checked="checked"
                    <?php } ?> >
          </label>
        </fieldset>

        <input type="submit" name="change_all" value="<?php I18n::e('admin.system_settings.all.submit'); ?>">
      </fieldset>
    </form>
  </section>
  <a href="<?php echo $lb->makeAdminLink('admin'); ?>" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
