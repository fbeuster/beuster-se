/** beuster{se} | (c) 2010-2016 **/

var admin = admin || {};

//@ lib/_jquery
//@ lib/_i18n
//@ lib/_utilities
//@ bbcode

$(document).ready(function(){
  admin.i18n.init()
                .complete( runApp );

  function runApp() {
    admin.bbCode.init();

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
        if(opt == 'Portfolio') {
            $('.newsNewHelpPort').css('display','block');
        }
        if(opt == 'Projekte') {
            $('.newsNewProj').css('display','block');
            $('.projChoose').removeAttr('disabled');
        }
    });
    if($('.cmtEnable').length > 0) {
        $('.cmtEnable tr').click(function(){
            $(this).children().each(function(){
                $(this).find('div').toggleClass('close');
            });
        });
    }
  }
});
