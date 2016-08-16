
  <?php $static_page = Lixter::getLix()->getPage(); ?>
    <article>
      <section class="article">
        <h1><?php echo $static_page->getTitle(); ?></h1>
        <?php echo $static_page->getParsedContent(); ?>
        <?php echo $static_page->addUriSnippets(); ?>
      </section>
    </article>
