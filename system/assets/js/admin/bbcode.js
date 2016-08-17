/** beuster{se} | (c) 2010-2016 **/

admin.bbCode = {

  target : '',

  init: function() {
    if(this.isNewsForm() || this.isUserForm()) {
      var tarFormCnt;

      if(this.isNewsForm()) this.target = '#newsinhalt';
      if(this.isUserForm()) this.target = '#usrcnt';

      this.bindHandlers();
    }
  },

  bindHandlers: function() {
    $('#btnbold').click(      { o: '[b]',    c: '[/b]' },    this.wrapSelection);
    $('#btnitalic').click(    { o: '[i]',    c: '[/i]' },    this.wrapSelection);
    $('#btnunderline').click( { o: '[u]',    c: '[/u]' },    this.wrapSelection);
    $('#btnmark').click(      { o: '[mark]', c: '[/mark]' }, this.wrapSelection);
    $('#btndel').click(       { o: '[del]',  c: '[/del]' },  this.wrapSelection);
    $('#btnins').click(       { o: '[ins]',  c: '[/ins]' },  this.wrapSelection);
    $('#btnuber2').click(     { o: '[h2]',   c: '[/h2]' },   this.wrapSelection);
    $('#btnuber3').click(     { o: '[h3]',   c: '[/h3]' },   this.wrapSelection);

    $('#btnlink').click(  { text: 'URL eingeben:',                     tag: 'url' },    this.wrapUrl);
    $('#btncite').click(  { text: 'Quelle angeben (Link oder Text):',  tag: 'cite' },   this.wrapUrl);
    $('#btnbquote').click({ text: 'Quelle angeben (Link oder Text):',  tag: 'bquote' }, this.wrapUrl);

    $('#btncode').click(  { o: '[code]',  c: '[/code]' },   this.wrapSelection);
    $('#btnquote').click( { o: '[quote]', c: '[/quote]' },  this.wrapSelection);

    $('#smsmile').click(  { o: '', c: ':)' }, this.wrapSelection);
    $('#smlaugh').click(  { o: '', c: ':D' }, this.wrapSelection);
    $('#smsad').click(    { o: '', c: ':(' }, this.wrapSelection);
    $('#smone').click(    { o: '', c: ';)' }, this.wrapSelection);

    if(this.isNewsForm()) {
      $('#btnpar').click( { o: '[/p]', c: '[p]' },   this.wrapSelection);
      $('#btnul').click(  { o: '[ul]', c: '[/ul]' }, this.wrapSelection);
      $('#btnol').click(  { o: '[ol]', c: '[/ol]' }, this.wrapSelection);
      $('#btnli').click(  { o: '[li]', c: '[/li]' }, this.wrapSelection);

      $('#btnyt').click(function(){
        var url = prompt('Videocode eingeben:','');
        admin.bbCode.wrapSelection(admin.bbCode.makeEventData('[yt]' + url, '[/yt]'));
      });
      $('#btnplay').click(function(){
        var url = prompt('Playlist-ID eingeben:','');
        admin.bbCode.wrapSelection(admin.bbCode.makeEventData('[play]' + url, '[/play]'));
      });
      $('#btnamazon').click(function(){
        var asin = prompt('ASIN eingeben:','');
        admin.bbCode.wrapSelection(admin.bbCode.makeEventData('[asin=' + asin + ']', '[/asin]'));
      });
    }
  },

  hasSelected: function() {
    var target = $(admin.bbCode.target)[0];
    return target.selectionStart != target.selectionEnd;
  },

  isNewsForm: function() {
    return $('#newsinhalt').length > 0;
  },

  isUserForm: function() {
    return $('#usercnt').length > 0;
  },

  makeEventData: function(open, close) {
    return { data: { o: open, c: close } };
  },

  wrapSelection: function(event) {
    var textArea  = $(admin.bbCode.target);
        value     = textArea.val(),
        start     = textArea[0].selectionStart,
        end       = textArea[0].selectionEnd,
        pre       = value.substring(0, start),
        text      = value.substring(start, end),
        post      = value.substring(end, value.length);
    textArea.val(pre + event.data.o + text + event.data.c + post);
  },

  wrapUrl: function(event) {
    var url = prompt(event.data.text, 'http://');
    if(url == 'http://' || url == '')
      return;

    var ext = admin.bbCode.hasSelected() ? '' : url;
    admin.bbCode.wrapSelection(
      admin.bbCode.makeEventData(
        '[' + event.data.tag + '=' + url + ']' + ext,
        '[/' + event.data.tag + ']'
      ));
  }
};