
  <?php $static_page = Lixter::getLix()->getPage(); ?>
    <article>
      <section class="article">
        <h1><?php echo $static_page->getTitle(); ?></h1>
        <?php echo $static_page->getDecoratedContent(); ?>
        <?php echo $static_page->addUriSnippets(); ?>
      </section>
    </article>

    <?php if ($static_page->getType() == Page::FEEDBACK_PAGE) { ?>
      <section class="comments">
        <?php # TODO title should be set in the static page form ?>
        <?php if ($static_page->getUrl() == 'kontakt') { ?>
          <h2>Kontaktformular</h2>

        <?php } else { ?>
          <h2>Hast du Feedback?</h2>
        <?php } ?>
        <?php $static_page->getForm()->show('userform'); ?>
      </section>
    <?php } ?>
