/** beuster{se} | (c) 2010-2016 **/

admin.articleEditor = {

  attachmentsSelectChangeListener: function() {
    var text    = this.options[this.selectedIndex].text,
        value   = $(this).val();

    if (value != 'error') {
      if (!admin.articleEditor.isAttachmentSelected(value)) {
        admin.articleEditor.selectAttachment(value, text);
      }
    }
  },

  init: function() {
    $('select[name=attachments_select]').change(this.attachmentsSelectChangeListener);
    this.loadPrefilledAttachments();
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

  selectAttachment : function(value, text) {
    var append  = value + ';',
        $hidden = $('input[name=attachments]'),
        $li     = $('<li></li>'),
        $list   = $('ul.current_attachments'),
        $remove = $('<a></a>');

    $hidden.val($hidden.val() + append);

    $remove.text('x')
            .addClass('delete')
            .attr( 'title', I18n.t('admin.article.edit.attachments.title') )
            .click(function(){
              $hidden.val($hidden.val().replace(append, ''));
              $li.remove();
            });

    $li.text(text)
        .append($remove)
        .appendTo($list);
  }
}