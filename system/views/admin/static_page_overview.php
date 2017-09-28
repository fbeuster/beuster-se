<?php
  $lb = Lixter::getLix()->getLinkBuilder();
?>

<article>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
  <section class="article">
  	<h1><?php I18n::e('admin.static_page.overview.label'); ?></h1>
    <form>
      <label>
        <?php I18n::e('admin.static_page.overview.filter.label'); ?>:
        <input type="text" name="article_filter" placeholder="<?php I18n::e('admin.static_page.overview.filter.placeholder'); ?>">
      </label>
    </form>
    <table class="entry_list static_pages">
      <thead>
        <tr>
          <th class="smallNumber">#</th>
          <th><?php I18n::e('admin.static_page.overview.table_header.url'); ?></th>
          <th><?php I18n::e('admin.static_page.overview.table_header.title'); ?></th>
          <th class="button"><?php I18n::e('admin.static_page.overview.table_header.actions'); ?></th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(count($this->pages)) {
          $i = 0;
          foreach($this->pages as $entry) { ?>
        <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?> data-static-page="<?php echo $entry['url']; ?>">
          <td ><?php echo ($i+1); ?></td>
          <td><?php echo $entry['url']; ?></td>
          <td class="title"><?php echo $entry['title']; ?></td>
          <td class="actions">
            <div>
              <a  class="edit"
                  title="<?php I18n::e('admin.static_page.overview.edit.title'); ?>"
                  href="<?php echo $lb->makeAdminLink('static-page-edit', $entry['url']); ?>">
                <?php I18n::e('admin.static_page.overview.edit.text'); ?>
              </a>
              <a class="delete" title="<?php I18n::e('admin.static_page.overview.delete.title'); ?>">
                <?php I18n::e('admin.static_page.overview.delete.text'); ?>
              </a>
            </div>
          </td>
        </tr>
      <?php
            $i++;
          }
        } else { ?>
        <tr class="backendTableRow0 empty">
          <td colspan="4">No static pages yet.</td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </section>

  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
