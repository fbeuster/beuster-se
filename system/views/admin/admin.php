<?php
  $lb = Lixter::getLix()->getLinkBuilder();
?>

<article>
  <section class="article">
    <h1><?php I18n::e('admin.label'); ?></h1>
    <h2><?php I18n::e('admin.navigation.article.label'); ?></h2>
    <ul class="pagesList">
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

    <h2><?php I18n::e('admin.navigation.static_page.label'); ?></h2>
    <ul class="pagesList">
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

    <h2><?php I18n::e('admin.navigation.attachment.label'); ?></h2>
    <ul class="pagesList">
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

    <h2><?php I18n::e('admin.navigation.category.label'); ?></h2>
    <ul class="pagesList">
      <li>
        <a href="<?php echo $lb->makeAdminLink('category-management'); ?>">
          <?php I18n::e('admin.navigation.category.manage'); ?>
        </a>
      </li>
    </ul>

    <h2><?php I18n::e('admin.navigation.comment.label'); ?></h2>
    <ul class="pagesList">
      <li>
        <a href="<?php echo $lb->makeAdminLink('comment-management'); ?>">
          <?php I18n::e('admin.navigation.comment.enable'); ?>
        </a>
      </li>
    </ul>

    <h2><?php I18n::e('admin.navigation.snippet.label'); ?></h2>
    <ul class="pagesList">
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

    <h2><?php I18n::e('admin.navigation.misc.label'); ?></h2>
    <ul class="pagesList">
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
  </section>
</article>
