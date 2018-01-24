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
          <th class="short_date">
            <?php I18n::e('admin.comment.overview.table_header.date'); ?>
          </th>
          <th>
            <?php I18n::e('admin.comment.overview.table_header.content'); ?>
          </th>
          <th class="button">
            <?php I18n::e('admin.comment.overview.table_header.actions'); ?>
          </th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($this->comments as $comment) { ?>
          <?php $article = new Article($comment->getNewsId()); ?>
          <tr class="comment"
              data-comment="<?php echo $comment->getId(); ?>">
            <td class="date"><?php echo date('d.m.Y', $comment->getDate()); ?></td>
            <td class="comment_info">
              <div  class="article"
                    data-search="<?php echo $article->getTitle(); ?>">
                <span>Article:</span>
                <a  href="<?php echo $article->getLink(); ?>">
                  <?php echo $article->getTitle(); ?>
                </a>
              </div>
              <?php
                $author  = $comment->getAuthor();
                $website = $author->getWebsite();
              ?>
              <div  class="author"
                    data-search="<?php echo $author->getClearname().
                                            $author->getMail(); ?>">
                <span>Author:</span>
                <span>
                  <?php if (isValidUserUrl(rewriteUrl($website))) { ?>
                    <a  class="author"
                        href="<?php echo rewriteUrl($website); ?>">
                      <?php echo $author->getClearname(); ?>
                    </a>

                  <?php } else { ?>
                    <?php echo $author->getClearname(); ?>
                  <?php } ?>
                  <span class="mail">
                    (<?php echo $author->getMail(); ?>)
                  </span>
                </span>
              </div>
              <div  class="content"
                    data-search="<?php echo $comment->getContent(); ?>">
                <span>Content:</span>
                <div>
                  <?php echo $comment->getContentParsed(); ?>
                </div>
              </div>
              <?php if ($comment->hasReplies()) { ?>
                <div class="replies">
                  Has <?php echo count($comment->getReplies()); ?> replies:
                </div>
              <?php } ?>
            </td>
            <td class="actions">
              <div>
                <a  class="delete"
                    title="<?php I18n::e('admin.comment.overview.delete.title'); ?>">
                  <?php I18n::e('admin.comment.overview.delete.text'); ?>
                </a>
              </div>
            </td>
          </tr>

          <?php foreach ($comment->getReplies() as $reply) { ?>
            <tr class="reply"
                data-comment="<?php echo $reply->getId(); ?>">
              <td class="date"><?php echo date('d.m.Y', $reply->getDate()); ?></td>
              <td class="comment_info">
                <div  class="article"
                      data-search="<?php echo $article->getTitle(); ?>">
                  <span>Article:</span>
                  <a  href="<?php echo $article->getLink(); ?>">
                    <?php echo $article->getTitle(); ?>
                  </a>
                </div>
                <?php
                  $author  = $reply->getAuthor();
                  $website = $author->getWebsite();
                ?>
                <div  class="author"
                      data-search="<?php echo $author->getClearname().
                                              $author->getMail(); ?>">
                  <span>Author:</span>
                  <span>
                    <?php if (isValidUserUrl(rewriteUrl($website))) { ?>
                      <a  class="author"
                          href="<?php echo rewriteUrl($website); ?>">
                        <?php echo $author->getClearname(); ?>
                      </a>

                    <?php } else { ?>
                      <?php echo $author->getClearname(); ?>
                    <?php } ?>
                    <span class="mail">
                      (<?php echo $author->getMail(); ?>)
                    </span>
                  </span>
                </div>
                <div  class="content"
                      data-search="<?php echo $reply->getContent(); ?>">
                  <span>Content:</span>
                  <div>
                    <?php echo $reply->getContentParsed(); ?>
                  </div>
                </div>
              </td>
              <td class="actions">
                <div>
                  <a  class="delete"
                      title="<?php I18n::e('admin.comment.overview.delete.title'); ?>">
                    <?php I18n::e('admin.comment.overview.delete.text'); ?>
                  </a>
                </div>
              </td>
            </tr>
          <?php } ?>
        <?php } ?>
      </tbody>
    </table>
  </section>

  <a href="<?php echo $lb->makeAdminLink('admin'); ?>" class="back"><?php I18n::e('admin.back_link'); ?></a>
</article>
