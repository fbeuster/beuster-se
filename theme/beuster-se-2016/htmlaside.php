
  <aside>
    <?php
      SidebarModuleArchive::html();

      $page = Lixter::getLix()->getPage();

      if ($page->getType() == Page::ARTICLE_PAGE) {
        echo '<p class="amazon_disclaimer">';
        I18n::e('article.amazon_disclaimer');
        echo '</p>';
      }
     ?>
  </aside>