$(document).ready(function() {
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
        $('.newsNeuHelpPort').css('display','none');
        $('.newsNeuProj').css('display','none');
        $('.projChoose').attr('disabled', 'disabled');
        var opt = $('.catSelect option:selected').text();
        if(opt == 'Portfolio') {
            $('.newsNeuHelpPort').css('display','block');
        }
        if(opt == 'Projekte') {
            $('.newsNeuProj').css('display','block');
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
});