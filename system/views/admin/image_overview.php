<?php
  $lb = Lixter::getLix()->getLinkBuilder();
?>

<article>
  <?php include 'navbar.php'; ?>
  <section class="article">
    <h1><?php I18n::e('admin.image.overview.label'); ?></h1>

    <?php if ($this->total_images > 0) { ?>
      <ul class="image_list">
        <?php foreach ($this->images as $image) { ?>
          <?php # TODO set width or height to 100, based on portrait or landscape ?>
          <li><img src="<?php echo $image->getPathThumb(800,450); ?>" data-id="<?php echo $image->getId(); ?>" data-meta="<?php echo htmlspecialchars(json_encode($image->getMetaInformation()), ENT_QUOTES, 'UTF-8'); ?>"></li>
        <?php } ?>
      </ul>
    <?php } ?>
  </section>
</article>
