<?php
  $lb   = $this->getLinkBuilder();
  $page = $this->getPage();

  if (EUCookieNotifier::areCookiesAccepted()) {
    $cookie_class = 'accepted';

  } else {
    $cookie_class = 'not_accepted';
  }

?>

<article>
  <section class="article privacy_settings">
    <h1><?php I18n::e('privacy_settings.title'); ?></h1>

    <span
      class="cookies toggle <?php echo $cookie_class; ?>"
      title="<?php I18n::e('privacy_settings.cookies.'.$cookie_class.'.title'); ?>">
      <span class="icon">c</span>
      <span class="label">
        <?php I18n::e('privacy_settings.cookies.'.$cookie_class.'.label'); ?>
      </span>
    </span>

    <?php if ($page->isNotificationDisablePage() && $page->sentByGet()) { ?>
      <?php if ($page->getError() !== '') { ?>
      <div><?php echo $page->getError(); ?></div>

      <?php } else { ?>
      <form action="<?php echo $page->getLink(); ?>" class="userform" method="post">
        <fieldset>
          <legend>
            <?php I18n::e('privacy_settings.notifications.legend'); ?>
          </legend>
          <p>
            <?php I18n::e('privacy_settings.notifications.thread'); ?>
          </p>
          <ul class="comment_list">
            <?php
              $comment  = $page->getComment();
              $user_id  = $page->getUser()->getId();

              if ($comment->getAuthor()->getId() == $user_id) {
                $classes = 'own';
              } else {
                $classes = '';
              }
            ?>
            <li class="<?php echo $classes; ?>">
              <span class="author"><?php echo $comment->getAuthor()->getName(); ?></span>
              <span class="message"><?php echo $comment->getContentParsed(); ?></span>
            </li>
            <?php
              foreach ($page->getComment()->getReplies() as $reply) {
                if ($reply->getAuthor()->getId() == $user_id) {
                  $classes = 'own';
                } else {
                  $classes = '';
                }
            ?>
            <li class="<?php echo $classes; ?>">
              <span class="author"><?php echo $reply->getAuthor()->getName(); ?></span>
              <span class="message"><?php echo $reply->getContentParsed(); ?></span>
            </li>
            <?php } ?>
          </ul>
          <label class="checkbox">
            <input type="checkbox" name="disable_thread_notifications">
            <span>
              <?php I18n::e('privacy_settings.notifications.disable_thread'); ?>
            </span>
          </label>
          <label class="checkbox">
            <input type="checkbox" name="disable_all_notifications">
            <span>
              <?php I18n::e('privacy_settings.notifications.disable_all',
                            array(Config::getConfig()->get('meta', 'name'))); ?>
            </span>
          </label>
          <input type="hidden" name="comment" value="<?php echo $page->getCommentHash(); ?>">
          <input type="hidden" name="user" value="<?php echo $page->getUserToken(); ?>">
          <input type="submit" name="disable_notifications"
                value="<?php I18n::e('privacy_settings.notifications.submit'); ?>">
        </fieldset>
      </form>
      <?php } ?>
    <?php } ?>

  </section>
</article>