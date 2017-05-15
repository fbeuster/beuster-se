
  <?php $static_page = Lixter::getLix()->getPage(); ?>
    <article>
      <section class="article">
        <h1><?php echo $static_page->getTitle(); ?></h1>
        <?php echo $static_page->getParsedContent(); ?>
        <?php echo $static_page->addUriSnippets(); ?>
      </section>
    </article>

    <?php if ($static_page->getType() == Page::FEEDBACK_PAGE) { ?>
      <section class="comments">
        <h2>Hast du Feedback?</h2>
      <?php $static_page->getForm()->show('userform'); ?>
      </section>
    <?php } ?>
