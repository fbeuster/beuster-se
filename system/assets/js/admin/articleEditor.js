/** beuster{se} | (c) 2010-2016 **/

admin.articleEditor = {
  preview_auto_update : true,
  preview_delay       : 0,
  preview_delay_time  : 2000,
  preview_old_value   : '',
  textarea_id         : '#newsinhalt',

  attachmentsSelectChangeListener: function() {
    var text    = this.options[this.selectedIndex].text,
        value   = $(this).val();

    if (value != 'error') {
      if (!admin.articleEditor.isAttachmentSelected(value)) {
        admin.articleEditor.selectAttachment(value, text);
      }
    }
  },

  fetchArticlePreview : function() {
    $.ajax({
      type: "POST",
      url: "/api.php",
      data: {
        'scope' : 'admin',
        'method' : 'ApiArticleEditorPreview',
        'data' : {
          'content' : admin.articleEditor.preview_old_value
        }
      },
      success: function(data) {
        if (data != '') {
          $('section.preview.article .content').html(data);

          if (!$('section.preview.article .content').is(':visible')) {
            $('section.preview.article .content').slideDown(400);
          }
        }
      }
    });
  },

  init: function() {
    if ($(this.textarea_id).length) {
      if ($('#preview_auto_update').is(':checked')) {
        this.preview_auto_update = true;

      } else {
        this.preview_auto_update = false;
      }

      $(this.textarea_id).blur(this.textareaBlurListener);
      $(this.textarea_id).focus(this.textareaFocusListener);

      if ($('.section_opener').length) {
        $('.section_opener').attr('title', 'Click to open');
        $('.section_opener').click(this.sectionOpenerListener);
      }

      if ($('#preview_manual_update').length &&
          $('#preview_auto_update').length) {
        $('#preview_manual_update').click(this.previewManualUpdate);
        $('#preview_auto_update').change(this.previewAutoUpdateListener);
      }

      if ($('input[name=attachments]').length) {
        $('select[name=attachments_select]').change(this.attachmentsSelectChangeListener);
        this.loadPrefilledAttachments();
      }
    }
  },

  isAttachmentSelected: function(value) {
    return $('input[name=attachments]').val().indexOf(value) > -1;
  },

  loadPrefilledAttachments : function() {
    var attachments = $('input[name=attachments]').val()
                                                  .split(';');
    $('input[name=attachments]').val(';');

    for (var i = 0; i < attachments.length; i++) {
      if (attachments[i].trim() != '') {
        var text = $('select[name=attachments_select] option[value=' + attachments[i] + ']').text();
        admin.articleEditor.selectAttachment(attachments[i], text);
      }
    }
  },

  previewAutoUpdateListener : function() {
    if ($('#preview_auto_update').is(':checked')) {
      admin.articleEditor.preview_auto_update = true;
      admin.articleEditor.previewManualUpdate();

    } else {
      this.preview_auto_update = false;
    }
  },

  previewManualUpdate : function() {
    var current_value = $(admin.articleEditor.textarea_id).val();

    if (current_value != admin.articleEditor.preview_old_value) {
      admin.articleEditor.preview_old_value = current_value;
      admin.articleEditor.fetchArticlePreview();
    }
  },

  previewUpdate : function() {
    clearTimeout(admin.articleEditor.preview_delay);

    admin.articleEditor.preview_delay = setTimeout(function(){
      clearTimeout(admin.articleEditor.preview_delay);
      admin.articleEditor.previewManualUpdate();

    }, admin.articleEditor.preview_delay_time);
  },

  sectionOpenerListener : function() {
    $(this).next('.section_expander').slideToggle();
  },

  selectAttachment : function(value, text) {
    var append  = value + ';',
        $hidden = $('input[name=attachments]'),
        $li     = $('<li></li>'),
        $list   = $('ul.current_attachments'),
        $remove = $('<a></a>');

    $hidden.val($hidden.val() + append);

    $remove.text('x')
            .addClass('delete')
            .attr( 'title', I18n.t('admin.article.editor.attachments.title') )
            .click(function(){
              $hidden.val($hidden.val().replace(append, ''));
              $li.remove();
            });

    $li.text(text)
        .append($remove)
        .appendTo($list);
  },

  textareaBlurListener : function() {
    $(admin.articleEditor.textarea_id).off('keyup');
  },

  textareaFocusListener : function() {
    if (admin.articleEditor.preview_auto_update) {
      $(admin.articleEditor.textarea_id).keyup(admin.articleEditor.previewUpdate);
    }
  }
}