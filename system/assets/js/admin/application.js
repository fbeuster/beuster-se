/** beuster{se} | (c) 2010-2016 **/

var admin = admin || {};

//@ lib/_jquery
//@ lib/_i18n
//@ lib/_utilities
//@ articleactions
//@ articleEditor
//@ attachmentActions
//@ commentActions
//@ staticPageActions
//@ bbcode
//@ snippetpreview

$(document).ready(function(){
  admin.i18n.init()
                .complete( runApp );

  function runApp() {
    admin.articleActions.init();
    admin.articleEditor.init();
    admin.attachmentActions.init();
    admin.commentActions.init();
    admin.staticPageActions.init();
    admin.bbCode.init();
    admin.snippetPreview.init();

    $('#allDel').bind('click',function(event){
        $('.del').attr('checked', 'checked');
    });
    $('#allEna').bind('click',function(event){
        $('.ena').attr('checked', 'checked');
    });
    $('#allUnC').bind('click',function(event){
        $('.ena').removeAttr('checked');
        $('.del').removeAttr('checked');
    });
    $('.delInp').click(function(){
        $('#files li:last-child').remove();
    });
    $('#addInp').click(function(){
        $('#files').append('<li><input type="file" name="file[]"></li>');
    });
    $('.adThumb').click(function(){
        $('.adImg img').attr('src', $(this).attr('src').replace('.jpg', '').replace('_', '.').replace('blog/thid', 'blog/id'));
    });
    $('.catSelect').change(function() {
        $('.newsNewHelpPort').css('display','none');
        $('.newsNewProj').css('display','none');
        $('.projChoose').attr('disabled', 'disabled');
        var opt = $('.catSelect option:selected').text();
        if(opt == 'Projekte') {
            $('.newsNewProj').css('display','block');
            $('.projChoose').removeAttr('disabled');
        }
    });
    if($('.commentlist').length > 0) {
        $('.commentlist tr').click(function(){
            $(this).children().each(function(){
                $(this).find('div').toggleClass('close');
            });
        });
    }

    $('input[name=article_filter]').on('focus', function() {
      $(this).on('keyup', function() {
        var value = this.value;

        $('table.entry_list tbody tr').each(function(){
          var text;

          if ($(this).find('.title a').length > 0) {
            text = $(this).find('.title a').text();

          } else {
            text = $(this).find('.title').text();
          }

          if (text.toLowerCase().includes(value.toLowerCase())) {
            $(this).removeClass('hidden');

          } else {
            $(this).addClass('hidden');
          }
        });
      });
    });

    $('input[name=comment_filter]').on('focus', function() {
      $(this).on('keyup', function() {
        var value = this.value;

        $('table.entry_list tbody tr').each(function(){
          var text = '';

          if ($(this).find('td div.article').length > 0) {
            text += $(this).find('td div.article').attr('data-search') + ' ';
          }
          text += $(this).find('td div.author').attr('data-search') + ' ';
          text += $(this).find('td div.content').attr('data-search');

          if (text.toLowerCase().includes(value.toLowerCase())) {
            $(this).removeClass('hidden');

          } else {
            $(this).addClass('hidden');
          }
        });
      });
    });
  }
});
