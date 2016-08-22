
<article>
  <a href="/admin" class="back"><?php echo I18n::t('admin.back_link'); ?></a>
  <section class="article">
    <h1><?php echo I18n::t('admin.stats.label'); ?></h1>

    <h2><?php echo I18n::t('admin.stats.rankings_table.last_label'); ?></h2>
    <table class="newslist">
      <thead>
        <tr>
          <th class="smallNumber">#</th>
          <th><?php echo I18n::t('admin.stats.rankings_table.header.article'); ?></th>
          <th class="bigNumber"><?php echo I18n::t('admin.stats.rankings_table.header.hits'); ?></th>
          <th class="bigNumber"><?php echo I18n::t('admin.stats.rankings_table.header.hits_per_day'); ?></th>
          <th class="date"><?php echo I18n::t('admin.stats.rankings_table.header.date'); ?></th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(count($data['last'])) {
          $i = 0;
          foreach($data['last'] as $entry) { ?>
        <tr>
          <td><?php echo ($i+1); ?></td>
          <td><a href="<?php echo $entry['link']; ?>"><?php echo $entry['title']; ?></a></td>
          <td><?php echo $entry['hits']; ?></td>
          <td><?php echo $entry['per_day']; ?></td>
          <td><?php echo $entry['date']; ?></td>
        </tr>
      <?php
            $i++;
          }
        } ?>
      </tbody>
    </table>

    <h2><?php echo I18n::t('admin.stats.rankings_table.top_label'); ?></h2>
    <table class="newslist">
      <thead>
        <tr>
          <th class="smallNumber">#</th>
          <th><?php echo I18n::t('admin.stats.rankings_table.header.article'); ?></th>
          <th class="bigNumber"><?php echo I18n::t('admin.stats.rankings_table.header.hits'); ?></th>
          <th class="bigNumber"><?php echo I18n::t('admin.stats.rankings_table.header.hits_per_day'); ?></th>
          <th class="date"><?php echo I18n::t('admin.stats.rankings_table.header.date'); ?></th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(count($data['top'])) {
          $i = 0;
          foreach($data['top'] as $entry) { ?>
        <tr>
          <td><?php echo ($i+1); ?></td>
          <td><a href="<?php echo $entry['link']; ?>"><?php echo $entry['title']; ?></a></td>
          <td><?php echo $entry['hits']; ?></td>
          <td><?php echo $entry['per_day']; ?></td>
          <td><?php echo $entry['date']; ?></td>
        </tr>
      <?php
            $i++;
          }
        } ?>
      </tbody>
    </table>

    <h2><?php echo I18n::t('admin.stats.downloads_table.label'); ?></h2>
    <table class="newslist">
      <thead>
        <tr>
          <th class="bigNumber"><?php echo I18n::t('admin.stats.downloads_table.header.downloads'); ?></th>
          <th><?php echo I18n::t('admin.stats.downloads_table.header.file'); ?></th>
        </tr>
      </thead>
      <tbody>
      <?php
        if(count($data['down'])){
          $i = 0;
          foreach($data['down'] as $down){ ?>
        <tr class=<?php echo '"backendTableRow'.($i%2).'"'; ?>>
          <td><?php echo $down['down']; ?></td>
          <td><?php echo $down['name']; ?></td>
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