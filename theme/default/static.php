
  <?php $static_page = Lixter::getLix()->getPage(); ?>
  <h1><?php echo $static_page->getTitle(); ?></h1>
  <?php echo $static_page->getParsedContent(); ?>
  <?php echo $static_page->addUriSnippets(); ?>
