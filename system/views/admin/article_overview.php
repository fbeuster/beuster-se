<?php
  $lb = Lixter::getLix()->getLinkBuilder();
?>

<article>
  <a href="<?php echo $lb->makeAdminLink('admin'); ?>" class="back"><?php I18n::e('admin.back_link'); ?></a>
  <section class="article">
  	<h1><?php I18n::e('admin.article.overview.label'); ?></h1>
  	<p>
      <?php I18n::e('admin.article.overview.summary',
                    array($this->total_articles,
                          $this->total_comments,
                          $this->unlisted )); ?>
  	</p>
    <form>
      <label>
        <?php I18n::e('admin.article.overview.filter.label'); ?>:
        <input type="text" name="article_filter" placeholder="<?php I18n::e('admin.article.overview.filter.placeholder'); ?>">
      </label>
    </form>
    <?php foreach ($this->article_lists as $list_name => $articles) { ?>
      <h2 class="entry_list_header"><?php I18n::e('admin.article.overview.subheader.'.$list_name); ?></h2>
      <?php if (count($articles)) { ?>
        <table class="entry_list articles">
          <thead>
            <tr>
              <th class="smallNumber">#</th>
              <th class="smallNumber"><?php I18n::e('admin.article.overview.table_header.id'); ?></th>
              <th><?php I18n::e('admin.article.overview.table_header.article'); ?></th>
              <th class="bigNumber"><?php I18n::e('admin.article.overview.table_header.hits'); ?></th>
              <th class="bigNumber"><?php I18n::e('admin.article.overview.table_header.hits_per_day'); ?></th>
              <th class="date"><?php I18n::e('admin.article.overview.table_header.date'); ?></th>
              <th class="button"><?php I18n::e('admin.snippet.overview.table_header.actions'); ?></th>
            </tr>
          </thead>
          <tbody>
          <?php
            $i = 0;
            foreach($articles as $entry) {
              $article = $entry['article']; ?>
          <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?> data-article="<?php echo $article->getId(); ?>">
            <td ><?php echo ($i+1); ?></td>
            <td ><?php echo $article->getId(); ?></td>
            <td class="title"><a href="<?php echo $article->getLink(); ?>"><?php echo $article->getTitle(); ?></a></td>
            <td ><?php echo $entry['hits']; ?></td>
            <td ><?php echo $entry['per_day']; ?></td>
            <td ><?php echo $article->getDateFormatted("d.m.Y H:i"); ?></td>
            <td class="actions">
              <div>
                <a  class="edit"
                    title="<?php I18n::e('admin.article.overview.edit.title'); ?>"
                    href="<?php echo $lb->makeAdminLink('article-edit', $article->getId()); ?>">
                  <svg viewBox="0 0 24 24" class="icon edit">
                    <use xlink:href="#icon-edit"></use>
                  </svg>
                </a>
                <a class="delete" title="<?php I18n::e('admin.article.overview.delete.title'); ?>">
                  <svg viewBox="0 0 24 24" class="icon delete">
                    <use xlink:href="#icon-delete"></use>
                  </svg>
                </a>
              </div>
            </td>
          </tr>
        <?php
              $i++;
            } ?>
          </tbody>
        </table>
      <?php } else { ?>
        <p class="empty"><?php I18n::e('admin.article.overview.empty.'.$list_name); ?></p>
      <?php  } ?>
    <?php } ?>
  </section>

  <a href="<?php echo $lb->makeAdminLink('admin'); ?>" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
