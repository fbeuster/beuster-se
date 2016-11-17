
<article>
  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
  <section class="article">
  	<h1><?php I18n::e('admin.article.overview.label'); ?></h1>
  	<p>
      <?php I18n::e('admin.article.overview.summary', array(
        count($data['news']),
        $data['cmtAmount'],
        $data['enaAmount']
      )); ?>
  	</p>
    <form>
      <label>
        <?php I18n::e('admin.article.overview.filter.label'); ?>:
        <input type="text" name="article_filter" placeholder="<?php I18n::e('admin.article.overview.filter.placeholder'); ?>">
      </label>
    </form>
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
        if(count($data['news'])) {
          $i = 0;
          foreach($data['news'] as $entry) { ?>
        <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?> data-article="<?php echo $entry['id']; ?>">
          <td ><?php echo ($i+1); ?></td>
          <td ><?php echo $entry['id']; ?></td>
          <td class="title"><a href="<?php echo $entry['link']; ?>"><?php echo $entry['title']; ?></a></td>
          <td ><?php echo $entry['hits']; ?></td>
          <td ><?php echo $entry['per_day']; ?></td>
          <td ><?php echo $entry['date']; ?></td>
          <td class="actions">
            <div>
              <a class="edit"  title="<?php I18n::e('admin.article.overview.edit.title'); ?>" href="/newsedit/a/<?php echo $entry['id']; ?>">
                <?php I18n::e('admin.article.overview.edit.text'); ?>
              </a>
              <a class="delete" title="<?php I18n::e('admin.article.overview.delete.title'); ?>">
                <?php I18n::e('admin.article.overview.delete.text'); ?>
              </a>
            </div>
          </td>
        </tr>
      <?php
            $i++;
          }
        } ?>
      </tbody>
    </table>
  </section>

  <a href="/admin" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
