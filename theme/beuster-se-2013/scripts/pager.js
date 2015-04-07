/** beuster{se} | (c) 2010-2015 **/

beusterse.pager = {
  currentPage : 0,
  groups      : 0,
  groupWidth  : 0,
  pagesToSee  : 7,
  slider      : 0,
  totalPages  : 0,
  width       : 0,

  init: function() {
    if($('#pager').length > 0) {
      $('#pager, #number, #pnr, .pArrows').addClass('hasJs');

      this.currentPage  = $('#pnr li.themecolor').index();
      this.totalPages   = $('#pnr').children().length;
      this.width        = $('#pnr').width();

      var fullPages     = this.totalPages % this.pagesToSee;
      this.groups       = (this.totalPages - fullPages) / this.pagesToSee + 1;
      this.groupWidth   = Math.ceil($('#number').width());

      $('#number').css('overflow','hidden');
      if(this.currentPage >= this.pagesToSee * 1) {
        $('#pagerleft').css('visibility','visible');
      }
      if(this.currentPage < this.pagesToSee * this.groups - 1 && this.pagesToSee < this.totalPages) {
        $('#pagerright').css('visibility','visible');
      }
      $('#number').scrollLeft(Math.floor(this.currentPage / this.pagesToSee) * this.groupWidth);

      this.bindHandlers();
    }
  },

  bindHandlers: function() {
    $('#pager')
      .on('mouseenter', '#pagerleft', this.slideLeft)
      .on('mouseenter', '#pagerright', this.slideRight)
      .on('mouseleave', '#pagerleft, #pagerright', this.clearSlider);
  },

  clearSlider: function() {
    clearInterval(beusterse.pager.slider);
  },

  doScroll: function(amount) {
    $('#number').scrollLeft($('#number').scrollLeft() + amount);
  },

  fadeIn: function(callback) {
    $('#number').animate({ opacity: 1 }, 350, callback);
  },

  fadeOut: function(callback) {
    $('#number').animate({ opacity: 0 }, 400, callback);
  },

  needClearLeft: function() {
    $('#number').scrollLeft() == beusterse.pager.groupWidth;
  },

  needClearRight: function() {
    $('#number').scrollLeft() + 2 * beusterse.pager.groupWidth >= beusterse.pager.width
  },

  slideClear: function(dir) {
    if( dir === 1 && beusterse.pager.needClearRight() ||
        dir === -1 && beusterse.pager.needClearLeft()) {
      beusterse.pager.clearSlider();
    }
  },

  slideLeft: function() {
    beusterse.pager.slider = setInterval(function(){
      beusterse.pager.fadeOut(function(){
        beusterse.pager.doScroll( -beusterse.pager.groupWidth );

        if($('#number').scrollLeft() < beusterse.pager.width) {
          $('#pagerright').css('visibility','visible');
        }
        if($('#number').scrollLeft() == 0) {
          $('#pagerleft').css('visibility','hidden');
        }
      });
      beusterse.pager.fadeIn(function(){
        beusterse.pager.slideClear();

        if($('#number').scrollLeft() == 0) {
          $('#number').stop();
        }
      });
    }, 300);
  },

  slideRight: function() {
    beusterse.pager.slider = setInterval(function(){
      beusterse.pager.fadeOut(function(){
        beusterse.pager.doScroll( beusterse.pager.groupWidth );

        if($('#number').scrollLeft() > 0) {
          $('#pagerleft').css('visibility','visible');
        }
        if($('#number').scrollLeft() + beusterse.pager.groupWidth >= beusterse.pager.width) {
          $('#pagerright').css('visibility','hidden');
        }
      });
      beusterse.pager.fadeIn(function(){
        beusterse.pager.slideClear();

        if($('#number').scrollLeft() + beusterse.pager.groupWidth >= beusterse.pager.width) {
          $('#number').stop();
        }
      });
    }, 300);
  }
}