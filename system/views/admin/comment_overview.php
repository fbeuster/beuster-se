<?php
  $lb = Lixter::getLix()->getLinkBuilder();
?>

<article>
  <a href="<?php echo $lb->makeAdminLink('admin'); ?>" class="back"><?php I18n::e('admin.back_link'); ?></a>
  <section class="article">
    <h1><?php I18n::e('admin.comment.overview.label'); ?></h1>
    <p>
      <?php I18n::e('admin.comment.overview.summary', array($this->total_comments)); ?>
    </p>
    <form>
      <label>
        <?php I18n::e('admin.comment.overview.filter.label'); ?>:
        <input type="text" name="comment_filter" placeholder="<?php I18n::e('admin.comment.overview.filter.placeholder'); ?>">
      </label>
    </form>
    <table class="entry_list comments">
      <thead>
        <tr>
          <th class="mediumNumber">
            <?php I18n::e('admin.comment.overview.table_header.article'); ?>
          </th>
          <th class="author">
            <?php I18n::e('admin.comment.overview.table_header.author'); ?>
          </th>
          <th>
            <?php I18n::e('admin.comment.overview.table_header.content'); ?>
          </th>
          <th class="date">
            <?php I18n::e('admin.comment.overview.table_header.date'); ?>
          </th>
          <th class="button">
            <?php I18n::e('admin.comment.overview.table_header.actions'); ?>
          </th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 0; ?>
        <?php foreach ($this->comments as $comment) { ?>
          <?php $article = new Article($comment->getNewsId()); ?>
          <tr class="comment <?php echo $i % 2 == 0 ? 'even' : 'odd'; ?>">
            <td class="article">
              <a  href="<?php echo $article->getLink(); ?>"
                  title="<?php echo $article->getTitle(); ?>">
                <?php echo $article->getId(); ?>
              </a>
            </td>
            <td class="author">
              <?php
                $author  = $comment->getAuthor();
                $website = $author->getWebsite();
              ?>

              <?php if (isValidUserUrl(rewriteUrl($website))) { ?>
                <a  class="author"
                    href="<?php echo rewriteUrl($website); ?>"
                    title="<?php echo $author->getMail(); ?>">
                  <?php echo $author->getClearname(); ?>
                </a>

              <?php } else { ?>
                <span title="<?php echo $author->getMail(); ?>">
                  <?php echo $author->getClearname(); ?>
                </span>
              <?php } ?>
            </td>
            <td class="content"
                data-search="<?php echo $comment->getContent(); ?>">
              <?php echo $comment->getContentParsed(); ?>
            </td>
            <td><?php echo date('d.m.Y H:i', $comment->getDate()); ?></td>
            <td class="actions">
              <div>
                <a  class="delete"
                    title="<?php I18n::e('admin.comment.overview.delete.title'); ?>">
                  <?php I18n::e('admin.comment.overview.delete.text'); ?>
                </a>
              </div>
            </td>
          </tr>
          <?php $i++; ?>
          <?php foreach ($comment->getReplies() as $reply) { ?>
            <tr class="reply <?php echo $i % 2 == 0 ? 'even' : 'odd'; ?>">
              <td></td>
              <td class="author">
                <?php
                  $author  = $reply->getAuthor();
                  $website = $author->getWebsite();
                ?>

                <?php if (isValidUserUrl(rewriteUrl($website))) { ?>
                  <a  class="author"
                      href="<?php echo rewriteUrl($website); ?>"
                      title="<?php echo $author->getMail(); ?>">
                    <?php echo $author->getClearname(); ?>
                  </a>

                <?php } else { ?>
                  <span title="<?php echo $author->getMail(); ?>">
                    <?php echo $author->getClearname(); ?>
                  </span>
                <?php } ?>
              </td>
              <td class="content"
                  data-search="<?php echo $reply->getContent(); ?>">
                <?php echo $reply->getContentParsed(); ?>
              </td>
              <td><?php echo date('d.m.Y H:i', $reply->getDate()); ?></td>
              <td class="actions">
                <div>
                  <a  class="delete"
                      title="<?php I18n::e('admin.comment.overview.delete.title'); ?>">
                    <?php I18n::e('admin.comment.overview.delete.text'); ?>
                  </a>
                </div>
              </td>
            </tr>
            <?php $i++; ?>
          <?php } ?>
        <?php } ?>
      </tbody>
    </table>
  </section>

  <a href="<?php echo $lb->makeAdminLink('admin'); ?>" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
