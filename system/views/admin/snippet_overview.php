<?php
  $lb = Lixter::getLix()->getLinkBuilder();
?>

<article>
  <a href="<?php echo $lb->makeAdminLink('admin'); ?>" class="back"><?php I18n::e('admin.back_link'); ?></a>
  <section class="article">
  	<h1><?php I18n::e('admin.snippet.overview.label'); ?></h1>
    <form>
      <label>
        <?php I18n::e('admin.snippet.overview.filter.label'); ?>:
        <input type="text" name="article_filter" placeholder="<?php I18n::e('admin.snippet.overview.filter.placeholder'); ?>">
      </label>
    </form>
    <table class="entry_list snippets">
      <thead>
        <tr>
          <th class="smallNumber">#</th>
          <th><?php I18n::e('admin.snippet.overview.table_header.name'); ?></th>
          <th class="date"><?php I18n::e('admin.snippet.overview.table_header.created'); ?></th>
          <th class="date"><?php I18n::e('admin.snippet.overview.table_header.edited'); ?></th>
          <th class="button"><?php I18n::e('admin.snippet.overview.table_header.actions'); ?></th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(count($this->snippets)) {
          $i = 0;
          foreach($this->snippets as $entry) { ?>
        <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?> data-snippet="<?php echo $entry['name']; ?>">
          <td ><?php echo ($i+1); ?></td>
          <td class="title" title="<?php I18n::e('admin.snippet.overview.preview.title'); ?>"><?php echo $entry['name']; ?></td>
          <td ><?php echo $entry['created']; ?></td>
          <td ><?php echo $entry['edited']; ?></td>
          <td class="actions">
            <div>
              <a  class="edit"
                  title="<?php I18n::e('admin.snippet.overview.edit.title'); ?>"
                  href="<?php echo $lb->makeAdminLink('snippet-edit', $entry['name']); ?>">
                <svg viewBox="0 0 24 24" class="icon edit">
                  <use xlink:href="#icon-edit"></use>
                </svg>
              </a>
              <a class="delete" title="<?php I18n::e('admin.snippet.overview.delete.title'); ?>">
                <svg viewBox="0 0 24 24" class="icon delete">
                  <use xlink:href="#icon-delete"></use>
                </svg>
              </a>
            </div>
          </td>
        </tr>
      <?php
            $i++;
          }
        } else { ?>
        <tr class="backendTableRow0 empty">
          <td colspan="5">No snippets yet.</td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </section>

  <a href="<?php echo $lb->makeAdminLink('admin'); ?>" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
