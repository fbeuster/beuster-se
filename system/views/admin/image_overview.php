<?php
  $lb = Lixter::getLix()->getLinkBuilder();
?>

<article>
  <?php include 'navbar.php'; ?>
  <section class="article">
    <?php if(count($this->errors)){ ?>
      <div class="error">
        <div class="title">Error</div>
        <ul class="messages">
        <?php foreach ($this->errors as $name => $error) { ?>

          <?php if (!isset($error['message'])) { ?>
            <?php foreach ($error as $single) { ?>
              <li><?php echo $single['message']; ?></li>
            <?php } ?>

          <?php } else { ?>
            <li><?php echo $error['message']; ?></li>
          <?php } ?>

        <?php } ?>
        </ul>
      </div>
    <?php } ?>

    <h1><?php I18n::e('admin.image.overview.label'); ?></h1>

    <?php if ($this->total_images > 0) { ?>
      <ul class="image_list">
        <?php foreach ($this->images as $image) { ?>
          <?php
            # TODO set width or height to 100, based on portrait or landscape
            $img_class = '';

            if (!empty($this->errors)) {
              $img_class .= $image->getId() == $this->values['img_id'] ? 'hasError' : '';
            }
          ?>
          <li class="<?php echo $img_class; ?>">
            <img
              src="<?php echo $image->getPathThumb(800,450); ?>"
              data-id="<?php echo $image->getId(); ?>"
              data-meta="<?php echo htmlspecialchars(json_encode($image->getMetaInformation()), ENT_QUOTES, 'UTF-8'); ?>">
          </li>
        <?php } ?>
      </ul>
    <?php } ?>
  </section>
</article>
