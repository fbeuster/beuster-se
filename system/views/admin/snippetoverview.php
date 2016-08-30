
<article>
  <a href="/admin" class="back"><?php echo I18n::t('admin.back_link'); ?></a>
  <section class="article">
  	<h1><?php echo I18n::t('admin.snippet.overview.label'); ?></h1>
    <form>
      <label>
        <?php echo I18n::t('admin.snippet.overview.filter.label'); ?>:
        <input type="text" name="article_filter" placeholder="<?php echo I18n::t('admin.snippet.overview.filter.placeholder'); ?>">
      </label>
    </form>
    <table class="newslist">
      <thead>
        <tr>
          <th class="smallNumber">#</th>
          <th><?php echo I18n::t('admin.snippet.overview.table_header.name'); ?></th>
          <th class="date"><?php echo I18n::t('admin.snippet.overview.table_header.created'); ?></th>
          <th class="date"><?php echo I18n::t('admin.snippet.overview.table_header.edited'); ?></th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(count($data['snippets'])) {
          $i = 0;
          foreach($data['snippets'] as $entry) { ?>
        <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?>>
          <td ><?php echo ($i+1); ?></td>
          <td class="title"><?php echo $entry['name']; ?></td>
          <td ><?php echo $entry['created']; ?></td>
          <td ><?php echo $entry['edited']; ?></td>
        </tr>
      <?php
            $i++;
          }
        } ?>
      </tbody>
    </table>
  </section>

  <a href="/admin" class="back"><?php echo I18n::t('admin.back_link'); ?></a>
</article>