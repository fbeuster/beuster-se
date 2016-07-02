<div class="bbDiv">
  <div class="bbSpan bbSpanFirst">
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.bold'); ?>" id="btnbold">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.italic'); ?>" id="btnitalic">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.underline'); ?>" id="btnunderline">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.mark'); ?>" id="btnmark">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.del'); ?>" id="btndel">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.ins'); ?>" id="btnins">&nbsp;</button>
  </div>
  <div class="bbSpan">
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.quote'); ?>" id="btnquote">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.cite'); ?>" id="btncite">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.bquote'); ?>" id="btnbquote">&nbsp;</button>
  </div>
  <div class="bbSpan">
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.ol'); ?>" id="btnol">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.ul'); ?>" id="btnul">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.li'); ?>" id="btnli">&nbsp;</button>
  </div>
  <div class="bbSpan">
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.code'); ?>" id="btncode">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.par'); ?>" id="btnpar">&nbsp;</button>
  </div>
  <div class="bbSpan">
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.link'); ?>" id="btnlink">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.yt'); ?>" id="btnyt">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.play'); ?>" id="btnplay">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.amazon'); ?>" id="btnamazon">&nbsp;</button>
  </div>
  <div class="bbSpan">
    <button class="bbImg" type="button" title=":)" id="smsmile">&nbsp;</button>
    <button class="bbImg" type="button" title=":(" id="smlaugh">&nbsp;</button>
    <button class="bbImg" type="button" title=":D" id="smsad">&nbsp;</button>
    <button class="bbImg" type="button" title=";)" id="smone">&nbsp;</button>
  </div>
  <div class="bbSpan">
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.uber2'); ?>" id="btnuber2">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php echo I18n::t('editor.toolbar.label.uber3'); ?>" id="btnuber3">&nbsp;</button>
  </div>
</div>
<textarea name="<?php echo $args['name']; ?>"
          id="<?php echo $args['id']; ?>"
          role="newEntryContent"
          cols="85" rows="20"
          ><?php echo $args['content']; ?></textarea>
