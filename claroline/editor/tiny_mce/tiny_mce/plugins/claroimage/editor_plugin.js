(function(){tinymce.create('tinymce.plugins.ClaroImagePlugin',{init:function(ed,url){ed.addCommand('mceClaroImage',function(){if(ed.dom.getAttrib(ed.selection.getNode(),'class').indexOf('mceItem')!=-1)return;ed.windowManager.open({file:url+'/image.php',width:640+parseInt(ed.getLang('advimage.delta_width',0)),height:620+parseInt(ed.getLang('advimage.delta_height',0)),inline:1},{plugin_url:url})});ed.addButton('claroimage',{title:'advimage.image_desc',cmd:'mceClaroImage',image:url+'/img/icon.png'})},getInfo:function(){return{longname:'Claroline custom Advanced image plugin',author:'Claroline team',authorurl:'http://www.claroline.net',infourl:'',version:tinymce.majorVersion+"."+tinymce.minorVersion}}});tinymce.PluginManager.add('claroimage',tinymce.plugins.ClaroImagePlugin)})();