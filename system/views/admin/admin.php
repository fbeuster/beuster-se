
<article>
  <section class="article">
    <h1><?php echo I18n::t('admin.label'); ?></h1>
    <h2><?php echo I18n::t('admin.navigation.article.label'); ?></h2>
    <ul class="pagesList">
      <li><a href="/newsoverview"><?php echo I18n::t('admin.navigation.article.overview'); ?></a></li>
      <li><a href="/newsnew"><?php echo I18n::t('admin.navigation.article.new'); ?></a></li>
      <li><a href="/newsedit"><?php echo I18n::t('admin.navigation.article.edit'); ?></a></li>
      <li><a href="/newsdel"><?php echo I18n::t('admin.navigation.article.delete'); ?></a></li>
    </ul>

    <h2><?php echo I18n::t('admin.navigation.category.label'); ?></h2>
    <ul class="pagesList">
      <li><a href="/admincat"><?php echo I18n::t('admin.navigation.category.manage'); ?></a></li>
    </ul>

    <h2><?php echo I18n::t('admin.navigation.comment.label'); ?></h2>
    <ul class="pagesList">
      <li><a href="/admincmtenable"><?php echo I18n::t('admin.navigation.comment.enable'); ?></a></li>
    </ul>

    <h2><?php echo I18n::t('admin.navigation.download.label'); ?></h2>
    <ul class="pagesList">
      <li><a href="/admindown"><?php echo I18n::t('admin.navigation.download.new'); ?></a></li>
      <li><a href="/admindownbea"><?php echo I18n::t('admin.navigation.download.delete'); ?></a></li>
    </ul>

    <h2><?php echo I18n::t('admin.navigation.snippet.label'); ?></h2>
    <ul class="pagesList">
      <li><a href="/snippetoverview"><?php echo I18n::t('admin.navigation.snippet.overview'); ?></a></li>
      <li><a href="/snippetnew"><?php echo I18n::t('admin.navigation.snippet.new'); ?></a></li>
      <li><a href="/snippetedit"><?php echo I18n::t('admin.navigation.snippet.edit'); ?></a></li>
      <li><a href="/snippetdelete"><?php echo I18n::t('admin.navigation.snippet.delete'); ?></a></li>
    </ul>

    <h2><?php echo I18n::t('admin.navigation.misc.label'); ?></h2>
    <ul class="pagesList">
      <li><a href="/stats"><?php echo I18n::t('admin.navigation.misc.stats'); ?></a></li>
      <li><a href="/userdata"><?php echo I18n::t('admin.navigation.misc.settings'); ?></a></li>
      <li><a href="/logout"><?php echo I18n::t('admin.navigation.misc.logout'); ?></a></li>
    </ul>
  </section>
</article>
