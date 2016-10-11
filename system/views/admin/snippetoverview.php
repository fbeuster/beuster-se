
<article>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
  <section class="article">
  	<h1><?php I18n::e('admin.snippet.overview.label'); ?></h1>
    <form>
      <label>
        <?php I18n::e('admin.snippet.overview.filter.label'); ?>:
        <input type="text" name="article_filter" placeholder="<?php I18n::e('admin.snippet.overview.filter.placeholder'); ?>">
      </label>
    </form>
    <table class="newslist">
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
        if(count($data['snippets'])) {
          $i = 0;
          foreach($data['snippets'] as $entry) { ?>
        <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?> data-snippet="<?php echo $entry['name']; ?>">
          <td ><?php echo ($i+1); ?></td>
          <td class="title" title="<?php I18n::e('admin.snippet.overview.preview.title'); ?>"><?php echo $entry['name']; ?></td>
          <td ><?php echo $entry['created']; ?></td>
          <td ><?php echo $entry['edited']; ?></td>
          <td class="actions">
            <div>
              <a class="edit"  title="<?php I18n::e('admin.snippet.overview.edit.title'); ?>" href="/snippetedit/s/<?php echo $entry['name']; ?>">
                <?php I18n::e('admin.snippet.overview.edit.text'); ?>
              </a>
              <a class="delete" title="<?php I18n::e('admin.snippet.overview.delete.title'); ?>">
                <?php I18n::e('admin.snippet.overview.delete.text'); ?>
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

  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
