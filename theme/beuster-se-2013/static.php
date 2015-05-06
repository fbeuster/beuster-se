
  <div class="beContentEntry">
    <?php $static_page = Lixter::getLix()->getPage(); ?>
    <h1 class="beContentEntryHeader"><?php echo $static_page->getTitle(); ?></h1>
    <?php echo $static_page->getParsedContent(); ?>
  </div>