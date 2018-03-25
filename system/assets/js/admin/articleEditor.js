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

  isPreviewAutoUpdateEnabled: function() {
    return $('#preview_auto_update').is(':checked');
  },

  keyboardShortcuts : function(event) {
    var alt   = event.altKey,
        ctrl  = event.ctrlKey || event.metaKey,
        key   = event.which || event.keyCode,
        shift = event.shiftKey;

    switch (key) {
      case KEYS.TWO:
        if (ctrl && !shift && alt) {
          event.preventDefault();
          admin.bbCode.wrapSelection({ data : {o: '[h2]', c: '[/h2]' }});
        }
        break;

      case KEYS.THREE:
        if (ctrl && !shift && alt) {
          event.preventDefault();
          admin.bbCode.wrapSelection({ data : {o: '[h3]', c: '[/h3]' }});
        }
        break;

      case KEYS.A:
        if (ctrl && !shift && alt) {
          event.preventDefault();
          var asin = prompt('ASIN eingeben:','');
          if(asin.trim() != '') {
            admin.bbCode.wrapSelection(admin.bbCode.makeEventData('[asin=' + asin + ']', '[/asin]'));
          }
        }
        break;

      case KEYS.B:
        if (ctrl && !shift && !alt) {
          event.preventDefault();
          admin.bbCode.wrapSelection({ data : {o: '[b]', c: '[/b]' }});
        }
        break;

      case KEYS.C:
        if (ctrl && !shift && alt) {
          event.preventDefault();
          admin.bbCode.wrapSelection({ data : {o: '[code]', c: '[/code]' }});
        }
        break;

      case KEYS.D:
        if (ctrl && shift && !alt) {
          event.preventDefault();
          admin.bbCode.wrapSelection({ data : {o: '[del]', c: '[/del]' }});
        }
        break;

      case KEYS.I:
        if (ctrl && shift && !alt) {
          event.preventDefault();
          admin.bbCode.wrapSelection({ data : {o: '[ins]', c: '[/ins]' }});
        }

        if (ctrl && !shift && alt) {
          event.preventDefault();
          admin.bbCode.wrapSelection({ data : {o: '[li]', c: '[/li]' }});
        }

        if (ctrl && !shift && !alt) {
          event.preventDefault();
          admin.bbCode.wrapSelection({ data : {o: '[i]', c: '[/i]' }});
        }
        break;

      case KEYS.M:
        if (ctrl && shift && !alt) {
          event.preventDefault();
          admin.bbCode.wrapSelection({ data : {o: '[mark]', c: '[/mark]' }});
        }
        break;

      case KEYS.O:
        if (ctrl && !shift && alt) {
          event.preventDefault();
          admin.bbCode.wrapSelection({ data : {o: '[ol]', c: '[/ol]' }});
        }
        break;

      case KEYS.Q:
        if (ctrl && !shift && !alt) {
          event.preventDefault();
          admin.bbCode.wrapSelection({ data : {o: '[quote]', c: '[/quote]' }});
        }

        if (ctrl && !shift && alt) {
          event.preventDefault();
          admin.bbCode.wrapUrl({data :{ text: 'Quelle angeben (Link oder Text):', tag: 'cite' }});
        }

        if (ctrl && shift && alt) {
          event.preventDefault();
          admin.bbCode.wrapUrl({data :{ text: 'Quelle angeben (Link oder Text):', tag: 'bquote' }});
        }
        break;

      case KEYS.T:
        if (ctrl && !shift && alt) {
          event.preventDefault();
          admin.bbCode.wrapSelection({ data : {o: '[tt]', c: '[/tt]' }});
        }
        break;

      case KEYS.U:
        if (ctrl && !shift && !alt) {
          event.preventDefault();
          admin.bbCode.wrapSelection({ data : {o: '[u]', c: '[/u]' }});
        }

        if (ctrl && shift && !alt) {
          event.preventDefault();
          admin.bbCode.wrapUrl({ data : { text: 'URL eingeben:', tag: 'url' }});
        }

        if (ctrl && !shift && alt) {
          event.preventDefault();
          admin.bbCode.wrapSelection({ data : {o: '[ul]', c: '[/ul]' }});
        }
        break;

      case KEYS.Y:
        if (ctrl && !shift && alt) {
          event.preventDefault();
          var url = prompt('Videocode eingeben:','');
          if(url.trim() != '') {
            admin.bbCode.wrapSelection(admin.bbCode.makeEventData('[yt]' + url, '[/yt]'));
          }
        }

        if (ctrl && shift && alt) {
          event.preventDefault();
          var url = prompt('Playlist-ID eingeben:','');
          if(url.trim() != '') {
            admin.bbCode.wrapSelection(admin.bbCode.makeEventData('[play]' + url, '[/play]'));
          }
        }
        break;

      default:
        break;
    }
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
    if (admin.articleEditor.isPreviewAutoUpdateEnabled()) {
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
        $remove = $('<a></a>'),
        $svg    = $(document.createElementNS('http://www.w3.org/2000/svg','svg')),
        $use    = $(document.createElementNS("http://www.w3.org/2000/svg","use"));

    $hidden.val($hidden.val() + append);

    $use[0].setAttributeNS("http://www.w3.org/1999/xlink", "xlink:href", '#icon-delete' );

    $svg
      .attr({
        'class'   : 'icon delete',
        'viewBox' : '0 0 24 24'
      })
      .append($use)
      .appendTo($remove);

    $remove
      .attr('title', I18n.t('admin.article.editor.attachments.title'))
      .click(function(){
        $hidden.val($hidden.val().replace(append, ''));
        $li.remove();
      });

    $li.text(text)
        .append($remove)
        .appendTo($list);
  },

  textareaBlurListener : function() {
    $(admin.articleEditor.textarea_id).off('keydown');
    $(admin.articleEditor.textarea_id).off('keyup');
  },

  textareaFocusListener : function() {
    $(admin.articleEditor.textarea_id).keydown(admin.articleEditor.keyboardShortcuts);

    if (admin.articleEditor.preview_auto_update) {
      $(admin.articleEditor.textarea_id).keyup(admin.articleEditor.previewUpdate);
    }
  }
}