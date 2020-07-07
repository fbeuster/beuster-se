/** beuster{se} | (c) 2010-2016 **/

admin.imageActions = {
  columns               : 5,
  detailContainerClass  : 'detail',
  expandedClass         : 'expanded',

  addDetailToList: function($img, $ul) {
    var $li = $img.parent();

    // find target
    var target_index = admin.imageActions.findTargetIndex($ul, $li);
    var $prev = $ul.children().eq(target_index - 1);

    // create container
    $div_image = $('<div></div>').addClass('image');
    $div_meta  = $('<div></div>').addClass('meta');

    $li_new = $('<li></li>').addClass('detail').height(0);
    $li_new.append($div_image).append($div_meta);

    // add controls
    admin.imageActions.createMetaControls($li_new)
                      .appendTo($div_meta);

    // add data
    var data = $img.data();
    admin.imageActions.createMetaList($img, data)
                      .appendTo($div_meta);

    // add image
    admin.imageActions.createDetailImage(data.meta.absolute_path)
                      .appendTo($div_image);

    // open container
    $li_new.insertAfter($prev);
    admin.imageActions.expandDetailContainer($li_new, $div_image);
  },

  addMetaListEntry: function($list, data, key) {
    $('<dt></dt>').text(I18n.t('admin.image.overview.detail.meta_' + key)).appendTo($list);
    $('<dd></dd>').addClass(key).text(data.meta[key]).appendTo($list);
  },

  bindHandlers: function() {
    $('.article .image_list img').click(function(){
      var $img = $(this),
          $ul  = $img.parent().parent();

      if (admin.imageActions.hasExpandedDetail($ul)) {
        admin.imageActions.changeDetailInList($img, $ul);
      } else {
        admin.imageActions.addDetailToList($img, $ul);
      }
    });
  },

  changeDetailInList: function($img, $ul) {
    var data = $img.data();
    var $detail = $('ul .' + admin.imageActions.detailContainerClass);
    var $detail_img = $detail.find('img');
    var $detail_meta = $detail.find('.meta');

    // check if update is needed
    if (admin.imageActions.isDifferentImage($detail_img, data.meta.absolute_path)) {
      // TODO close container if needed

      // update image and data
      admin.imageActions.updateDetailImage($detail_img, data.meta.absolute_path);
      admin.imageActions.updateDetailMeta($detail_meta, data);

      // TODO re-open container in new location
    }
  },

  collapseAndRemoveDetailContainer: function($container) {
    $container.animate({height : 0}, 400, function() {
      $container.remove();
    });
  },

  createDetailImage: function(src) {
    return $('<img>').attr('src', src);
  },

  createMetaControls: function($target) {
    var $div_controls = $('<div></div>').addClass('controls');
    var $a_close      = $('<a></a>').attr('title', I18n.t('admin.image.overview.detail.close_title'));
    $svg_close    = $(document.createElementNS("http://www.w3.org/2000/svg","svg"))
                      .attr({
                        'class'   : 'icon clear',
                        'viewBox' : '0 0 24 24'
                      })
                      .click(function(){
                        admin.imageActions.collapseAndRemoveDetailContainer($target);
                      });
    $svg_use      = $(document.createElementNS("http://www.w3.org/2000/svg","use"))
                      .attr('xlink:href', '#icon-clear');
    $svg_use[0].setAttributeNS("http://www.w3.org/1999/xlink", "xlink:href", '#icon-clear' );

    $svg_use.appendTo($svg_close);
    $svg_close.appendTo($a_close);
    $a_close.appendTo($div_controls);

    return $div_controls;
  },

  createMetaList: function($img, data) {
    var $dl = $('<dl></dl>');

    admin.imageActions.addMetaListEntry($dl, data, 'caption');
    admin.imageActions.addMetaListEntry($dl, data, 'file_name');
    admin.imageActions.addMetaListEntry($dl, data, 'added');

    return $dl;
  },

  expandDetailContainer: function($container, $image) {
    var width       = $image.width();
    var outer_width = $image.outerWidth();
    var spacing     = outer_width - width;
    var height      = width / 16 * 9 + spacing;

    $container.animate({height : height}, 400, function() {
      $container.addClass(admin.imageActions.expandedClass);
    });
  },

  findTargetIndex: function($ul, $li) {
    var current_index = $li.index(),
        max_index     = $ul.children().length,
        target_index  = current_index
                        + admin.imageActions.columns
                        - (current_index % admin.imageActions.columns);

    if (target_index > max_index) {
      target_index = max_index;
    }

    return target_index;
  },

  hasExpandedDetail: function($ul) {
    return $ul.find('.' + admin.imageActions.detailContainerClass +
                    '.' + admin.imageActions.expandedClass).length > 0;
  },

  init: function() {
    this.bindHandlers();
  },

  isDifferentImage: function($img, new_src) {
    return $img.attr('src') != new_src;
  },

  updateDetailImage: function($img, new_src) {
    $img.attr('src', new_src);
  },

  updateDetailMeta: function($meta, data) {
    $meta.find('.added').text(data.meta.added);
    $meta.find('.caption').text(data.meta.caption);
    $meta.find('.file_name').text(data.meta.file_name);
  }
};
