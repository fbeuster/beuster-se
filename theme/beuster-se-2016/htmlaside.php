<?php
  $page = Lixter::getLix()->getPage();

  if ($page->getType() == Page::ARTICLE_PAGE) { ?>
  <aside>
    <?php
      echo '<p class="amazon_disclaimer">';
      I18n::e('article.amazon_disclaimer');
      echo '</p>';

      // this integration will follow at a later point
      // echo moduleArchive();
    ?>
  </aside>
<?php } ?>