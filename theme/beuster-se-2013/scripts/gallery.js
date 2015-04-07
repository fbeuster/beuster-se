/** beuster{se} | (c) 2010-2015 **/

beusterse.gallery = {

  init: function() {
    $('.beContentEntryGalleryImageList li').toggleClass('hasjs');

    this.bindHandlers();
  },

  bindHandlers: function() {
    $('.beContentEntryGalleryImageList li img').click(this.enlargeThumb);

    $('.beContentEntry').click(this.hideBigImage);
    $('.beLightbox').click(this.hideLightbox);

    $('#galL').click(this.showPreviousImage);
    $('#galR').click(this.showNextImage);

    $('.beContentEntryImage img').click(this.lightbox);
    $('.bePortfolioEntry img').click(this.lightbox);
    $('#pic').click(this.lightbox);

    $('.beLightboxClose').click(this.closeLightbox);

    $(document).keydown(function(event) {
      var key = event.which || event.keyCode;
      if(key == KEY_ESCAPE && beusterse.gallery.isLightboxVisible()) {
        beusterse.gallery.closeLightbox();
      }
    });

    $('.beContentEntryPlaylist li a img').hover(function(){
      $('#vidwahl')
        .text($(this).attr('title'))
        .css('color', '#000000');
    }, function(){
      $('#vidwahl').css('color', '#efefef');
    });
  },

  changeBigImage: function($replace) {
    var $big_image = $('#pic');

    $big_image.fadeOut(400, function(){
      $big_image.attr({
        'src'   : beusterse.gallery.getImageSource($replace),
        'alt'   : $replace.attr('id'),
        'name'  : $replace.attr('name')
      });
    });
    $big_image.fadeIn(400);
  },

  closeLightbox: function() {
    $('body').css('overflow','inherit');
    $('.beLightbox').fadeOut(350);
  },

  enlargeThumb: function() {
    var $bigImg = $('.beContentEntryGalleryBigImage');
        thumb   = {
                    src     : $(this).attr('src'),
                    replace : beusterse.gallery.getImageSource($(this)),
                    id      : $(this).attr('id'),
                    name    : $(this).attr('name'),
                    title   : $(this).attr('title')
                  },
        $pic = $('#pic');

    if($bigImg.css('display') != 'none') {
      if($pic.attr('src') != thumb.replace) {
        $pic.animate({ opacity: 0 }, function(){
          $pic.attr('src', thumb.replace);
          $pic.animate({ opacity: 1 });
        });
      }
    } else {
      $pic.css({
        'display'     : 'block',
        'visibility'  : 'hidden'
      });

      $pic.attr({
        'src'   : thumb.replace,
        'name'  : thumb.name,
        'title' : thumb.title,
        'alt'   : thumb.id
      });

      $pic.css({
        'display'     : 'none',
        'visibility'  : 'visible'
      });

      $bigImg.css('height', 'auto');

      $bigImg.slideToggle(500, function(){
        $pic.fadeIn(400);
        $('#galL').fadeIn(400);
        $('#galR').fadeIn(400);
      });
    }
  },

  getImageSource: function(thumb) {
    return thumb.attr('src')
            .replace('blog/thid','blog/id')
            .replace('.jpg', '')
            .replace('_', '.');
  },

  hideBigImage: function(event) {
    if( !beusterse.gallery.isMouseOverControl(event.target) &&
        beusterse.gallery.isBigImageVisible() ) {
      console.log('hide');
      $('#galL, #galR, #pic').fadeOut(400);
      $('.beContentEntryGalleryBigImage').slideToggle(500);
    }
  },

  hideLightbox: function(event) {
    if( !(beusterse.gallery.isMouseOverLightbox(event.target)) ) {
      beusterse.gallery.closeLightbox();
    }
  },

  isBigImageVisible: function() {
    return $('.beContentEntryGalleryBigImage').css('display') == 'block';
  },

  isLightboxVisible: function() {
    return $('.beLightbox').css('display') == 'block';
  },

  isMouseOverControl: function(target) {
    return  $(target).is('#pic')  || $(target).is('#galL') ||
            $(target).is('#galR') || $(target).is('.beContentEntryGalleryImageList li img');
  },

  isMouseOverLightbox: function(target) {
    return  $(target).is('#imgViewport') || $(target).is('.beLightboxWrapper') ||
            $(target).is('.beLightboxText span');
  },

  lightbox: function() {
    var view    = $('#imgViewport'),
        newImg  = new Image();
    $image = $(this);

    beusterse.gallery.openLightbox();

    view.attr({
      'title' : $image.attr('title'),
      'name'  : $image.attr('name'),
      'alt'   : $image.attr('alt')
    });

    newImg.onload = function() {
      view.attr('src', $image.attr('src')).load(function(){
        var newScale  = beusterse.gallery.scaleLightbox(newImg.width, newImg.height)
            imgHeight = newScale.height,
            imgWidth  = newScale.width;

        $('.beLightboxWrapper').css({
          'marginTop' : (window.innerHeight - (imgHeight + 30)) / 2,
          'width'     : imgWidth,
          'height'    : imgHeight + 30
        });
        view.css({
          'width'   : imgWidth,
          'height'  : imgHeight
        });
      });
    }
    newImg.src = $image.attr('src');
    $('.beLightboxDecription').text(view.attr('name'));

    if($image.parent().find('.infotext').length > 0) {
        $('.beLightboxImage .infotext').text($image.parent().find('.infotext').text());
    }
  },

  openLightbox: function() {
    $('body').css('position','hidden');
    $('.beLightbox').fadeIn(350);
  },

  scaleLightbox: function(_width, _height) {
    while(  (_width + 30) >= window.innerWidth ||
            (_height + 30) >= window.innerHeight ) {
      _width  = _width * 0.9;
      _height = _height * 0.9;
    }
    return { width: _width, height: _height };
  },

  showNextImage: function() {
    var next_image_no = parseInt($('#pic').attr('alt').split('pic')[1]) + 1;
        $next_image   = $('img#pic' + next_image_no),
        image_count   = $('.beContentEntryGalleryImageList li').size();

    if(next_image_no - 1 < image_count) {
      beusterse.gallery.changeBigImage($next_image);
    }
  },

  showPreviousImage: function() {
    var previous_image_no = parseInt($('#pic').attr('alt').split('pic')[1] - 1),
        $previous_image   = $('img#pic' + previous_image_no);

    if(previous_image_no > 0) {
      beusterse.gallery.changeBigImage($previous_image);
    }
  }
}