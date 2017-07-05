
<article>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
  <section class="article">
  	<h1><?php I18n::e('admin.attachment.overview.label'); ?></h1>
  	<p>
      <?php I18n::e('admin.attachment.overview.summary',
                    array($this->total_attachments)); ?>
  	</p>
    <form>
      <label>
        <?php I18n::e('admin.attachment.overview.filter.label'); ?>:
        <input type="text" name="article_filter" placeholder="<?php I18n::e('admin.attachment.overview.filter.placeholder'); ?>">
      </label>
    </form>
    <?php if (count($this->attachments)) { ?>
      <table class="entry_list articles">
        <thead>
          <tr>
            <th class="smallNumber">#</th>
            <th><?php I18n::e('admin.attachment.overview.table_header.file_name'); ?></th>
            <th><?php I18n::e('admin.attachment.overview.table_header.file_path'); ?></th>
            <th class="bigNumber"><?php I18n::e('admin.attachment.overview.table_header.downloads'); ?></th>
            <th class="bigNumber"><?php I18n::e('admin.attachment.overview.table_header.version'); ?></th>
            <th class="button"><?php I18n::e('admin.snippet.overview.table_header.actions'); ?></th>
          </tr>
        </thead>
        <tbody>
        <?php
          $i = 0;
          foreach($this->attachments as $entry) { ?>
        <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?> data-article="<?php echo $entry['id']; ?>">
          <td ><?php echo ($i+1); ?></td>
          <td class="title"><?php echo $entry['file_name']; ?></td>
          <td class="title"><?php echo $entry['file_path']; ?></td>
          <td ><?php echo $entry['downloads']; ?></td>
          <td ><?php echo $entry['version']; ?></td>
          <td class="actions">
            <div>
              <a class="edit"  title="<?php I18n::e('admin.attachment.overview.edit.title'); ?>" href="/attachment-edit/a/<?php echo $entry['id']; ?>">
                <?php I18n::e('admin.attachment.overview.edit.text'); ?>
              </a>
              <a class="delete" title="<?php I18n::e('admin.attachment.overview.delete.title'); ?>">
                <?php I18n::e('admin.attachment.overview.delete.text'); ?>
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
      <p class="empty"><?php I18n::e('admin.attachment.overview.empty.'.$list_name); ?></p>
    <?php  } ?>
  </section>

  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
