<div class="bbDiv">
  <div class="bbSpan bbSpanFirst">
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.bold'); ?>" id="btnbold">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.italic'); ?>" id="btnitalic">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.underline'); ?>" id="btnunderline">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.mark'); ?>" id="btnmark">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.del'); ?>" id="btndel">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.ins'); ?>" id="btnins">&nbsp;</button>
  </div>
  <div class="bbSpan">
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.quote'); ?>" id="btnquote">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.cite'); ?>" id="btncite">&nbsp;</button>
    <button class="bbImg mar_left" type="button" title="<?php I18n::e('editor.toolbar.label.bquote'); ?>" id="btnbquote">&nbsp;</button>
  </div>
  <div class="bbSpan">
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.ol'); ?>" id="btnol">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.ul'); ?>" id="btnul">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.li'); ?>" id="btnli">&nbsp;</button>
  </div>
  <div class="bbSpan">
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.tt'); ?>" id="btntt">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.code'); ?>" id="btncode">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.par'); ?>" id="btnpar">&nbsp;</button>
  </div>
  <div class="bbSpan">
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.link'); ?>" id="btnlink">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.yt'); ?>" id="btnyt">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.play'); ?>" id="btnplay">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.amazon'); ?>" id="btnamazon">&nbsp;</button>
  </div>
  <div class="bbSpan">
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.uber2'); ?>" id="btnuber2">&nbsp;</button>
    <button class="bbImg" type="button" title="<?php I18n::e('editor.toolbar.label.uber3'); ?>" id="btnuber3">&nbsp;</button>
  </div>
</div>
<textarea name="<?php echo $args['name']; ?>"
          id="<?php echo $args['id']; ?>"
          role="newEntryContent"
          cols="85" rows="20"
          ><?php echo $args['content']; ?></textarea>
