/*****************************************/
// Name: Javascript Textarea BBCode Markup Editor
// Version: 1.3
// Author: Balakrishnan
// Last Modified Date: 25/jan/2009
// License: Free
// URL: http://www.corpocrat.com
/******************************************/

var textarea;
var content;

function edToolbar(obj) {
	document.write("<img class=\"button\" src=\"/images/bbbold.gif\" name=\"btnBold\" title=\"Fett\" onClick=\"doAddTags('[b]','[/b]','" + obj + "')\">");
    document.write("<img class=\"button\" src=\"/images/bbitalic.gif\" name=\"btnItalic\" title=\"Kursiv\" onClick=\"doAddTags('[i]','[/i]','" + obj + "')\">");
	document.write("<img class=\"button\" src=\"/images/bbunderline.gif\" name=\"btnUnderline\" title=\"Unterstrichen\" onClick=\"doAddTags('[u]','[/u]','" + obj + "')\">");
	document.write("<img class=\"button\" src=\"/images/bblink.gif\" name=\"btnLink\" title=\"Link setzten\" onClick=\"doURL('" + obj + "')\">");
	document.write("<img class=\"button\" src=\"/images/bbyt.gif\" name=\"btnYT\" title=\"Video einbinden\" onClick=\"doYT('" + obj + "')\">");
	document.write("<img class=\"button\" src=\"/images/bbquote.gif\" name=\"btnQuote\" title=\"Quote\" onClick=\"doAddTags('[quote]','[/quote]','" + obj + "')\">");
	document.write("<img class=\"button\" src=\"/images/bbpar.gif\" name=\"btnPar\" title=\"Neuer Absatz\" onClick=\"doAddTags('[/p]','[p]','" + obj + "')\">"); 
	document.write("<img class=\"button\" src=\"/images/bbul.gif\" name=\"btnul\" title=\"Liste erstellen\" onClick=\"doAddTags('[ul]','[/ul]','" + obj + "')\">"); 
	document.write("<img class=\"button\" src=\"/images/bbli.gif\" name=\"btnli\" title=\"Listenelement\" onClick=\"doAddTags('[li]','[/li]','" + obj + "')\">");
	document.write("<img class=\"button\" src=\"/images/smsmile.gif\" name=\"smile\" title=\"Smiling\" onClick=\"doSmily(':)','" + obj + "')\">"); 
	document.write("<img class=\"button\" src=\"/images/smlaugh.gif\" name=\"laugh\" title=\"Laughing\" onClick=\"doSmily(':D','" + obj + "')\">"); 
	document.write("<img class=\"button\" src=\"/images/smsad.gif\" name=\"sad\" title=\"I'm sad.\" onClick=\"doSmily(':(','" + obj + "')\">"); 
	document.write("<img class=\"button\" src=\"/images/smone.gif\" name=\"one\" title=\"You know...\" onClick=\"doSmily(';)','" + obj + "')\">"); 
}
                
function edToolbarCmt(obj, t) {
	document.write("<img class=\"button\" src=\"/images/bbbold.gif\" name=\"btnBold\" title=\"Fett\" onClick=\"doAddTags('[b]','[/b]','" + obj + "')\">");
    document.write("<img class=\"button\" src=\"/images/bbitalic.gif\" name=\"btnItalic\" title=\"Kursiv\" onClick=\"doAddTags('[i]','[/i]','" + obj + "')\">");
	document.write("<img class=\"button\" src=\"/images/bbunderline.gif\" name=\"btnUnderline\" title=\"Unterstrichen\" onClick=\"doAddTags('[u]','[/u]','" + obj + "')\">"); 
    if(t != 'mob') document.write("<br>");
	document.write("<img class=\"button\" src=\"/images/bblink.gif\" name=\"btnLink\" title=\"Link setzten\" onClick=\"doURL('" + obj + "')\">");
	document.write("<img class=\"button\" src=\"/images/bbquote.gif\" name=\"btnQuote\" title=\"Quote\" onClick=\"doAddTags('[quote]','[/quote]','" + obj + "')\">");  
    if(t != 'mob') document.write("<br>");
	document.write("<img class=\"button\" src=\"/images/smsmile.gif\" name=\"smile\" title=\"Smiling\" onClick=\"doSmily(':)','" + obj + "')\">"); 
	document.write("<img class=\"button\" src=\"/images/smlaugh.gif\" name=\"laugh\" title=\"Laughing\" onClick=\"doSmily(':D','" + obj + "')\">"); 
	document.write("<img class=\"button\" src=\"/images/smsad.gif\" name=\"sad\" title=\"I'm sad.\" onClick=\"doSmily(':(','" + obj + "')\">"); 
    if(t != 'mob') document.write("<br>");
	document.write("<img class=\"button\" src=\"/images/smone.gif\" name=\"one\" title=\"You know...\" onClick=\"doSmily(';)','" + obj + "')\">"); 
}

function doSmily(sm, obj) {
    textarea = document.getElementById(obj);
    // Code for IE
    if (document.selection) {
        textarea.focus();
        var sel = document.selection.createRange();
        sel.text = sel.text + sm;
    } else { 
   // Code for Mozilla Firefox
        var len = textarea.value.length;
        var start = textarea.selectionStart;
        var end = textarea.selectionEnd;
        var scrollTop = textarea.scrollTop;
        var scrollLeft = textarea.scrollLeft;
        var sel = textarea.value.substring(start, end);
        var rep = sel + sm;
        textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
        textarea.scrollTop = scrollTop;
        textarea.scrollLeft = scrollLeft;
    }
}

function doURL(obj) {
    textarea = document.getElementById(obj);
    var url = prompt('Enter the URL:','http://');
    var scrollTop = textarea.scrollTop;
    var scrollLeft = textarea.scrollLeft;
    if (url != '' && url != null) {
        if (document.selection) {
            textarea.focus();
            var sel = document.selection.createRange();
            if(sel.text=="") {
                sel.text = '[url]'  + url + '[/url]';
            } else {
                sel.text = '[url=' + url + ']' + sel.text + '[/url]';
            }
        } else {
            var len = textarea.value.length;
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;
            var sel = textarea.value.substring(start, end);
            if(sel=="") {
                var rep = '[url]' + url + '[/url]';
            } else {
                var rep = '[url=' + url + ']' + sel + '[/url]';
            }
            textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
            textarea.scrollTop = scrollTop;
            textarea.scrollLeft = scrollLeft;
        }
    }
}

function doYT(obj) {
    textarea = document.getElementById(obj);
    var url = prompt('Enter Videocode:','');
    var scrollTop = textarea.scrollTop;
    var scrollLeft = textarea.scrollLeft;
    if (url != '' && url != null) {
        if (document.selection) {
            textarea.focus();
            var sel = document.selection.createRange();
            sel.text = '[yt]' + url  + '[/yt]';						
        } else {
            var len = textarea.value.length;
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;
            var sel = textarea.value.substring(start, end);
            var rep = '[yt]' + url + '[/yt]';
            textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
            textarea.scrollTop = scrollTop;
            textarea.scrollLeft = scrollLeft;
        }
    }
}

function doAddTags(tag1,tag2,obj) {
    textarea = document.getElementById(obj);
	// Code for IE
	if (document.selection) {
        textarea.focus();
        var sel = document.selection.createRange();
        sel.text = tag1 + sel.text + tag2;
    } else { 
    // Code for Mozilla Firefox
        var len = textarea.value.length;
        var start = textarea.selectionStart;
        var end = textarea.selectionEnd;
        var scrollTop = textarea.scrollTop;
        var scrollLeft = textarea.scrollLeft;
        var sel = textarea.value.substring(start, end);
        var rep = tag1 + sel + tag2;
        textarea.value =  textarea.value.substring(0,start) + rep + textarea.value.substring(end,len);
        textarea.scrollTop = scrollTop;
        textarea.scrollLeft = scrollLeft;
    }
}