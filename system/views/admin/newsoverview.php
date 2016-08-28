
<article>
  <a href="/admin" class="back"><?php echo I18n::t('admin.back_link'); ?></a>
  <section class="article">
  	<h1><?php echo I18n::t('admin.article.overview.label'); ?></h1>
  	<p>
      <?php echo I18n::t('admin.article.overview.summary', array(
        count($data['news']),
        $data['cmtAmount'],
        $data['enaAmount']
      )); ?>
  	</p>
    <form>
      <label>
        <?php echo I18n::t('admin.article.overview.filter.label'); ?>:
        <input type="text" name="article_filter" placeholder="<?php echo I18n::t('admin.article.overview.filter.placeholder'); ?>">
      </label>
    </form>
    <table class="newslist">
      <thead>
        <tr>
          <th class="smallNumber">#</th>
          <th class="smallNumber"><?php echo I18n::t('admin.article.overview.table_header.id'); ?></th>
          <th><?php echo I18n::t('admin.article.overview.table_header.article'); ?></th>
          <th class="bigNumber"><?php echo I18n::t('admin.article.overview.table_header.hits'); ?></th>
          <th class="bigNumber"><?php echo I18n::t('admin.article.overview.table_header.hits_per_day'); ?></th>
          <th class="date"><?php echo I18n::t('admin.article.overview.table_header.date'); ?></th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(count($data['news'])) {
          $i = 0;
          foreach($data['news'] as $entry) { ?>
        <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?>>
          <td ><?php echo ($i+1); ?></td>
          <td ><?php echo $entry['id']; ?></td>
          <td class="title"><a href="<?php echo $entry['link']; ?>"><?php echo $entry['title']; ?></a></td>
          <td ><?php echo $entry['hits']; ?></td>
          <td ><?php echo $entry['per_day']; ?></td>
          <td ><?php echo $entry['date']; ?></td>
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
