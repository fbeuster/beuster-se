
    <ul class="navbar">
      <li>
        <a href="<?php echo $lb->makeAdminLink('article-overview'); ?>">
          <button class="articles"
                  title="<?php I18n::e('admin.navigation.article.label'); ?>"
                  type="buttom">
              <svg viewBox="0 0 24 24" class="icon articles">
                <use xlink:href="#icon-description"></use>
              </svg>
          </button>
        </a>
        <ul>
          <li>
            <a href="<?php echo $lb->makeAdminLink('article-overview'); ?>">
              <?php I18n::e('admin.navigation.article.overview'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo $lb->makeAdminLink('article-create'); ?>">
              <?php I18n::e('admin.navigation.article.new'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo $lb->makeAdminLink('article-edit'); ?>">
              <?php I18n::e('admin.navigation.article.edit'); ?>
            </a>
          </li>
        </ul>
      </li>
      <li>
        <button class="categories"
                title="<?php I18n::e('admin.navigation.category.label'); ?>"
                type="buttom">
          <svg viewBox="0 0 24 24" class="icon categories">
            <use xlink:href="#icon-view-module"></use>
          </svg>
        </button>
        <ul>
          <li>
            <a href="<?php echo $lb->makeAdminLink('category-management'); ?>">
              <?php I18n::e('admin.navigation.category.manage'); ?>
            </a>
          </li>
        </ul>
      </li>
      <li>
        <a href="<?php echo $lb->makeAdminLink('image-overview'); ?>">
          <button class="images"
                  title="<?php I18n::e('admin.navigation.image.label'); ?>"
                  type="buttom">
            <svg viewBox="0 0 24 24" class="icon images">
              <use xlink:href="#icon-photo"></use>
            </svg>
          </button>
        </a>
      </li>
      <li>
        <a href="<?php echo $lb->makeAdminLink('attachment-overview'); ?>">
          <button class="attachments"
                  title="<?php I18n::e('admin.navigation.attachment.label'); ?>"
                  type="buttom">
            <svg viewBox="0 0 24 24" class="icon attachments">
              <use xlink:href="#icon-attachment"></use>
            </svg>
          </button>
        </a>
        <ul>
          <li>
            <a href="<?php echo $lb->makeAdminLink('attachment-overview'); ?>">
              <?php I18n::e('admin.navigation.attachment.overview'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo $lb->makeAdminLink('attachment-create'); ?>">
              <?php I18n::e('admin.navigation.attachment.new'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo $lb->makeAdminLink('attachment-edit'); ?>">
              <?php I18n::e('admin.navigation.attachment.edit'); ?>
            </a>
          </li>
        </ul>
      </li>
      <li>
        <a href="<?php echo $lb->makeAdminLink('comment-overview'); ?>">
          <button class="comments"
                  title="<?php I18n::e('admin.navigation.comment.label'); ?>"
                  type="buttom">
            <svg viewBox="0 0 24 24" class="icon comments">
              <use xlink:href="#icon-comment"></use>
            </svg>
          </button>
        </a>
        <ul>
          <li>
            <a href="<?php echo $lb->makeAdminLink('comment-overview'); ?>">
              <?php I18n::e('admin.navigation.comment.overview'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo $lb->makeAdminLink('comment-management'); ?>">
              <?php I18n::e('admin.navigation.comment.enable'); ?>
            </a>
          </li>
        </ul>
      </li>
      <li>
        <a href="<?php echo $lb->makeAdminLink('static-page-overview'); ?>">
          <button class="static-pages"
                  title="<?php I18n::e('admin.navigation.static_page.label'); ?>"
                  type="buttom">
            <svg viewBox="0 0 24 24" class="icon static-pages">
              <use xlink:href="#icon-description"></use>
            </svg>
          </button>
        </a>
        <ul>
          <li>
            <a href="<?php echo $lb->makeAdminLink('static-page-overview'); ?>">
              <?php I18n::e('admin.navigation.static_page.overview'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo $lb->makeAdminLink('static-page-create'); ?>">
              <?php I18n::e('admin.navigation.static_page.new'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo $lb->makeAdminLink('static-page-edit'); ?>">
              <?php I18n::e('admin.navigation.static_page.edit'); ?>
            </a>
          </li>
        </ul>
      </li>
      <li>
        <a href="<?php echo $lb->makeAdminLink('snippet-overview'); ?>">
          <button class="snippets"
                  title="<?php I18n::e('admin.navigation.snippet.label'); ?>"
                  type="buttom">
            <svg viewBox="0 0 24 24" class="icon snippets">
              <use xlink:href="#icon-note-add"></use>
            </svg>
          </button>
        </a>
        <ul>
          <li>
            <a href="<?php echo $lb->makeAdminLink('snippet-overview'); ?>">
              <?php I18n::e('admin.navigation.snippet.overview'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo $lb->makeAdminLink('snippet-create'); ?>">
              <?php I18n::e('admin.navigation.snippet.new'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo $lb->makeAdminLink('snippet-edit'); ?>">
              <?php I18n::e('admin.navigation.snippet.edit'); ?>
            </a>
          </li>
        </ul>
      </li>
      <li>
        <button class="misc"
                title="<?php I18n::e('admin.navigation.misc.label'); ?>"
                type="buttom">
          <svg viewBox="0 0 24 24" class="icon misc">
            <use xlink:href="#icon-settings"></use>
          </svg>
        </button>
        <ul>
          <li>
            <a href="<?php echo $lb->makeAdminLink('system-settings'); ?>">
              <?php I18n::e('admin.navigation.misc.system_settings'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo $lb->makeAdminLink('statistics'); ?>">
              <?php I18n::e('admin.navigation.misc.statistics'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo $lb->makeAdminLink('user-settings'); ?>">
              <?php I18n::e('admin.navigation.misc.user_settings'); ?>
            </a>
          </li>
          <li>
            <a href="<?php echo $lb->makeAdminLink('logout'); ?>">
              <?php I18n::e('admin.navigation.misc.logout'); ?>
            </a>
          </li>
        </ul>
      </li>
    </ul>